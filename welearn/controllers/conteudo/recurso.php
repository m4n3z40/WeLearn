<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recurso extends WL_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('recurso.js')
                       ->setTemplate('curso');
    }

    public function index ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'listaModulos' => array(),
                'moduloSelecionado' => '',
                'listaAulas' => array()
            );


            $this->_renderTemplateCurso($curso, 'curso/conteudo/recurso/index', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir a index de recursos
                                  de curso: ' . create_exception_description($e));

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
            'descricao' => $curso->getDescricao(),
            'menuContexto' => $this->template->loadPartial('menu', array('idCurso'=> $curso->getId()), 'curso/conteudo')
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}