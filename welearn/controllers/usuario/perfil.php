<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('perfil')
        ->appendJSImport('convite.js');
    }

    public function index()
    {
        $this->template->render('usuario/perfil/index');

    }

    public function carregarPerfil($id)
    {

        $usuarioDao= WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $conviteCadastradoDao=WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
        $usuarioObj=$usuarioDao->recuperar($id);
        $conviteCadastrado=$conviteCadastradoDao->criarNovo();
        $conviteCadastrado->setRemetente($this->autenticacao->getUsuarioAutenticado());
        $conviteCadastrado->setDestinatario($usuarioObj);
        try{
            $saoAmigos=$conviteCadastradoDao->recuperar_por_chave($conviteCadastrado);
        }catch(cassandra_NotFoundException $e){
            $saoAmigos=null;
        }
        $dados=array('id' => $usuarioObj->getId(), 'nome' => $usuarioObj->getNome(),
                     'sobrenome' => $usuarioObj->getSobrenome(), 'email' => $usuarioObj->getEmail(),
                     'saoAmigos' => $saoAmigos
                    );
        $this->_renderTemplatePerfil('usuario/perfil/index',$dados);
    }

    private function _renderTemplatePerfil($view = '', $dados=array() )
    {
        $dadosBarraEsquerda = array(
            'usuario' => $dados
        );

        $dadosBarraDireita = array(
            'imagem'=>'implementar imagem',
            'amigos'=>'implementar amigos',
            'cursos'=>'implementar cursos'
        );

        $this->template->setDefaultPartialVar('perfil/barra_lateral_esquerda', $dadosBarraEsquerda)
            ->setDefaultPartialVar('perfil/barra_lateral_direita', $dadosBarraDireita)
            ->render($view, $dados);
    }

}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */