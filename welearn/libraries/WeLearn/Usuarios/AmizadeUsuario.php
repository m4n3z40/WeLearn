<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 15:53
 *
 * Description:
 *
 */

class WeLearn_Usuarios_AmizadeUsuario extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_usuario;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_amigo;

    /**
     * @var int
     */
    private $_comecouEm;

    /**
     * @var int
     */
    private $_status;

    /**
     * @var AmizadeUsuarioDAO
     */
    private $_amizadeDao;

    /**
     * @param null|WeLearn_Usuarios_Usuario $usuario
     * @param null|WeLearn_Usuarios_Usuario $amigo
     * @param int $comecouEm
     * @param int $status
     */

    public function __construct(WeLearn_Usuarios_Usuario $usuario = null,
        WeLearn_Usuarios_Usuario $amigo = null,
        $comecouEm = null,
        $status = WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA)
    {
        $dados = array(
            'usuario' => $usuario,
            'amigo' => $amigo,
            'comecouEm' => $comecouEm,
            'status' => $status
        );

        parent::__construct($dados);

        $this->_amizadeDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $amigo
     */
    public function setAmigo(WeLearn_Usuarios_Usuario $amigo)
    {
        $this->_amigo = $amigo;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getAmigo()
    {
        return $this->_amigo;
    }

    /**
     * @param int $comecouEm
     */
    public function setComecouEm($comecouEm)
    {
        $this->_comecouEm = (int)$comecouEm;
    }

    /**
     * @return int
     */
    public function getComecouEm()
    {
        return $this->_comecouEm;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = (int)$status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $usuario
     */
    public function setUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        $this->_usuario = $usuario;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getUsuario()
    {
        return $this->_usuario;
    }

    /**
     * @return void
     */
    public function aceitar()
    {
        //TODO: Implementar este método!
    }

    /**
     * @return void
     */
    public function alterarStatus()
    {
        //TODO: Implementar este método!
    }

    /**
     * @return void
     */
    public function interromper()
    {
        //TODO: Implementar este método!
    }

    /**
     * @return void
     */
    public function recusar()
    {
        //TODO: Implementar este método!
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'usuario' => $this->getUsuario()->toArray(),
            'amigo' => $this->getAmigo()->toArray(),
            'comecouEm' => $this->getComecouEm(),
            'status' => $this->getStatus(),
            'persistido' => $this->isPersistido()
        );
    }

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @return array
     */
    public function toCassandra()
    {
        return array(
            'id' => $this->_amizadeDao->gerarIdAmizade($this->getUsuario(), $this->getAmigo()),
            'timeUUID' => UUID::mint(1, null, null, $this->getComecouEm() * 1000000)->string,
            'comecouEm' => $this->getComecouEm(),
            'status' => $this->getStatus()
        );
    }

    /**
     * @return \AmizadeUsuarioDAO
     */
    public function getAmizadeDao()
    {
        return $this->_amizadeDao;
    }
}