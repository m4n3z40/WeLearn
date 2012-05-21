<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends WL_Controller
{
    function __construct()
    {
        parent::__construct();
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
                $dadosPessoais = $dadosPessoaisDao->criarNovo($dados_post);

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

                $dadosProfissionais = $dadosProfissionaisDao->criarNovo( $dados_post );

                if ( $dados_post['segmento'] != '0' ) {

                    $dadosProfissionais->setSegmentoTrabalho(
                        $segmentoDao->recuperar( $dados_post['segmento'] )
                    );

                }

                $usuarioAtual->setDadosProfissionais( $dadosProfissionais );

                $usuarioAtual->salvarDadosProfissionais();

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

    }

    public function upload_imagem()
    {

    }

    public function salvar_configuracao()
    {

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
}

/* End of file usuario.php */
/* Location: ./application/controllers/usuario.php */