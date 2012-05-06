<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:44
 *
 * Description:
 *
 */

class WeLearn_Cursos_Certificado extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @var boolean
     */
    private $_ativo;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var string
     */
    private $_urlBig;
    
    /**
     * @var string
     */
    private $_urlSmall;

    /**
     * @var string
     */
    protected $_extensao;

    /**
     * @var string
     */
    protected $_assinaturaBig;
    
    /**
     * @var string
     */
    protected $_assinaturaSmall;

    /**
     * @var string
     */
    protected $_caminho;

    /**
     * @var string
     */
    protected $_caminhoCompletoBig;

    /**
     * @var string
     */
    protected $_caminhoCompletoSmall;

    /**
     * @var string
     */
    protected $_mimeType;

    /**
     * @var float KB
     */
    protected $_tamanhoBig;
    
    /**
     * @var float KB
     */
    protected $_tamanhoSmall;

    /**
     * @var int
     */
    protected $_larguraImagemBig;
    
    /**
     * @var int
     */
    protected $_larguraImagemSmall;

    /**
     * @var int
     */
    protected $_alturaImagemBig;
    
    /**
     * @var int
     */
    protected $_alturaImagemSmall;

    /**
     * @var string
     */
    protected $_tipoImagem;

    /**
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        if ( ! is_bool( $ativo ) ) {
            $ativo = (int)$ativo;
            $ativo = ( $ativo > 0 );
        }

        $this->_ativo = $ativo;
    }

    /**
     * @return boolean
     */
    public function isAtivo()
    {
        return $this->_ativo;
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
     * @param string $urlBig
     */
    public function setUrlBig($urlBig)
    {
        $this->_urlBig = (string)$urlBig;
    }

    /**
     * @return string
     */
    public function getUrlBig()
    {
        return $this->_urlBig;
    }

    /**
     * @param string $urlSmall
     */
    public function setUrlSmall($urlSmall)
    {
        $this->_urlSmall = (string)$urlSmall;
    }

    /**
     * @return string
     */
    public function getUrlSmall()
    {
        return $this->_urlSmall;
    }

    /**
     * @param int $alturaImagemBig
     */
    public function setAlturaImagemBig($alturaImagemBig)
    {
        $this->_alturaImagemBig = (int)$alturaImagemBig;
    }

    /**
     * @return int
     */
    public function getAlturaImagemBig()
    {
        return $this->_alturaImagemBig;
    }

    /**
     * @param int $alturaImagemSmall
     */
    public function setAlturaImagemSmall($alturaImagemSmall)
    {
        $this->_alturaImagemSmall = (int)$alturaImagemSmall;
    }

    /**
     * @return int
     */
    public function getAlturaImagemSmall()
    {
        return $this->_alturaImagemSmall;
    }

    /**
     * @param string $assinaturaBig
     */
    public function setAssinaturaBig($assinaturaBig)
    {
        $this->_assinaturaBig = (string)$assinaturaBig;
    }

    /**
     * @return string
     */
    public function getAssinaturaBig()
    {
        return $this->_assinaturaBig;
    }

    /**
     * @param string $assinaturaSmall
     */
    public function setAssinaturaSmall($assinaturaSmall)
    {
        $this->_assinaturaSmall = (string)$assinaturaSmall;
    }

    /**
     * @return string
     */
    public function getAssinaturaSmall()
    {
        return $this->_assinaturaSmall;
    }
    
    /**
     * @param string $caminho
     */
    public function setCaminho($caminho)
    {
        $this->_caminho = (string)$caminho;
    }

    /**
     * @return string
     */
    public function getCaminho()
    {
        return $this->_caminho;
    }

    /**
     * @param string $caminhoCompletoBig
     */
    public function setCaminhoCompletoBig($caminhoCompletoBig)
    {
        $this->_caminhoCompletoBig = (string)$caminhoCompletoBig;
    }

    /**
     * @return string
     */
    public function getCaminhoCompletoBig()
    {
        return $this->_caminhoCompletoBig;
    }

    /**
     * @param string $caminhoCompletoSmall
     */
    public function setCaminhoCompletoSmall($caminhoCompletoSmall)
    {
        $this->_caminhoCompletoSmall = (string)$caminhoCompletoSmall;
    }

    /**
     * @return string
     */
    public function getCaminhoCompletoSmall()
    {
        return $this->_caminhoCompletoSmall;
    }

    /**
     * @param string $extensao
     */
    public function setExtensao($extensao)
    {
        $this->_extensao = (string)$extensao;
    }

    /**
     * @return string
     */
    public function getExtensao()
    {
        return $this->_extensao;
    }

    /**
     * @param int $larguraImagemBig
     */
    public function setLarguraImagemBig($larguraImagemBig)
    {
        $this->_larguraImagemBig = (int)$larguraImagemBig;
    }

    /**
     * @return int
     */
    public function getLarguraImagemBig()
    {
        return $this->_larguraImagemBig;
    }

    /**
     * @param int $larguraImagemSmall
     */
    public function setLarguraImagemSmall($larguraImagemSmall)
    {
        $this->_larguraImagemSmall = (int)$larguraImagemSmall;
    }

    /**
     * @return int
     */
    public function getLarguraImagemSmall()
    {
        return $this->_larguraImagemSmall;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->_mimeType = (string)$mimeType;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->_mimeType;
    }

    /**
     * @param float $tamanho
     */
    public function setTamanhoBig($tamanhoBig)
    {
        $this->_tamanhoBig = (float)$tamanhoBig;
    }

    /**
     * @return float
     */
    public function getTamanhoBig()
    {
        return $this->_tamanhoBig;
    }

    /**
     * @param float $tamanhoSmall
     */
    public function setTamanhoSmall($tamanhoSmall)
    {
        $this->_tamanhoSmall = (float)$tamanhoSmall;
    }

    /**
     * @return float
     */
    public function getTamanhoSmall()
    {
        return $this->_tamanhoSmall;
    }

    /**
     * @param string $tipoImagem
     */
    public function setTipoImagem($tipoImagem)
    {
        $this->_tipoImagem = (string)$tipoImagem;
    }

    /**
     * @return string
     */
    public function getTipoImagem()
    {
        return $this->_tipoImagem;
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
            'descricao' => $this->getDescricao(),
            'ativo' => $this->isAtivo(),
            'curso' => $this->getCurso()->toArray(),
            'urlBig' => $this->getUrlBig(),
            'urlSmall' => $this->getUrlSmall(),
            'extensao' => $this->getExtensao(),
            'assinaturaBig' => $this->getAssinaturaBig(),
            'assinaturaSmall' => $this->getAssinaturaSmall(),
            'caminho' => $this->getCaminho(),
            'caminhoCompletoBig' => $this->getCaminhoCompletoBig(),
            'caminhoCompletoSmaill' => $this->getCaminhoCompletoSmall(),
            'mimeType' => $this->getMimeType(),
            'tamanhoBig' => $this->getTamanhoBig(),
            'tamanhoSmall' => $this->getTamanhoSmall(),
            'alturaImagemBig' => $this->getAlturaImagemBig(),
            'alturaImagemSmall' => $this->getAlturaImagemSmall(),
            'larguraImagemBig' => $this->getLarguraImagemBig(),
            'larguraImagemSmall' => $this->getLarguraImagemSmall(),
            'tipoImagem' => $this->getTipoImagem(),
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
            'descricao' => $this->getDescricao(),
            'ativo' => $this->isAtivo() ? 'true' : 'false',
            'curso' => ($this->_curso instanceof WeLearn_Cursos_Curso)
                       ? $this->getCurso()->getId() : '',
            'urlBig' => $this->getUrlBig(),
            'urlSmall' => $this->getUrlSmall(),
            'extensao' => $this->getExtensao(),
            'assinaturaBig' => $this->getAssinaturaBig(),
            'assinaturaSmall' => $this->getAssinaturaSmall(),
            'caminho' => $this->getCaminho(),
            'caminhoCompletoBig' => $this->getCaminhoCompletoBig(),
            'caminhoCompletoSmall' => $this->getCaminhoCompletoSmall(),
            'mimeType' => $this->getMimeType(),
            'tamanhoBig' => $this->getTamanhoBig(),
            'tamanhoSmall' => $this->getTamanhoSmall(),
            'alturaImagemBig' => $this->getAlturaImagemBig(),
            'alturaImagemSmall' => $this->getAlturaImagemSmall(),
            'larguraImagemBig' => $this->getLarguraImagemBig(),
            'larguraImagemSmall' => $this->getLarguraImagemSmall(),
            'tipoImagem' => $this->getTipoImagem()
        );
    }

    function __toString()
    {
        $url = $this->getUrlSmall();
        $alt = 'Certificado do curso "' . $this->getCurso()->getNome() . '"';
        $descricao = ( strlen($this->getDescricao()) > 100 )
                     ? substr($this->getDescricao(), 0, 100) . '...'
                     : $this->getDescricao();

        return
            "<div>
                <figure>
                    <img src=\"{$url}\" alt=\"{$alt}\">
                    <figcaption>Miniatura do Certificado</figcaption>
                </figure>
                <blockquote>{$descricao}</blockquote>
            </div>";
    }
}
