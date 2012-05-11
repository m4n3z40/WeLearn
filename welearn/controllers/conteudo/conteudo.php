<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 22/03/12
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */
class Conteudo extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index ($idCurso)
    {
        $curso = $this->_cursoDao->recuperar($idCurso);

        $this->_renderTemplateCurso($curso, 'curso/conteudo/index');
    }

    protected function _renderTemplateCurso(WeLearn_Cursos_Curso $curso,
                                            $view = '',
                                            array $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                array( 'idCurso' => $curso->getId() ),
                'curso/conteudo'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
