<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('categoria_forum.js');
    }

    public function index($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar($idCurso)
    {
        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar($idCurso);

            $this->_expulsarNaoAutorizados($curso);

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');

            try {
                $listaCategorias = $categoriaDao->recuperarTodosPorCurso($curso, '', '', $count + 1);
            } catch (cassandra_NotFoundException $e) {
                $listaCategorias = array();
            }

            $this->load->helper('paginacao_cassandra');
            $dados_paginacao = create_paginacao_cassandra($listaCategorias, $count);

            $dadosLista = array(
                'listaCategorias' => $listaCategorias
            );

            $dadosViewListar = array(
                'idCurso' => $curso->getId(),
                'haCategorias' => !empty($listaCategorias),
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

            $curso = $this->_cursoDao->recuperar($cursoId);

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
            $curso = $this->_cursoDao->recuperar($idCurso);

            $this->_expulsarNaoAutorizados($curso);

            $dadosFormCriar = array(
                'nomeAtual' => '',
                'descricaoAtual' => ''
            );

            $dadosViewCriar = array(
                'formAction' => 'forum/categoria/salvar',
                'extraOpenForm' => 'id="form-criar-categoria-forum"',
                'hiddenFormData' => array('cursoId' => $curso->getId(), 'acao' => 'criar'),
                'formCriar' => $this->template->loadPartial('form', $dadosFormCriar, 'curso/forum/categoria'),
                'textoBotaoSubmit' => 'Criar nova categoria!'
            );

            $this->_renderTemplateCurso($curso, 'curso/forum/categoria/criar', $dadosViewCriar);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir formulário de criação de categoria de fórum: ' . create_exception_description($e));

            show_404();
        }
    }

    public function alterar($id)
    {
        try {
            $UUID = CassandraUtil::import($id);

            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
            $categoria = $categoriaDao->recuperar($UUID);

            $this->_expulsarNaoAutorizados($categoria->getCurso());

            $dadosFormAlterar = array(
                'nomeAtual' => $categoria->getNome(),
                'descricaoAtual' => $categoria->getDescricao()
            );

            $dadosViewAlterar = array(
                'idCurso' => $categoria->getCurso()->getId(),
                'formAction' => 'forum/categoria/salvar',
                'extraOpenForm' => 'id="form-alterar-categoria-forum"',
                'hiddenFormData' => array ('categoriaId' => $categoria->getId(), 'acao' => 'alterar'),
                'formAlterar' => $this->template->loadPartial('form', $dadosFormAlterar, 'curso/forum/categoria'),
                'textoBotaoSubmit' => 'Salvar!'
            );

            $this->_renderTemplateCurso($categoria->getCurso(), 'curso/forum/categoria/alterar', $dadosViewAlterar);
        } catch(Exception $e) {
            log_message('error', 'Erro ao exibir formulário de alteração de categoria de fórum:' . create_exception_description($e));
            show_404();
        }
    }

    public function remover($id)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');

            $categoriaRemovida = $categoriaDao->remover($id);

            $this->load->helper('notificacao_js');

            $dadosNotificacao = array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A categoria <strong>' . $categoriaRemovida->getNome() . '</strong> foi removida com sucesso!',
                    10000
                )
            );

            $json = create_json_feedback(true, '', Zend_Json::encode($dadosNotificacao));
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar remover uma categoria de fórum: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado ao remover esta categoria.'
                                                    .'Estamos verificando no momento, tente mais tarde.');

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
                $dadosCategoria = $this->input->post();
                $categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');

                $this->load->helper('notificacao_js');

                if (isset($dadosCategoria['acao']) && $dadosCategoria['acao'] == 'criar') {

                    $dadosCategoria['curso'] = $this->_cursoDao->recuperar($dadosCategoria['cursoId']);
                    $dadosCategoria['criador'] = $this->autenticacao->getUsuarioAutenticado();

                    $novaCategoria = $categoriaDao->criarNovo($dadosCategoria);
                    $categoriaDao->salvar($novaCategoria);

                    $notificacoesFlash = create_notificacao_json(
                        'sucesso',
                        'A nova categoria de fóruns foi criada com sucesso. <br/> Comece a adicionar fóruns à esta categoria!',
                        10000
                   );

                    $json = create_json_feedback(true, '', '"idCurso":"' . $novaCategoria->getCurso()->getid() . '"');
                } elseif (isset($dadosCategoria['acao']) && $dadosCategoria['acao'] == 'alterar') {
                    $categoria = $categoriaDao->recuperar($dadosCategoria['categoriaId']);
                    $categoria->preencherPropriedades($dadosCategoria);
                    $categoriaDao->salvar($categoria);

                    $notificacoesFlash = create_notificacao_json(
                        'sucesso',
                        'A categoria <strong>' . $categoria->getNome() . '</strong> foi alterada com sucesso!',
                        10000
                    );

                    $json = create_json_feedback(true, '', '"idCurso":"' . $categoria->getCurso()->getId() . '"');
                } else {
                    throw new WeLearn_Base_Exception('Ação de salvar categoria de fórum inválida!');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Erro a criar categoria de fórum: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');
                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
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
}