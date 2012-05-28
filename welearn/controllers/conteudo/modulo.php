<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 22/03/12
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */
class Modulo extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('modulo.js');
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            $moduloDAO = WeLearn_DAO_DAOFactory::create('ModuloDAO');

            try {
                $listaModulos = $moduloDAO->recuperarTodosPorCurso( $curso );
                $totalModulos = count( $listaModulos );

                $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
                $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

                foreach ($listaModulos as $modulo) {  //recuperar total de aulas e se existe avaliação para cada módulo
                    $modulo->setQtdTotalAulas(
                        $aulaDao->recuperarQtdTotalPorModulo( $modulo )
                    );

                    $modulo->setExisteAvaliacao(
                        $avaliacaoDao->existeAvaliacao( $modulo )
                    );
                }

            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
                $totalModulos = 0;
            }

            $dadosPartial = array(
                'listaModulos' => $listaModulos
            );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haModulos' => ! empty($listaModulos),
                'totalModulos' => $totalModulos,
                'listaModulos' => $this->template->loadPartial(
                                      'lista',
                                      $dadosPartial,
                                      'curso/conteudo/modulo'
                                  )
            );

            $this->_renderTemplateCurso($curso, 'curso/conteudo/modulo/listar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir lista de módulos: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function salvar_posicoes ($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $this->_salvarAlteracoesOrdem( $this->input->get(), $curso );

            $this->load->helper('notificacao_js');

            $notificacao = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A nova ordem dos módulos deste curso foi salva com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $notificacao);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao salvar novas posicoes
                        de um módulo de curso' . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function remover ($idModulo)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }
        set_json_header();

        $this->load->helper('notificacao_js');

        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $moduloRemovido = $moduloDao->remover($idModulo);

            $this->_salvarAlteracoesOrdem( $this->input->get(),
                                           $moduloRemovido->getCurso() );
            
            $notificacao = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O módulo "<em>' . $moduloRemovido->getNome() .
                        '</em>" foi removido com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $notificacao);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar remover
                         um módulo de curso' . create_exception_description($e));

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

    public function criar ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $ultrapassouLimite = ( $moduloDao->recuperarQtdTotalPorCurso($curso)
                                   >= ModuloDAO::MAX_MODULOS );

            if ($ultrapassouLimite) {
                $form = '';
            } else {
                $dadosPartial = array(
                    'formAction' => '/conteudo/modulo/salvar',
                    'extraOpenForm' => 'id="modulo-criar-form"',
                    'formHidden' => array('cursoId' => $curso->getId(), 'acao' => 'criar'),
                    'nomeAtual' => '',
                    'descricaoAtual' => '',
                    'objetivosAtual' => '',
                    'txtBotaoEnviar' => 'Criar!'
                );

                $form = $this->template->loadPartial('form', $dadosPartial, 'curso/conteudo/modulo');
            }

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'ultrapassouLimite' => $ultrapassouLimite,
                'maxModulos' => ModuloDAO::MAX_MODULOS,
                'form' => $form
            );

            $this->_renderTemplateCurso($curso, 'curso/conteudo/modulo/criar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação
                         de Módulo: ' . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ($idModulo)
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar($idModulo);

            $dadosPartial = array(
                'formAction' => '/conteudo/modulo/salvar',
                'extraOpenForm' => 'id="modulo-alterar-form"',
                'formHidden' => array('moduloId' => $modulo->getId(), 'acao' => 'alterar'),
                'nomeAtual' => $modulo->getNome(),
                'descricaoAtual' => $modulo->getDescricao(),
                'objetivosAtual' => $modulo->getObjetivos(),
                'txtBotaoEnviar' => 'Salvar!'
            );

            $dadosView = array(
                'nomeModulo' => $modulo->getNome(),
                'idCurso' => $modulo->getCurso()->getId(),
                'form' => $this->template->loadPartial('form', $dadosPartial, 'curso/conteudo/modulo')
            );

            $this->_renderTemplateCurso($modulo->getCurso(),
                                        'curso/conteudo/modulo/alterar',
                                        $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de alteração
                                  de módulo: ' . create_exception_description($e));

            show_404();
        }
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
            $this->load->helper('notificacao_js');

            try {
                switch ($this->input->post('acao')) {
                    case 'criar':
                        $json = $this->_adicionar( $this->input->post() );

                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'O módulo foi criado com sucesso!
                             Verifique os dados inseridos na lista de módulos abaixo.'
                        );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );

                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'As alterações do módulo foram salvas com sucesso!'
                        );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação inválida ao salvar módulo.');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar módulo de curso: ' .
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
        $curso = $this->_cursoDao->recuperar( $post['cursoId'] );

        $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

        $novoModulo = $moduloDao->criarNovo($post);
        $novoModulo->setCurso($curso);

        $moduloDao->salvar($novoModulo);

        $idCursoJson = Zend_Json::encode(array('idCurso' => $curso->getId()));

        return create_json_feedback(true, '', $idCursoJson);
    }

    public function _alterar(array $post)
    {
        $moduloDao = $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
        $modulo = $moduloDao->recuperar( $post['moduloId'] );

        $modulo->preencherPropriedades($post);

        $moduloDao->salvar($modulo);

        $idCursoJson = Zend_Json::encode(array(
            'idCurso' => $modulo->getCurso()->getId()
        ));

        return create_json_feedback(true, '', $idCursoJson);
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

    private function _salvarAlteracoesOrdem(array $arrayAlteracoes,
                                           WeLearn_Cursos_Curso $curso)
    {
        $arrayAlteracoes = array_flip( $arrayAlteracoes );

        $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

        $moduloDao->atualizarPosicao( $curso, $arrayAlteracoes );
    }
}
