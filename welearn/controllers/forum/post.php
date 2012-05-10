<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends WL_Controller
{
    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('post_forum.js');

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }

    public function index($idForum)
    {
        $this->listar($idForum);
    }

    public function listar($idForum)
    {
        try {
            $this->template->appendJSImport('forum.js');

            $count = 10;

            $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
            $forum = $forumDao->recuperar($idForum);
            $forum->recuperarQtdPosts();

            $dadosViewCriar = array(
                'formAction' => '/forum/post/salvar',
                'extraOpenForm' => 'id="form-criar-post"',
                'formHidden' => array('idForum' => $forum->getId(), 'acao' => 'criar'),
                'tituloAtual' => '',
                'conteudoAtual' => '',
                'textoBotaoSubmit' => 'Postar!'
            );

            $formCriar = $this->template->loadPartial('form', $dadosViewCriar, 'curso/forum/post');

            $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');

            try {
                $listaPosts = $postDao->recuperarTodosPorForum($forum, '', '', $count + 1);
            } catch (cassandra_NotFoundException $e) {
                $listaPosts = array();
            }

            $this->load->helper('paginacao_cassandra');

            $dadosPaginacao = create_paginacao_cassandra($listaPosts, $count);

            //A exibição será debaixo para cima! REVERSE!
            $listaPosts = array_reverse($listaPosts);

            $dadosListaView = array(
                'listaPosts' => $listaPosts
            );

            $htmlListaPost = $this->template->loadPartial('lista', $dadosListaView, 'curso/forum/post');

            $dadosListarView = array(
                'forum' => $forum,
                'haPosts' => !empty($listaPosts),
                'listaPosts' => $htmlListaPost,
                'haMaisPosts' => $dadosPaginacao['proxima_pagina'],
                'inicioProxPagina' => $dadosPaginacao['inicio_proxima_pagina'],
                'formCriar' => $formCriar
            );

            $this->_renderTemplateCurso($forum->getCategoria()->getCurso(), 'curso/forum/post/listar', $dadosListarView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar listar posts de um fórum: ' . create_exception_description($e));

            show_404();
        }
    }

    public function proxima_pagina($idForum, $idProximo)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
            $forum = $forumDao->recuperar($idForum);

            $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');

            $listaPosts = $postDao->recuperarTodosPorForum($forum, $idProximo, '', $count + 1);

            $this->load->helper('paginacao_cassandra');

            $dados_paginacao = create_paginacao_cassandra($listaPosts, $count);

            $listaPosts = array_reverse($listaPosts);

            $htmlListaPosts = $this->template->loadPartial('lista', array('listaPosts' => $listaPosts), 'curso/forum/post');

            $json = create_json_feedback(true, '', Zend_Json::encode(array(
                'htmlListaPosts' => $htmlListaPosts,
                'dadosPaginacao' => $dados_paginacao
            )));
        } catch (Exception $e) {
            log_message('error', 'Erro ao recuperar página mais antiga de posts: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos tentando resolver, tente novamente amis tarde.');

            $json = create_json_feedback('false', $error);
        }

        echo $json;
    }

    public function alterar($idPost)
    {
        try {
            $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');

            $post = $postDao->recuperar($idPost);

            $dadosFormAlterar = array(
                'formAction' => '/curso/forum/post/salvar',
                'extraOpenForm' => 'id="form-alterar-post"',
                'formHidden' => array( 'idPost' => $post->getId(), 'acao' => 'alterar' ),
                'tituloAtual' => $post->getTitulo(),
                'conteudoAtual' => $post->getConteudo(),
                'textoBotaoSubmit' => 'Salvar alterações!'
            );

            $formAlterar = $this->template->loadPartial('form', $dadosFormAlterar, 'curso/forum/post');

            $dadosView = array(
                'post' => $post,
                'formAlterar' => $formAlterar
            );

            $this->_renderTemplateCurso( $post->getForum()->getCategoria()->getCurso(), 'curso/forum/post/alterar', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir formulário de edição de post: ' . create_exception_description($e));

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

                if ( $this->input->post('acao') == 'criar' ) {
                    $json = $this->_salvarNovoPost( $this->input->post() );
                } elseif ( $this->input->post('acao') == 'alterar' ) {
                    $json = $this->_salvarAlteracoesPost( $this->input->post() );
                } else {
                    throw new WeLearn_Base_Exception('Ação inválida ao salvar um post.');
                }

            } catch (Exception $e) {
                log_message('error', 'Erro ao salvar post: ' . create_exception_description($e));

                $erro = create_json_feedback_error_json('Ocorreu um erro inexperado! Já estamos verificando, tente novamente mais tarde.');

                $json = create_json_feedback(false, $erro);
            }
        }

        echo $json;
    }

    public function remover ($idPost)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');

            $postDao->remover($idPost);

            $this->load->helper('notificacao_js');

            $extraJson = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O seu post foi removido com sucesso!',
                    10000
                )
            ));

            $json = create_json_feedback(true, '', $extraJson);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover um post: ' . create_exception_description($e));

            $erro = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');

            $json = create_json_feedback(false, $erro);
        }

        echo $json;
    }

    private function _salvarNovoPost (array $dadosPost)
    {
        $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
        $forum = $forumDao->recuperar( $dadosPost['idForum'] );

        $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');
        $novoPost = $postDao->criarNovo( $dadosPost );

        $novoPost->setForum($forum);
        $novoPost->setCriador( $this->autenticacao->getUsuarioAutenticado() );

        $postDao->salvar($novoPost);

        $listaPosts = array($novoPost);

        $extraFeedBackJson = Zend_Json::encode(array(
            'htmlNovoPost' => $this->template->loadPartial(
                'lista',
                array( 'listaPosts' => $listaPosts ),
                'curso/forum/post'
            )
        ));

        return create_json_feedback(true, '', $extraFeedBackJson);
    }

    private function _salvarAlteracoesPost (array $dadosPost)
    {
        $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');

        $post = $postDao->recuperar( $dadosPost['idPost'] );
        $post->preencherPropriedades($dadosPost);

        $postDao->salvar($post);

        $notificacoesFlash = Zend_Json::encode(array(
            'nivel' => 'sucesso',
            'msg' => 'O post foi alterado com sucesso!',
            'tempo' => 10000
        ));

        $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

        return create_json_feedback(true, '', Zend_Json::encode(array( 'idForum' => $post->getForum()->getId() )));
    }

    public function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
    {
        $vinculo = $this->_cursoDao->recuperarTipoDeVinculo(
            $this->autenticacao->getUsuarioAutenticado(),
            $curso
        );

        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao(),
            'usuarioNaoVinculado' => $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO,
            'usuarioPendente' => ($vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO_INSCRICAO_PENDENTE
                              || $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_CONVITE_PENDENTE),
            'idCurso' => $curso->getId(),
            'menuContexto' => $this->template->loadPartial('menu', array('idCurso' => $curso->getId()), 'curso/forum')
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}