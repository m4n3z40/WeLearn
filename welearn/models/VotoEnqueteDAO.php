<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 11/11/11
 * Time: 16:38
 * To change this template use File | Settings | File Templates.
 */
 
class VotoEnqueteDAO extends WeLearn_DAO_AbstractDAO {
    protected $_nomeCF = 'cursos_enquete_votos';

    private $_nomeVotosPorEnqueteSuperCF = 'cursos_enquete_votos_por_enquete';

    private $_votosPorEnquetesSuperCF;

    private $_enqueteDao;
    private $_usuarioDao;
    private $_alternativaEnqueteDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_votosPorEnquetesSuperCF = $phpCassa->getColumnFamily($this->_nomeVotosPorEnqueteSuperCF);

        $this->_enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_alternativaEnqueteDao = WeLearn_DAO_DAOFactory::create('AlternativaEnqueteDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        // TODO: Implement _adicionar() method.
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implement _atualizar() method.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implement recuperarTodos() method.
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
        // TODO: Implement criarNovo() method.
    }
}
