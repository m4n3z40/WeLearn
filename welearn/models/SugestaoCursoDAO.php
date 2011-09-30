<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan Baptista (allan.marques@ymail.com)
 * Date: 08/09/11
 * Time: 14:06
 * To change this template use File | Settings | File Templates.
 */
 
class SugestaoCursoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_sugestao_curso';

    private $_nomeSugestaoPorAreaCF = 'cursos_sugestao_por_area';
    private $_nomeSugestaoPorSegmentoCF = 'cursos_sugestao_por_segmento';
    private $_nomeSugestaoPorUsuarioCF = 'cursos_sugestao_por_usuario';
    private $_nomeSugestaoPorStatus = 'cursos_sugestao_por_status';
    private $_nomeSugestaoAceitaPorAreaCF = 'cursos_sugestao_aceita_por_area';
    private $_nomeSugestaoAceitaPorSegmentoCF = 'cursos_sugestao_aceita_por_segmento';
    private $_nomeSugestaoAceitaPorUsuarioCF = 'cursos_sugestao_aceita_por_usuario';
    private $_nomeSugestaoUsuariosVotantes = 'cursos_sugestao_usuarios_votantes';
    private $_nomeContador = 'contadores_timeuuid';
    private $_keyContador = 'cursos_sugestao_votos';

    private $_sugestaoPorAreaCF;
    private $_sugestaoPorSegmentoCF;
    private $_sugestaoPorUsuarioCF;
    private $_sugestaoPorStatusCF;
    private $_sugestaoAceitaPorAreaCF;
    private $_sugestaoAceitaPorSegmentoCF;
    private $_sugestaoAceitaPorUsuarioCF;
    private $_sugestaoUsuariosVotantesCF;
    private $_contadorCF;

    private $_mysql_tbl_name = 'cursos_sugestao';

    private $_usuarioDao;
    private $_segmentoDao;


    function __construct()
    {
        $phpCassa =& WL_Phpcassa::getInstance();

        $this->_sugestaoPorAreaCF = $phpCassa->getColumnFamily($this->_nomeSugestaoPorAreaCF);
        $this->_sugestaoPorSegmentoCF = $phpCassa->getColumnFamily($this->_nomeSugestaoPorSegmentoCF);
        $this->_sugestaoPorUsuarioCF = $phpCassa->getColumnFamily($this->_nomeSugestaoPorUsuarioCF);
        $this->_sugestaoPorStatusCF = $phpCassa->getColumnFamily($this->_nomeSugestaoPorStatus);
        $this->_sugestaoAceitaPorAreaCF = $phpCassa->getColumnFamily($this->_nomeSugestaoAceitaPorAreaCF);
        $this->_sugestaoAceitaPorSegmentoCF = $phpCassa->getColumnFamily($this->_nomeSugestaoAceitaPorSegmentoCF);
        $this->_sugestaoAceitaPorUsuarioCF = $phpCassa->getColumnFamily($this->_nomeSugestaoAceitaPorUsuarioCF);
        $this->_sugestaoUsuariosVotantesCF = $phpCassa->getColumnFamily($this->_nomeSugestaoUsuariosVotantes);
        $this->_contadorCF = $phpCassa->getColumnFamily($this->_nomeContador);

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $uuidObj = UUID::mint();

        $dto->setId($uuidObj->string);
        $dto->setDataCriacao(time());
        $dto->setStatus(WeLearn_Cursos_StatusSugestaoCurso::EM_ESPERA);

        $this->_cf->insert($uuidObj->bytes, $dto->toCassandra());

        $this->_sugestaoPorAreaCF->insert('_todos', array($uuidObj->bytes => ''));
        $this->_sugestaoPorAreaCF->insert($dto->getSegmento()->getArea()->getId(), array($uuidObj->bytes => ''));
        $this->_sugestaoPorSegmentoCF->insert($dto->getSegmento()->getId(), array($uuidObj->bytes => ''));
        $this->_sugestaoPorUsuarioCF->insert($dto->getCriador()->getId(), array($uuidObj->bytes => ''));
        $this->_sugestaoPorStatusCF->insert($dto->getStatus(), array($uuidObj->bytes => ''));

        $indexMySqlVotos = array(
            'id' => $dto->getId(),
            'votos' => 0,
            'area_id' => $dto->getSegmento()->getArea()->getId(),
            'segmento_id' => $dto->getSegmento()->getId()
        );

        get_instance()->db->insert($this->_mysql_tbl_name, $indexMySqlVotos);

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $uuidObj = CassandraUtil::import($dto->getId());

        $this->_cf->insert($uuidObj->bytes, $dto->toCassandra());
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        $count = isset($filtros['count']) ? $filtros['count'] : 10;

        if (isset($filtros['area']) && ($filtros['area'] instanceof WeLearn_Cursos_Area)) {
            return $this->recuperarTodosPorArea($filtros['area'], $de, $ate, $count);
        }

        if (isset($filtros['segmento']) && ($filtros['segmento'] instanceof WeLearn_Cursos_Segmento)) {
            return $this->recuperarTodosPorSegmento($filtros['segmento'], $de, $ate, $count);
        }

        if (isset($filtros['usuario']) && ($filtros['usuario'] instanceof WeLearn_Usuarios_Usuario)) {
            return $this->recuperarTodosPorUsuario($filtros['usuario'], $de, $ate, $count);
        }

        return $this->recuperarTodosRecentes($de, $ate, $count);
    }

    public function recuperarTodosRecentes($de = '', $ate = '', $count = 10)
    {
        if (is_string($de) && $de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if (is_string($ate) && $ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $sugestoesArrayKeys = array_keys(
            $this->_sugestaoPorAreaCF->get('_todos', null, $de, $ate, true, $count)
        );

        $sugestoesArray = $this->_cf->multiget($sugestoesArrayKeys);

        $sugestoesObjs = $this->_criarVariosFromCassandra($sugestoesArray);

        return $sugestoesObjs;
    }

    public function recuperarTodosPorArea(WeLearn_Cursos_Area $area, $de = '', $ate = '', $count = 10)
    {
        if (is_string($de) && $de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if (is_string($ate) && $ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsSugestoes = array_keys(
            $this->_sugestaoPorAreaCF->get($area->getId(),
                                           null,
                                           $de,
                                           $ate,
                                           true,
                                           $count)
        );

        $sugestoesArray = $this->_cf->multiget($idsSugestoes);

        $sugestoesObjs = $this->_criarVariosFromCassandra($sugestoesArray);

        return $sugestoesObjs;
    }

    public function recuperarTodosPorSegmento(WeLearn_Cursos_Segmento $segmento, $de = '', $ate = '', $count = 10)
    {
        if (is_string($de) && $de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if (is_string($ate) && $ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsSugestoes = array_keys(
            $this->_sugestaoPorSegmentoCF->get($segmento->getId(),
                                               null,
                                               $de,
                                               $ate,
                                               true,
                                               $count)
        );

        $sugestoesArray = $this->_cf->multiget($idsSugestoes);

        $sugestoesObjs = $this->_criarVariosFromCassandra($sugestoesArray, null, $segmento);

        return $sugestoesObjs;
    }

    public function recuperarTodosPorUsuario(WeLearn_Usuarios_Usuario $usuario, $de = '', $ate = '', $count = 10)
    {
        if (is_string($de) && $de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if (is_string($ate) && $ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsSugestoes = array_keys(
            $this->_sugestaoPorUsuarioCF->get($usuario->getId(),
                                              null,
                                              $de,
                                              $ate,
                                              true,
                                              $count)
        );

        $sugestoesArray = $this->_cf->multiget($idsSugestoes);

        $sugestoesObjs = $this->_criarVariosFromCassandra($sugestoesArray, $usuario);

        return $sugestoesObjs;
    }

    public function recuperarTodosPorPopularidade($de = 0, $count = 10, array $filtros = null)
    {
        $db = get_instance()->db;

        $db->select('id')
           ->from('cursos_sugestao')
           ->order_by('votos', 'desc')
           ->limit($count, $de);

        if (isset($filtros['area']) && $filtros['area'] instanceof WeLearn_Cursos_Area) {
            $db->where('area_id', $filtros['area']->getId());
        }

        if (isset($filtros['segmento']) && $filtros['segmento'] instanceof WeLearn_Cursos_Segmento) {
            $db->where('segmento_id', $filtros['segmento']->getId());
        }

        $sugestoesIds = $db->get()->result();

        $sugestoesUUIDs = array();
        foreach ($sugestoesIds as $sugestaoId) {
            $sugestoesUUIDs[] = CassandraUtil::import($sugestaoId->id)->bytes;
        }

        $sugestoesArray = $this->_cf->multiget($sugestoesUUIDs);

        $sugestoesObjs = $this->_criarVariosFromCassandra($sugestoesArray);

        return $sugestoesObjs;
    }

    public function recuperarTodosAceitos($de = '', $ate = '', array $filtros = null)
    {
        
    }

    public function votar(WeLearn_Cursos_SugestaoCurso $sugestao, WeLearn_Usuarios_Usuario $votante)
    {
        $sugestaoUUID = CassandraUtil::import($sugestao->getId());

        try {
            $this->_sugestaoUsuariosVotantesCF->get($sugestaoUUID->bytes, array($votante->getId()));
            throw new WeLearn_Cursos_UsuarioJaVotouException();
        } catch (cassandra_NotFoundException $e) {
            $this->_contadorCF->add($this->_keyContador, $sugestaoUUID->bytes);

            $votos = array_values(
                $this->_contadorCF->get($this->_keyContador, array($sugestaoUUID->bytes))
            );
            $votos = $votos[0];

            $this->_cf->insert($sugestaoUUID->bytes, array('votos' => $votos));

            get_instance()->db->where('id', $sugestaoUUID->string)
                              ->update($this->_mysql_tbl_name, array('votos'=>$votos));

            $this->_sugestaoUsuariosVotantesCF->insert($sugestaoUUID->bytes, array($votante->getId()=>''));

            return $votos;
        }
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $sugestaoUUID = CassandraUtil::import($id);
        $column = $this->_cf->get($sugestaoUUID->bytes);

        return $this->_criarFromCassandra($column);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implement recuperarQtdTotal() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implement remover() method.
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_SugestaoCurso($dados);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Usuarios_Usuario $criadorPadrao = null,
                                         WeLearn_Cursos_Segmento $segmentoPadrao = null)
    {
        $column['criador'] = ($criadorPadrao instanceof WeLearn_Usuarios_Usuario)
                             ? $criadorPadrao
                             : $this->_usuarioDao->recuperar($column['criador']);

        $column['segmento'] = ($segmentoPadrao instanceof WeLearn_Cursos_Segmento)
                             ? $segmentoPadrao
                             : $this->_segmentoDao->recuperar($column['segmento']);

        $sugestao = new WeLearn_Cursos_SugestaoCurso();
        $sugestao->fromCassandra($column);
        return $sugestao;
    }

    private function _criarVariosFromCassandra(array $columns = null,
                                               WeLearn_Usuarios_Usuario $criadorPadrao = null,
                                               WeLearn_Cursos_Segmento $segmentoPadrao = null)
    {
        $sugestoes = array();

        foreach ($columns as $column) {
            $sugestoes[] = $this->_criarFromCassandra($column, $criadorPadrao, $segmentoPadrao);
        }

        return $sugestoes;
    }
}
