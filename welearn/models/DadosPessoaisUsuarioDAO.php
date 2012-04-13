<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 16/03/12
 * Time: 16:50
 * To change this template use File | Settings | File Templates.
 */
class DadosPessoaisUsuarioDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'usuarios_dados_pessoais';

    private $_nomeIMCF = 'usuarios_im';
    private $_nomeIMPorUsuarioCF = 'usuarios_im_por_usuario';
    private $_nomeRSCF = 'usuarios_rs';
    private $_nomeRSPorUsuarioCF = 'usuarios_rs_por_usuario';

    /**
     * @var ColumnFamily
     */
    private $_IMCF;

    /**
     * @var ColumnFamily
     */
    private $_IMPorUsuarioCF;

    /**
     * @var ColumnFamily
     */
    private $_RSCF;

    /**
     * @var ColumnFamily
     */
    private $_RSPorUsuarioCF;

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $this->_cf->insert( $dto->getUsuarioId(), $dto->toCassandra() );

        $this->_salvarListaDeIM( $dto->getUsuarioId(), $dto->getListaDeIM() );
        $this->_salvarListaDeRS( $dto->getUsuarioId(), $dto->getListaDeRS() );

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->_cf->insert( $dto->getUsuarioId(), $dto->toCassandra() );

        $this->_removerListaDeIM( $dto->getUsuarioId() );
        $this->_removerListaDeRS( $dto->getUsuarioId() );

        $this->_salvarListaDeIM( $dto->getUsuarioId(), $dto->getListaDeIM() );
        $this->_salvarListaDeRS( $dto->getUsuarioId(), $dto->getListaDeRS() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        return array();
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $dadosPessoais = $this->criarNovo();

        $column = $this->_cf->get($id);
        $column['listaDeIM'] = $this->_recuperarListaDeIM($id);
        $column['listaDeRS'] = $this->_recuperarListaDeRS($id);

        $dadosPessoais->fromCassandra($column);

        return $dadosPessoais;
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return 0;
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $dadosPessoais = $this->recuperar($id);

        $this->_cf->remove($id);
        $this->_removerListaDeIM($id);
        $this->_removerListaDeRS($id);

        $dadosPessoais->setPersistido(false);

        return $dadosPessoais;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Usuarios_DadosPessoaisUsuario($dados);
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Usuarios_InstantMessenger
     */
    public function criarNovoIM(array $dados = null)
    {
        return new WeLearn_Usuarios_InstantMessenger($dados);
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Usuarios_RedeSocial
     */
    public function criarNovoRS(array $dados = null)
    {
        return new WeLearn_Usuarios_RedeSocial($dados);
    }

    /**
     * @param string $usuarioId
     * @return array
     */
    private function _recuperarListaDeIM($usuarioId)
    {
        try {
            $listaDeIM = array();

            $idsIM = array_keys( $this->_IMPorUsuarioCF->get($usuarioId) );

            $columns = $this->_IMCF->multiget($idsIM);

            foreach ($columns as $column) {
                $IM = $this->criarNovoIM();
                $IM->fromCassandra($column);
                $listaDeIM[] = $IM;
            }

            return $listaDeIM;
        } catch ( cassandra_NotFoundException $e ) {
            return array();
        }
    }

    /**
     * @param string $usuarioId
     * @param array $listaDeIM
     * @return void
     */
    private function _salvarListaDeIM($usuarioId, array $listaDeIM)
    {
        if ( empty($listaDeIM) ) return;

        $batchListaIM = array();
        $listaIMKeys = array();

        foreach ($listaDeIM as $IM) {
            $imUUID = UUID::mint();

            $IM->setId( $imUUID->string );

            $batchListaIM[ $imUUID->bytes ] = $IM->toCassandra();

            $listaIMKeys[ $imUUID->bytes ] = '';

            $IM->setPersistido(true);
        }

        $this->_IMCF->batch_insert( $batchListaIM );
        $this->_IMPorUsuarioCF->insert( $usuarioId, $listaIMKeys );
    }

    /**
     * @param string $usuarioId
     * @return void
     */
    private function _removerListaDeIM($usuarioId)
    {
        try {
            $idsIM = array_keys( $this->_IMPorUsuarioCF->get($usuarioId) );

            foreach ($idsIM as $id) { $this->_IMCF->remove($id); }

            $this->_IMPorUsuarioCF->remove($usuarioId);
        } catch( cassandra_NotFoundException $e ) {
            return;
        }
    }

    /**
     * @param string $usuarioId
     * @return array
     */
    private function _recuperarListaDeRS($usuarioId)
    {
        try {
            $listaDeRS = array();

            $idsRS = array_keys( $this->_RSPorUsuarioCF->get($usuarioId) );

            $columns = $this->_RSCF->multiget($idsRS);

            foreach ($columns as $column) {
                $RS = $this->criarNovoRS();
                $RS->fromCassandra($column);
                $listaDeRS[] = $RS;
            }

            return $listaDeRS;
        } catch ( cassandra_NotFoundException $e ) {
            return array();
        }
    }

    /**
     * @param string $usuarioId
     * @param array $listaDeRS
     * @return void
     */
    private function _salvarListaDeRS($usuarioId, array $listaDeRS)
    {
        if ( empty($listaDeRS) ) return;

        $batchListaRS = array();
        $listaRSKeys = array();

        foreach ($listaDeRS as $RS) {
            $rsUIID = UUID::mint();

            $RS->setId( $rsUIID->string );

            $batchListaRS[ $rsUIID->bytes ] = $RS->toCassandra();

            $listaRSKeys[ $rsUIID->bytes ] = '';

            $RS->setPersistido(true);
        }

        $this->_RSCF->batch_insert( $batchListaRS );
        $this->_RSPorUsuarioCF->insert( $usuarioId, $listaRSKeys );
    }

    /**
     * @param string $usuarioId
     * @return void
     */
    private function _removerListaDeRS($usuarioId)
    {
        try {
            $idsRS = array_keys( $this->_RSPorUsuarioCF->get($usuarioId) );

            foreach ($idsRS as $id) { $this->_RSCF->remove($id); }

            $this->_RSPorUsuarioCF->remove($usuarioId);
        } catch ( cassandra_NotFoundException $e ) {
            return;
        }
    }

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_IMCF = $phpCassa->getColumnFamily($this->_nomeIMCF);
        $this->_IMPorUsuarioCF = $phpCassa->getColumnFamily($this->_nomeIMPorUsuarioCF);
        $this->_RSCF = $phpCassa->getColumnFamily($this->_nomeRSCF);
        $this->_RSPorUsuarioCF = $phpCassa->getColumnFamily($this->_nomeRSPorUsuarioCF);
    }
}
