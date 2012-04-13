<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('home')
                       ->appendJSImport('home.js');
    }

    public function index()
    {
        $this->_renderTemplateHome('usuario/home/index');
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

/* End of file home.php */
/* Location: ./application/controllers/home.php */