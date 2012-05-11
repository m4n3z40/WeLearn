<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerenciador extends Curso_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('gerenciador.js');
    }

    public function index ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $this->_renderTemplateCurso($curso);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir index do gerenciamento de gerenciadores'
                . create_exception_description($e));

            show_404();
        }
    }
}
