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
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_nome;

    /**
     * @var string
     */
    protected $_descricao;

    /**
     * @var int
     */
    protected $_dataInclusao;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    protected $_criador;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var string
     */
    protected $_extensao;

    /**
     * @var string
     */
    protected $_assinatura;

    /**
     * @var string
     */
    protected $_caminho;

    /**
     * @var string
     */
    protected $_caminhoCompleto;

    /**
     * @var string
     */
    protected $_mimeType;

    /**
     * @var float KB
     */
    protected $_tamanho;

    /**
     * @var boolean
     */
    protected $_isImagem;

    /**
     * @var int
     */
    protected $_larguraImagem;

    /**
     * @var int
     */
    protected $_alturaImagem;

    /**
     * @var string
     */
    protected $_tipoImagem;

    /**
     * @var int
     */
    protected $_tipo = WeLearn_Cursos_Recursos_TipoRecurso::GERAL;

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
     * @param int $dataInclusao
     */
    public function setDataInclusao($dataInclusao)
    {
        $this->_dataInclusao = (int)$dataInclusao;
    }

    /**
     * @return int
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
        $this->_id = (string)$id;
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
     * @param int $alturaImagem
     */
    public function setAlturaImagem($alturaImagem)
    {
        $this->_alturaImagem = (int)$alturaImagem;
    }

    /**
     * @return int
     */
    public function getAlturaImagem()
    {
        return $this->_alturaImagem;
    }

    /**
     * @param string $assinatura
     */
    public function setAssinatura($assinatura)
    {
        $this->_assinatura = (string)$assinatura;
    }

    /**
     * @return string
     */
    public function getAssinatura()
    {
        return $this->_assinatura;
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
     * @param string $caminhoCompleto
     */
    public function setCaminhoCompleto($caminhoCompleto)
    {
        $this->_caminhoCompleto = (string)$caminhoCompleto;
    }

    /**
     * @return string
     */
    public function getCaminhoCompleto()
    {
        return $this->_caminhoCompleto;
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
     * @param boolean $isImagem
     */
    public function setIsImagem($isImagem)
    {
        $this->_isImagem = (boolean)$isImagem;
    }

    /**
     * @return boolean
     */
    public function isImagem()
    {
        return $this->_isImagem;
    }

    /**
     * @param int $larguraImagem
     */
    public function setLarguraImagem($larguraImagem)
    {
        $this->_larguraImagem = (int)$larguraImagem;
    }

    /**
     * @return int
     */
    public function getLarguraImagem()
    {
        return $this->_larguraImagem;
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
    public function setTamanho($tamanho)
    {
        $this->_tamanho = (float)$tamanho;
    }

    /**
     * @return float
     */
    public function getTamanho()
    {
        return $this->_tamanho;
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
     * @return int
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
            'dataInclusao' => $this->getDataInclusao(),
            'url' => $this->getUrl(),
            'criador' => ($this->_criador instanceof WeLearn_Usuarios_Usuario)
                             ? $this->getCriador()->toArray() : '',
            'tipo' => $this->getTipo(),
            'extensao' => $this->getExtensao(),
            'assinatura' => $this->getAssinatura(),
            'caminho' => $this->getCaminho(),
            'caminhoCompleto' => $this->getCaminhoCompleto(),
            'mimeType' => $this->getMimeType(),
            'tamanho' => $this->getTamanho(),
            'isImagem' => $this->isImagem(),
            'alturaImagem' => $this->getAlturaImagem(),
            'larguraImagem' => $this->getLarguraImagem(),
            'tipoImagem' => $this->getTipoImagem(),
            'persistido' => $this->isPersistido()
        );
    }

    public function toCassandra()
    {
        return array(
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'dataInclusao' => $this->getDataInclusao(),
            'url' => $this->getUrl(),
            'criador' => ($this->_criador instanceof WeLearn_Usuarios_Usuario)
                             ? $this->getCriador()->getId() : '',
            'tipo' => $this->getTipo(),
            'extensao' => $this->getExtensao(),
            'assinatura' => $this->getAssinatura(),
            'caminho' => $this->getCaminho(),
            'caminhoCompleto' => $this->getCaminhoCompleto(),
            'mimeType' => $this->getMimeType(),
            'tamanho' => $this->getTamanho(),
            'isImagem' => $this->isImagem(),
            'alturaImagem' => $this->getAlturaImagem(),
            'larguraImagem' => $this->getLarguraImagem(),
            'tipoImagem' => $this->getTipoImagem()
        );
    }

    public function preencherPropriedades(array $dados = null)
    {
        parent::preencherPropriedades($dados);

        if ( isset( $dados['file_ext'] ) ) $this->setExtensao( $dados['file_ext'] );
        if ( isset( $dados['orig_name'] ) ) $this->setAssinatura( $dados['orig_name'] );
        if ( isset( $dados['file_path'] ) ) $this->setCaminho( $dados['file_path'] );
        if ( isset( $dados['full_path'] ) ) $this->setCaminhoCompleto( $dados['full_path'] );
        if ( isset( $dados['file_type'] ) ) $this->setMimeType( $dados['file_type'] );
        if ( isset( $dados['file_size'] ) ) $this->setTamanho( $dados['file_size'] );
        if ( isset( $dados['is_image'] ) ) $this->setIsImagem( $dados['is_image'] );
        if ( isset( $dados['image_height'] ) ) $this->setAlturaImagem( $dados['image_height'] );
        if ( isset( $dados['image_width'] ) ) $this->setLarguraImagem( $dados['image_width'] );
        if ( isset( $dados['image_type'] ) ) $this->setTipoImagem( $dados['image_type'] );
    }

    function __toString()
    {
        $class = 'filetype-' . str_replace('.', '', strtolower($this->getExtensao()));
        $link = anchor( $this->getUrl(), $this->getAssinatura() );
        $extensao = strtoupper( $this->getExtensao() );
        $tamanho = $this->getTamanho() . 'KB';
        $tipo = $this->getMimeType();

        return

            "<dl class='{$class}'>
                <dt>Arquivo:</dt>
                <dd>{$link}</dd>
                <dt>Extensão do arquivo:</dt>
                <dd>{$extensao}</dd>
                <dt>Tipo do Arquivo</dt>
                <dd>{$tipo}</dd>
                <dt>Tamanho do arquivo:</dt>
                <dd>{$tamanho}</dd>
            </dl>";
    }
}
