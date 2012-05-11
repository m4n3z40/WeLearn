<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 24/04/12
 * Time: 15:09
 * To change this template use File | Settings | File Templates.
 */
class Comentario extends Curso_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('comentario.js');
    }

    function index ( $idCurso )
    {
        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

            try {
                $listaModulos = $moduloDao->recuperarTodosPorCurso( $curso );
            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
            }

            $listaAulas = array();
            $listaPaginas = array();
            $moduloSelecionado = '0';
            $aulaSelecionada = '0';
            $paginaSelecionada = '0';
            if ( $paginaId = $this->input->get('p') ) {
                $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
                $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

                $pagina = $paginaDao->recuperar( $paginaId );

                try {
                    $listaAulas = $aulaDao->recuperarTodosPorModulo( $pagina->getAula()->getModulo() );
                } catch (cassandra_NotFoundException $e) {
                    $listaAulas = array();
                }

                try {
                    $listaPaginas = $paginaDao->recuperarTodosPorAula( $pagina->getAula() );
                } catch (cassandra_NotFoundException $e) {
                    $listaPaginas = array();
                }

                $moduloSelecionado = $pagina->getAula()->getModulo()->getId();
                $aulaSelecionada = $pagina->getAula()->getId();
                $paginaSelecionada = $pagina->getId();
            }

            $this->load->helper('modulo');
            $dadosSelectModulo = array(
                'listaModulos' => lista_modulos_para_dados_dropdown( $listaModulos ),
                'moduloSelecionado' => $moduloSelecionado,
                'extra' => 'id="slt-modulos"'
            );

            $this->load->helper('aula');
            $dadosSelectAulas = array(
                'listaAulas' => lista_aulas_para_dados_dropdown( $listaAulas ),
                'aulaSelecionada' => $aulaSelecionada,
                'extra' => 'id="slt-aulas"'
            );

            $this->load->helper('pagina');
            $dadosSelectPaginas = array(
                'listaPaginas' => lista_paginas_para_dados_dropdown( $listaPaginas ),
                'paginaSelecionada' => $paginaSelecionada,
                'extra' => 'id="slt-paginas"'
            );

            $dadosFormCriar = array(
                'formAction' => 'conteudo/comentario/salvar',
                'extraOpenForm' => 'id="form-comentario-criar"',
                'formHidden' => array('acao' => 'criar', 'paginaId' => ''),
                'assuntoAtual' => '',
                'txtComentarioAtual' => '',
                'idBotaoEnviar' => 'btn-form-comentario-criar',
                'txtBotaoEnviar' => 'Postar Comentário!'
            );

            $dadosView = array(
                'paginaSelecionada' => ( $paginaId ) ? true : false,
                'selectModulos' => $this->template->loadPartial(
                    'select_modulos',
                    $dadosSelectModulo,
                    'curso/conteudo'
                ),
                'selectAulas' => $this->template->loadPartial(
                    'select_aulas',
                    $dadosSelectAulas,
                    'curso/conteudo'
                ),
                'selectPaginas' => $this->template->loadPartial(
                    'select_paginas',
                    $dadosSelectPaginas,
                    'curso/conteudo'
                ),
                'idCurso' => $curso->getId(),
                'formCriar' => $this->template->loadPartial(
                    'form',
                    $dadosFormCriar,
                    'curso/conteudo/comentario'
                )
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/comentario/index',
                $dadosView
            );

        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir a index de comentarios
                                  de aulas: ' . create_exception_description($e));

            show_404();
        }
    }

    public function recuperar_lista( $paginaId )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 50;

            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
            $pagina = $paginaDao->recuperar( $paginaId );

            $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioDAO');

            if ( ! ( $de = $this->input->get('inicioProxPagina') ) ) { $de = ''; }

            try {
                $listaComentarios = $comentarioDao->recuperarTodosPorPagina(
                    $pagina,
                    $de,
                    '',
                    $count + 1
                );
            } catch (cassandra_NotFoundException $e) {
                $listaComentarios = array();
            }

            $totalComentarios = $comentarioDao->recuperarQtdTotalPorPagina( $pagina );

            $this->load->helper('paginacao_cassandra');
            $dadosPaginacao = create_paginacao_cassandra($listaComentarios, $count);
            $qtdComentarios = count($listaComentarios);

            //Para possibilitar a visualização de baixo para cima.
            $listaComentarios = array_reverse($listaComentarios);

            $response = Zend_Json::encode(array(
                'htmlListaComentarios' => ( $qtdComentarios )
                                          ? $this->template->loadPartial(
                                                'lista',
                                                array(
                                                    'listaComentarios' => $listaComentarios
                                                ),
                                                'curso/conteudo/comentario'
                                          )
                                          : '',
                'paginacao' => $dadosPaginacao,
                'qtdComentarios' => $qtdComentarios,
                'totalComentarios' => $totalComentarios,
                'nomePagina' => $pagina->getNome(),
                'nomeAula' => $pagina->getAula()->getNome()
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar lista de
                                    comentários via ajax: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function alterar ($comentarioId)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioDAO');
            $comentario = $comentarioDao->recuperar( $comentarioId );

            $dadosForm = array(
                'formAction' => '/conteudo/comentario/salvar',
                'extraOpenForm' => 'id="form-comentario-alterar"',
                'formHidden' => array(
                    'acao' => 'alterar',
                    'comentarioId' => $comentario->getId()
                ),
                'assuntoAtual' => $comentario->getAssunto(),
                'txtComentarioAtual' => $comentario->getTxtComentario(),
                'idBotaoEnviar' => 'btn-form-comentario-alterar',
                'txtBotaoEnviar' => 'Salvar Comentário!'
            );

            $htmlForm = $this->load->view(
                'curso/conteudo/comentario/alterar',
                array (
                    'comentario' => $comentario,
                    'form' => $this->template->loadPartial(
                        'form',
                        $dadosForm,
                        'curso/conteudo/comentario'
                    )
                ),
                true
            );

            $response = Zend_Json::encode(array(
                'htmlFormAlterar' => $htmlForm
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário de alteração comentário: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function remover ($comentarioId)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioDAO');
            $comentarioRemovido = $comentarioDao->remover( $comentarioId );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O Comentário foi removido com sucesso da página <em>"'
                        . $comentarioRemovido->getPagina()->getNome() . '"</em>!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar remover comentário: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar ()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $this->load->helper('notificacao_js');
            $this->load->library('form_validation');

            if ( ! $this->form_validation->run() ) {
                $json = create_json_feedback(false, validation_errors_json());
            } else {

                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criar( $this->input->post() );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação ao salvar inválida!');
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar salvar comentário: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    private function _criar ($post)
    {
        $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
        $pagina = $paginaDao->recuperar( $post['paginaId'] );

        $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioDAO');
        $novoComentario = $comentarioDao->criarNovo( $post );
        $novoComentario->setPagina( $pagina );
        $novoComentario->setCriador( $this->autenticacao->getUsuarioAutenticado() );

        $comentarioDao->salvar( $novoComentario );

        $response = Zend_Json::encode(array(
            'htmlNovoComentario' => $this->template->loadPartial(
                'lista',
                array( 'listaComentarios' => array($novoComentario) ),
                'curso/conteudo/comentario'
            ),
            'notificacao' => create_notificacao_array(
                'sucesso',
                'O Comentário foi postado na página <em>"' . $pagina->getNome() . '"</em> com sucesso!'
            )
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _alterar ($post)
    {
        $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioDAO');
        $comentario = $comentarioDao->recuperar( $post['comentarioId'] );

        $comentario->preencherPropriedades( $post );

        $comentarioDao->salvar( $comentario );

        $this->load->helper('notificacao_js');

        $response = Zend_Json::encode(array(
            'htmlComentarioAlterado' => $this->template->loadPartial(
                'lista',
                array( 'listaComentarios' => array($comentario) ),
                'curso/conteudo/comentario'
            ),
            'notificacao' => create_notificacao_array(
                'sucesso',
                'O comentário foi alterado com sucesso!'
            )
        ));

        return create_json_feedback(true, '', $response);
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
                'curso/conteudo'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
