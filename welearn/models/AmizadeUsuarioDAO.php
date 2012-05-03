<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 09:22
 * To change this template use File | Settings | File Templates.
 */
 
class AmizadeUsuarioDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'usuarios_amizade';

    //indexes
    private $_nomeAmizadeAmigos = 'usuarios_amizade_amigos';
    private $_nomeAmizadeAmigosPorDataCF = 'usuarios_amizade_amigos_por_data';
    private $_nomeAmizadeRequisicoesCF = 'usuarios_amizade_requisicoes';
    private $_nomeAmizadeRequisicoesPorDataCF = 'usuarios_amizade_requisicoes_por_data';

    private $_amizadeAmigosCF;
    private $_amizadeAmigosPorDataCF;
    private $_amizadeRequisicoesCF;
    private $_amizadeRequisicoesPorDataCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    function __construct()
    {


        $phpCassa = WL_Phpcassa::getInstance();

        $this->_amizadeAmigosCF = $phpCassa->getColumnFamily($this->_nomeAmizadeAmigos);
        $this->_amizadeAmigosPorDataCF = $phpCassa->getColumnFamily($this->_nomeAmizadeAmigosPorDataCF);
        $this->_amizadeRequisicoesCF = $phpCassa->getColumnFamily($this->_nomeAmizadeRequisicoesCF);
        $this->_amizadeRequisicoesPorDataCF = $phpCassa->getColumnFamily($this->_nomeAmizadeRequisicoesPorDataCF);

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $column = $this->_cf->get($id);

        return $this->_criarFromCassandra($column);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if(isset($filtros['count'])) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if (isset($filtros['opcao'])) {
            switch ($filtros['opcao']) {
                case 'amigos':
                    return $this->recuperarTodosAmigos($filtros['usuario'], $de, $ate, $count);
                case 'amigosPorData':
                    return $this->recuperarTodosAmigosPorData($filtros['usuario'], $de, $ate, $count);
                case 'requisicoes':
                    return $this->recuperarTodasRequisicoes($filtros['usuario'], $de, $ate, $count);
                case 'requisicoesPorData':
                    return $this->recuperarTodasRequisicoesPorData($filtros['usuario'], $de, $ate, $count);
                default:
            }
        }

        return $this->recuperarTodosAmigos($filtros['usuario'], $de, $ate, $count);
    }

    public function recuperarTodosAmigos(WeLearn_Usuarios_Usuario $usuario, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsAmigos = array_keys(
            $this->_amizadeAmigosCF->get($usuario->getId(),
                                         null,
                                         $de,
                                         $ate,
                                         false,
                                         $count)
        );

        return $this->_recuperarUsuariosPorIds($idsAmigos);
    }

    public function recuperarTodosAmigosPorData(WeLearn_Usuarios_Usuario $usuario, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsAmigos = array_values(
            $this->_amizadeAmigosPorDataCF->get($usuario->getId(),
                                                null,
                                                $de,
                                                $ate,
                                                true,
                                                $count)
        );

        return $this->_recuperarUsuariosPorIds($idsAmigos);
    }

    public function recuperarTodasRequisicoes(WeLearn_Usuarios_Usuario $usuario, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsAmigos = array_keys(
            $this->_amizadeRequisicoesCF->get($usuario->getId(),
                                              null,
                                              $de,
                                              $ate,
                                              false,
                                              $count)
        );

        return $this->_recuperarUsuariosPorIds($idsAmigos);
    }

    public function recuperarTodasRequisicoesPorData(WeLearn_Usuarios_Usuario $usuario, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsAmigos = array_values(
            $this->_amizadeRequisicoesPorDataCF->get($usuario->getId(),
                                                     null,
                                                     $de,
                                                     $ate,
                                                     true,
                                                     $count)
        );

        return $this->_recuperarUsuariosPorIds($idsAmigos);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return $this->recuperarQtdTotalAmigos($de);
    }

    public function recuperarQtdTotalAmigos(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_amizadeAmigosCF->get_count($usuario->getId());
    }

    public function recuperarQtdTotalRequisicoes(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_amizadeRequisicoesCF->get_count($usuario->getId());
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $column = $this->_cf->get($id);
        $timeUUID = CassandraUtil::import($column['timeUUID'])->bytes;
        $amizadeRemovida = $this->_criarFromCassandra($column);

        $this->_cf->remove($id);

        if ($amizadeRemovida->getStatus() === WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA) {
            $this->_amizadeRequisicoesCF->remove(
                $amizadeRemovida->getUsuario()->getId(),
                array($amizadeRemovida->getAmigo()->getId())
            );

            $this->_amizadeRequisicoesPorDataCF->remove(
                $amizadeRemovida->getUsuario()->getId(),
                array($timeUUID)
            );
        } else {
            $this->_amizadeAmigosCF->remove(
                $amizadeRemovida->getUsuario()->getId(),
                array($amizadeRemovida->getAmigo()->getId())
            );

            $this->_amizadeAmigosPorDataCF->remove(
                $amizadeRemovida->getUsuario()->getId(),
                array($timeUUID)
            );
        }

        $amizadeRemovida->setPersistido(false);

        return $amizadeRemovida;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        $novaAmizade = new WeLearn_Usuarios_AmizadeUsuario($dados['convite']->getRemetente(),$dados['convite']->getDestinatario(),time(),0);
        return $novaAmizade;
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $idAmizade = $this->gerarIdAmizade($dto->getUsuario(), $dto->getAmigo());

        $statusArray = $this->_cf->get($idAmizade, array('status','timeUUID'));
        $statusAntigo = $statusArray['status'];
        $timeUUID = CassandraUtil::import($statusArray['timeUUID'])->bytes;

        $this->_cf->insert($idAmizade, $dto->toCassandra());

        if ($statusAntigo != $dto->getStatus()) {
            if ( (int)$statusAntigo === WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA ) {
                $this->_amizadeRequisicoesCF->remove(
                    $dto->getUsuario()->getId(),
                    array($dto->getAmigo()->getId())
                );

                $this->_amizadeRequisicoesPorDataCF->remove(
                    $dto->getUsuario()->getId(),
                    array($timeUUID)
                );

                $this->_amizadeAmigosCF->insert(
                    $dto->getUsuario()->getId(),
                    array($dto->getAmigo()->getId() => '')
                );

                $this->_amizadeAmigosPorDataCF->insert(
                    $dto->getUsuario()->getId(),
                    array($timeUUID => $dto->getAmigo()->getId())
                );
            } else {
                $this->_amizadeAmigosCF->remove(
                    $dto->getUsuario()->getId(),
                    array($dto->getAmigo()->getId())
                );

                $this->_amizadeAmigosPorDataCF->remove(
                    $dto->getUsuario()->getId(),
                    array($timeUUID)
                );

                $this->_amizadeRequisicoesCF->insert(
                    $dto->getUsuario()->getId(),
                    array($dto->getAmigo()->getId() => '')
                );

                $this->_amizadeRequisicoesPorDataCF->insert(
                    $dto->getUsuario()->getId(),
                    array($timeUUID => $dto->getAmigo()->getId())
                );
            }
        }
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $idAmizade = $this->gerarIdAmizade($dto->getUsuario(), $dto->getAmigo());

        $amizadeColumns = $dto->toCassandra();

        $this->_cf->insert($idAmizade, $amizadeColumns);

        $this->_amizadeRequisicoesCF->insert(
            $dto->getUsuario()->getId(),
            array($dto->getAmigo()->getId() => '')
        );

        $this->_amizadeRequisicoesPorDataCF->insert(
            $dto->getUsuario()->getId(),
            array( CassandraUtil::import($amizadeColumns['timeUUID'])->bytes => $dto->getAmigo()->getId() )
        );

        $dto->setPersistido(true);
    }

    public function gerarIdAmizade(WeLearn_Usuarios_Usuario $usuario, WeLearn_Usuarios_Usuario $amigo)
    {
        $arraySort = array($usuario->getId(), $amigo->getId());
        sort($arraySort);

        return implode('::', $arraySort);
    }

    private function _recuperarUsuariosDeIdAmizade($idAmizade, WeLearn_Usuarios_Usuario $usuarioAtual = null) {
        $arrayIdUsuarios = explode('::', $idAmizade);
        $arrayRetorno = array();

        if ($usuarioAtual instanceof WeLearn_Usuarios_Usuario) {
            $arrayRetorno['usuario'] = $usuarioAtual;
            if ($arrayIdUsuarios[0] == $usuarioAtual->getId()) {
                $arrayRetorno['amigo'] = $this->_usuarioDao->recuperar($arrayIdUsuarios[1]);

                return $arrayRetorno;
            } elseif($arrayIdUsuarios[1] == $usuarioAtual->getId()) {
                $arrayRetorno['amigo'] = $this->_usuarioDao->recuperar($arrayIdUsuarios[0]);

                return $arrayRetorno;
            }
        }

        $arrayRetorno['usuario'] = $this->_usuarioDao->recuperar($arrayIdUsuarios[0]);
        $arrayRetorno['amigo'] = $this->_usuarioDao->recuperar($arrayIdUsuarios[1]);

        return $arrayRetorno;
    }

    private function _criarFromCassandra(array $column, WeLearn_Usuarios_Usuario $usuarioPadrao = null)
    {
        $column = array_merge(
            $column,
            $this->_recuperarUsuariosDeIdAmizade($column['id'], $usuarioPadrao)
        );

        $amizade = new WeLearn_Usuarios_AmizadeUsuario();
        $amizade->fromCassandra($column);

        return $amizade;
    }

    private function _recuperarUsuariosPorIds(array $arrayIds)
    {
        $arrayUsuarios = array();

        foreach ($arrayIds as $id) {
            $arrayUsuarios[] = $this->_usuarioDao->recuperar($id);
        }

        return $arrayUsuarios;
    }
}