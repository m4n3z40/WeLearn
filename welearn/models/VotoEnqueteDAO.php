<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 11/11/11
 * Time: 16:38
 * To change this template use File | Settings | File Templates.
 */
 
class VotoEnqueteDAO extends WeLearn_DAO_AbstractDAO {
    protected $_nomeCF = 'cursos_enquete_votos_por_enquete';

    private $_nomeVotosPorAlternativaCF = 'cursos_enquete_votos_por_alternativa';

    /**
     * @var ColumnFamily
     */
    private $_votosPorAlternativaCF;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_votosPorAlternativaCF = $phpCassa->getColumnFamily($this->_nomeVotosPorAlternativaCF);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $enqueteUUID = CassandraUtil::import($dto->getEnquete()->getId());
        $alternativaUUID = CassandraUtil::import($dto->getAlternativa()->getId());

        $dto->setDataVoto(time());

        $this->_cf->insert($enqueteUUID->bytes, $dto->toCassandra());

        $this->_votosPorAlternativaCF->insert(
            $alternativaUUID->bytes,
            array( $dto->getVotante()->getId() => $dto->getDataVoto() )
        );

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->cancelar($dto);
        $this->_adicionar($dto);
    }
    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        return array();
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        return null;
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ( ($de instanceof WeLearn_Cursos_Enquetes_Enquete) && $ate instanceof WeLearn_Cursos_Enquetes_AlternativaEnquete) {
            return $this->recuperarQtdTotalPorAlternativa($de, $ate);
        }

        if ( $de instanceof WeLearn_Cursos_Enquetes_Enquete ) {
            return $this->recuperarQtdTotalPorEnquete($de);
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return int
     */
    public function recuperarQtdTotalPorEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        $enqueteUUID = CassandraUtil::import($enquete->getId());

        return $this->_cf->get_count($enqueteUUID->bytes);
    }

    /**
     * @param WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa
     * @return int
     */
    public function recuperarQtdTotalPorAlternativa(WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa)
    {
        $alternativaUUID = CassandraUtil::import($alternativa->getId());

        return $this->_votosPorAlternativaCF->get_count($alternativaUUID->bytes);
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        return null;
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     */
    public function removeTodosrPorEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        $enqueteUUID = CassandraUtil::import( $enquete->getId() );

        $alternativasUUIDs = array();
        foreach ( $enquete->getAlternativas() as $alternativa ) {
            $alternativasUUIDs[] = CassandraUtil::import( $alternativa->getId() );
        }

        $this->_cf->remove( $enqueteUUID->bytes );

        foreach ( $alternativasUUIDs as $alternativaUUID ) {
            $this->_votosPorAlternativaCF->remove( $alternativaUUID->bytes );
        }
    }

    /**
     * @param WeLearn_Cursos_Enquetes_VotoEnquete $votoEnquete
     */
    public function cancelar(WeLearn_Cursos_Enquetes_VotoEnquete $votoEnquete)
    {
        $enqueteUUID = CassandraUtil::import( $votoEnquete->getEnquete()->getId() );
        $alternativaUUID = CassandraUtil::import( $votoEnquete->getAlternativa()->getId() );

        $this->_cf->remove( $enqueteUUID->bytes, array( $votoEnquete->getVotante()->getId() ) );
        $this->_cf->remove( $alternativaUUID->bytes, array( $votoEnquete->getVotante()->getId() ) );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        $voto = new WeLearn_Cursos_Enquetes_VotoEnquete();
        $voto->preencherPropriedades($dados);

        return $voto;
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return bool
     */
    public function usuarioJaVotouEnquete(WeLearn_Usuarios_Usuario $usuario, WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        try {
            $enqueteUUID = CassandraUtil::import( $enquete->getId() );

            $this->_cf->get( $enqueteUUID->bytes, array($usuario->getId()) );

            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }
}
