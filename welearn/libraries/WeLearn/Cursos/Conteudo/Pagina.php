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
            'persistido' => $this->isPersistido()
        );
    }
}
