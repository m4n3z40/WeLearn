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
     */
    protected function _setPapel(WeLearn_Cursos_Curso $curso)
    {
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

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return WeLearn_Usuarios_Autorizacao_Papel
     */
    protected function _getPapel(WeLearn_Cursos_Curso $curso = null)
    {
        if ( $curso instanceof WeLearn_Cursos_Curso &&
            ( null === $this->_papel ||
              $this->_nivelAcessoCursoId != $curso->getId() )
        ) {

            $this->_setPapel( $curso );

        }

        return $this->_papel;
    }

    /**
     * @param WeLearn_Cursos_Curso $doCurso
     */
    protected function _expulsarNaoAutorizados(WeLearn_Cursos_Curso $doCurso)
    {
        if ( ! $this->autorizacao->isAutorizadoNaAcaoAtual( $this->_getPapel( $doCurso ) ) ) {

            show_404();

            exit;

        }
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
             ->_barraEsquerdaSetVar( 'papelUsuarioAtual', $this->_getPapel( $curso ) )

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
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioPerfil = $usuarioDao->recuperar($dados['usuarioPerfil']->getId());
        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
        try{

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
            if($usuarioAutenticado->getId()== $usuarioPerfil->getId())
            {
               $link = 'usuario/amigos/listar';
               $legenda = 'Meus Amigos';
            }
            else{
                $link = 'usuario/perfil/listar_amigos/'.$usuarioPerfil->id;
                $legenda = 'Amigos de '.$usuarioPerfil->getNome();
            }
            $widgets[] = $this->template->loadPartial(
                'widget_amigos',
                array('legenda' => $legenda,'link'=>$link ,'listaRandonicaAmigos' => $listaRandonicaAmigos),
                'usuario/amigos'
            );
        }
        if(!is_null($listaRandonicaCursosCriados)){
            if($usuarioAutenticado->getId()== $usuarioPerfil->getId())
            {
                $link = 'curso/meus_cursos_criador';
                $legenda = 'Cursos criados por mim';
            }
            else{
                $link = 'usuario/perfil/meus_cursos_criador/'.$usuarioPerfil->id;
                $legenda = 'Cursos criados por '.$usuarioPerfil->getNome();
            }
            $widgets[] = $this->template->loadPartial(
                'widget_cursos_criados',
                array('legenda' => $legenda ,'link'=> $link,'listaRandonicaCursosCriados' => $listaRandonicaCursosCriados),
                'usuario/cursos'
            );
        }
        if(!is_null($listaRandonicaCursosInscritos)){
            if($usuarioAutenticado->getId()== $usuarioPerfil->getId())
            {
                $link = 'curso/meus_cursos_aluno';
                $legenda = 'Cursos em que participo';
            }
            else{
                $link = 'usuario/perfil/meus_cursos_aluno/'.$usuarioPerfil->id;
                $legenda = 'Cursos em que '.$usuarioPerfil->getNome().' participa';
            }
            $widgets[] = $this->template->loadPartial(
              'widget_cursos_aluno',
                array('legenda' => $legenda,'link' => $link,'listaRandonicaCursosInscritos' => $listaRandonicaCursosInscritos),
                'usuario/cursos'
            );
        }

        if($usuarioPerfil->getId() != $usuarioAutenticado->getId() )
        {
            $saoAmigos=$amizadeUsuarioDao->SaoAmigos($usuarioAutenticado,$usuarioPerfil);
            $dados['saoAmigos']=$saoAmigos;



            if($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA )// se houver requisicoes de amizade em espera, carrega a partial convites
            {
                $convitePendente = $conviteCadastradoDao->recuperarPendentes($usuarioAutenticado,$usuarioPerfil);
                $dados['convitePendente']=$convitePendente;
            }
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