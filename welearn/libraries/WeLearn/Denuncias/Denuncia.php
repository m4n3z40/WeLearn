<?php
/**
 * Created by Thiago Monteiro
 * Date: 22/07/11
 * Time: 20:21
 *
 * Description:
 *
 */

class WeLearn_Denuncias_Denuncia extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var WeLearn_Compartilhamento_Feed
     */
    private $_denunciado;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @var int
     */
    private $_status;

    /**
     * @var int
     */
    private $_tipo;

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
     * @param \WeLearn_Compartilhamento_Feed $denunciado
     */
    public function setDenunciado(WeLearn_Compartilhamento_Feed $denunciado)
    {
        $this->_denunciado = $denunciado;
    }

    /**
     * @return \WeLearn_Compartilhamento_Feed
     */
    public function getDenunciado()
    {
        return $this->_denunciado;
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
     * @return void
     */
    public function aceitar()
    {
        //TODO: Implementar este método!
    }

    /**
     * @return void
     */
    public function enviar()
    {
        //TODO: Implementar este método!
    }

    /**
     * @return void
     */
    public function ler()
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
     * @return void
     */
    public function solucionar()
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
            'id' => $this->getId(),
            'criador' => $this->getCriador()->toArray(),
            'dataEnvio' => $this->getDataEnvio(),
            'denunciado' => $this->getDenunciado()->toArray(),
            'descricao' => $this->getDescricao(),
            'status' => $this->getStatus(),
            'tipo' => $this->getTipo(),
            'persistido' => $this->isPersistido()
        );
    }
}