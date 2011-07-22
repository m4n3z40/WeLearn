<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 16:06
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Recursos_Recurso extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_nome;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @var string
     */
    private $_dataInclusao;

    /**
     * @var string
     */
    private $_urlArquivo;

    /**
     * @var WeLearn_Usuarios_Moderador
     */
    private $_criador;

    /**
     * @var WeLearn_Cursos_Conteudo_Aula
     */
    private $_aula;

    /**
     * @var int
     */
    private $_totalRecursos;

    /**
     * @var int
     */
    private $_tipo;

    /**
     * @param \WeLearn_Cursos_Conteudo_Aula $aula
     */
    public function setAula(WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $this->_aula = $aula;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Aula
     */
    public function getAula()
    {
        return $this->_aula;
    }

    /**
     * @param \WeLearn_Usuarios_Moderador $criador
     */
    public function setCriador(WeLearn_Usuarios_Moderador $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Moderador
     */
    public function getCriador()
    {
        return $this->_criador;
    }

    /**
     * @param string $dataInclusao
     */
    public function setDataInclusao($dataInclusao)
    {
        $this->_dataInclusao = (string)$dataInclusao;
    }

    /**
     * @return string
     */
    public function getDataInclusao()
    {
        return $this->_dataInclusao;
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
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->_nome = (string)$nome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->_nome;
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
     * @return int
     */
    public function getTotalRecursos()
    {
        return $this->_totalRecursos;
    }

    /**
     * @param string $urlArquivo
     */
    public function setUrlArquivo($urlArquivo)
    {
        $this->_urlArquivo = (string)$urlArquivo;
    }

    /**
     * @return string
     */
    public function getUrlArquivo()
    {
        return $this->_urlArquivo;
    }

    /**
     * @return void
     */
    public function recuperarQtdTotalRecursos()
    {
        //@TODO: Implementar este método!
    }

    /**
     * @param array $dadosNavegador
     * @return void
     */
    public function isVisualizavel(array $dadosNavegador)
    {
        //@TODO: Implementar este método!
    }
}
