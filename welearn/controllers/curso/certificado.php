<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificado extends WL_Controller
{
    private $_tempCertificadosDir;
    private $_certificadosArquivosDir;


    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('certificado.js');

        $this->_tempCertificadosDir = TEMP_UPLOAD_DIR . 'certificados/';
        $this->_certificadosArquivosDir = CURSOS_FILES_DIR . 'certificados/';
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar( $idCurso );

            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');

            try {
                $listaCertificados = $certificadoDao->recuperarTodosPorCurso( $curso );
            } catch (cassandra_NotFoundException $e) {
                $listaCertificados = array();
            }

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'maxCertificados' => CertificadoDAO::MAX_CERTIFICADOS,
                'haCertificados' => ! empty( $listaCertificados )
            );

            $this->_renderTemplateCurso($curso, 'curso/certificado/listar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir listagem de certificados'
                . create_exception_description($e));

            show_404();
        }
    }

    public function criar ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar( $idCurso );

            $dadosForm = array(
                'formAction' => '/curso/certificado/salvar',
                'extraOpenForm' => 'id="certificado-form-criar"',
                'formHidden' => array(
                    'acao' => 'criar',
                    'cursoId' => $curso->getId()
                ),
                'descricaoAtual' => '',
                'isAtivo' => false,
                'imagemAtual' => null,
                'txtBotaoEnviar' => 'Enviar!'
            );

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/certificado'
                )
            );

            $this->_renderTemplateCurso($curso, 'curso/certificado/criar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário de envio de certificados'
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ($idCertificado)
    {

    }

    public function remover ($idCertificado)
    {

    }

    public function salvar_upload_temporario ()
    {
        if ( ! isset($_FILES['imagemRecurso']) ) {
            $error = create_json_feedback_error_json('O arquivo que está tentando
                                        enviar é muito grande!', 'imagemRecurso');

            $json = create_json_feedback(false, $error);

            die($json);
        }

        $idCertificado = UUID::mint()->string;

        $diretorioTemp = $this->_tempCertificadosDir;

        $upload_config = array(
            'upload_path' => $diretorioTemp,
            'file_name' => $idCertificado,
            'allowed_types' => 'jpg|jpeg|gif|png',
            'max_size' => '5120',
            'max_width' => '3000',
            'max_height' => '1600'
        );

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('imagemRecurso') ) {

            $errorMsg = $this->upload->display_errors('','');
            $error = create_json_feedback_error_json($errorMsg, 'imagemRecurso');

            $json = create_json_feedback(false, $error);

        } else {

            $this->load->helper('notificacao_js');

            $dadosUpload = $this->upload->data();

            $dadosUpload['certificadoId'] = $idCertificado;

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

    public function remover_upload_temporario () {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        if ( unlink($this->_tempCertificadosDir . $this->input->get('assinatura') ) ) {
            $json = create_json_feedback(true);
        } else {
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

    }

    public function _criar (array $post)
    {

    }

    public function _alterar (array $post)
    {

    }

    private function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null,
                                          $view = '',
                                          array $dados = null)
    {
        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao()
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}