<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 09/05/12
 * Time: 18:15
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . '/UsuarioDAO.php';

class GerenciadorAuxiliarDAO extends UsuarioDAO
{
    private $_nomeGerenciadoresPorCursoCF = 'cursos_gerenciador_por_curso';

    /**
     * @var ColumnFamily|null
     */
    private $_gerenciadoresPorCursoCF;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    function __construct()
    {
        parent::__construct();

        $this->_gerenciadoresPorCursoCF = WL_Phpcassa::getInstance()->getColumnFamily(
            $this->_nomeGerenciadoresPorCursoCF
        );

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }
}
