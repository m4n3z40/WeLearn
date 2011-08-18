<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 17/08/11
 * Time: 01:59
 * To change this template use File | Settings | File Templates.
 */
 
class WL_Autenticacao
{
    private $_ci;
    private $_usuario;
    private $_autenticado = false;
    private $_sessaoHabilitada = true;

    public function __construct()
    {
        $this->_ci =& get_instance();

        if (isset($this->_ci->session)) {
            $usuarioSer = $this->_ci->session->userdata('usuario');
            
            if ($usuarioSer != false) {
                $this->_usuario = unserialize($usuarioSer);
                $this->_autenticado = true;
            }
        } else {
            $this->_sessaoHabilitada = false;
        }
    }

    public function autenticar($login, $senha)
    {
        if ($this->_sessaoHabilitada) {
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $this->_usuario = $usuarioDao->autenticar($login, $senha);
            $this->_ci->session->set_userdata('usuario', serialize($this->_usuario));
        } else {
            throw new WeLearn_Base_SessaoDesabilitadaException();
        }
    }

    public function isAutenticado()
    {
        if ($this->_sessaoHabilitada) {
            return $this->_autenticado;
        }

        throw new WeLearn_Base_SessaoDesabilitadaException();
    }

    public function getUsuarioAutenticado()
    {
        if ( ! $this->_sessaoHabilitada ) {
            throw new WeLearn_Base_SessaoDesabilitadaException();
        }

        if($this->_autenticado == false) {
            return false;
        }

        return $this->_usuario;
    }
}