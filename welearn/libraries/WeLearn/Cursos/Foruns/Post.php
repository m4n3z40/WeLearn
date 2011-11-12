<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 15:48
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Foruns_Post extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_titulo;

    /**
     * @var string
     */
    private $_conteudo;

    /**
     * @var int
     */
    private $_dataCriacao;

    /**
     * @var int
     */
    private $_dataAlteracao;

    /**
     * @var WeLearn_Cursos_Foruns_Forum
     */
    private $_forum;

    /**
     * @var WeLearn_Usuarios_Usuario
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
     * @param int $dataAlteracao
     */
    public function setDataAlteracao($dataAlteracao)
    {
        $this->_dataAlteracao = (int)$dataAlteracao;
    }

    /**
     * @return int
     */
    public function getDataAlteracao()
    {
        return $this->_dataAlteracao;
    }

    /**
     * @param int $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->_dataCriacao = (int)$dataCriacao;
    }

    /**
     * @return int
     */
    public function getDataCriacao()
    {
        return $this->_dataCriacao;
    }

    /**
     * @param \WeLearn_Cursos_Foruns_Forum $forum
     */
    public function setForum(WeLearn_Cursos_Foruns_Forum $forum)
    {
        $this->_forum = $forum;
    }

    /**
     * @return \WeLearn_Cursos_Foruns_Forum
     */
    public function getForum()
    {
        return $this->_forum;
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
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->_titulo = (string)$titulo;
    }

    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->_titulo;
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
            'titulo' => $this->getTitulo(),
            'conteudo' => $this->getConteudo(),
            'dataCriacao' => $this->getDataCriacao(),
            'dataAlteracao' => $this->getDataAlteracao(),
            'forum' => $this->getForum()->toArray(),
            'criador' => $this->getCriador()->toArray(),
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
            'id' => $this->getId(),
            'titulo' => $this->getTitulo(),
            'conteudo' => $this->getConteudo(),
            'dataCriacao' => $this->getDataCriacao(),
            'dataAlteracao' => $this->getDataAlteracao(),
            'forum' => ($this->_forum instanceof WeLearn_Cursos_Foruns_Forum) ? $this->getForum()->getId() : '',
            'criador' => ($this->_criador instanceof WeLearn_Usuarios_Usuario) ? $this->getCriador()->getId() : ''
        );
    }
}
