<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerenciador extends Curso_Controller
{
    /**
     * @var GerenciadorAuxiliarDAO
     */
    private $_gerenciadorDao;

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('gerenciador.js');

        $this->_gerenciadorDao = WeLearn_DAO_DAOFactory::create('GerenciadorAuxiliarDAO');
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar( $idCurso );

            try {
                $listaGerenciadores = $this->_gerenciadorDao->recuperarTodosPorCurso($curso, '', '', $count + 1);
                $totalGerenciadores = $this->_gerenciadorDao->recuperarQtdTotalPorCurso($curso);
            } catch(cassandra_NotFoundException $e) {
                $listaGerenciadores = array();
                $totalGerenciadores = 0;
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaGerenciadores, $count);

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haGerenciadores' => $totalGerenciadores > 0,
                'qtdGerenciadores' => count( $listaGerenciadores ),
                'totalGerenciadores' => $totalGerenciadores,
                'listaGerenciadores' => $this->template->loadPartial(
                    'lista_gerenciadores',
                    array(
                        'idCurso' => $curso->getId(),
                        'listaGerenciadores' => $listaGerenciadores
                    ),
                    'curso/gerenciador'
                ),
                'haMaisPaginas' => $paginacao['proxima_pagina'],
                'idProximo' => $paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateCurso($curso, '/curso/gerenciador/listar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir lista de gerenciadores do curso.'
                . create_exception_description($e));

            show_404();
        }
    }

    public function mais_gerenciadores ($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $inicio = $this->input->get('proximo');

            try {
                $listaGerenciadores = $this->_gerenciadorDao->recuperarTodosPorCurso($curso, $inicio, '', $count + 1);
            } catch(cassandra_NotFoundException $e) {
                $listaGerenciadores = array();
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaGerenciadores, $count);

            $response = Zend_Json::encode(array(
                'htmlGerenciadores' => $this->template->loadPartial(
                    'lista_gerenciadores',
                    array(
                        'listaGerenciadores' => $listaGerenciadores,
                        'idCurso' => $curso->getId()
                    ),
                    'curso/gerenciador'
                ),
                'qtdGerenciadores' => count( $listaGerenciadores ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar recuperar outra página da lista de gerenciadores do curso :'
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function convites ($idCurso)
    {
        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar( $idCurso );

            try {
                $listaConvites = $this->_gerenciadorDao->recuperarTodosConvitesPorCurso( $curso, '', '', $count + 1 );
                $totalConvites = $this->_gerenciadorDao->recuperarQtdTotalConvitesPorCurso( $curso );
            } catch (cassandra_NotFoundException $e) {
                $listaConvites = array();
                $totalConvites = 0;
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaConvites, $count);

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haConvites' => $totalConvites > 0,
                'qtdConvites' => count( $listaConvites ),
                'totalConvites' => $totalConvites,
                'listaConvites' => $this->template->loadPartial(
                    'lista_convites',
                    array(
                        'listaConvites' => $listaConvites,
                        'idCurso' => $curso->getId()
                    ),
                    'curso/gerenciador'
                ),
                'haMaisPaginas' => $paginacao['proxima_pagina'],
                'idProximo' => $paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateCurso($curso, 'curso/gerenciador/convites', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir listagem de convites pendentes:'
                . create_exception_description($e));

            show_404();
        }
    }

    public function mais_convites ($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $inicio = $this->input->get('proximo');

            try {
                $listaConvites = $this->_gerenciadorDao->recuperarTodosConvitesPorCurso($curso, $inicio, '', $count + 1);
            } catch (cassandra_NotFoundException $e) {
                $listaConvites = array();
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaConvites, $count);

            $response = Zend_Json::encode(array(
                'htmlConvites' => $this->template->loadPartial(
                    'lista_convites',
                    array(
                        'listaConvites' => $listaConvites,
                        'idCurso' => $curso->getId()
                    ),
                    'curso/gerenciador'
                ),
                'qtdConvites' => count( $listaConvites ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar recuperar outra página de convites para gerenciamento :'
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function cancelar_convite( $idCurso )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $idUsuario = $this->input->get('usuarioId');

            $usuario = $this->_gerenciadorDao->recuperar( $idUsuario );

            $this->_gerenciadorDao->cancelarConvite( $usuario, $curso );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O convite de gerenciamento enviado ao usuário "'
                        . $usuario->getNome() . '" foi cancelado com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar cancelar convite para gerenciamento :'
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function aceitar_convite($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $usuario = $this->autenticacao->getUsuarioAutenticado();

            $this->_gerenciadorDao->aceitarConvite( $usuario, $curso );

            $this->load->helper('notificacao_js');

            $this->session->set_flashdata('notificacoesFlash', create_notificacao_json(
                'sucesso',
                'Parabéns! Vocẽ acaba de se tornar o mais novo gerenciador deste curso, dê uma olhada nas ações dísponíveis!<br>
                Mas cuidado! Grandes poderes acompanham grandes responsabilidades ;)',
                10000
            ));

            $response = Zend_Json::encode(array(
                'urlCurso' => site_url('/curso/' . $curso->getId())
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar aceitar convite para gerenciamento :'
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function recusar_convite($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $usuario = $this->autenticacao->getUsuarioAutenticado();

            $this->_gerenciadorDao->recusarConvite( $usuario, $curso );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O Convite para gerenciamento do curso <em>"'
                        . $curso->getNome() . '"</em> foi recusado com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar recusar convite para gerenciamento :'
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function desvincular($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $idGerenciador = $this->input->get('gerenciadorId');

            $gerenciador = $this->_gerenciadorDao->criarGerenciadorAuxiliar(
                $this->_gerenciadorDao->recuperar( $idGerenciador )
            );

            $this->_gerenciadorDao->desvincular( $gerenciador, $curso );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O usuário <em>"'
                        . $gerenciador->getNome() . '"</em> não faz mais parte do gerenciamento deste curso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar desvincular gerenciador :'
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function convidar ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'formAction' => '/curso/gerenciador/buscar_usuarios',
                'extraOpenForm' => 'id="form-gerenciador-buscar-usuarios"',
                'formHidden' => array( 'inicio' => 0 )
            );

            $this->_renderTemplateCurso($curso, 'curso/gerenciador/convidar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário de busca e convite de usuários: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function buscar_usuarios()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            $termo = $this->input->post('termo');
            $inicio = (int) $this->input->post('inicio');

            try {
                $usuariosEncontrados = $this->_gerenciadorDao->recuperarTodos(
                    $inicio,
                    null,
                    array(
                        'busca' => $termo,
                        'count' => $count + 1
                    )
                );
            } catch (cassandra_NotFoundException $e) {
                $usuariosEncontrados = array();
            }

            $this->load->helper( 'paginacao_mysql' );
            $paginacao = create_paginacao_mysql( $usuariosEncontrados, $inicio, $count );

            $response = Zend_Json::encode(array(
                'htmlResultados' => $this->template->loadPartial(
                    'lista_usuarios',
                    array('listaUsuarios' => $usuariosEncontrados),
                    'curso/gerenciador'
                ),
                'qtdResultados' => count( $usuariosEncontrados ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar buscar usuários para convidar para gerenciamento: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function enviar_convites($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $idUsuarios = explode( ',', $this->input->get('usuarios') );

            $errors = array();

            foreach ($idUsuarios as $idUsuario) {

                try {
                    $usuario = $this->_gerenciadorDao->recuperar( $idUsuario );

                    $vinculo = $this->_cursoDao->recuperarTipoDeVinculo($usuario, $curso);

                    switch ( $vinculo ) {

                        case WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO_INSCRICAO_PENDENTE:
                            $errors[] =  anchor( '/perfil/' . $usuario->getId(), $usuario->getNome() )
                                      . ': Há uma requisição de inscrição no curso pendente vindo deste usuário.';
                            break;

                        case WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_CONVITE_PENDENTE:
                            $errors[] = anchor( '/perfil/' . $usuario->getId(), $usuario->getNome() )
                                      . ': Este usuário já foi convidado para o gerenciamento deste curso.';
                            break;

                        case WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR:
                        case WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_PRINCIPAL:
                            $errors[] = anchor( '/perfil/' . $usuario->getId(), $usuario->getNome() )
                                      . ': Este usuário já está vinculado ao gerenciamento deste curso.';
                            break;

                        case WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO:
                            $errors[] = anchor( '/perfil/' . $usuario->getId(), $usuario->getNome() )
                                      . ': Este usuário terá que deixar de ser aluno para ser convidado ao gerenciamento.';
                            break;
                        case WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO:
                        default:
                            $this->_gerenciadorDao->convidar( $usuario, $curso );

                    }

                } catch ( Exception $e ) {
                    $errors[] = 'Não foi possível enviar convite ao usuário "'
                        . $idUsuario . '". Tente novamente mais tarde.';
                }

            }

            $this->load->helper('notificacao_js');

            if ( count( $errors ) > 0 ) {

                $errorsHtml = '';
                foreach ($errors as $error) {
                    $errorsHtml .= "<li>{$error}</li>";
                }

                $notificacoesFlash = create_notificacao_json(
                    'erro',
                    "<div><span>Alguns convites não foram enviados devido à erros, mais informações abaixo:</span><ul>{$errorsHtml}</ul></div>",
                    0
                );

            } else {

                $notificacoesFlash = create_notificacao_json(
                    'sucesso',
                    'Todos os convites foram enviados aos destinatários com sucesso!'
                );

            }

            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

            $json = create_json_feedback(true);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar buscar usuários para convidar para gerenciamento: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                   Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
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
                array(
                    'idCurso' => $curso->getId(),
                    'totalGerenciadores' => $this->_gerenciadorDao->recuperarQtdTotalPorCurso( $curso ),
                    'totalConvites' => $this->_gerenciadorDao->recuperarQtdTotalConvitesPorCurso( $curso )
                ),
                'curso/gerenciador'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
