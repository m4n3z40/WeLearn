<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 24/08/11
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */
 
class PaisEstadoDAO
{
    private $_paisCF;
    private $_estadoCF;
    private $_estadosEmPaisCF;

    public function __construct()
    {
        $phpcassa =& WL_Phpcassa::getInstance();

        $this->_paisCF = $phpcassa->getColumnFamily('usuarios_pais');
        $this->_estadoCF = $phpcassa->getColumnFamily('usuarios_estado');
        $this->_estadosEmPaisCF = $phpcassa->getColumnFamily('usuarios_estados_em_pais');
    }

    public function recuperarPais($codigoPais)
    {
        return $this->_paisCF->get($codigoPais);
    }

    public function recuperarEstado($codigoEstado)
    {
        return $this->_estadoCF->get($codigoEstado);
    }

    public function recuperarTodosPaises()
    {
        $paisesIterator = $this->_paisCF->get_range('', '', 500);

        $pais = array();
        foreach ($paisesIterator as $key => $paisCassandra) {
            $pais[] = $paisCassandra;
        }

        return $pais;
    }

    public function recuperarEstadosDeUmPais($codigoPais)
    {
        $estadosIds = $this->_estadosEmPaisCF->get($codigoPais);

        $estadosCassandra = $this->_estadoCF->multiget(array_keys($estadosIds));

        $estados = array();
        foreach ($estadosCassandra as $key => $estadoCassandra) {
            $estados[] = $estadoCassandra;
        }

        return $estados;
    }

    public function recuperarTodosPaisesSimplificado()
    {
        $paisesRows = $this->recuperarTodosPaises();

        $paises = array();
        foreach ($paisesRows as $paisRow) {
            $paises[$paisRow['id']] = $paisRow['descricao'];
        }

        asort($paises, SORT_STRING);

        return $paises;
    }

    public function recuperarEstadosDeUmPaisSimplificado($codigoPais)
    {
        $estadosRows = $this->recuperarEstadosDeUmPais($codigoPais);

        $estados = array();
        foreach ($estadosRows as $estadoRow) {
            $estados[$estadoRow['id']] = $estadoRow['descricao'];
        }

        asort($estados, SORT_STRING);

        return $estados;
    }

    public function  recuperarEstadosDeUmPaisJSON($codigoPais)
    {
        return Zend_Json::encode($this->recuperarEstadosDeUmPais($codigoPais));
    }

    public function recuperarTodosPaisesJSON()
    {
        return Zend_Json::encode($this->recuperarTodosPaises());
    }

    public function recuperarPaisJSON($codigoPais)
    {
        return Zend_Json::encode($this->recuperarPais($codigoPais));
    }

    public function recuperarEstadoJSON($codigoEstado)
    {
        return Zend_Json::encode($this->recuperarEstado($codigoEstado));
    }
}