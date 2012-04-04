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
        $mensagemDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        $dados = $mensagemDao->recuperarListaAmigosMensagens($this->autenticacao->getUsuarioAutenticado());
        $dadosView = array(
            'mensagens' => $dados
        );

        $this->_renderTemplateHome('mensagem/index', $dadosView);
    }

    public function listar($idAmigo='', $de = '', $count = '')
    {

        if( $idAmigo=='' ) {
            show_404();
        }

        if ($count == '') {
            $count = 10;
        }
        $usuario = $this->autenticacao->getUsuarioAutenticado();
        $destinatario = WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($idAmigo);
        $mensagemDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        try {
            $dados = $mensagemDao->recuperarTodosPorUsuario($usuario, $destinatario, $de, '', $count + 1);
            $this->load->helper('paginacao_cassandra');
            $dadosPaginados = create_paginacao_cassandra($dados, $count);
            $dadosView = array(
                'mensagens' => $dados, 'paginacao' => $dadosPaginados, 'idAmigo' => $idAmigo, 'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],'haMensagens' => !empty($dadosPaginados)
            );
        } catch (UUIDException $e)
        {
            $dadosView = array(
                'idAmigo' => $idAmigo,'haMensagens' => !empty($dadosPaginados)
            );
        }

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
