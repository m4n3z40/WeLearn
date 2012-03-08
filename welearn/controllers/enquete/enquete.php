<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('enquete.js');
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        try {
            $count = 20;

            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

            try {
                $listaEnquetes = $enqueteDao->recuperarTodosPorSituacao($curso, WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA, '', '', $count + 1);
            } catch (cassandra_NotFoundException $e) {
                $listaEnquetes = array();
            }

            $this->load->helper('paginacao_cassandra');
            $dadosPaginacao = create_paginacao_cassandra($listaEnquetes, $count);

            $dadosPartialLista = array( 'listaEnquetes' => $listaEnquetes );
            $partialLista = $this->template->loadPartial('lista', $dadosPartialLista, 'curso/enquete/enquete');

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haEnquetes' => !empty($listaEnquetes),
                'listaEnquetes' => $partialLista,
                'haMaisPaginas' => $dadosPaginacao['proxima_pagina'],
                'inicioProxPagina' => $dadosPaginacao['inicio_proxima_pagina'],
            );

            $this->_renderTemplateCurso($curso, 'curso/enquete/enquete/listar', $dadosView);
        } catch (Exception $e) {
            echo create_exception_description($e);
        }
    }

    public function exibir ($idEnquete)
    {

    }

    public function criar ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $dadosViewCriar = array(
                'formAction' => 'enquete/enquete/salvar',
                'extraOpenForm' => 'id="form-criar-enquete"',
                'hiddenFormData' => array('cursoId' => $curso->getId(), 'acao' => 'criar')
            );

            $this->_renderTemplateCurso($curso, 'curso/enquete/enquete/criar', $dadosViewCriar);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de enquetes:'
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ($idEnquete)
    {

    }

    public function salvar ()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_message('_validarQtdAlternativas', 'O número de %s contidas nesta enquete é inválido.');

        if ( ! $this->form_validation->run() ) {

            $json = create_json_feedback(false, validation_errors_json());

        } else {
            try {

                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criarEnquete( $this->input->post() );
                        break;
                    case 'alterar';
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Opção de salvamento inválida!');
                }

            } catch ( Exception $e ) {

                echo create_exception_description($e);

            }
        }

        echo $json;
    }

    public function remover ($idEnquete)
    {

    }

    private function _criarEnquete ($post)
    {
        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $curso = $cursoDao->recuperar( $post['cursoId'] );

        $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
        $novaEnquete = $enqueteDao->criarNovo();

        $this->load->helper('date');
        $dataExpiracao = datetime_ptbr_to_en($post['dataExpiracao'], true);

        $novaEnquete->setCurso( $curso );
        $novaEnquete->setCriador( $this->autenticacao->getUsuarioAutenticado() );
        $novaEnquete->setQuestao( $post['questao'] );
        $novaEnquete->setDataExpiracao( $dataExpiracao );
        $novaEnquete->setQtdAlternativas( count( $post['alternativas'] ) );

        $enqueteDao->salvar( $novaEnquete );

        foreach ($post['alternativas'] as $alternativa ) {
            $novaAlternativa = $enqueteDao->criarAlternativa(array(
                'txtAlternativa' => $alternativa,
                'enqueteId' => $novaEnquete->getId()
            ));

            $novaEnquete->adicionarAlternativa( $novaAlternativa );
        }

        $enqueteDao->salvarAlternativas( $novaEnquete->getAlternativas() );

        return create_json_feedback(true, '', Zend_Json::encode( array('idEnquete' => $novaEnquete->getId()) ));
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
            'menuContexto' => ''
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }

    public function _validarQtdAlternativas($alternativas)
    {
        return (count($alternativas) >= 2) && (count($alternativas) <= 10);
    }
}
