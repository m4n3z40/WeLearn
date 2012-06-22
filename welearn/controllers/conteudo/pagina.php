<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 13/04/12
 * Time: 20:59
 * To change this template use File | Settings | File Templates.
 */
class Pagina extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('libs/tiny_mce/tiny_mce.js')
                       ->appendJSImport('pagina.js');
    }

    public function recuperar_lista( $idAula )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar( $idAula );

            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

            try {
                $listaPaginas = $paginaDao->recuperarTodosPorAula( $aula );
            } catch (cassandra_NotFoundException $e) {
                $listaPaginas = array();
            }

            $arrayPaginas = array();

            if ( count( $listaPaginas ) > 0 ) {
                $i = 0;
                foreach ($listaPaginas as $pagina) {
                    $arrayPaginas[] = array(
                        'value' => $pagina->getId(),
                        'name' => 'Página ' . ++$i . ': ' . $pagina->getNome()
                    );
                }
            }

            $jsonPagina = Zend_Json::encode(array( 'paginas' => $arrayPaginas ));

            $json = create_json_feedback(true, '', $jsonPagina);

        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar lista de
                        páginas via ajax: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function index($idAula)
    {
        $this->listar($idAula);
    }

    public function exibir($idPagina)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
            $pagina = $paginaDao->recuperar( $idPagina );

            $response = Zend_Json::encode(array(
                'htmlVisualizacao' => htmlspecialchars_decode($pagina->getConteudo()),
                'nome' => $pagina->getNome()
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de páginas: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function listar($idAula)
    {
        try {
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar( $idAula );

            $this->_expulsarNaoAutorizados($aula->getModulo()->getCurso());

            $curso = $aula->getModulo()->getCurso();

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $listaModulos = $moduloDao->recuperarTodosPorCurso( $curso );

            $this->load->helper('modulo');
            $optionsModulos = lista_modulos_para_dados_dropdown( $listaModulos );

            $dadosSelectModulos = array(
                'listaModulos' => $optionsModulos,
                'moduloSelecionado' => $aula->getModulo()->getId(),
                'extra' => 'id="slt-modulos"'
            );

            $listaAulas = $aulaDao->recuperarTodosPorModulo( $aula->getModulo() );

            $this->load->helper('aula');
            $optionsAulas = lista_aulas_para_dados_dropdown( $listaAulas );

            $dadosSelectAulas = array(
                'listaAulas' => $optionsAulas,
                'aulaSelecionada' => $aula->getId(),
                'extra' => 'id="slt-aulas"'
            );

            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
            $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioDAO');

            try {
                $listaPaginas = $paginaDao->recuperarTodosPorAula( $aula );

                foreach ($listaPaginas as $pagina) {
                    $pagina->setQtdTotalComentarios(
                        $comentarioDao->recuperarQtdTotalPorPagina( $pagina )
                    );
                }
            } catch (cassandra_NotFoundException $e) {
                $listaPaginas = array();
            }

            $dadosView = array(
                'aula' => $aula,
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
                'haPaginas' => ! empty($listaPaginas),
                'totalPaginas' => count( $listaPaginas ),
                'listaPaginas' => $this->template->loadPartial(
                    'lista',
                    array('listaPaginas' => $listaPaginas),
                    'curso/conteudo/pagina'
                )
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/pagina/listar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir index de páginas: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function criar($idAula)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar($idAula);

            if ( $aula->getModulo()->getCurso()->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO ) {

                throw new WeLearn_Cursos_ConteudoAbertoException();

            }

            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

            $dadosForm = array(
                'formAction' => '/conteudo/pagina/salvar',
                'extraOpenForm' => 'id="pagina-criar-form"',
                'formHidden' => array(
                    'aulaId' => $aula->getId(),
                    'acao' => 'criar'
                ),
                'nomeAtual' => '',
                'conteudoAtual' => ''
            );

            $ultrapassouLimite = (
                $paginaDao->recuperarQtdTotalPorAula($aula) >= PaginaDAO::MAX_PAGINAS
            );

            $dadosView = array(
                'aula' => $aula,
                'ultrapassouLimite' => $ultrapassouLimite,
                'maxPaginas' => PaginaDAO::MAX_PAGINAS,
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/conteudo/pagina'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlFormAdicionar' => $this->load->view(
                    'curso/conteudo/pagina/criar',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (WeLearn_Cursos_ConteudoAbertoException $e) {

            $error = create_json_feedback_error_json( $e->getMessage() );

            $json = create_json_feedback(false, $error);

        } catch (cassandra_NotFoundException $e) {

            log_message('error', 'Erro ao tentar exibir formulário de criação de páginas: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);

        }

        echo $json;
    }

    public function alterar($idPagina)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
            $pagina = $paginaDao->recuperar( $idPagina );

            $dadosForm = array(
                'formAction' => '/conteudo/pagina/salvar',
                'extraOpenForm' => 'id="pagina-alterar-form"',
                'formHidden' => array(
                    'paginaId' => $pagina->getId(),
                    'acao' => 'alterar'
                ),
                'nomeAtual' => $pagina->getNome(),
                'conteudoAtual' => $pagina->getConteudo()
            );

            $dadosView = array(
                'pagina' => $pagina,
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/conteudo/pagina'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlFormAlterar' => $this->load->view(
                    'curso/conteudo/pagina/alterar',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar exibir formulário de alteração de páginas: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar_posicoes( $idAula )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar( $idAula );

            if ( $aula->getModulo()->getCurso()->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO ) {

                throw new WeLearn_Cursos_ConteudoAbertoException();

            }

            $this->_salvarAlteracoesOrdem(
                $this->input->get(),
                $aula
            );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A nova posição das páginas foi salva com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (WeLearn_Cursos_ConteudoAbertoException $e) {

            $error = create_json_feedback_error_json( $e->getMessage() );

            $json = create_json_feedback(false, $error);

        } catch(Exception $e) {

            log_message('error', 'Erro ao tentar remover página: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

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

        if ( $this->form_validation->run() == false ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            $this->load->helper('notificacao_js');

            try {
                switch( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criar( $this->input->post() );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação errada ao salvar!');
                }
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar página: '
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

    public function remover($idPagina)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

            $pagina = $paginaDao->recuperar( $idPagina );

            if ( $pagina->getAula()->getModulo()->getCurso()->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO ) {

                throw new WeLearn_Cursos_ConteudoAbertoException();

            }

            $paginaRemovida = $paginaDao->remover( $idPagina );

            $this->_salvarAlteracoesOrdem(
                $this->input->get(),
                $paginaRemovida->getAula()
            );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A página <em>"'
                        . $paginaRemovida->getNome()
                        . '"</em> foi removida com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (WeLearn_Cursos_ConteudoAbertoException $e) {

            $this->load->helper('notificacao_js');

            $notificacoesFlash = create_notificacao_json(
                'erro',
                $e->getMessage()
            );

            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

            $json = create_json_feedback(false);

        } catch(Exception $e) {
            log_message('error', 'Erro ao tentar remover página: '
                . create_exception_description($e));

            $this->load->helper('notificacao_js');

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

    public function _criar($post)
    {
        $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
        $aula = $aulaDao->recuperar( $post['aulaId'] );

        $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

        $novaPagina = $paginaDao->criarNovo( $post );
        $novaPagina->setAula( $aula );
        
        $paginaDao->salvar( $novaPagina );
        
        $dadosLista = array(
            'listaPaginas' => array( $novaPagina )
        );

        $foiPrimeiroAdicionado = ($paginaDao->recuperarQtdTotalPorAula( $aula ) == 1);
        
        $response = Zend_Json::encode(array(
            'primeiroAdicionado' => $foiPrimeiroAdicionado,
            'htmlNovaPagina' => $this->template->loadPartial(
                'lista',
                $dadosLista,
                'curso/conteudo/pagina'
            ),
            'notificacao' => create_notificacao_array(
                'sucesso',
                'A página <em>"'
                    . $novaPagina->getNome()
                    . '"</em> foi adicionada com sucesso à aula <em>"'
                    . $aula->getNome()
                    . '"</em>!'
            )
        ));

        if ( $foiPrimeiroAdicionado ) {
            $this->session->set_flashdata(
                'notificacoesFlash',
                create_notificacao_json(
                    'sucesso',
                    'A página <em>"'
                        . $novaPagina->getNome()
                        . '"</em> foi adicionada com sucesso à aula <em>"'
                        . $aula->getNome()
                        . '"</em>!'
                )
            );
        }

        return create_json_feedback(true, '', $response);
    }

    public function _alterar($post)
    {
        $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
        $pagina = $paginaDao->recuperar( $post['paginaId'] );
        
        $pagina->preencherPropriedades( $post );
        
        $paginaDao->salvar( $pagina );
        
        $response = Zend_Json::encode(array(
            'novoNome' => $pagina->getNome(),
            'notificacao' => create_notificacao_array(
                'sucesso',
                'A página <em>"' . $pagina->getNome() . '"</em> foi salva com sucesso!'
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
                                            WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $arrayAlteracoes = array_flip( $arrayAlteracoes );

        $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

        $paginaDao->atualizarPosicoes( $aula, $arrayAlteracoes );
    }
}
