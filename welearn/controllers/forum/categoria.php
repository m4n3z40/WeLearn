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
            $count = 20;

            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');

            $listaCategorias = $categoriaDao->recuperarTodosPorCurso($curso, '', '', $count + 1);

            $this->load->helper('paginacao_cassandra');
            $dados_paginacao = create_paginacao_cassandra($listaCategorias, $count);

            $dadosLista = array(
                'listaCategorias' => $listaCategorias
            );

            $dadosViewListar = array(
                'idCurso' => $curso->getId(),
                'listaCategorias' => $this->template->loadPartial('lista', $dadosLista, 'curso/forum/categoria'),
                'haMaisPaginas' => $dados_paginacao['proxima_pagina'],
                'inicioProxPagina' => $dados_paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateCurso($curso, 'curso/forum/categoria/listar', $dadosViewListar);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao exibir a lista de Categoria de Fórum:'
                                 . create_exception_description($e));

            show_404();
        }
    }

    public function proxima_pagina($cursoId, $inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 10;

            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($cursoId);

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $listaCategorias = $categoriaDao->recuperarTodosPorCurso($curso, $inicio, '', $count + 1);

            $this->load->helper('paginacao_cassandra');
            $dados_paginacao = create_paginacao_cassandra($listaCategorias, $count);

            $dadosLista = array(
                'listaCategorias' => $listaCategorias
            );

            $response = array(
                'success' => true,
                'htmlListaCategorias' => $this->template->loadPartial('lista', $dadosLista, 'curso/forum/categoria'),
                'paginacao' => $dados_paginacao
            );

            $json = Zend_Json::encode($response);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao recuperar outra página de categorias de fóruns: '
                                 . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado. Já estamos verificando, tente novamente mais tarde.');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
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
                'formCriar' => $this->template->loadPartial('form_criar', $dadosFormCriar, 'curso/forum/categoria'),
                'textoBotaoSubmit' => 'Criar nova categoria!'
            );

            $this->_renderTemplateCurso($curso, 'curso/forum/categoria/criar', $dadosViewCriar);
        } catch (Exception $e) {
            show_404();
        }
    }

    public function alterar($id)
    {

    }

    public function remover($id)
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