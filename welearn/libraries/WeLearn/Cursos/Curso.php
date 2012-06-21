<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:51
 *
 * Description:
 *
 */

class WeLearn_Cursos_Curso extends WeLearn_Cursos_CursoBasico
{
    /**
     * @var string
     */
    private $_objetivos;

    /**
     * @var string
     */
    private $_conteudoProposto;

    /**
     * @var float
     */
    private $_tempoDuracaoMax;

    /**
     * @var float
     */
    private $_mediaQualidade;

    /**
     * @var float
     */
    private $_mediaDificuldade;

    /**
     * @var int
     */
    private $_totalReviews;

    /**
     * @var WeLearn_Usuarios_GerenciadorPrincipal
     */
    private $_criador;

    /**
     * @var WeLearn_Cursos_ImagemCurso
     */
    private $_imagem;

    /**
     * @var WeLearn_Cursos_ConfiguracaoCurso
     */
    private $_configuracao;

    /**
     * @var int
     */
    private $_totalAlunos;

    /**
     * @param \WeLearn_Cursos_ConfiguracaoCurso $configuracao
     */
    public function setConfiguracao(WeLearn_Cursos_ConfiguracaoCurso $configuracao)
    {
        $this->_configuracao = $configuracao;
    }

    /**
     * @return \WeLearn_Cursos_ConfiguracaoCurso
     */
    public function getConfiguracao()
    {
        return $this->_configuracao;
    }

    /**
     * @param string $conteudoProposto
     */
    public function setConteudoProposto($conteudoProposto)
    {
        $this->_conteudoProposto = (string)$conteudoProposto;
    }

    /**
     * @return string
     */
    public function getConteudoProposto()
    {
        return $this->_conteudoProposto;
    }

    /**
     * @param \WeLearn_Usuarios_GerenciadorPrincipal $criador
     */
    public function setCriador(WeLearn_Usuarios_GerenciadorPrincipal $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_GerenciadorPrincipal
     */
    public function getCriador()
    {
        return $this->_criador;
    }

    /**
     * @param \WeLearn_Cursos_ImagemCurso $imagem
     */
    public function setImagem(WeLearn_Cursos_ImagemCurso $imagem)
    {
        $this->_imagem = $imagem;
    }

    /**
     * @return \WeLearn_Cursos_ImagemCurso
     */
    public function getImagem()
    {
        return $this->_imagem;
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
     * @param float $tempoDuracaoMax
     */
    public function setTempoDuracaoMax($tempoDuracaoMax)
    {
        $this->_tempoDuracaoMax = (float)$tempoDuracaoMax;
    }

    /**
     * @return float
     */
    public function getTempoDuracaoMax()
    {
        return $this->_tempoDuracaoMax;
    }

    /**
     * @param int $totalReviews
     */
    public function setTotalReviews($totalReviews)
    {
        $this->_totalReviews = $totalReviews;
    }

    /**
     * @return int
     */
    public function getTotalReviews()
    {
        return $this->_totalReviews;
    }

    /**
     * @param float $mediaDificuldade
     */
    public function setMediaDificuldade($mediaDificuldade)
    {
        $this->_mediaDificuldade = (float)$mediaDificuldade;
    }

    /**
     * @return float
     */
    public function getMediaDificuldade()
    {
        return $this->_mediaDificuldade;
    }

    /**
     * @param float $mediaQualidade
     */
    public function setMediaQualidade($mediaQualidade)
    {
        $this->_mediaQualidade = (float)$mediaQualidade;
    }

    /**
     * @return float
     */
    public function getMediaQualidade()
    {
        return $this->_mediaQualidade;
    }

    /**
     * @return int
     */
    public function getTotalAlunos()
    {
        if ($this->_totalAlunos === null) {
            $this->_totalAlunos = WeLearn_DAO_DAOFactory::create('AlunoDAO')->recuperarQtdTotalPorCurso( $this );
        }

        return $this->_totalAlunos;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'objetivos' => $this->getObjetivos(),
                'conteudoProposto' => $this->getConteudoProposto(),
                'tempoDuracaoMax' => $this->getTempoDuracaoMax(),
                'mediaQualidade' => $this->getMediaQualidade(),
                'mediaDificuldade' => $this->getMediaDificuldade(),
                'criador' => $this->getCriador()->toArray(),
                'imagem' => $this->getImagem()->toArray(),
                'configuracao' => $this->getConfiguracao()->toArray()
            )
        );
        
        return $selfArray;
    }

    public function toCassandra()
    {
        $selfArrayCassandra = parent::toCassandra();

        $selfArrayCassandra = array_merge(
            $selfArrayCassandra,
            array(
                'objetivos' => $this->getObjetivos(),
                'conteudoProposto' => $this->getConteudoProposto(),
                'tempoDuracaoMax' => $this->getTempoDuracaoMax(),
                'criador' => $this->getCriador()->getId()
            )
        );

        return $selfArrayCassandra;
    }

    public function toMySQL()
    {
        return array(
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'tema' => $this->getTema(),
            'descricao' => $this->getDescricao(),
            'area_id' => ( $this->_segmento instanceof WeLearn_Cursos_Segmento
                         && $this->getSegmento()->getArea() instanceof WeLearn_Cursos_Area )
                         ? $this->getSegmento()->getArea()->getId() : '',
            'segmento_id' => ( $this->_segmento instanceof WeLearn_Cursos_Segmento )
                             ? $this->getSegmento()->getId() : ''
        );
    }

    public function toHTML($pequeno=false)
    {
        $htmlImagem = $this->htmlImagemLink($pequeno);
        $segmento = $this->getSegmento()->getDescricao();
        $area = $this->getSegmento()->getArea()->getDescricao();
        $tema = $this->getTema();
        $totalAlunos = $this->getTotalAlunos();

        return "{$htmlImagem}<ul>
                <li>Há <strong>{$totalAlunos}</strong> alunos neste curso.</li>
                <li><strong>Segmento:</strong> {$segmento}</li>
                <li><strong>Área:</strong> {$area}</li>
                <li><strong>Tema:</strong> <pre>{$tema}</pre></li></ul>";
    }

    public function htmlImagemLink($pequeno=false)
    {
        $tam = '';

        if ( $pequeno ) {
            $tam = 'width="80" height="65"';
        }

        $url = site_url( '/curso/' . $this->getId() );
        $urlImagem = ( $this->_imagem instanceof WeLearn_Cursos_ImagemCurso )
            ? $this->getImagem()->getUrl()
            : site_url( get_instance()->config->item('default_curso_img_uri') );
        $nome = $this->getNome();
        $descricao = $this->getDescricao();

        return "<figure class=\"fig-curso\"><a href=\"{$url}\" title=\"{$descricao}\">
                <img src=\"{$urlImagem}\" alt=\"{$nome}\" {$tam}>
                <span>{$nome}</span></a></figure>";
    }

    function __toString() {
        return $this->toHTML();
    }
}
