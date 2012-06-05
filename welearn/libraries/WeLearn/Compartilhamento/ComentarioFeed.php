<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 03/06/12
 * Time: 15:14
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Compartilhamento_ComentarioFeed extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     **/
    private $_id;

    /**
     * @var string
     **/
    private $_conteudo;

    /**
     * @var WeLearn_Usuarios_Usuario
     **/
    private $_criador;

    /**
     * @var string
     **/
    private $_dataEnvio;

    /**
     * @var WeLearn_Compartilhamento_Feed
     */
    private $_compartilhamento;


    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $conteudo
     */
    public function setConteudo($conteudo)
    {
        $this->_conteudo = (string)$conteudo;
    }

    /**
     * @return string
     */
    public function getConteudo()
    {
        return $this->_conteudo;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $criador
     */
    public function setCriador(WeLearn_Usuarios_Usuario $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getCriador()
    {
        return $this->_criador;
    }

    /**
     * @param string $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->_dataEnvio = (string)$dataEnvio;
    }

    /**
     * @return string
     */
    public function getDataEnvio()
    {
        return $this->_dataEnvio;
    }

    /**
     * @param WeLearn_Compartilhamento_Feed $compartilhamento
     */
   public function setCompartilhamento(WeLearn_Compartilhamento_Feed $compartilhamento)
   {
       $this->_compartilhamento = $compartilhamento;
   }

    /**
     * @return WeLearn_Compartilhamento_Feed
     */
   public function getCompartilhamento()
   {
       return $this->_compartilhamento;
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
            'id' => $this->getId(),
            'conteudo' => $this->getConteudo(),
            'criador' => $this->getCriador()->toArray(),
            'dataEnvio' => $this->getDataEnvio(),
            'compartilhamento' => $this->getCompartilhamento(),
            'persistido' => $this->isPersistido()
        );
    }

    public function toCassandra()
    {
        return array(
            'id' => $this->getId(),
            'conteudo' => $this->getConteudo(),
            'criador' => $this->getCriador()->getId(),
            'dataEnvio' => $this->getDataEnvio(),
            'compartilhamento' => $this->getCompartilhamento()->getId()
        );
    }
}
