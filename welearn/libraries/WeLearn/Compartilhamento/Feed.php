<?php
/**
 * Created by Thiago Monteiro
 * Date: 26/07/11
 * Time: 18:35
 *
 * Description:
 *
 */

class WeLearn_Compartilhamento_Feed extends WeLearn_DTO_AbstractDTO
{
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
     * @var string
     **/
    private $_descricao;

    /**
     * @var string
     **/
    private $_id;

    /**
     * @var int
     **/
    private $_tipo;

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
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->_descricao = (string)$descricao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $tipo
     */
    public function setTipo($tipo)
    {
        $this->_tipo = (int)$tipo;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->_tipo;
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
            'conteudo' => $this->getConteudo(),
            'criador' => $this->getCriador()->toArray(),
            'dataEnvio' => $this->getDataEnvio(),
            'descricao' => $this->getDescricao(),
            'id' => $this->getId(),
            'tipo' => $this->getTipo(),
            'persistido' => $this->isPersistido()
        );
    }
}