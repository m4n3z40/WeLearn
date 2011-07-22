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
    private $_objetivos;

    /**
     * @var int
     */
    private $_nroOrdem;

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
     * @param int $nroOrdem
     */
    public function setNroOrdem($nroOrdem)
    {
        $this->_nroOrdem = (string)$nroOrdem;
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
}
