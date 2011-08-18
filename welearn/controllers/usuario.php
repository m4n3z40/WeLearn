<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller
{

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
    }

    public function cadastrar()
    {
        $partial_cadastro = array(
            'form_cadastro' => $this->template->loadPartial('form_cadastro', null, 'usuario')
        );

        $this->template->appendJSImport('cadastro_usuario.js');

        $this->template->render('usuario/cadastrar', $partial_cadastro);
    }

    public function validar_cadastro()
    {
        //Se não for request ajax, exibe que página não foi encontrada
        if( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        //O retorno será em JSON.
        header('Content-type: applcation/json');

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
            $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
            $segmento = $segmentoDao->recuperar($this->input->post('segmento'));
            $novoUsuario->setSegmentoInteresse($segmento);

            $configuracaoDao = WeLearn_DAO_DAOFactory::create('ConfiguracaoUsuarioDAO');
            $configuracao = $configuracaoDao->criarNovo();
            $configuracao->setUsuarioId($novoUsuario->getNomeUsuario());
            $novoUsuario->setConfiguracao($configuracao);

            $usuarioDao->salvar($novoUsuario);

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
        exit($json);
    }

    public function quickstart()
    {

    }

    public function login()
    {
        if ( ! $this->input->is_ajax_request() ) {
           show_404();
        }

        $this->load->library('form_validation');
        if ($this->form_validation->run() === FALSE) {
            $json = create_json_feedback(false, validation_errors_json());

            exit($json);
        }

        //O retorno será em JSON.
        header('Content-type: applcation/json');

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
        exit($json);
    }

    public function logout()
    {
        if ( ! $this->input->is_ajax_request() ) {
           show_404();
        }

        //O retorno será em JSON.
        header('Content-type: applcation/json');

        $this->session->sess_destroy();

        $json = create_json_feedback(true);

        exit($json);
    }

	public function index()
	{
		$this->template->setTitle('Welcome to CodeIgniter')
                       ->render('welcome_message');
    }
}

/* End of file usuario.php */
/* Location: ./application/controllers/usuario.php */