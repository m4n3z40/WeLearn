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

    private $_sugestaoPorAreaCF;
    private $_sugestaoPorSegmentoCF;
    private $_sugestaoPorUsuarioCF;
    private $_sugestaoPorStatusCF;
    private $_sugestaoAceitaPorAreaCF;
    private $_sugestaoAceitaPorSegmentoCF;
    private $_sugestaoAceitaPorUsuarioCF;

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

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $uuidObj = UUID::mint(1);

        $dto->setId($uuidObj->string);
        $dto->setDataCriacao(time());
        $dto->setStatus(WeLearn_Cursos_StatusSugestaoCurso::EM_ESPERA);

        $this->_cf->insert($uuidObj->bytes, $dto->toCassandra());

        $this->_sugestaoPorAreaCF->insert('_todos', array($uuidObj->bytes => ''));
        $this->_sugestaoPorAreaCF->insert($dto->getSegmento()->getArea()->getId(), array($uuidObj->bytes => ''));
        $this->_sugestaoPorSegmentoCF->insert($dto->getSegmento()->getId(), array($uuidObj->bytes => ''));
        $this->_sugestaoPorUsuarioCF->insert($dto->getCriador()->getId(), array($uuidObj->bytes => ''));
        $this->_sugestaoPorStatusCF->insert($dto->getStatus(), array($uuidObj->bytes => ''));

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $uuidObj = UUID::import($dto->getId());

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

        $sugestoesObjs = array();
        foreach ($sugestoesArray as $key => $column) {
            $column['segmento'] = $this->_segmentoDao->recuperar($column['segmento']);
            $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);

            $tempObj = new WeLearn_Cursos_SugestaoCurso();
            $tempObj->fromCassandra($column);
            $sugestoesObjs[] = $tempObj;
        }

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

        $sugestoesObjs = array();
        foreach ($sugestoesArray as $column) {
            $column['segmento'] = $this->_segmentoDao->recuperar($column['segmento']);
            $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);

            $tempObj = new WeLearn_Cursos_SugestaoCurso();
            $tempObj->fromCassandra($column);
            $sugestoesObjs[] = $tempObj;
        }

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

        $sugestoesObjs = array();
        foreach ($sugestoesArray as $column) {
            $column['segmento'] = $segmento;
            $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);

            $tempObj = new WeLearn_Cursos_SugestaoCurso();
            $tempObj->fromCassandra($column);
            $sugestoesObjs[] = $tempObj;
        }

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

        $sugestoesObjs = array();
        foreach ($sugestoesArray as $column) {
            $column['segmento'] = $this->_segmentoDao->recuperar($column['segmento']);
            $column['criador'] = $usuario;

            $tempObj = new WeLearn_Cursos_SugestaoCurso();
            $tempObj->fromCassandra($column);
            $sugestoesObjs[] = $tempObj;
        }

        return $sugestoesObjs;
    }

    public function recuperarTodosAceitos($de = '', $ate = '', array $filtros = null)
    {
        
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implement recuperar() method.
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
}
