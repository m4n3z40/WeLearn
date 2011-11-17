<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('forum.js');
    }

    public function index($idCurso)
    {
        $this->listar_categorias($idCurso);
    }

    public function listar_categorias($idCurso)
    {
        try {
            $count = ColumnFamily::DEFAULT_COLUMN_COUNT;

            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');

            try {
                $listaCategorias = $categoriaDao->recuperarTodosPorCurso($curso, '', '', $count + 1);

                foreach ( $listaCategorias as $categoria ) {
                    $categoria->recuperarQtdForuns();
                }
            } catch (cassandra_NotFoundException $e) {
                $listaCategorias = array();
            }

            $this->load->helper('paginacao_cassandra');

            $dados_paginacao = create_paginacao_cassandra($listaCategorias, $count);

            $partialListaCategorias = $this->template->loadPartial(
                'lista_categorias',
                array('listaCategorias' => $listaCategorias),
                'curso/forum/forum'
            );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haCategorias' => !empty($listaCategorias),
                'listaCategorias' => $partialListaCategorias,
                'haMaisPaginas' => $dados_paginacao['proxima_pagina'],
                'inicioProxPagina' => $dados_paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateCurso($curso, 'curso/forum/forum/lista_categorias', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar listar as categorias de foruns: '
                                 . create_exception_description($e));

            show_404();
        }
    }

    public function proxima_pagina_categorias($cursoId, $inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = ColumnFamily::DEFAULT_COLUMN_COUNT;

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
                'htmlListaCategorias' => $this->template->loadPartial('lista_categorias', $dadosLista, 'curso/forum/forum'),
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

    public function listar($idCategoria)
    {
        try {
            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $categoria = $categoriaDao->recuperar($idCategoria);

            $this->_renderTemplateCurso($categoria->getCurso());
        } catch (Exception $e) {

        }
    }

    public function criar($idCategoria)
    {
        try {
            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $categoria = $categoriaDao->recuperar($idCategoria);

            $dadosFormCriar = array(
                'tituloAtual' => '',
                'descricaoAtual' => ''
            );

            $dadosViewCriar = array(
                'formAction' => 'forum/forum/salvar',
                'extraOpenForm' => 'id="form-criar-forum"',
                'hiddenFormData' => array('categoriaId' => $categoria->getId(), 'acao' => 'criar'),
                'formCriar' => $this->template->loadPartial('form', $dadosFormCriar, 'curso/forum/forum'),
                'textoBotaoSubmit' => 'Criar novo fórum!'
            );

            $this->_renderTemplateCurso($categoria->getCurso(), 'curso/forum/forum/criar', $dadosViewCriar);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir o formulário de criação de fórum: '
                                 . create_exception_description($e));

            show_404();
        }
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

            } catch (Exception $e) {

            }
        }

        echo $json;
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
            'menuContexto' => $this->template->loadPartial('menu', array('idCurso' => $curso->getId()), 'curso/forum')
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}