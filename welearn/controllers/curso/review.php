<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review extends WL_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('review.js');
    }

    public function index ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar( $idCurso );

            $this->_renderTemplateCurso($curso);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir index do gerenciamento de reviews'
                . create_exception_description($e));

            show_404();
        }
    }

    private function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
    {
        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao()
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}
