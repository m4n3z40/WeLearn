<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 14:31
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_ListaDeIM extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_idIM;

    /**
     * @var WeLearn_Usuarios_InstantMessenger
     */
    private $_messenger;

    /**
     * @var WeLearn_Usuarios_DadosPessoaisUsuario
     */
    private $_dadosPessoais;

    /**
     * @param string $idIM
     * @param null|WeLearn_Usuarios_InstantMessenger $messenger
     * @param null|WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function __construct($idIM = '',
        WeLearn_Usuarios_InstantMessenger $messenger = null,
        WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais = null)
    {
        $dados = array(
            'idIM' => $idIM,
            'messenger' => $messenger,
            'dadosPessoais' => $dadosPessoais
        );

        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function setDadosPessoais(WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais)
    {
        $this->_dadosPessoais = $dadosPessoais;
    }

    /**
     * @return \WeLearn_Usuarios_DadosPessoaisUsuario
     */
    public function getDadosPessoais()
    {
        return $this->_dadosPessoais;
    }

    /**
     * @param string $idIM
     */
    public function setIdIM($idIM)
    {
        $this->_idIM = (string)$idIM;
    }

    /**
     * @return string
     */
    public function getIdIM()
    {
        return $this->_idIM;
    }

    /**
     * @param \WeLearn_Usuarios_InstantMessenger $messenger
     */
    public function setMessenger(WeLearn_Usuarios_InstantMessenger $messenger)
    {
        $this->_messenger = $messenger;
    }

    /**
     * @return \WeLearn_Usuarios_InstantMessenger
     */
    public function getMessenger()
    {
        return $this->_messenger;
    }
}
