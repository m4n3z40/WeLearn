<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:20
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_Aula extends WeLearn_DTO_AbstractDTO
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
     * @var int
     */
    private $_nroOrdem;

    /**
     * @var WeLearn_Cursos_Conteudo_Modulo
     */
    private $_modulo;

    /**
     * @var int
     */
    private $_qtdTotalPaginas;

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
     * @param \WeLearn_Cursos_Conteudo_Modulo $modulo
     */
    public function setModulo(WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $this->_modulo = $modulo;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Modulo
     */
    public function getModulo()
    {
        return $this->_modulo;
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
     * @return int
     */
    public function getQtdTotalPaginas()
    {
        return $this->_qtdTotalPaginas;
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
            'nroOrdem' => $this->getNroOrdem(),
            'modulo' => $this->getModulo()->toArray(),
            'qtdTotalPaginas' => $this->getQtdTotalPaginas(),
            'persistido' => $this->isPersistido()
        );
    }
}
