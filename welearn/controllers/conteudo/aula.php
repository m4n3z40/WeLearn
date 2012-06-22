<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 28/03/12
 * Time: 22:15
 * To change this template use File | Settings | File Templates.
 */
class Aula extends Curso_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('aula.js');
    }

    public function recuperar_lista($idModulo)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar($idModulo);

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

            try {
                $listaAulas = $aulaDao->recuperarTodosPorModulo($modulo);
            } catch (cassandra_NotFoundException $e) {
                $listaAulas = array();
            }

            $arrayAulas = array();

            if ( count( $listaAulas ) > 0 ) {
                $i = 0;
                foreach ($listaAulas as $aula) {
                    $arrayAulas[] = array(
                        'value' => $aula->getId(),
                        'name' => 'Aula ' . ++$i . ': ' . $aula->getNome()
                    );
                }
            }

            $jsonAulas = Zend_Json::encode(array( 'aulas' => $arrayAulas ));

            $json = create_json_feedback(true, '', $jsonAulas);

        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar lista de
                        aulas via ajax: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function index($idCurso)
    {
        if ( $idModulo = $this->input->get('m') ) {
            $this->listar($idModulo); return;
        }

        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            $this->_expulsarNaoAutorizados($curso);

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

            try{
                $listaModulos = $moduloDao->recuperarTodosPorCurso( $curso );
                $totalModulos = count( $listaModulos );

                foreach ($listaModulos as $modulo) {
                    $modulo->setQtdTotalAulas(
                        $aulaDao->recuperarQtdTotalPorModulo( $modulo )
                    );
                }
            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
                $totalModulos = 0;
            }

            $dadosView = array(
                'haModulos' => $totalModulos > 0,
                'totalModulos' => $totalModulos,
                'listaModulos' => $listaModulos,
                'idCurso' => $idCurso
            );

            $this->_renderTemplateCurso($curso, 'curso/conteudo/aula/index', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir index do gerenciamento
                        de aulas do curso: ' . create_exception_description($e));

            show_404();
        }
    }

    public function listar($idModulo)
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar($idModulo);

            $this->_expulsarNaoAutorizados($modulo->getCurso());

            try{
                $listaModulos = $moduloDao->recuperarTodosPorCurso( $modulo->getCurso() );
            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
            }

            $this->load->helper('modulo');

            $dadosPartialSelect = array(
                'listaModulos' => lista_modulos_para_dados_dropdown($listaModulos),
                'moduloSelecionado' => '0',
                'extra' => 'id="slt-aula-modulos"'
            );

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

            try {
                $listaAulas = $aulaDao->recuperarTodosPorModulo($modulo);
                $totalAulas = count( $listaAulas );
            } catch ( cassandra_NotFoundException $e ) {
                $listaAulas = array();
                $totalAulas = 0;
            }

            if ( $totalAulas > 0 ) {
                $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
                $recursoDao = WeLearn_DAO_DAOFactory::create('RecursoDAO');

                foreach ($listaAulas as $aula) {
                    $aula->setQtdTotalPaginas(
                        $paginaDao->recuperarQtdTotalPorAula( $aula )
                    );

                    $aula->setQtdTotalRecursos(
                        $recursoDao->recuperarQtdTotalRestritoS( $aula )
                    );
                }

                unset($aula);
            }

            $dadosPartial = array(
                'listaAulas' => $listaAulas
            );

            $dadosView = array(
                'modulo' => $modulo,
                'selectModulo' => $this->template->loadPartial(
                    'select_modulos',
                    $dadosPartialSelect,
                    'curso/conteudo'
                ),
                'haAulas' => ! empty( $listaAulas ),
                'totalAulas' => $totalAulas,
                'listaAulas' => $this->template->loadPartial(
                    'lista',
                    $dadosPartial,
                    'curso/conteudo/aula'
                )
            );

            $this->_renderTemplateCurso(
                $modulo->getCurso(),
                'curso/conteudo/aula/listar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir lista de aulas do curso: '
                                 . create_exception_description($e));

            show_404();
        }
    }

    public function criar ($idModulo)
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar($idModulo);

            $this->_expulsarNaoAutorizados($modulo->getCurso());

            if ( $modulo->getCurso()->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO ) {

                show_404();

            }

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $ultrapassouLimite = ( $aulaDao->recuperarQtdTotalPorModulo($modulo)
                                    >= AulaDAO::MAX_AULAS );

            $dadosPartial = array(
                'formAction' => 'conteudo/aula/salvar',
                'extraOpenForm' => 'id="aula-criar-form"',
                'formHidden' => array(
                    'moduloId' => $modulo->getId(),
                    'acao' => 'criar'
                ),
                'nomeAtual' => '',
                'descricaoAtual' => '',
                'txtBotaoEnviar' => 'Criar!'
            );

            $dadosView = array(
                'modulo' => $modulo,
                'ultrapassouLimite' => $ultrapassouLimite,
                'maxAulas' => AulaDAO::MAX_AULAS,
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosPartial,
                    'curso/conteudo/aula'
                )
            );

            $this->_renderTemplateCurso(
                $modulo->getCurso(),
                'curso/conteudo/aula/criar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir form de criação de aula: '
                                 . create_exception_description($e));

            show_404();
        }
    }

    public function alterar($idAula)
    {
        try {
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar($idAula);

            $this->_expulsarNaoAutorizados($aula->getModulo()->getCurso());

            $dadosPartial = array(
                'formAction' => 'conteudo/aula/salvar',
                'extraOpenForm' => 'id="aula-alterar-form"',
                'formHidden' => array(
                    'aulaId' => $aula->getId(),
                    'acao' => 'alterar'
                ),
                'nomeAtual' => $aula->getNome(),
                'descricaoAtual' => $aula->getDescricao(),
                'txtBotaoEnviar' => 'Salvar!'
            );

            $dadosView = array(
                'nomeAula' => $aula->getNome(),
                'idModulo' => $aula->getModulo()->getId(),
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosPartial,
                    'curso/conteudo/aula'
                )
            );

            $this->_renderTemplateCurso(
                $aula->getModulo()->getCurso(),
                'curso/conteudo/aula/alterar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir form de alteração de aula: '
                                 . create_exception_description($e));

            show_404();
        }
    }

    public function salvar_posicoes($idModulo) {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar( $idModulo );

            if ( $modulo->getCurso()->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO ) {

                throw new WeLearn_Cursos_ConteudoAbertoException();

            }

            $this->_salvarAlteracoesOrdem( $this->input->get(), $modulo );

            $this->load->helper('notificacao_js');

            $notificacao = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A nova ordem das aulas deste módulo foi salva com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $notificacao);

        } catch (WeLearn_Cursos_ConteudoAbertoException $e) {

            $error = create_json_feedback_error_json( $e->getMessage() );

            $json = create_json_feedback(false, $error);

        } catch (Exception $e) {

            log_message('error', 'Ocorreu um erro ao salvar novas posicoes
                        de aula de um módulo: ' . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);

        }

        echo $json;
    }

    public function remover($idAula)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->helper('notificacao_js');

        try {

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

            $aula = $aulaDao->recuperar( $idAula );

            if ( $aula->getModulo()->getCurso()->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO ) {

                throw new WeLearn_Cursos_ConteudoAbertoException();

            }

            $aulaRemovida = $aulaDao->remover($idAula);

            $this->_salvarAlteracoesOrdem(
                $this->input->get(),
                $aulaRemovida->getModulo()
            );

            $notificacao = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A aula "<em>' . $aulaRemovida->getNome() .
                        '</em>" foi removida com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $notificacao);

        } catch (WeLearn_Cursos_ConteudoAbertoException $e) {

            $notificacoesFlash = create_notificacao_json(
                'erro',
                $e->getMessage()
            );

            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

            $json = create_json_feedback(false);

        } catch (Exception $e) {

            log_message('error', 'Ocorreu um erro ao tentar remover
                         uma aula de um módulo: ' . create_exception_description($e));

            $notificacoesFlash = create_notificacao_json(
                'erro',
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

            $json = create_json_feedback(false);

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
            $this->load->helper('notificacao_js');

            try {
                switch( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_adicionar( $this->input->post() );

                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'A aula foi criada com sucesso!
                             Verifique abaixo os dados inseridos na
                             lista de aulas deste módulo.'
                        );
                        break;
                    case 'alterar':
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'As alterações da aula foram salvas com sucesso!'
                        );
                        $json = $this->_alterar( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação inválida ao salvar aula.');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar aula de módulo: ' .
                    create_exception_description($e));

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos tentando resolver.
                    Tente novamente mais tarde!'
                );

                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }

    private function _adicionar(array $post)
    {
        $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
        $modulo = $moduloDao->recuperar( $post['moduloId'] );

        $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

        $novaAula = $aulaDao->criarNovo( $post );
        $novaAula->setModulo( $modulo );

        $aulaDao->salvar( $novaAula );

        $idModuloJson = Zend_Json::encode(array(
            'idModulo' => $modulo->getId()
        ));

        return create_json_feedback(true, '', $idModuloJson);
    }

    private function _alterar(array $post)
    {
        $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
        $aula = $aulaDao->recuperar( $post['aulaId'] );

        $aula->preencherPropriedades( $post );

        $aulaDao->salvar( $aula );

        $idModuloJson = Zend_Json::encode(array(
            'idModulo' => $aula->getModulo()->getId()
        ));

        return create_json_feedback(true, '', $idModuloJson);
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

    private function _salvarAlteracoesOrdem(array $arrayAlteracoes,
                                            WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $arrayAlteracoes = array_flip( $arrayAlteracoes );

        $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');

        $aulaDao->atualizarPosicao( $modulo, $arrayAlteracoes );
    }
}
