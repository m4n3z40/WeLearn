<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:26
 *
 * Description:
 *
 */

class WeLearn_Cursos_ConfiguracaoCurso extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var string
     */
    private $_cursoId;

    /**
     * @var int
     */
    private $_privacidadeConteudo;

    /**
     * @var int
     */
    private $_privacidadeInscricao;

    /**
     * @var int
     */
    private $_permissaoCriacaoEnquete;

    /**
     * @var int
     */
    private $_permissaoCriacaoForum;

    /**
     * @param int $permissaoCriacaoEnquete
     */
    public function setPermissaoCriacaoEnquete($permissaoCriacaoEnquete)
    {
        $this->_permissaoCriacaoEnquete = (int)$permissaoCriacaoEnquete;
    }

    /**
     * @return int
     */
    public function getPermissaoCriacaoEnquete()
    {
        return $this->_permissaoCriacaoEnquete;
    }

    /**
     * @param int $permissaoCriacaoForum
     */
    public function setPermissaoCriacaoForum($permissaoCriacaoForum)
    {
        $this->_permissaoCriacaoForum = (int)$permissaoCriacaoForum;
    }

    /**
     * @return int
     */
    public function getPermissaoCriacaoForum()
    {
        return $this->_permissaoCriacaoForum;
    }

    /**
     * @param int $privacidadeConteudo
     */
    public function setPrivacidadeConteudo($privacidadeConteudo)
    {
        $this->_privacidadeConteudo = (int)$privacidadeConteudo;
    }

    /**
     * @return int
     */
    public function getPrivacidadeConteudo()
    {
        return $this->_privacidadeConteudo;
    }

    /**
     * @param int $privacidadeInscricao
     */
    public function setPrivacidadeInscricao($privacidadeInscricao)
    {
        $this->_privacidadeInscricao = $privacidadeInscricao;
    }

    /**
     * @return int
     */
    public function getPrivacidadeInscricao()
    {
        return $this->_privacidadeInscricao;
    }

    /**
     * @param string $cursoId
     */
    public function setCursoId($cursoId)
    {
        $this->_cursoId = (string)$cursoId;
    }

    /**
     * @return string
     */
    public function getCursoId()
    {
        return $this->_cursoId;
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
            'cursoId' => $this->getCursoId(),
            'privacidadeConteudo' => $this->getPrivacidadeConteudo(),
            'privacidadeInscricao' => $this->getPrivacidadeInscricao(),
            'permissaoCriacaoEnquete' => $this->getPermissaoCriacaoEnquete(),
            'permissaoCriacaoForum' => $this->getPermissaoCriacaoForum(),
            'persistido' => $this->isPersistido()
        );
    }
}
