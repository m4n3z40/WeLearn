<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 15:48
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Foruns_Categoria extends WeLearn_DTO_AbstractDTO
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
    private $_dataCriacao;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

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
     * @param \WeLearn_Cursos_Curso $curso
     */
    public function setCurso(WeLearn_Cursos_Curso $curso)
    {
        $this->_curso = $curso;
    }

    /**
     * @return \WeLearn_Cursos_Curso
     */
    public function getCurso()
    {
        return $this->_curso;
    }

    /**
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->_dataCriacao = (string)$dataCriacao;
    }

    /**
     * @return string
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
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'dataCriacao' => $this->getDataCriacao(),
            'criador' => $this->getCriador()->toArray(),
            'curso' => $this->getCurso()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
