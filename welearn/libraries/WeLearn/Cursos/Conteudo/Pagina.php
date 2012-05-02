<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:21
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_Pagina extends WeLearn_DTO_AbstractDTO
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
    private $_conteudo;

    /**
     * @var int
     */
    private $_nroOrdem;

    /**
     * @var WeLearn_Cursos_Conteudo_Aula
     */
    private $_aula;

    /**
     * @var int
     */
    private $_qtdTotalComentarios;

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
     * @param int $qtdTotalComentarios
     */
    public function setQtdTotalComentarios($qtdTotalComentarios)
    {
        $this->_qtdTotalComentarios = $qtdTotalComentarios;
    }

    /**
     * @return int
     */
    public function getQtdTotalComentarios()
    {
        return $this->_qtdTotalComentarios;
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
            'conteudo' => $this->getConteudo(),
            'nroOrdem' => $this->getNroOrdem(),
            'aula' => $this->getAula()->toArray(),
            'qtdTotalComentarios' => $this->getQtdTotalComentarios(),
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
            'conteudo' => $this->getConteudo(),
            'nroOrdem' => $this->getNroOrdem(),
            'aula' => ($this->_aula instanceof WeLearn_Cursos_Conteudo_Aula)
                       ? $this->getAula()->getId()
                       : ''
        );
    }
}
