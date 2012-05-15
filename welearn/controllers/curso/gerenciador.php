<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerenciador extends Curso_Controller
{
    /**
     * @var GerenciadorAuxiliarDAO
     */
    private $_gerenciadorDao;

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('gerenciador.js');

        $this->_gerenciadorDao = WeLearn_DAO_DAOFactory::create('GerenciadorAuxiliarDAO');
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

    protected function _renderTemplateCurso(WeLearn_Cursos_Curso $curso,
                                            $view = '',
                                            array $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                array(
                    'idCurso' => $curso->getId(),
                    'totalGerenciadores' => $this->_gerenciadorDao->recuperarQtdTotalPorCurso( $curso ),
                    'totalConvites' => $this->_gerenciadorDao->recuperarQtdTotalConvitesPorCurso( $curso )
                ),
                'curso/gerenciador'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
