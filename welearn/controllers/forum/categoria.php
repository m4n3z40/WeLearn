<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('categoria_forum.js');
    }

    public function index($idCurso)
    {

    }

    public function listar($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $this->_renderTemplateCurso($curso);
        } catch (Exception $e) {
            show_404();
        }
    }

    public function criar($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $dadosFormCriar = array(
                'nomeAtual' => '',
                'descricaoAtual' => ''
            );

            $dadosViewCriar = array(
                'formAction' => 'forum/categoria/salvar',
                'extraOpenForm' => 'id="form-criar-categoria-forum"',
                'hiddenFormData' => array('cursoId' => $curso->getId()),
                'formCriar' => $this->template->loadPartial('form_criar', $dadosFormCriar, 'curso/forum'),
                'textoBotaoSubmit' => 'Criar nova categoria!'
            );

            $this->_renderTemplateCurso($curso, 'curso/forum/criar', $dadosViewCriar);
        } catch (Exception $e) {
            show_404();
        }
    }

    public function alterar($idCurso)
    {

    }

    public function salvar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            try {
                $dadosCategoria = $this->input->post();

                $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
                $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');

                $dadosCategoria['curso'] = $cursoDao->recuperar($dadosCategoria['cursoId']);
                $dadosCategoria['criador'] = $this->autenticacao->getUsuarioAutenticado();

                $novaCategoria = $categoriaDao->criarNovo($dadosCategoria);
                $categoriaDao->salvar($novaCategoria);

                $notificacoesFlash = Zend_Json::encode(array(
                                                           'msg'=> 'A nova categoria de fóruns foi criada com sucesso. <br/>'
                                                                 . 'Comece a adicionar fóruns à esta categoria!',
                                                           'nivel' => 'sucesso',
                                                           'tempo' => '15000'
                                                       ));

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

                $json = create_json_feedback(true, '', '"idCurso":"' . $novaCategoria->getCurso()->getid() . '"');
            } catch (Exception $e) {
                log_message('error', 'Erro a criar categoria de fórum: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');
                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }

    public function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
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