<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerenciador extends WL_Controller
{
    /**
     * @var CursoDAO
     */
    var $_cursoDao;

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('gerenciador.js');

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
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
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}
