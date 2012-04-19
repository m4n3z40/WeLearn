<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:19
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_Modulo extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var string
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
    private $_objetivos;

    /**
     * @var int
     */
    private $_nroOrdem;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var int
     */
    private $_qtdTotalAulas;

    /**
     * @var boolean
     */
    private $_existeAvaliacao;

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
     * @param int $nroOrdem
     */
    public function setNroOrdem($nroOrdem)
    {
        $this->_nroOrdem = (int)$nroOrdem;
    }

    /**
     * @return int
     */
    public function getNroOrdem()
    {
        return $this->_nroOrdem;
    }

    /**
     * @param string $objetivos
     */
    public function setObjetivos($objetivos)
    {
        $this->_objetivos = (string)$objetivos;
    }

    /**
     * @return string
     */
    public function getObjetivos()
    {
        return $this->_objetivos;
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
     * @param int $qtdTotalAulas
     */
    public function setQtdTotalAulas($qtdTotalAulas)
    {
        $this->_qtdTotalAulas = (int)$qtdTotalAulas;
    }

    /**
     * @return int
     */
    public function getQtdTotalAulas()
    {
        return $this->_qtdTotalAulas;
    }

    /**
     * @param boolean $existeAvaliacao
     */
    public function setExisteAvaliacao($existeAvaliacao)
    {
        $this->_existeAvaliacao = (boolean)$existeAvaliacao;
    }

    /**
     * @return boolean
     */
    public function getExisteAvaliacao()
    {
        return $this->_existeAvaliacao;
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
            'objetivos' => $this->getObjetivos(),
            'nroOrdem' => $this->getNroOrdem(),
            'curso' => $this->getCurso()->toArray(),
            'qtdTotalAulas' => $this->getQtdTotalAulas(),
            'existeAvaliacao' => $this->getExisteAvaliacao(),
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
            'nome' => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'objetivos' => $this->getObjetivos(),
            'nroOrdem' => $this->getNroOrdem(),
            'curso' => ($this->_curso instanceof WeLearn_Cursos_Curso) ? $this->getCurso()->getId() : ''
        );
    }
}
