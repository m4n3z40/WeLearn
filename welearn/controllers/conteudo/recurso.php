<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recurso extends Curso_Controller
{
    private $_tempRecursoDir;
    private $_recursoArquivosDir;

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('recurso.js');

        $this->_tempRecursoDir = TEMP_UPLOAD_DIR . 'recursos/';
        $this->_recursoArquivosDir = CURSOS_FILES_DIR . 'recursos/';
    }

    public function index ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            $this->_expulsarNaoAutorizados($curso);

            $dadosView = array(
                'idCurso' => $curso->getId()
            );

            $this->_renderTemplateCurso($curso, 'curso/conteudo/recurso/index', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir a index de recursos
                                  de curso: ' . create_exception_description($e));

            show_404();
        }
    }

    public function geral ($idCurso)
    {
        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar($idCurso);

            $this->_expulsarNaoAutorizados($curso);

            $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');

            try {
                $listaRecursos = $recursoDao->recuperarTodosGerais($curso,
                                                                   '',
                                                                   '',
                                                                   $count + 1);
            } catch (cassandra_NotFoundException $e) {
                $listaRecursos = array();
            }

            $this->load->helper('paginacao_cassandra');

            $dadosPaginacao = create_paginacao_cassandra($listaRecursos, $count);

            $dadosPartial = array(
                'listaRecursos' => $listaRecursos
            );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haRecursos' => ! empty( $listaRecursos ),
                'qtdExibindo' => count( $listaRecursos ),
                'qtdTotal' => $recursoDao->recuperarQtdTotalGerais($curso),
                'listaRecursos' => $this->template->loadPartial(
                    'lista',
                    $dadosPartial,
                    'curso/conteudo/recurso'
                ),
                'haMaisPaginas' => $dadosPaginacao['proxima_pagina'],
                'inicioProxPagina' => $dadosPaginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateCurso($curso,
                                        'curso/conteudo/recurso/geral',
                                        $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir lista geral de recursos
                                  de um curso: ' . create_exception_description($e));

            show_404();
        }
    }

    public function restrito ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            $this->_expulsarNaoAutorizados($curso);

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            try {
                $listaModulos = $moduloDao->recuperarTodosPorCurso($curso);
            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
            }

            $moduloSelecionado = '0';
            $listaAulas = array();
            $aulaSelecionada = '0';
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

            if ( $idModulo = $this->input->get('m') ) {
                $modulo = $moduloDao->recuperar($idModulo);

                try {
                    $listaAulas = $aulaDao->recuperarTodosPorModulo( $modulo );
                } catch(cassandra_NotFoundException $e) {

                }

                $moduloSelecionado = $modulo->getId();
            }

            if ( $idAula = $this->input->get('a') ) {
                $aula = $aulaDao->recuperar( $idAula );

                try {
                    $listaAulas = $aulaDao->recuperarTodosPorModulo( $aula->getModulo() );
                } catch(cassandra_NotFoundException $e) {

                }

                $moduloSelecionado = $aula->getModulo()->getId();
                $aulaSelecionada = $aula->getId();
            }

            $this->load->helper('modulo');

            $dadosSelectModulos = array(
                'listaModulos' => lista_modulos_para_dados_dropdown($listaModulos),
                'moduloSelecionado' => $moduloSelecionado,
                'extra' => 'id="slt-modulos"'
            );

            $this->load->helper('aula');

            $dadosSelectAulas = array(
                'listaAulas' => lista_aulas_para_dados_dropdown($listaAulas),
                'aulaSelecionada' => $aulaSelecionada,
                'extra' => 'id="slt-aulas"'
            );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'exibirAulas' => ($idModulo || $idAula),
                'selectModulos' => $this->template->loadPartial(
                    'select_modulos',
                    $dadosSelectModulos,
                    'curso/conteudo'
                ),
                'selectAulas' => $this->template->loadPartial(
                    'select_aulas',
                    $dadosSelectAulas,
                    'curso/conteudo'
                )
            );

            $this->_renderTemplateCurso($curso,
                                        'curso/conteudo/recurso/restrito',
                                        $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir lista restrita de recursos
                                  de um curso: ' . create_exception_description($e));

            show_404();
        }
    }

    public function recuperar_lista_restrita($idAula)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar( $idAula );

            $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');

            try {
                $listaRecursos = $recursoDao->recuperarTodosRestritos($aula,
                                                                      '',
                                                                      '',
                                                                      $count + 1);
            } catch (cassandra_NotFoundException $e) {
                $listaRecursos = array();
            }

            $this->load->helper('paginacao_cassandra');
            $dadosPaginacao = create_paginacao_cassandra($listaRecursos, $count);

            $dadosPartial = array(
                'listaRecursos' => $listaRecursos
            );

            $dadosView = array(
                'idCurso' => $aula->getModulo()->getCurso()->getId(),
                'haRecursos' => ! empty( $listaRecursos ),
                'qtdExibindo' => count( $listaRecursos ),
                'qtdTotal' => $recursoDao->recuperarQtdTotalRestritoS($aula),
                'listaRecursos' => $this->template->loadPartial(
                    'lista',
                    $dadosPartial,
                    'curso/conteudo/recurso'
                ),
                'haMaisPaginas' => $dadosPaginacao['proxima_pagina'],
                'inicioProxPagina' => $dadosPaginacao['inicio_proxima_pagina'],
                'idAula' => $aula->getId()
            );

            $response = Zend_Json::encode(array(
                'htmlListaRecursos' => $this->template->loadPartial(
                    'lista_restrita',
                    $dadosView,
                    'curso/conteudo/recurso'
                )
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {
            log_message('error','Ocorreu um erro ao tentar recuperar lista de recursos
                        restritos: ' . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function recuperar_proxima_pagina($tipoRecurso, $idParent, $idInicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 10;

            $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');

            try {
                if ( ( (int)$tipoRecurso ) == WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO ) {

                    $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
                    $aula = $aulaDao->recuperar( $idParent );

                    $listaRecursos = $recursoDao->recuperarTodosRestritos($aula,
                                                                          $idInicio,
                                                                          '',
                                                                          $count + 1);

                } else {

                    $curso = $this->_cursoDao->recuperar( $idParent );

                    $listaRecursos = $recursoDao->recuperarTodosGerais($curso,
                                                                       $idInicio,
                                                                       '',
                                                                       $count + 1);

                }
            } catch (cassandra_NotFoundException $e) {
                $listaRecursos = array();
            }

            $this->load->helper('paginacao_cassandra');

            $dadosPaginacao = create_paginacao_cassandra($listaRecursos, $count);

            $response = Zend_Json::encode(array(
                'htmlListaRecursos' => $this->template->loadPartial(
                    'lista',
                    array('listaRecursos' => $listaRecursos),
                    'curso/conteudo/recurso'
                ),
                'qtdRecuperados' => count( $listaRecursos ),
                'paginacao' => $dadosPaginacao
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {
            log_message('error','Ocorreu um erro ao tentar recuperar outra página
                        da lista de recursos: ' . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function recuperar_recursos_aluno($tipoRecurso, $idParent, $idInicio = '')
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 5;

            $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');

            try {
                if ( ( (int)$tipoRecurso ) == WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO ) {

                    $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
                    $aula = $aulaDao->recuperar( $idParent );

                    $listaRecursos = $recursoDao->recuperarTodosRestritos($aula,
                                                                          $idInicio,
                                                                          '',
                                                                          $count + 1);

                    $totalRecursos = $recursoDao->recuperarQtdTotalRestritos( $aula );

                } else {

                    $curso = $this->_cursoDao->recuperar( $idParent );

                    $listaRecursos = $recursoDao->recuperarTodosGerais($curso,
                                                                       $idInicio,
                                                                       '',
                                                                       $count + 1);

                    $totalRecursos = $recursoDao->recuperarQtdTotalGerais( $curso );

                }
            } catch (cassandra_NotFoundException $e) {
                $listaRecursos = array();
                $totalRecursos = 0;
            }

            $this->load->helper('paginacao_cassandra');

            $dadosPaginacao = create_paginacao_cassandra($listaRecursos, $count);

            $response = Zend_Json::encode(array(
                'htmlListaRecursos' => $this->template->loadPartial(
                    'lista_aluno',
                    array( 'listaRecursos' => $listaRecursos ),
                    'curso/conteudo/recurso'
                ),
                'qtdRecuperados' => count( $listaRecursos ),
                'totalRecursos' => $totalRecursos,
                'nomeAula' => isset( $aula ) ? $aula->getNome() : '',
                'paginacao' => $dadosPaginacao
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {
            log_message('error','Ocorreu um erro ao tentar recuperar lista de recursos para sala de aula: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function criar ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $this->_expulsarNaoAutorizados($curso);

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            try {
                $listaModulos = $moduloDao->recuperarTodosPorCurso($curso);
            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
            }

            $this->load->helper('modulo');

            $dadosSelectModulos = array(
                'listaModulos' => lista_modulos_para_dados_dropdown($listaModulos),
                'moduloSelecionado' => '0',
                'extra' => 'id="slt-modulos"'
            );

            $dadosSelectAulas = array(
                'listaAulas' => array(),
                'aulaSelecionada' => '',
                'extra' => 'id="slt-aulas"'
            );

            $dadosPartialForm = array(
                'formAction' => 'conteudo/recurso/salvar',
                'extraOpenForm' => 'id="recurso-criar-form"',
                'formHidden' => array(
                    'cursoId' => $curso->getId(),
                    'acao'=>'criar'
                ),
                'nomeAtual' => '',
                'descricaoAtual' => '',
                'optionsTipo' => array(
                    '' => 'Escolha o tipo de recurso...',
                    WeLearn_Cursos_Recursos_TipoRecurso::GERAL => 'Recurso Geral',
                    WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO => 'Recurso Restrito'
                ),
                'tipoAtual' => '',
                'selectModulos' => $this->template->loadPartial(
                    'select_modulos',
                    $dadosSelectModulos,
                    'curso/conteudo'
                ),
                'selectAulas' => $this->template->loadPartial(
                    'select_aulas',
                    $dadosSelectAulas,
                    'curso/conteudo'
                ),
                'txtBotaoEnviar' => 'Criar!'
            );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosPartialForm,
                    'curso/conteudo/recurso'
                )
            );

            $this->_renderTemplateCurso($curso, 'curso/conteudo/recurso/criar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de recurso: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ($idRecurso)
    {
        try {
            $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');
            $recurso = $recursoDao->recuperar( $idRecurso );

            $curso = ($recurso instanceof WeLearn_Cursos_Recursos_RecursoRestrito)
                      ? $recurso->getAula()->getModulo()->getCurso()
                      : $recurso->getCurso();

            $this->_expulsarNaoAutorizados($curso);

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'formAction' => '/conteudo/recurso/salvar',
                'extraOpenForm' => 'id="recurso-alterar-form"',
                'formHidden' => array(
                    'recursoId' => $recurso->getId(),
                    'tipo' => $recurso->getTipo(),
                    'upload' => $recurso->getAssinatura(),
                    'acao' => 'alterar'
                ),
                'recurso' => $recurso
            );

            $this->_renderTemplateCurso($curso, 'curso/conteudo/recurso/alterar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário de
                         alteração de recurso: ' . create_exception_description($e));

            show_404();
        }
    }

    public function remover ($idRecurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $this->load->helper(array('file','notificacao_js'));

            $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');
            $recursoRemovido = $recursoDao->remover( $idRecurso );

            if ( is_dir( $recursoRemovido->getCaminho() ) ) {
                delete_files( $recursoRemovido->getCaminho(), true );
                rmdir( $recursoRemovido->getCaminho() );
            }

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O recursos <em>"'
                        . $recursoRemovido->getNome()
                        . '"</em> foi removido com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover recurso: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

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

        $this->load->library('form_validation');

        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            try {
                $this->load->helper('notificacao_js');

                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_adicionar( $this->input->post() );
                        
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'O recurso <em>"' . $this->input->post('nome')
                                . '"</em> foi criado com sucesso!
                                O arquivo já está disponível para os alunos.'
                        );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );

                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'O recurso <em>"' . $this->input->post('nome')
                                . '"</em> foi salvo com sucesso!'
                        );
                        break;
                    default:
                        throw new WeLearn_Base_Exception(
                            "Ação inválida ao salvar recurso"
                        );
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar recurso: '
                    . create_exception_description($e));

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos tentando resolver.
                    Tente novamente mais tarde!'
                );

                $json = create_json_feedback(false, $error);
            }
        }
        echo $json;
    }

    public function salvar_upload_temporario ()
    {
        if ( ! isset($_FILES['arquivoRecurso']) ) {
            $error = create_json_feedback_error_json('O arquivo que está tentando
                                        enviar é muito grande!', 'arquivoRecurso');

            $json = create_json_feedback(false, $error);

            die($json);
        }

        $idRecurso = UUID::mint()->string;

        $diretorioTemp = $this->_tempRecursoDir . $idRecurso;

        mkdir($diretorioTemp);

        $upload_config = array(
            'upload_path' => $diretorioTemp,
            'allowed_types' => 'jpg|jpeg|gif|png|zip|zipx|rar|tar|gz|tar.gz|pdf'
                             .'|txt|doc|docx|ppt|pptx|pps|ppsx|xls|xlsx|flv|avi'
                             .'|mpg|mov|rm|wmv|wma|mp3',
            'max_size' => '25600',
            'max_width' => '3000',
            'max_height' => '1600'
        );

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('arquivoRecurso') ) {

            rmdir($diretorioTemp);

            $errorMsg = $this->upload->display_errors('','');
            $error = create_json_feedback_error_json($errorMsg, 'arquivoRecurso');

            $json = create_json_feedback(false, $error);

        } else {

            $this->load->helper('notificacao_js');
            $this->load->library('encrypt');

            $dadosUpload = $this->upload->data();

            $dadosUpload['file_path'] = $this->encrypt->encode($dadosUpload['file_path']);
            $dadosUpload['full_path'] = $this->encrypt->encode($dadosUpload['full_path']);
            $dadosUpload['recursoId'] = $idRecurso;

            $response = Zend_Json::encode(array(
                'upload' => $dadosUpload,
                'notificacao' => create_notificacao_array(
                    'sucesso',
                     'O arquivo "'
                        . $dadosUpload['client_name']
                        . '" foi carregado com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);

        }

        echo $json;
    }

    public function remover_upload($idRecurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $this->load->helper('file');

            try {
                $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');
                $recurso = $recursoDao->recuperar( $idRecurso );

                $dir = $recurso->getCaminho();
            } catch (cassandra_NotFoundException $e) {
                $dir = $this->_tempRecursoDir . $idRecurso;
            }

            if (is_dir( $dir )) {
                delete_files($dir, true);
                rmdir($dir);
            }

            $json = create_json_feedback(true);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao remover upload temporario: '
                . create_exception_description($e));

            $json = create_json_feedback(false);
        }

        echo $json;
    }

    private  function _adicionar ($post)
    {
        $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');

        if ($post['tipo'] == WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO) {

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar( $post['aulas'] );
            $curso = $aula->getModulo()->getCurso();

            $recurso = $recursoDao->criarNovoRestrito();
            $recurso->setAula( $aula );

        } else {

            $curso = $this->_cursoDao->recuperar( $post['cursoId'] );

            $recurso = $recursoDao->criarNovoGeral();
            $recurso->setCurso( $curso );

        }

        $dadosUpload = $post['upload'];

        switch( $dadosUpload['is_image'] ) {
            case 'true' : $dadosUpload['is_image'] = true; break;
            case 'false' :
            default: $dadosUpload['is_image'] = false;
        }

        $recurso->setId( $dadosUpload['recursoId'] );
        $recurso->setCriador( $this->autenticacao->getUsuarioAutenticado() );

        $recurso->preencherPropriedades( $post );

        $this->load->library('encrypt');
        $caminhoCompletoAtual = $this->encrypt->decode( $dadosUpload['full_path'] );
        $caminhoAtual         = $this->encrypt->decode( $dadosUpload['file_path'] );

        $caminhoRecursoDoCurso = $this->_recursoArquivosDir . $curso->getId() . '/';

        if ( ! is_dir( $caminhoRecursoDoCurso ) ) {
            mkdir( $caminhoRecursoDoCurso );
        }

        $novoCaminho = $caminhoRecursoDoCurso . $recurso->getId() . '/';
        $novoCaminhoCompleto = $novoCaminho . $dadosUpload['orig_name'];

        mkdir( $novoCaminho );
        rename( $caminhoCompletoAtual, $novoCaminhoCompleto );
        rmdir( $caminhoAtual );

        $dadosUpload['full_path'] = $novoCaminhoCompleto;
        $dadosUpload['file_path'] = $novoCaminho;

        $recurso->preencherPropriedades( $dadosUpload );
        $recurso->setUrl( str_replace(FCPATH, base_url(), $novoCaminhoCompleto) );

        $recursoDao->salvar( $recurso );

        $response = Zend_Json::encode(array(
            'tipoRecurso' => $recurso->getTipo(),
            'idCurso' => ($recurso instanceof WeLearn_Cursos_Recursos_RecursoRestrito)
                         ? $recurso->getAula()->getModulo()->getCurso()->getId()
                         : $recurso->getCurso()->getId(),
            'idAula' => ($recurso instanceof WeLearn_Cursos_Recursos_RecursoRestrito)
                        ? $recurso->getAula()->getId()
                        : ''
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _alterar($post)
    {
        $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');
        $recurso = $recursoDao->recuperar( $post['recursoId'] );

        $recurso->preencherPropriedades( $post );

        $recursoDao->salvar( $recurso );

        $response = Zend_Json::encode(array(
            'tipoRecurso' => $recurso->getTipo(),
            'idCurso' => ($recurso instanceof WeLearn_Cursos_Recursos_RecursoRestrito)
                         ? $recurso->getAula()->getModulo()->getCurso()->getId()
                         : $recurso->getCurso()->getId(),
            'idAula' => ($recurso instanceof WeLearn_Cursos_Recursos_RecursoRestrito)
                        ? $recurso->getAula()->getId()
                        : ''
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
                array(
                    'papelUsuarioAtual' => $this->_getPapel( $curso ),
                    'idCurso' => $curso->getId()
                ),
                'curso/conteudo'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}