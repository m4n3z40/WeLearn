<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:34
 *
 * Description:
 *
 */

class WeLearn_Cursos_ImagemCurso extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_cursoId;

    /**
     * @param string $url
     * @param string $cursoId
     */
    public function __construct($url = '', $cursoId = '')
    {
        $dados = array(
            'url' => $url,
            'cursoId' => $cursoId
        );

        parent::__construct($dados);
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = (string)$url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param string $cursoId
     */
    public function setCursoId($cursoId)
    {
        $this->_cursoId = $cursoId;
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
            'url' => $this->getUrl(),
            'cursoId' => $this->getCursoId(),
            'persistido' => $this->isPersistido()
        );
    }
}
