<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificado extends Curso_Controller
{
    private $_tempCertificadosDir;
    private $_certificadosArquivosDir;


    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('certificado.js');

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
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');

            try {
                $listaCertificados = $certificadoDao->recuperarTodosPorCurso( $curso );
                $totalCertificados = count( $listaCertificados );

                $certificadoAtivo = null;
                for ($i = 0; $i < $totalCertificados; $i++) {
                    if ( $listaCertificados[$i]->isAtivo() ) {
                        $certificadoAtivo = $listaCertificados[$i];
                        unset($listaCertificados[$i]);
                    }
                }
            } catch (cassandra_NotFoundException $e) {
                $listaCertificados = array();
                $totalCertificados = 0;
                $certificadoAtivo = null;
            }

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'maxCertificados' => CertificadoDAO::MAX_CERTIFICADOS,
                'totalCertificados' => $totalCertificados,
                'certificadoAtivo' => $this->template->loadPartial(
                    'lista',
                    array('listaCertificados' => $certificadoAtivo ? array($certificadoAtivo) : array()),
                    'curso/certificado'
                ),
                'certificadosInativos' => $this->template->loadPartial(
                    'lista',
                    array('listaCertificados' => $listaCertificados),
                    'curso/certificado'
                ),
                'haCertificados' => $totalCertificados > 0
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
            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');

            $curso = $this->_cursoDao->recuperar( $idCurso );

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

            $qtdCertificados = $certificadoDao->recuperarQtdTotalPorCurso($curso);

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'atingiuMaximo' => $qtdCertificados >= CertificadoDAO::MAX_CERTIFICADOS,
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
        try {
            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
            $certificado = $certificadoDao->recuperar( $idCertificado );

            $dadosForm = array(
                'formAction' => '/curso/certificado/salvar',
                'extraOpenForm' => 'id="certificado-form-alterar"',
                'formHidden' => array(
                    'acao' => 'alterar',
                    'certificadoId' => $certificado->getId()
                ),
                'descricaoAtual' => $certificado->getDescricao(),
                'isAtivo' => $certificado->isAtivo(),
                'imagemAtual' => '<img src="' . $certificado->getUrlBig() . '" alt="Certificado atual">',
                'txtBotaoEnviar' => 'Salvar!'
            );

            $dadosView = array(
                'idCurso' => $certificado->getCurso()->getId(),
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/certificado'
                )
            );

            $this->_renderTemplateCurso(
                $certificado->getCurso(),
                'curso/certificado/alterar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário de alteração de certificado.'
                . create_exception_description($e));

            show_404();
        }
    }

    public function exibir ($idCertificado)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
            $certificado = $certificadoDao->recuperar( $idCertificado );

            $response = Zend_Json::encode(array(
                'htmlExibicao' => $this->load->view(
                    'curso/certificado/exibir',
                    array( 'certificado' => $certificado ),
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir Certificado: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function exibir_aluno($idCertificado)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
            $certificado = $certificadoDao->recuperar( $idCertificado );

            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $aluno = $usuarioDao->criarAluno( $this->autenticacao->getUsuarioAutenticado() );

            $participacaoCursoDao = WeLearn_DAO_DAOFactory::create('ParticipacaoCursoDAO');
            $participacaoCurso = $participacaoCursoDao->recuperarPorCurso(
                $aluno,
                $certificado->getCurso()
            );

            $response = Zend_Json::encode(array(
                'htmlExibicao' => $this->load->view(
                    'curso/certificado/exibir_aluno',
                    array(
                        'htmlCertificado' => $this->template->loadPartial(
                            'exibicao_aluno',
                            array( 'certificado' => $certificado ),
                            'curso/certificado'
                        ),
                        'dataInscricao' => $participacaoCurso->getDataInscricao(),
                        'dataUltimoAcesso' => $participacaoCurso->getDataUltimoAcesso(),
                        'frequenciaTotal' => $participacaoCurso->getFrequenciaTotal(),
                        'crFinal' => $participacaoCurso->getCrFinal()
                    ),
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir Certificado para aluno: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function remover ($idCertificado)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
            $certificadoRemovido = $certificadoDao->remover( $idCertificado );

            unlink( $certificadoRemovido->getCaminhoCompletoBig() );
            unlink( $certificadoRemovido->getCaminhoCompletoSmall() );

            $qtdCertificadosRestantes = $certificadoDao->recuperarQtdTotalPorCurso(
                $certificadoRemovido->getCurso()
            );

            if ( $qtdCertificadosRestantes == 0 ) {
                $this->load->helper('file');

                delete_files( $certificadoRemovido->getCaminho(), true );
            }

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'O certificado foi removido com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover Certificado: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
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

            $this->load->library('encrypt');
            $this->load->helper('notificacao_js');

            $dadosUpload = $this->upload->data();

            $dadosUpload['urlTemporaria'] = str_replace(FCPATH, base_url(), $dadosUpload['full_path']);
            $dadosUpload['full_path'] = $this->encrypt->encode($dadosUpload['full_path']);
            $dadosUpload['certificadoId'] = $idCertificado;

            $nomeArquivo = $dadosUpload['client_name'];

            unset(
                $dadosUpload['file_path'],
                $dadosUpload['orig_name'],
                $dadosUpload['client_name'],
                $dadosUpload['is_image'],
                $dadosUpload['image_width'],
                $dadosUpload['image_height'],
                $dadosUpload['image_size_str'],
                $dadosUpload['file_size']
            );

            $response = Zend_Json::encode(array(
                'upload' => $dadosUpload,
                'notificacao' => create_notificacao_array(
                    'sucesso',
                     'O arquivo "' . $nomeArquivo . '" foi carregado com sucesso!'
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
                        $json = $this->_criar( $this->input->post() );
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'O Certificado foi criado com sucesso!'
                        );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'Os dados do Certificado foram alterados com sucesso!'
                        );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação errada ao salvar!');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar Certificado: '
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

    public function _criar (array $post)
    {
        $curso = $this->_cursoDao->recuperar( $post['cursoId'] );

        $this->load->library('encrypt');

        $diretorioCurso = $this->_certificadosArquivosDir . $curso->getId() . '/';
        $caminhoArquivoTemp = $this->encrypt->decode( $post['full_path'] );
        $nomeArquivo = $post['file_name'];
        $nomeArquivoCompleto = $diretorioCurso . $nomeArquivo;
        $nomeArquivoSmall = $post['raw_name'] . '_thumb' . $post['file_ext'];
        $nomeArquivoSmallCompleto = $diretorioCurso . $nomeArquivoSmall;
        $urlDiretorioCurso = str_replace(FCPATH, base_url(), $diretorioCurso);
        $larguraBig = 560;
        $alturaBig = 400;
        $larguraSmall = 160;
        $alturaSmall = 120;

        if ( ! is_dir($diretorioCurso) ) {
            mkdir( $diretorioCurso );
        }

        rename( $caminhoArquivoTemp, $nomeArquivoCompleto );

        $this->load->library('image_lib');

        $configResizeBig = array(
            'source_image' => $nomeArquivoCompleto,
            'width' => $larguraBig,
            'height' => $alturaBig
        );

        $this->image_lib->initialize($configResizeBig);
        $this->image_lib->resize();

        $this->image_lib->clear();

        $configResizeSmall = array(
            'source_image' => $nomeArquivoCompleto,
            'new_image' => $nomeArquivoSmallCompleto,
            'width' => $larguraSmall,
            'height' => $alturaSmall
        );

        $this->image_lib->initialize($configResizeSmall);
        $this->image_lib->resize();

        $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
        $certificado = $certificadoDao->criarNovo($post);

        $certificado->setId( $post['raw_name'] );
        $certificado->setCurso( $curso );
        $certificado->setExtensao( $post['file_ext'] );
        $certificado->setCaminho( $diretorioCurso );
        $certificado->setMimeType( $post['file_type'] );
        $certificado->setTipoImagem( $post['image_type'] );
        $certificado->setUrlBig( $urlDiretorioCurso . $nomeArquivo );
        $certificado->setUrlSmall( $urlDiretorioCurso . $nomeArquivoSmall );
        $certificado->setAssinaturaBig( $nomeArquivo );
        $certificado->setAssinaturaSmall( $nomeArquivoSmall );
        $certificado->setCaminhoCompletoBig( $nomeArquivoCompleto );
        $certificado->setCaminhoCompletoSmall( $nomeArquivoSmallCompleto );
        $certificado->setLarguraImagemBig( $larguraBig );
        $certificado->setLarguraImagemSmall( $larguraSmall );
        $certificado->setAlturaImagemBig( $alturaBig );
        $certificado->setAlturaImagemSmall( $alturaSmall );
        $certificado->setTamanhoBig( filesize($nomeArquivoCompleto) );
        $certificado->setTamanhoSmall( filesize($nomeArquivoSmallCompleto) );

        $certificadoDao->salvar($certificado);

        $response = Zend_Json::encode(array(
            'idCurso' => $curso->getId()
        ));

        return create_json_feedback(true, '', $response);
    }

    public function _alterar (array $post)
    {
        $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
        $certificado = $certificadoDao->recuperar( $post['certificadoId'] );

        $certificado->preencherPropriedades( $post );

        $certificadoDao->salvar( $certificado );

        $response = Zend_Json::encode(array(
            'idCurso' => $certificado->getCurso()->getId()
        ));

        return create_json_feedback(true, '', $response);
    }
}