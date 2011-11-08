<?php

class WL_Controller extends CI_Controller 
{
	function __construct() 
	{
		parent::__construct();
		
		if( ! $this->autenticacao->isAutenticado() ) {
			redirect('/');
		}

        $nomeUsuario = $this->autenticacao->getUsuarioAutenticado()->getNomeUsuario();

        $this->template->setDefaultPartialVar('perfil/barra_usuario', array('nomeUsuario' => $nomeUsuario));
	}
}