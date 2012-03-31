<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 28/03/12
 * Time: 22:15
 * To change this template use File | Settings | File Templates.
 */
class Aula extends WL_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('aula.js')
                       ->setTemplate('curso');
    }

    public function index($idCurso)
    {
        if ( $idModulo = $this->input->get('m') ) {
            $this->listar($idModulo); return;
        }

        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $listaModulos = $moduloDao->recuperarTodosPorCurso($curso);

            $this->load->helper('modulo');

            $dadosPartial = array(
                'listaModulos' => lista_modulos_para_dados_dropdown($listaModulos),
                'moduloSelecionado' => '0',
                'extra' => 'id="slt-aula-modulos"'
            );

            $dadosView = array(
                'selectModulos' => $this->template->loadPartial(
                    'select_modulos',
                    $dadosPartial,
                    'curso/conteudo'
                ),
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

            $dadosView = array(
                'modulo' => $modulo,
                'haAulas' => false,
                'listaAulas' => ''
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

    }

    public function salvar()
    {
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
                        $json = $this->_adicionar( $this->input->post() );
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
            'menuContexto' => $this->template->loadPartial('menu', array('idCurso'=> $curso->getId()), 'curso/conteudo')
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}
