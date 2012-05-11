<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('forum.js');
    }

    public function index($idCurso)
    {
        $this->listar_categorias($idCurso);
    }

    public function listar($idCategoria)
    {
        try {
            $count = 10;

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $categoria = $categoriaDao->recuperar($idCategoria);

            $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');

            $filtro = $this->input->get('f');

            try {
                $listaForuns = $this->_recuperarForuns($categoria, $filtro);
            } catch (cassandra_NotFoundException $e) {
                $listaForuns = array();
            }

            $this->load->helper('paginacao_cassandra');
            $dadosPaginacao = create_paginacao_cassandra($listaForuns, $count);

            $dadosPartialLista = array( 'listaForuns' => $listaForuns );
            $partialLista = $this->template->loadPartial('lista', $dadosPartialLista, 'curso/forum/forum');

            $dadosView = array(
                'tituloLista' => $this->_tituloLista($filtro),
                'categoria' => $categoria,
                'qtdTodos' => $forumDao->recuperarQtdTotalPorCategoria($categoria),
                'qtdAtivos' => $forumDao->recuperarQtdTotalPorCategoriaEStatus($categoria, WeLearn_Cursos_Foruns_StatusForum::ATIVO),
                'qtdInativos' => $forumDao->recuperarQtdTotalPorCategoriaEStatus($categoria, WeLearn_Cursos_Foruns_StatusForum::INATIVO),
                'partialLista' => $partialLista,
                'haForuns' => !empty($listaForuns),
                'haMaisPaginas' => $dadosPaginacao['proxima_pagina'],
                'inicioProxPagina' => $dadosPaginacao['inicio_proxima_pagina'],
            );

            $this->_renderTemplateCurso($categoria->getCurso(), 'curso/forum/forum/listar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao listar os fóruns: ' . create_exception_description($e));

            show_404();
        }
    }

    public function proxima_pagina($idCategoria, $inicio)
    {
        if ( !$this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 10;

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $categoria = $categoriaDao->recuperar($idCategoria);

            $listaForuns = $this->_recuperarForuns($categoria, $this->input->get('f'), $inicio);

            $this->load->helper('Paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaForuns, $count);

            $response = array(
                'success' => true,
                'htmlListaForuns' => $this->template->loadPartial('lista', array('listaForuns'=>$listaForuns), 'curso/forum/forum'),
                'paginacao' => $paginacao
            );

            $json = Zend_Json::encode($response);
        } catch(Exception $e) {
            log_message('error', 'Ocorreu um erro ao recuperar outra página de fóruns: '
                                 . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos verificando. Tente novamente mais tarde.');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function listar_categorias($idCurso)
    {
        try {
            $count = ColumnFamily::DEFAULT_COLUMN_COUNT;

            $curso = $this->_cursoDao->recuperar($idCurso);

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

            $curso = $this->_cursoDao->recuperar($cursoId);

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $listaCategorias = $categoriaDao->recuperarTodosPorCurso($curso, $inicio, '', $count + 1);

            foreach( $listaCategorias as $categoria ) {
                $categoria->recuperarQtdForuns();
            }

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

    public function alterar($idForum)
    {
        try {
            $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
            $forum = $forumDao->recuperar($idForum);

            $dadosFormAlterar = array(
                'tituloAtual' => $forum->getTitulo(),
                'descricaoAtual' => $forum->getDescricao()
            );

            $dadosViewAlterar = array(
                'idForum' => $forum->getId(),
                'formAction' => 'forum/forum/salvar',
                'extraOpenForm' => 'id="form-alterar-forum"',
                'hiddenFormData' => array('forumId' => $forum->getId(), 'acao' => 'alterar'),
                'formAlterar' => $this->template->loadPartial('form', $dadosFormAlterar, 'curso/forum/forum'),
                'textoBotaoSubmit' => 'Salvar alterações!'
            );

            $this->_renderTemplateCurso($forum->getCategoria()->getCurso(), 'curso/forum/forum/alterar', $dadosViewAlterar);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir formulário de alteração de fórum: ' . create_exception_description($e));

            show_404();
        }
    }

    public function alterar_status($idForum)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }
        
        set_json_header();

        try {
            $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
            $forum = $forumDao->recuperar($idForum);

            $forum->alterarStatus();

            $forumDao->salvar($forum);

            $statusStr = $forum->getStatus() == WeLearn_Cursos_Foruns_StatusForum::ATIVO ? 'ativado' : 'desativado';

            $this->load->helper('notificacao_js');

            $notificacao = Zend_Json::encode(array(
                 'statusAtual' => $statusStr,
                 'notificacao' => create_notificacao_array(
                     'sucesso',
                     'O fórum <strong>' . $forum->getTitulo() . '</strong> foi ' . $statusStr . ' com sucesso!',
                     10000
                 )
             ));

            $json = create_json_feedback(true, '', $notificacao);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar alterar o status do fórum: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function remover($idForum)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');

            $forumRemovido = $forumDao->remover($idForum);

            $this->load->helper('notificacao_js');

            $notificacao = Zend_Json::encode(array(
                 'idCategoria' => $forumRemovido->getCategoria()->getId(),
                 'notificacao' => create_notificacao_array(
                     'sucesso',
                     'O fórum <strong>' . $forumRemovido->getTitulo() . '</strong> foi removido com sucesso!',
                     10000
                 )
             ));

            $json = create_json_feedback(true, '', $notificacao);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover um fórum: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
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
                $this->load->helper('notificacao_js');

                if ($this->input->post('acao') == 'criar') {
                    $json = $this->_salvarNovoForum( $this->input->post() );
                    $notificacoesFlash = create_notificacao_json(
                        'sucesso',
                        'O novo fórum foi criado com sucesso, na verdade você já está nele!<br/> Aguarde enquanto essa discussão se desenvolve.',
                        10000
                    );
                } elseif ($this->input->post('acao') == 'alterar') {
                    $json = $this->_salvarAlteracoesForum( $this->input->post() );
                    $notificacoesFlash = create_notificacao_json(
                        'sucesso',
                        'Fórum alterado com sucesso!',
                        10000
                    );
                } else {
                    throw new WeLearn_Base_Exception('Ação de salvar fórum inválida!');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Ocorreu um erro ao tentar salvar o fórum: ' . create_exception_description($e));

                $erro = create_json_feedback_error_json('Ocorreu um erro desconhecido ao salvar o fórum. Tente novamente mais tarde.');
                $json = create_json_feedback(false, $erro);
            }
        }

        echo $json;
    }

    private function _recuperarForuns(WeLearn_Cursos_Foruns_Categoria $categoria, $filtro, $inicio = '', $count = 10)
    {
        $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');

        switch ($filtro) {
            case 'todos':
                $listaForuns = $forumDao->recuperarTodosPorCategoria(
                    $categoria,
                    $inicio,
                    '',
                    $count + 1
                );
            break;
            case 'inativos':
                $listaForuns = $forumDao->recuperarTodosPorCategoriaEStatus(
                    $categoria,
                    WeLearn_Cursos_Foruns_StatusForum::INATIVO,
                    $inicio,
                    '',
                    $count + 1
                );
            break;
            case 'ativos':
            default:
                $listaForuns = $forumDao->recuperarTodosPorCategoriaEStatus(
                    $categoria,
                    WeLearn_Cursos_Foruns_StatusForum::ATIVO,
                    $inicio,
                    '',
                    $count + 1
                );
        }

        foreach($listaForuns as $forum) {
            $forum->recuperarQtdPosts();
        }

        return $listaForuns;
    }

    private function _salvarNovoForum(array $dadosPost)
    {
        $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
        $categoria = $categoriaDao->recuperar($dadosPost['categoriaId']);

        $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');

        $novoForum = $forumDao->criarNovo($dadosPost);
        $novoForum->setCategoria($categoria);
        $novoForum->setCriador($this->autenticacao->getusuarioAutenticado());

        $forumDao->salvar($novoForum);

        return create_json_feedback(true, '', Zend_Json::encode(array('idForum' => $novoForum->getId())));
    }

    private function _salvarAlteracoesForum(array $dadosPost)
    {
        $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
        $forum = $forumDao->recuperar($dadosPost['forumId']);

        $forum->preencherPropriedades($dadosPost);

        $forumDao->salvar($forum);

        return create_json_feedback(true, '', Zend_Json::encode(array('idForum' => $forum->getId())));
    }

    protected function _renderTemplateCurso(WeLearn_Cursos_Curso $curso,
                                            $view = '',
                                            array $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                array( 'idCurso' => $curso->getId() ),
                'curso/forum'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }

    private function _tituloLista($filtro)
    {
        switch ($filtro) {
            case 'todos': return '- Todos os Fóruns';
            case 'ativos': return ' - Fóruns Ativos';
            case 'inativos': return ' - Fóruns Inativos';
            default: return '';
        }
    }
}