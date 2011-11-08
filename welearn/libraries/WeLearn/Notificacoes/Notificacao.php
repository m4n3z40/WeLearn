<?php

class WeLearn_Notificacoes_Notificacao extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_assunto;

    /**
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var int
     */
    private $_status;

    /**
     * @var int
     */
    private $_tipoRemetente;

    /**
     * @param string $assunto
     */
    public function setAssunto($assunto)
    {
        $this->_assunto = (string)$assunto;
    }

    /**
     * @return string
     */
    public function getAssunto()
    {
        return $this->_assunto;
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
     * @param int $tipoRemetente
     */
    public function setTipoRemetente($tipoRemetente)
    {
        $this->_tipoRemetente = (int)$tipoRemetente;
    }

    /**
     * @return int
     */
    public function getTipoRemetente()
    {
        return $this->_tipoRemetente;
    }

    public function enviar()
    {
        // TODO: Implementar este método!
    }

    public function ler()
    {
        // TODO: Implementar este método!
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
            'assunto' => $this->getAssunto(),
            'dataEnvio' => $this->getDataEnvio(),
            'descricao' => $this->getDescricao(),
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'tipoRemetente' => $this->getTipoRemetente(),
            'persistido' => $this->isPersistido()
        );
    }
}