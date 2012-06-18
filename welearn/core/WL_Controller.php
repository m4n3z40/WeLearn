<?php

class WL_Controller extends CI_Controller 
{
    /**
     * @var string
     */
    private $_template = 'default';

    /**
     * @var string
     */
    private $_barraUsuarioVar    = '';
    /**
     * @var string
     */
    private $_barraEsquerdaVar   = '';
    /**
     * @var string
     */
    private $_barraDireitaVar    = '';

    /**
     * @var array
     */
    private $_dadosBarraUsuario  = array();
    /**
     * @var array
     */
    private $_dadosBarraEsquerda = array();
    /**
     * @var array
     */
    private $_dadosBarraDireita  = array();

    /**
     *
     */
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * @param $path
     * @return WL_Controller
     */
    protected function _setBarraUsuarioPath($path)
    {
        $this->_barraUsuarioVar = (string)$path;

        return $this;
    }

    /**
     * @return string
     */
    protected function _getBarraUsuarioPath()
    {
        return $this->_barraUsuarioVar;
    }

    /**
     * @param $path
     * @return WL_Controller
     */
    protected function _setBarraEsquerdaPath($path)
    {
        $this->_barraEsquerdaVar = (string)$path;

        return $this;
    }

    /**
     * @return string
     */
    protected function _getBarraEsquerdaPath()
    {
        return $this->_barraEsquerdaVar;
    }

    /**
     * @param $path
     * @return WL_Controller
     */
    protected function _setBarraDireitaPath($path)
    {
        $this->_barraDireitaVar = (string)$path;

        return $this;
    }

    /**
     * @return string
     */
    protected function _getBarraDireitaPath()
    {
        return $this->_barraDireitaVar;
    }

    /**
     * @param $var
     * @param $valor
     * @return WL_Controller
     */
    protected function _barraUsuarioSetVar ($var, $valor)
    {
        $this->_dadosBarraUsuario[ trim($var) ] = $valor;

        return $this;
    }

    /**
     * @param $var
     * @return WL_Controller
     */
    protected function _barraUsuarioUnsetVar ($var)
    {
        $var = trim($var);
        if ( isset( $this->_dadosBarraUsuario[ $var ] ) ) {
            unset( $this->_dadosBarraUsuario[ $var ] );
        }

        return $this;
    }

    /**
     * @param $var
     * @param $valor
     * @return WL_Controller
     */
    protected function _barraEsquerdaSetVar ($var, $valor)
    {
        $this->_dadosBarraEsquerda[ trim($var) ] = $valor;

        return $this;
    }

    /**
     * @param $var
     * @return WL_Controller
     */
    protected function _barraEsquerdaUnsetVar ($var)
    {
        $var = trim($var);
        if ( isset( $this->_dadosBarraEsquerda[ $var ] ) ) {
            unset( $this->_dadosBarraEsquerda[ $var ] );
        }

        return $this;
    }

    /**
     * @param $var
     * @param $valor
     * @return WL_Controller
     */
    protected function _barraDireitaSetVar ($var, $valor)
    {
        $this->_dadosBarraDireita[ trim($var) ] = $valor;

        return $this;
    }

    /**
     * @param $var
     * @return WL_Controller
     */
    protected function _barraDireitaUnsetVar ($var)
    {
        $var = trim($var);
        if ( isset( $this->_dadosBarraDireita[ $var ] ) ) {
            unset( $this->_dadosBarraDireita[ $var ] );
        }

        return $this;
    }

    /**
     * @param $templateName
     * @return WL_Controller
     */
    protected function _setTemplate($templateName)
    {
        $this->_template = (string) $templateName;

        return $this;
    }

    /**
     * @return string
     */
    protected function _getTemplate()
    {
        return $this->_template;
    }

    /**
     * @param string $view
     * @param array|null $dados
     */
    protected function _renderTemplate ($view = '', array $dados = null)
    {
        $this->template->setTemplate($this->_template);

        if ( $this->_barraUsuarioVar ) {

            $this->_barraUsuarioSetVar(
                'usuario',
                $this->autenticacao->getUsuarioAutenticado()
            );

            $this->template->setDefaultPartialVar(
                $this->_barraUsuarioVar,
                $this->_dadosBarraUsuario
            );

        }

        if ( $this->_barraEsquerdaVar ) {

            $this->template->setDefaultPartialVar(
                $this->_barraEsquerdaVar,
                $this->_dadosBarraEsquerda
            );

        }

        if ( $this->_barraDireitaVar ) {

            $this->template->setDefaultPartialVar(
                $this->_barraDireitaVar,
                $this->_dadosBarraDireita
            );

        }

        $this->template->render( $view, $dados );
    }
}

class Home_Controller extends WL_Controller
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        if( ! $this->autenticacao->isAutenticado() ) {
            redirect('/');
        }
    }

    /**
     * @param string $view
     * @param array $dados
     */
    protected function _renderTemplateHome( $view = '', $dados = null )
    {

        $this->_setTemplate( 'home' )
             ->_setBarraUsuarioPath( 'perfil/barra_usuario' )
             ->_setBarraEsquerdaPath( 'home/barra_lateral_esquerda' )
             ->_setBarraDireitaPath( 'home/barra_lateral_direita' )

             ->_barraEsquerdaSetVar(
                 'usuario',
                 $this->autenticacao->getUsuarioAutenticado()
             )

             ->_renderTemplate( $view, $dados );
    }

}


class Curso_Controller extends WL_Controller
{
    /**
     * @var CursoDAO
     */
    protected $_cursoDao;

    /**
     * @var int
     */
    private $_nivelAcesso;
    /**
     * @var string
     */
    private $_nivelAcessoCursoId;
    /**
     * @var WeLearn_Usuarios_Autorizacao_Papel
     */
    private $_papel;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        if( ! $this->autenticacao->isAutenticado() ) {
            redirect('/');
        }

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');

        $this->template->appendJSImport('curso.js');
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    protected function _getNivelAcesso(WeLearn_Cursos_Curso $curso)
    {
        if ( null === $this->_nivelAcesso || $this->_nivelAcessoCursoId != $curso->getId() ) {

            $this->_nivelAcessoCursoId = $curso->getId();

            $this->_nivelAcesso = $this->_cursoDao->recuperarTipoDeVinculo(
                $this->autenticacao->getUsuarioAutenticado(),
                $curso
            );

        }

        return $this->_nivelAcesso;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return WeLearn_Usuarios_Autorizacao_Papel
     */
    protected function _getPapel(WeLearn_Cursos_Curso $curso)
    {
        if ( null === $this->_papel || $this->_nivelAcessoCursoId != $curso->getId() ) {

            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $nivelAcesso = $this->_getNivelAcesso( $curso );
            $usuario = $this->autenticacao->getUsuarioAutenticado();

            switch ( $nivelAcesso ) {
                case WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_PRINCIPAL:
                    $this->_papel = $usuarioDao->criarGerenciadorPrincipal( $usuario );
                    break;
                case WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR:
                    $this->_papel = $usuarioDao->criarGerenciadorAuxiliar( $usuario );
                    break;
                case WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO:
                    $this->_papel = $usuarioDao->criarAluno( $usuario );
                    break;
                case WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO_INSCRICAO_PENDENTE:
                case WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_CONVITE_PENDENTE:
                case WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO:
                default:
                    $this->_papel = $usuario;
            }

        }

        return $this->_papel;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param string $view
     * @param array|null $dados
     */
    protected function _renderTemplateCurso(WeLearn_Cursos_Curso $curso,
                                            $view = '',
                                            array $dados = null)
    {
        $vinculo = $this->_getNivelAcesso( $curso );

        $urlImagem =   ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                      ? $curso->getImagem()->getUrl()
                      : site_url( $this->config->item('default_curso_img_uri') );

        $this->_setTemplate( 'curso' )
             ->_setBarraUsuarioPath('perfil/barra_usuario')
             ->_setBarraEsquerdaPath( 'curso/barra_lateral_esquerda' )
             ->_setBarraDireitaPath( 'curso/barra_lateral_direita' )

             ->_barraEsquerdaSetVar( 'idCurso', $curso->getId() )

             ->_barraDireitaSetVar( 'nome' , $curso->getNome() )
             ->_barraDireitaSetVar( 'imagemUrl', $urlImagem )
             ->_barraDireitaSetVar( 'descricao', $curso->getDescricao() )
             ->_barraDireitaSetVar( 'tipoVinculo', $vinculo )
             ->_barraDireitaSetVar( 'nomeCriador', $curso->getCriador()->getNomeUsuario() )
             ->_barraDireitaSetVar( 'idCurso', $curso->getId() )

             ->_renderTemplate( $view, $dados );
    }
}

class Perfil_Controller extends WL_Controller
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        if( ! $this->autenticacao->isAutenticado() ) {
            redirect('/');
        }
    }

    public function _renderTemplatePerfil( $view = '', $dados = null )
    {
        $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioPerfil = $usuarioDao->recuperar($dados['usuarioPerfil']->getId());
        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        try{
            $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');

            $listaRandonicaAmigos = $amizadeUsuarioDao->recuperarAmigosAleatorios(
                $usuarioPerfil,
                10
            );

        }catch(cassandra_NotFoundException $e){

            $listaRandonicaAmigos = null;

        }

        try{
            $gerenciadorPrincipal = $usuarioDao->criarGerenciadorPrincipal($usuarioPerfil);
            $listaRandonicaCursosCriados = $cursoDao->recuperarTodosPorCriadorAleatorios(
                $gerenciadorPrincipal,
                10
            );

        }catch(cassandra_NotFoundException $e){

            $listaRandonicaCursosCriados = null;

        }

        try{
            $aluno = $usuarioDao->criarAluno($usuarioPerfil);
            $listaRandonicaCursosInscritos = $cursoDao->recuperarTodosPorAlunoAleatorios(
               $aluno,
               10
            );
        }catch(cassandra_NotFoundException $e)
        {
            $listaRandonicaCursosInscritos = null;
        }

        $widgets = array();

        if(!is_null($listaRandonicaAmigos)){
            $widgets[] = $this->template->loadPartial(
                'widget_amigos',
                array('legenda' =>'Amigos de '.$usuarioPerfil->getNome(),'link'=>'usuario/perfil/listar_amigos/'.$usuarioPerfil->id,'listaRandonicaAmigos' => $listaRandonicaAmigos),
                'usuario/amigos'
            );
        }
        if(!is_null($listaRandonicaCursosCriados)){
            $widgets[] = $this->template->loadPartial(
                'widget_cursos_criados',
                array('legenda' => 'Cursos criados por '.$usuarioPerfil->getNome(),'link'=>'link para curso','listaRandonicaCursosCriados' => $listaRandonicaCursosCriados),
                'usuario/cursos'
            );
        }
        if(!is_null($listaRandonicaCursosInscritos)){
            $widgets[] = $this->template->loadPartial(
              'widget_cursos_aluno',
                array('legenda' => 'Cursos em que '.$usuarioPerfil->getNome().' participa','link'=>'link para curso','listaRandonicaCursosInscritos' => $listaRandonicaCursosInscritos),
                'usuario/cursos'
            );
        }

        $this->_setTemplate( 'perfil' )
             ->_setBarraUsuarioPath('perfil/barra_usuario')
             ->_setBarraEsquerdaPath( 'perfil/barra_lateral_esquerda' )
             ->_setBarraDireitaPath( 'perfil/barra_lateral_direita' )

             ->_barraEsquerdaSetVar( 'usuario', $dados )

            ->_barraDireitaSetVar(
            'widgetsContexto',
            $widgets
        )
             ->_renderTemplate($view, $dados);
    }
}