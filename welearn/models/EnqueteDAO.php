<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 11/08/11
 * Time: 20:06
 * To change this template use File | Settings | File Templates.
 */
 
class EnqueteDAO extends WeLearn_DAO_AbstractDAO {
    protected $_nomeCF = 'cursos_enquete';

    private $_nomeEnquetePorCursoCF = 'cursos_enquete_por_curso';
    private $_nomeEnquetePorStatusSuperCF = 'cursos_enquete_por_status';
    private $_nomeEnquetePorSituacaoSuperCF = 'cursos_enquete_por_situacao';

    private $_enquetePorCursoCF;
    private $_enquetePorStatusSuperCF;
    private $_enquetePorSituacaoSuperCF;

    private $_cursoDao;
    private $_usuarioDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_enquetePorCursoCF = $phpCassa->getColumnFamily($this->_nomeEnquetePorCursoCF);
        $this->_enquetePorStatusSuperCF = $phpCassa->getColumnFamily($this->_nomeEnquetePorStatusSuperCF);
        $this->_enquetePorSituacaoSuperCF = $phpCassa->getColumnFamily($this->_nomeEnquetePorSituacaoSuperCF);

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
       // TODO: Implementar este metodo.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
       // TODO: Implementar este metodo.
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
       // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
           // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param array $dadosAlterantiva
     * @return void
     */
    public function criarAlternativas(array $dadosAlterantiva)
    {
         // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return void
     */
    public function recuperarAlternativas(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
         // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return int
     */
    public function recuperarQtdTotalVotos(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
         // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return void
     */
    public function zerarVotos(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        // TODO: Implementar este metodo.
    }
}
