<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 15:48
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Foruns_Forum extends WeLearn_DTO_AbstractDTO
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
    private $_descricao;

    /**
     * @var int
     */
    private $_dataCriacao;

    /**
     * @var WeLearn_Cursos_Foruns_Categoria
     */
    private $_categoria;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var int
     */
    private $_status = WeLearn_Cursos_Foruns_StatusForum::ATIVO;

    /**
     * @param \WeLearn_Cursos_Foruns_Categoria $categoria
     */
    public function setCategoria(WeLearn_Cursos_Foruns_Categoria $categoria)
    {
        $this->_categoria = $categoria;
    }

    /**
     * @return \WeLearn_Cursos_Foruns_Categoria
     */
    public function getCategoria()
    {
        return $this->_categoria;
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
     * @return int
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
     * @return void
     */
    public function alterarStatus()
    {
        //@TODO: Implementar este método!!
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
            'descricao' => $this->getDescricao(),
            'dataCriacao' => $this->getDataCriacao(),
            'categoria' => $this->getCategoria()->toArray(),
            'criador' => $this->getCriador()->toArray(),
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
            'id' => $this->getId(),
            'titulo' => $this->getTitulo(),
            'descricao' => $this->getDescricao(),
            'dataCriacao' => $this->getDataCriacao(),
            'categoria' => ($this->_categoria instanceof WeLearn_Cursos_Foruns_Categoria) ? $this->getCategoria()->getId() : '',
            'criador' => ($this->_criador instanceof WeLearn_Usuarios_Usuario) ? $this->getCriador()->getId() : '',
            'status' => $this->getStatus()
        );
    }
}
