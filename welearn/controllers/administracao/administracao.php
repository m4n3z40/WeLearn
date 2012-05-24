<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gevigier
 * Date: 23/05/12
 * Time: 18:40
 * To change this template use File | Settings | File Templates.
 */
class Administracao extends Home_Controller{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_renderTemplateHome(
            'administracao/index'
        );
    }

    protected function _renderTemplateHome($view = '', $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial( 'menu', Array(), 'administracao' )
        );

        parent::_renderTemplateHome($view, $dados);
    }


}