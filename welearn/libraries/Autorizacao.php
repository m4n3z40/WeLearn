<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 17/08/11
 * Time: 01:59
 * To change this template use File | Settings | File Templates.
 */
 
class WL_Autorizacao extends WeLearn_Usuarios_Autorizacao_ControleAcesso
{
    private $_ci;

    public function __construct()
    {
        parent::__construct();

        $this->_ci =& get_instance();
    }

    /**
     * @param WeLearn_Usuarios_Autorizacao_Papel $papel
     * @return bool
     */
    public function isAutorizadoNaAcaoAtual(WeLearn_Usuarios_Autorizacao_Papel $papel)
    {
        $funcao = $this->_ci->router->class . '/' . $this->_ci->router->method;

        return parent::isAutorizado( $papel, $funcao );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $autorDoConteudo
     * @return bool
     */
    public function isAutorUsuarioAtual(WeLearn_Usuarios_Usuario $autorDoConteudo)
    {
        $usuarioAtual = $this->_ci->autenticacao->getUsuarioAutenticado();

        return parent::isAutor( $usuarioAtual, $autorDoConteudo );
    }
}
