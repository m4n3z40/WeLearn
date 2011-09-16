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
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_conteudo;

    /**
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var WeLearn_Cursos_Reviews_Resenha
     */
    private $_resenha;

    /**
     * @var WeLearn_Usuarios_GerenciadorAuxiliar
     */
    private $_criador;

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
     * @param \WeLearn_Usuarios_GerenciadorAuxiliar $criador
     */
    public function setCriador(WeLearn_Usuarios_GerenciadorAuxiliar $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_GerenciadorAuxiliar
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param \WeLearn_Cursos_Reviews_Resenha $resenha
     */
    public function setResenha(WeLearn_Cursos_Reviews_Resenha $resenha)
    {
        $this->_resenha = $resenha;
    }

    /**
     * @return \WeLearn_Cursos_Reviews_Resenha
     */
    public function getResenha()
    {
        return $this->_resenha;
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
            'dataEnvio' => $this->getDataEnvio(),
            'resenha' => $this->getResenha()->toArray(),
            'criador' => $this->getCriador()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
