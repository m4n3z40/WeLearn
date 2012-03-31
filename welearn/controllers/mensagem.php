<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thiago
 * Date: 28/03/12
 * Time: 19:13
 * To change this template use File | Settings | File Templates.
 */
class Mensagem extends WL_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template->setTemplate('home')
            ->appendJSImport('home.js')
            ->appendJSImport('mensagem.js');
    }

    public function index()
    {
        $mensagemDao= WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        $dados=$mensagemDao->recuperarListaAmigosMensagens($this->autenticacao->getUsuarioAutenticado());
        $dadosView = array(
            'mensagens' => $dados
        );

        $this->_renderTemplateHome('mensagem/index', $dadosView);
    }

    public function listar($idAmigo){
        $usuario=$this->autenticacao->getUsuarioAutenticado();
        $mensagemDao= WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        $chave=$mensagemDao->gerarChave($idAmigo,$usuario->getId());
        $dados=$mensagemDao->recuperar($chave);
        $dadosView = array(
            'mensagens' => $dados
        );

        $this->_renderTemplateHome('mensagem/listar', $dadosView);

    }

    private function _renderTemplateHome($view = '', $dados = array())
    {
        $dadosBarraEsquerda = array(
            'usuario' => $this->autenticacao->getUsuarioAutenticado()
        );

        $dadosBarraDireita = array(

        );

        $this->template->setDefaultPartialVar('home/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('home/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}
