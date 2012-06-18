<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends WL_Controller
{
    private $_tempUploadDir;
    private $_userpicDir;

    function __construct()
    {
        parent::__construct();

        $this->_tempUploadDir = TEMP_UPLOAD_DIR . 'img/';
        $this->_userpicDir = USER_IMG_DIR . 'userpics/';
    }

    public function salvar_dados_pessoais()
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
                $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();
                $dados_post = $this->input->post();

                $dadosPessoaisDao = WeLearn_DAO_DAOFactory::create('DadosPessoaisUsuarioDAO');

                try {

                    $dadosPessoais = $dadosPessoaisDao->recuperar( $usuarioAtual->getId() );
                    $dadosPessoais->preencherPropriedades( $dados_post );
                    $dadosPessoais->setListaDeRS(array());
                    $dadosPessoais->setListaDeIM(array());

                } catch( cassandra_NotFoundException $e ) {

                    $dadosPessoais = $dadosPessoaisDao->criarNovo($dados_post);

                }

                for ($i = 0; $i < count($dados_post['rsId']); $i++) {
                    if ( $dados_post['rsId'][$i] ) {
                        $dadosRS = array(
                            'usuarioId' => $usuarioAtual->getId(),
                            'descricaoRS' => $dados_post['rsId'][$i],
                            'urlUsuarioRS' => $dados_post['rsUsuario'][$i]
                        );

                        $dadosPessoais->adicionarRS( $dadosPessoaisDao->criarNovoRS($dadosRS) );
                    }
                }

                for ($i = 0; $i < count($dados_post['imId']); $i++) {
                    if ( $dados_post['imId'][$i] ) {
                        $dadosIM = array(
                            'usuarioId' => $usuarioAtual->getId(),
                            'descricaoIM' => $dados_post['imId'][$i],
                            'descricaoUsuarioIM' => $dados_post['imUsuario'][$i]
                        );

                        $dadosPessoais->adicionarIM( $dadosPessoaisDao->criarNovoIM($dadosIM) );
                    }
                }

                $usuarioAtual->setDadosPessoais( $dadosPessoais );

                $usuarioAtual->salvarDadosPessoais();

                $this->load->helper('notificacao_js');
                $this->session->set_flashdata('notificacoesFlash', create_notificacao_json(
                    'sucesso',
                    'Seus dados pessoais foram salvos com sucesso!'
                ));

                $json = create_json_feedback(true);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar dados pessoais de usuário. '
                    . create_exception_description($e));

                $errors =  create_json_feedback_error_json(
                    'Ops! Ocorreu um erro no servidor, desculpe pelo incidente.<br/>'
                   .'Já estamos verificando, tente novamente em breve.'
                );

                $json = create_json_feedback(false, $errors);
            }
        }

        echo $json;
    }

    public function salvar_dados_profissionais()
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
                $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();
                $dados_post = $this->input->post();

                $dadosProfissionaisDao = WeLearn_DAO_DAOFactory::create('DadosProfissionaisUsuarioDAO');
                $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

                try {
                    $dadosProfissionais = $dadosProfissionaisDao->recuperar( $usuarioAtual->getId() );
                    $dadosProfissionais->preencherPropriedades( $dados_post );
                } catch (cassandra_NotFoundException $e) {
                    $dadosProfissionais = $dadosProfissionaisDao->criarNovo( $dados_post );
                }

                if ( $dados_post['segmento'] != '0' ) {

                    $dadosProfissionais->setSegmentoTrabalho(
                        $segmentoDao->recuperar( $dados_post['segmento'] )
                    );

                }

                $usuarioAtual->setDadosProfissionais( $dadosProfissionais );

                $usuarioAtual->salvarDadosProfissionais();

                $this->load->helper('notificacao_js');
                $this->session->set_flashdata('notificacoesFlash', create_notificacao_json(
                    'sucesso',
                    'Seus dados profissionais foram salvos com sucesso!'
                ));

                $json = create_json_feedback(true);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar dados profissionais de usuário. '
                    . create_exception_description($e));

                $errors =  create_json_feedback_error_json(
                    'Ops! Ocorreu um erro no servidor, desculpe pelo incidente.<br/>'
                   .'Já estamos verificando, tente novamente em breve.'
                );

                $json = create_json_feedback(false, $errors);
            }
        }

        echo $json;
    }

    public function salvar_imagem()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();
            $hashUsuario = md5( $usuarioAtual->getId() );

            $imagemDao = WeLearn_DAO_DAOFactory::create('ImagemUsuarioDAO');

            $dadosUpload = $this->input->post('imagem');

            $extensao = $dadosUpload['ext'];
            $nomeImagem = $dadosUpload['id'] . $extensao;
            $diretorio = $this->_userpicDir . $hashUsuario . '/';
            $diretorioCompleto = $diretorio . $nomeImagem;
            $url = str_replace(FCPATH, base_url(), $diretorioCompleto);

            $diretorioCompletoTemp = $this->_tempUploadDir . $nomeImagem;

            if ( is_file( $diretorioCompletoTemp ) ) {

                if ( is_file( $diretorioCompleto ) ) {
                    unlink( $diretorioCompleto );
                }

                if ( ! is_dir( $diretorio ) ) {
                    mkdir( $diretorio );
                }

                rename( $diretorioCompletoTemp, $diretorioCompleto );

                $dadosImagem = array(
                    'usuarioId' => $usuarioAtual->getId(),
                    'url' => $url,
                    'nome' => $nomeImagem,
                    'extensao' => $extensao,
                    'diretorio' => $diretorio,
                    'diretorioCompleto' => $diretorioCompleto
                );

                if ( $usuarioAtual->getImagem() instanceof WeLearn_Usuarios_ImagemUsuario ) {

                    $usuarioAtual->getImagem()->preencherPropriedades( $dadosImagem );

                } else {

                    $imagemUsuario = $imagemDao->criarNovo( $dadosImagem );
                    $usuarioAtual->setImagem( $imagemUsuario );

                }

                $usuarioAtual->salvarImagem();

                $this->autenticacao->setUsuarioAutenticado( $usuarioAtual );

                $this->load->helper('notificacao_js');
                $this->session->set_flashdata('notificacoesFlash', create_notificacao_json(
                    'sucesso',
                    'Sua imagem de exibição foi salva com sucesso!'
                ));

                $json = create_json_feedback(true);
            } else {

                throw new WeLearn_Base_Exception('Erro ao tentar mover arquivo de usuario.');

            }
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar salvar imagem de usuário. '
                . create_exception_description($e));

            $errors =  create_json_feedback_error_json(
                'Ops! Ocorreu um erro no servidor, desculpe pelo incidente.<br/>'
               .'Já estamos verificando, tente novamente em breve.'
            );

            $json = create_json_feedback(false, $errors);
        }

        echo $json;
    }

    public function upload_imagem()
    {
        $idImagem = md5( $this->autenticacao->getUsuarioAutenticado()->getId() );

        $upload_config = array(
            'upload_path' => TEMP_UPLOAD_DIR . 'img/',
            'allowed_types' => 'jpg|jpeg|gif|png',
            'max_size' => '2048',
            'max_width' => '2048',
            'max_height' => '1536',
            'overwrite' => true,
            'file_name' =>  $idImagem
        );

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('imagemUsuario') ) {
            $resultado = array(
                'success' => false,
                'error_msg' => $this->upload->display_errors('','')
            );
        } else {
            $upload_data = $this->upload->data();

            $image_config = array(
                'source_image' => $upload_data['full_path'],
                'width' => 160,
                'height' => 160
            );

            $this->load->library('image_lib', $image_config);

            if ( ! $this->image_lib->resize() ) {
                $resultado = array(
                    'success' => false,
                    'error_msg' => $this->image_lib->display_errors('','')
                );
            } else {
                $resultado = array(
                    'success' => true,
                    'upload_data' => array(
                        'imagem_id' => $idImagem,
                        'imagem_url' => site_url('/temp/img/' . $upload_data['file_name']),
                        'imagem_ext' => $upload_data['file_ext']
                    )
                );
            }
        }

        $json = Zend_Json::encode($resultado);

        echo $json;
    }

    public function salvar_configuracao()
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
                $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();
                $dados_post = $this->input->post();

                $usuarioAtual->getConfiguracao()->preencherPropriedades( $dados_post );

                $usuarioAtual->salvarConfiguracao();

                $this->autenticacao->setUsuarioAutenticado( $usuarioAtual );

                $this->load->helper('notificacao_js');
                $this->session->set_flashdata('notificacoesFlash', create_notificacao_json(
                    'sucesso',
                    'Seus dados profissionais foram salvos com sucesso!'
                ));

                $json = create_json_feedback(true);
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar configurações de usuário. '
                    . create_exception_description($e));

                $errors =  create_json_feedback_error_json(
                    'Ops! Ocorreu um erro no servidor, desculpe pelo incidente.<br/>'
                   .'Já estamos verificando, tente novamente em breve.'
                );

                $json = create_json_feedback(false, $errors);
            }
        }

        echo $json;
    }

    public function validar_cadastro()
    {
        //Se não for request ajax, exibe que página não foi encontrada
        if( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        //O retorno será em JSON.
        set_json_header();

        //Faz a validação dos dados do formulário.
        $this->load->library('form_validation');
        if ($this->form_validation->run() === FALSE) {//Se validação falhar
            $json = create_json_feedback(false, validation_errors_json());
            exit($json);
        }

        //Continuamos já que a validação foi bem sucedida
        try {
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $novoUsuario = $usuarioDao->criarNovo($this->input->post());

            //verificar se usuário e email já estão cadastrados.
            $emailCadastrado = $usuarioDao->emailCadastrado($novoUsuario->getEmail());
            $usuarioCadastrado = $usuarioDao->usuarioCadastrado($novoUsuario->getNomeUsuario());
            if ($emailCadastrado || $usuarioCadastrado) {//Se o email ou o nome de usuario ja estiver cadastrado

                $errors = array();
                if (isset($emailCadastrado) && $emailCadastrado === true) {
                    $errors[] = create_json_feedback_error_json(
                        'Este email já está cadastrado no WeLearn.<br/>'
                       .'Entre com outro email e tente novamente.',
                        'email'
                    );
                }

                if (isset($usuarioCadastrado) && $usuarioCadastrado === true){
                    $errors[] = create_json_feedback_error_json(
                        'Este usuário já está cadastrado no WeLearn.<br/>'
                       .'Entre outro nome de usuário e tente novamente.',
                        'nomeUsuario'
                    );
                }

                $json = create_json_feedback(false, $errors);
                exit($json);
            }

            //Se chegamos até aqui, tudo certo, é hora de cadastrar o usuário novo.
            $segmentoDao = $usuarioDao->getSegmentoDao();
            $segmento = $segmentoDao->recuperar($this->input->post('segmento'));
            $novoUsuario->setSegmentoInteresse($segmento);

            $configuracaoDao = $usuarioDao->getConfiguracaoDao();
            $configuracao = $configuracaoDao->criarNovo();
            $configuracao->setUsuarioId($novoUsuario->getNomeUsuario());
            $novoUsuario->setConfiguracao($configuracao);

            $usuarioDao->salvar($novoUsuario);

            $this->autenticacao->autenticar($novoUsuario->getNomeUsuario(), $novoUsuario->getSenha());

            $json = create_json_feedback(true);

        } catch (Exception $e) {//Caso haja um erro não esperado, o erro é logado e uma mensagem generica é retornada
            log_message('error', 'Erro ao cadastrar usuário. ' . create_exception_description($e));

            $errors =  create_json_feedback_error_json(
                'Ops! Ocorreu um erro no servidor, desculpe pelo incidente.<br/>'
               .'Já estamos verificando, tente novamente em breve.'
            );

            $json = create_json_feedback(false, $errors);
        }

        //Retorna o resultado!
        echo $json;
    }

    public function login()
    {
        if ( ! $this->input->is_ajax_request() ) {
           show_404();
        }
        
        //O retorno será em JSON.
        set_json_header();

        $this->load->library('form_validation');
        if ($this->form_validation->run() === FALSE) {
            $json = create_json_feedback(false, validation_errors_json());

            exit($json);
        }

        try {
            $login = $this->input->post('login');
            $senha = $this->input->post('password');

            $this->autenticacao->autenticar($login, $senha);

            $json = create_json_feedback(true);
        } catch (WeLearn_Usuarios_AutenticacaoLoginInvalidoException $e) {
            $error = create_json_feedback_error_json(
                $e->getMessage(),
                'login'
            );

            $json = create_json_feedback(false, $error);
        } catch (WeLearn_Usuarios_AutenticacaoSenhaInvalidaException $e) {
            $error = create_json_feedback_error_json(
                $e->getMessage(),
                'password'
            );

            $json = create_json_feedback(false, $error);
        } catch (Exception $e) {
            log_message('error', 'Erro ao logar usuário. ' . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ops, ocorreu um erro. Não foi possível efetuar o login no momento. <br />'
               .'Já estamos verificando. Tente novamente em breve.'
            );

            $json = create_json_feedback(false, $error);
        }

        //Retorna o resultado!
        echo $json;
    }

    public function logout()
    {
        if ( ! $this->input->is_ajax_request() ) {
           show_404();
        }

        //O retorno será em JSON.
        set_json_header();

        $this->autenticacao->limparSessao();

        echo create_json_feedback(true);
    }

    public function verificar_sessao()
    {

        if ( ! $this->input->is_ajax_request() ) {
                   show_404();
        }

        //O retorno será em JSON.
        set_json_header();


        if ( $this->autenticacao->isAutenticado() ) {

            $response = Zend_Json::encode(array(
                'sid' => $this->autenticacao->getUsuarioAutenticado()->getId(),
                'username' => $this->autenticacao->getUsuarioAutenticado()->getNomeUsuario()
            ));

            $json = create_json_feedback(true, '', $response);

        } else {

            $json = create_json_feedback(false);

        }

        echo $json;
    }
}

/* End of file usuario.php */
/* Location: ./application/controllers/usuario.php */