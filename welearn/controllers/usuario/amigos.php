<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 01/05/12
 * Time: 23:11
 * To change this template use File | Settings | File Templates.
 */
class Amigos extends Home_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->template->appendJSImport('home.js')
                       ->appendJSImport('amizade.js');
    }

    public function index()
    {
        $this->_renderTemplateHome('usuario/amigos/index', $dadosView=null);
    }

    protected function _renderTemplateHome($view = '', $dados = array())
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                array(),
                'usuario/convite'
            )
        );

        parent::_renderTemplateHome($view, $dados);
    }
}
