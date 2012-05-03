<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 01/05/12
 * Time: 23:11
 * To change this template use File | Settings | File Templates.
 */
class Amigos extends WL_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template->setTemplate('home')
            ->appendJSImport('home.js')
            ->appendJSImport('amizade.js');
    }

    public function index()
    {
        $this->_renderTemplateHome('usuario/amigos/index', $dadosView=null);
    }

    private function _renderTemplateHome($view = '', $dados = array())
    {
        $dadosBarraEsquerda = array(
            'usuario' => $this->autenticacao->getUsuarioAutenticado()
        );

        $dadosBarraDireita = array(
            'menuContexto' => $this->template->loadPartial('menu', array(), 'usuario/convite')
        );

        $this->template->setDefaultPartialVar('home/barra_lateral_esquerda', $dadosBarraEsquerda)
            ->setDefaultPartialVar('home/barra_lateral_direita', $dadosBarraDireita)
            ->render($view, $dados);
    }
}
