<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 16:21
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Reviews_RespostaResenha extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_resenhaId;

    /**
     * @var string
     */
    private $_conteudo;

    /**
     * @var int
     */
    private $_dataEnvio;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @param string $resenhaId
     */
    public function setResenhaId($resenhaId)
    {
        $this->_resenhaId = (string)$resenhaId;
    }

    /**
     * @return string
     */
    public function getResenhaId()
    {
        return $this->_resenhaId;
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
     * @param int $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->_dataEnvio = (int)$dataEnvio;
    }

    /**
     * @return int
     */
    public function getDataEnvio()
    {
        return $this->_dataEnvio;
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
            'resenhaId' => $this->getResenhaId(),
            'conteudo' => $this->getConteudo(),
            'dataEnvio' => $this->getDataEnvio(),
            'criador' => ( $this->_criador instanceof WeLearn_Usuarios_Usuario )
                         ? $this->getCriador()->toArray() : '',
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
            'resenhaId' => $this->getResenhaId(),
            'conteudo' => $this->getConteudo(),
            'dataEnvio' => $this->getDataEnvio(),
            'criador' => ( $this->_criador instanceof WeLearn_Usuarios_Usuario )
                         ? $this->getCriador()->getId() : ''
        );
    }
}
