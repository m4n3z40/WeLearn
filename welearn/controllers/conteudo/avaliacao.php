<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 18/04/12
 * Time: 21:20
 * To change this template use File | Settings | File Templates.
 */
class Avaliacao extends WL_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('avaliacao.js');
    }

    public function index ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar( $idCurso );

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

            try {
                $listaModulos = $moduloDao->recuperarTodosPorCurso( $curso );
                $totalModulos = count( $listaModulos );

                $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

                foreach ($listaModulos as $modulo) {
                    $modulo->setExisteAvaliacao(
                        $avaliacaoDao->existeAvaliacao( $modulo )
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

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/avaliacao/index',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir index de avaliações de curso: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function exibir ($idModulo)
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar( $idModulo );

            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

            $modulo->setExisteAvaliacao(
                $avaliacaoDao->existeAvaliacao( $modulo )
            );

            $dadosView = array(
                'modulo' => $modulo
            );

            $this->_renderTemplateCurso(
                $modulo->getCurso(),
                'curso/conteudo/avaliacao/exibir',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir avaliação de um módulo: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function criar ( $idModulo )
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar( $idModulo );

            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

            $modulo->setExisteAvaliacao(
                $avaliacaoDao->existeAvaliacao( $modulo )
            );

            $dadosForm = array(//TODO: Continuar implementação do módulo de avaliações pela criação da avaliação.

            );

            $dadosView = array(
                'modulo' => $modulo,
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/conteudo/avaliacao'
                )
            );

            $this->_renderTemplateCurso(
                $modulo->getCurso(),
                'curso/conteudo/avaliacao/criar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de avaliação: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ( $idAvaliacao )
    {

    }

    public function salvar ()
    {
        set_json_header();

        $this->load->library('form_validation');
        
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            try {
                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criar( $this->input->post() );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception(
                            'Opção inválida ao salvar avaliação.'
                        );
                }
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar avaliacao de módulo: ' .
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

    private function _criar ( $post )
    {

    }

    private function _alterar ( $post )
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
