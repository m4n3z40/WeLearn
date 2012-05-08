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
     * @param array $opcoes
     * @return void
     */
    public function alterarOpcoesPrivacidade(array $opcoes)
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @param array $opcoes
     * @return void
     */
    public function alterarOpcoesEnquete(array $opcoes)
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @param array $opcoes
     * @return void
     */
    public function alterarOpcoesForum(array $opcoes)
    {
        //@TODO: Implementar este método!!
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
}
