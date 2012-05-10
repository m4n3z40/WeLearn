<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 22/03/12
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */
class Conteudo extends WL_Controller
{
    /**
     * @var CursoDAO
     */
    var $_cursoDao;

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso');

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }

    public function index ($idCurso)
    {
        $curso = $this->_cursoDao->recuperar($idCurso);

        $this->_renderTemplateCurso($curso, 'curso/conteudo/index');
    }

    private function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
    {
        $vinculo = $this->_cursoDao->recuperarTipoDeVinculo(
            $this->autenticacao->getUsuarioAutenticado(),
            $curso
        );

        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao(),
            'usuarioNaoVinculado' => $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO,
            'usuarioPendente' => ($vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO_INSCRICAO_PENDENTE
                              || $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_CONVITE_PENDENTE),
            'idCurso' => $curso->getId(),
            'menuContexto' => $this->template->loadPartial('menu', array('idCurso'=> $curso->getId()), 'curso/conteudo')
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}
