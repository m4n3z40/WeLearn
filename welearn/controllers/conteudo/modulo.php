<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 22/03/12
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */
class Modulo extends WL_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('modulo.js')
                       ->setTemplate('curso');
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        $count = 50;

        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $curso = $cursoDao->recuperar($idCurso);

        $moduloDAO = WeLearn_DAO_DAOFactory::create('ModuloDAO');
        $listaModulos = $moduloDAO->recuperarTodosPorCurso($curso, '', '', $count);

        $dadosPartial = array(
            'listaModulos' => $listaModulos
        );

        $dadosView = array(
            'idCurso' => $curso->getId(),
            'haModulos' => ! empty($listaModulos),
            'listaModulos' => $this->template->loadPartial('lista', $dadosPartial, 'curso/conteudo/modulo'),
            'haMaisPaginas' => false,
            'inicioProxPagina' => ''
        );

        $this->_renderTemplateCurso($curso, 'curso/conteudo/modulo/listar', $dadosView);
    }

    public function recuperar_proxima_pagina ($idProximo)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }
    }

    public function remover ($idModulo)
    {

    }

    public function criar ($idCurso)
    {
        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $curso = $cursoDao->recuperar($idCurso);

        $dadosPartial = array(
            'formAction' => '/conteudo/modulo/salvar',
            'extraOpenForm' => 'id="modulo-criar-form"',
            'formHidden' => array('cursoId' => $curso->getId(), 'acao' => 'criar'),
            'nomeAtual' => '',
            'descricaoAtual' => '',
            'objetivosAtual' => '',
            'txtBotaoEnviar' => 'Criar!'
        );

        $dadosView = array(
            'idCurso' => $curso->getId(),
            'form' => $this->template->loadPartial('form', $dadosPartial, 'curso/conteudo/modulo')
        );

        $this->_renderTemplateCurso($curso, 'curso/conteudo/modulo/criar', $dadosView);
    }

    public function alterar ($idModulo)
    {
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
            'form' => $this->template->loadPartial('form', $dadosPartial, 'curso/conteudo/modulo')
        );

        $this->_renderTemplateCurso($modulo->getCurso(),
                                    'curso/conteudo/modulo/alterar',
                                    $dadosView);
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
                             Verifique os dados inseridos na lista abaixo
                             na lista abaixo.'
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
                        throw new WeLearn_Base_Exception('Ação inválida ao salvar módulo');
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
        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $curso = $cursoDao->recuperar( $post['cursoId'] );

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
