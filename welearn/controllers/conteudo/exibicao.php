<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 28/05/12
 * Time: 12:00
 * To change this template use File | Settings | File Templates.
 */
class Exibicao extends Curso_Controller
{
    /**
     * @var AlunoDAO
     */
    private $_alunoDao;

    /**
     * @var ParticipacaoCursoDAO
     */
    private $_participacaoCursoDao;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    /**
     * @var AulaDAO
     */
    private $_aulaDao;

    /**
     * @var ModuloDAO
     */
    private $_moduloDao;

    /**
     * @var AvaliacaoDAO
     */
    private $_avaliacaoDao;

    /**
     * @var QuestaoAvaliacaoDAO
     */
    private $_questaoAvaliacaoDao;

    /**
     * @var AlternativaAvaliacaoDAO
     */
    private $_alternativaAvaliacaoDao;

    /**
     * @var ControleAvaliacaoDAO
     */
    private $_controleAvaliacaoDao;

    /**
     * @var AnotacaoDAO
     */
    private $_anotacaoDao;

    /**
     * @var ComentarioDAO
     */
    private $_comentarioDao;

    /**
     * @var RecursoDAO
     */
    private $_recursoDao;

    /**
     * @var WeLearn_Usuarios_Aluno
     */
    private $_alunoAtual;

    public function __construct()
    {
        parent::__construct();

        $this->_alunoDao = WeLearn_DAO_DAOFactory::create('AlunoDAO');
        $this->_participacaoCursoDao = WeLearn_DAO_DAOFactory::create('ParticipacaoCursoDAO');
        $this->_paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
        $this->_aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
        $this->_moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

        $this->_alunoAtual = $this->_alunoDao->criarAluno(
            $this->autenticacao->getUsuarioAutenticado()
        );

        $this->template->appendCSS('sala_de_aula.css')
                       ->appendJSImport('exibicao_conteudo_curso.js');
    }

    public function index( $idCurso )
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $curso
            );;

            $iniciouCurso = false;
            $totalPaginas = 0;
            $totalPaginasVistas = 0;

            //Verifica se conteudo do curso não está bloqueado nas configurações, o que resultaria na não-exibição da página
            $conteudoAberto = ( $curso->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO );
            if ( $conteudoAberto ) {

                //Recupera quantidade total de páginas existente no curso para geração do gráfico de progresso.
                $totalPaginas = $this->_paginaDao->recuperarQtdTotalPorCurso( $curso );
                $totalPaginasVistas = $this->_participacaoCursoDao
                                           ->getControlePaginaDAO()
                                           ->recuperarQtdTotal(
                                               $participacaoCurso
                                           );
                $iniciouCurso = $totalPaginasVistas > 0;

                $conteudoAtual = $this->_recuperarConteudoAtual( $participacaoCurso );

                $moduloAtual = $conteudoAtual['modulo'];
                $aulaAtual = $conteudoAtual['aula'];
                $paginaAtual = $conteudoAtual['pagina'];
                $avaliacaoAtual = $conteudoAtual['avaliacao'];
                $srcIframeConteudo  = $conteudoAtual['url'];

            } else {

                $moduloAtual = null;
                $aulaAtual = null;
                $paginaAtual = null;
                $avaliacaoAtual = null;
                $srcIframeConteudo  = site_url( 'curso/conteudo/exibicao/exibir/' . $curso->getId() );

            }

            $haModulo = ( $moduloAtual instanceof WeLearn_Cursos_Conteudo_Modulo );
            $haAula = ( $aulaAtual instanceof WeLearn_Cursos_Conteudo_Aula );
            $haPagina = ( $paginaAtual instanceof WeLearn_Cursos_Conteudo_Pagina );
            $haAvaliacao = ( $avaliacaoAtual instanceof WeLearn_Cursos_Avaliacoes_Avaliacao );

            $dadosViewSalaDeAula = array(
                'conteudoAberto' => $conteudoAberto,
                'tipoConteudo' => $participacaoCurso->getTipoConteudoAtual(),
                'idCurso' => $curso->getId(),
                'idModulo' => $haModulo ? $moduloAtual->getId() : '',
                'idAula' => $haAula ? $aulaAtual->getId() : '',
                'idPagina' => $haPagina ? $paginaAtual->getId() : '',
                'idAvaliacao' => $haAvaliacao ? $avaliacaoAtual->getId() : '',
                'srcIframeConteudo' => $srcIframeConteudo,
                'htmlSectionAnotacao' => $haPagina ? $this->_loadSectionAnotacaoView( $paginaAtual ) : '',
                'htmlSectionComentarios' => $haPagina ? $this->_loadSectionComentariosView( $paginaAtual ) : '',
                'htmlSectionInfoEtapa' => $paginaAtual ? $this->_loadSectionInfoEtapaView( $paginaAtual ) : '',
                'htmlSectionRecursos' => $aulaAtual ? $this->_loadSectionRecursosView() : ''
            );

            $dadosView = array(
                'iniciouCurso' => $iniciouCurso,
                'paginaAtual' => $paginaAtual,
                'aulaAtual' => $aulaAtual,
                'moduloAtual' => $moduloAtual,
                'progressoNoCurso' => ( $totalPaginas > 0 )
                    ? number_format( ( $totalPaginasVistas / $totalPaginas ) * 100, 1 )
                    : 0,
                'htmlJanelaSalaDeAula' => $this->load->view(
                    'curso/conteudo/exibicao/sala_de_aula',
                    $dadosViewSalaDeAula,
                    true
                )
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/exibicao/index',
                $dadosView
            );

        } catch(Exception $e) {
            log_message('error', 'Erro ao tentar index do modulo de visualização de conteudo do curso.');

            show_404();
        }
    }

    public function exibir( $idCurso )
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $curso
            );

            $tipoConteudo = $this->input->get('t');
            $idConteudo   = $this->input->get('id');

            switch ( $tipoConteudo ) {
                case WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA:
                    $pagina = $this->_paginaDao->recuperar( $idConteudo );
                    $this->_exibirPagina( $participacaoCurso, $pagina );
                    break;
                case WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO:
                    $avaliacao = $this->_avaliacaoDao->recuperar( $idConteudo );
                    $this->_aplicarAvaliacao( $participacaoCurso, $avaliacao );
                    break;
                case WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM:
                default:
                    throw new WeLearn_Base_Exception('Tipo de conteúdo incorreto!');
            }            
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir conteúdo para aluno na sala de aula: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function acessar_proximo( $idcurso )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idcurso );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $curso
            );

            $tipoConteudoAnterior = $this->input->get('t');
            $idConteudoAnterior = $this->input->get('id');

            switch ( $tipoConteudoAnterior ) {
                case WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA:

                    break;
                case WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO:

                    break;
                case WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM:
                default:
                    throw new WeLearn_Base_Exception('Tipo de conteúdo inválido!');
            }

        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar proximo conteúdo de aula: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar_anotacao($idPagina)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $this->_anotacaoDao = WeLearn_DAO_DAOFactory::create('AnotacaoDAO');

            $pagina = $this->_paginaDao->recuperar( $idPagina );

            $anotacao = $this->_anotacaoDao->criarNovo(array(
                'conteudo' => $this->input->post('anotacao'),
                'usuario' => $this->_alunoAtual,
                'pagina' => $pagina
            ));

            $this->_anotacaoDao->salvar( $anotacao );

            $json = create_json_feedback( true );

        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar salvar anotação de página: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    private function _loadSectionAnotacaoView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $this->_anotacaoDao = WeLearn_DAO_DAOFactory::create('AnotacaoDAO');

        try {
            $anotacaoAtual = $this->_anotacaoDao->recuperarPorUsuario(
                $pagina,
                $this->_alunoAtual
            );
        } catch (cassandra_NotFoundException $e) {
            $anotacaoAtual = null;
        }

        $dadosAnotacaoView = array(
            'formAction' => '/curso/conteudo/exibicao/salvar_anotacao/' . $pagina->getId(),
            'extraOpenForm' => 'id="exibicao-conteudo-anotacao-form"',
            'formHidden' => array(),
            'idPagina' => $pagina->getId(),
            'anotacaoAtual' => $anotacaoAtual
        );

        return $this->template->loadPartial(
            'section_anotacao',
            $dadosAnotacaoView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionComentariosView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $dadosFormComentario = array(
            'formAction' => 'conteudo/comentario/salvar',
            'extraOpenForm' => 'id="form-comentario-criar"',
            'formHidden' => array('acao' => 'criar', 'paginaId' => $pagina->getId()),
            'assuntoAtual' => '',
            'txtComentarioAtual' => '',
            'idBotaoEnviar' => 'btn-form-comentario-criar',
            'txtBotaoEnviar' => 'Postar Comentário!'
        );

        $dadosComentariosView = array(
            'formCriar' => $this->template->loadPartial(
                'form',
                $dadosFormComentario,
                'curso/conteudo/comentario'
            )
        );

        return $this->template->loadPartial(
            'section_comentarios',
            $dadosComentariosView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionInfoEtapaView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $this->load->helper(array('modulo', 'aula', 'pagina'));

        $listaModulos = $this->_moduloDao->recuperarTodosPorCurso(
            $pagina->getAula()->getModulo()->getCurso()
        );

        $listaAulas = $this->_aulaDao->recuperarTodosPorModulo(
            $pagina->getAula()->getModulo()
        );

        $listaPaginas = $this->_paginaDao->recuperarTodosPorAula(
            $pagina->getAula()
        );

        $dadosInfoEtapaView = array(
            'modulo' => $pagina->getAula()->getModulo(),
            'aula' => $pagina->getAula(),
            'pagina' => $pagina,
            'selectModulos' => $this->template->loadPartial(
                'select_modulos',
                array(
                    'listaModulos' => lista_modulos_para_dados_dropdown( $listaModulos ),
                    'moduloSelecionado' => $pagina->getAula()->getModulo()->getId(),
                    'extra' => 'id="slt-modulos"'
                ),
                'curso/conteudo'
            ),
            'selectAulas' => $this->template->loadPartial(
                'select_aulas',
                array(
                    'listaAulas' => lista_aulas_para_dados_dropdown( $listaAulas ),
                    'aulaSelecionada' => $pagina->getAula()->getId(),
                    'extra' => 'id="slt-aulas"'
                ),
                'curso/conteudo'
            ),
            'selectPaginas' => $this->template->loadPartial(
                'select_paginas',
                array(
                    'listaPaginas' => lista_paginas_para_dados_dropdown( $listaPaginas ),
                    'paginaSelecionada' => $pagina->getId(),
                    'extra' => 'id="slt-paginas"'
                ),
                'curso/conteudo'
            )
        );

        return $this->template->loadPartial(
            'section_info_etapa',
            $dadosInfoEtapaView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionRecursosView()
    {
        $dadosRecursosView = array();

        return $this->template->loadPartial(
            'section_recursos',
            $dadosRecursosView,
            'curso/conteudo/exibicao'
        );
    }

    private function _exibirPagina(
        WeLearn_Cursos_ParticipacaoCurso $participacaocurso,
        WeLearn_Cursos_Conteudo_Pagina $pagina
    ) {
        $paginaDisponivel = $this->_participacaoCursoDao
                                 ->getControlePaginaDAO()
                                 ->isDisponivel( $participacaocurso, $pagina );

        if ( $paginaDisponivel ) {

            $conteudo = $this->_loadConteudoPaginaView( $pagina );

        } else {

            $conteudo = $this->template->loadPartial(
                'conteudo_indisponivel',
                array(),
                'curso/conteudo/exibicao'
            );

        }

        $this->load->view(
            'curso/conteudo/exibicao/exibir',
            array( 'conteudo' => $conteudo )
        );
    }

    private function _loadConteudoPaginaView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $dadosConteudoPaginaView = array(
            'pagina' => $pagina
        );

        return $this->template->loadPartial(
            'conteudo_pagina',
            $dadosConteudoPaginaView,
            'curso/conteudo/exibicao'
        );
    }

    private function _aplicarAvaliacao(
        WeLearn_Cursos_ParticipacaoCurso $participacaocurso,
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
    ) {

    }

    private function _loadAplicacaoAvaliacaoView(WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao = null)
    {
        $dadosAplicacaoAvaliacaoView = array();

        return $this->template->loadPartial(
            'aplicacao_avaliacao',
            $dadosAplicacaoAvaliacaoView,
            'curso/conteudo/exibicao'
        );
    }

    private function _recuperarConteudoAtual(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $url = site_url(
            'curso/conteudo/exibicao/exibir/' . $participacaoCurso->getCurso()->getId()
        );

        switch ( $participacaoCurso->getTipoConteudoAtual() ) {
            case WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA:
                if ( $participacaoCurso->getPaginaAtual() instanceof WeLearn_Cursos_Conteudo_Pagina ) {

                    return array(
                        'pagina' => $participacaoCurso->getPaginaAtual(),
                        'aula' => $participacaoCurso->getPaginaAtual()->getAula(),
                        'modulo' => $participacaoCurso->getPaginaAtual()->getAula()->getModulo(),
                        'avaliacao' => null,
                        'url' => $url . '?t=' . $participacaoCurso->getTipoConteudoAtual()
                                      . '&id=' . $participacaoCurso->getPaginaAtual()->getId()
                    );

                }
                break;
            case WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO:
                if ( $participacaoCurso->getAvaliacaoAtual() instanceof WeLearn_Cursos_Avaliacoes_Avaliacao ) {

                    return array(
                        'pagina' => null,
                        'aula' => null,
                        'modulo' => $participacaoCurso->getAvaliacaoAtual()->getModulo(),
                        'avaliacao' => $participacaoCurso->getAvaliacaoAtual(),
                        'url' => $url . '?t=' . $participacaoCurso->getTipoConteudoAtual()
                                      . '&id=' . $participacaoCurso->getAvaliacaoAtual()->getId()
                    );

                }
                break;
            case WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM;
            default:
                $moduloAtual = $this->_moduloDao->recuperarProximo( $participacaoCurso->getCurso() );
                $aulaAtual = $moduloAtual ? $this->_aulaDao->recuperarProxima( $moduloAtual ) : false;
                $paginaAtual = $aulaAtual ? $this->_paginaDao->recuperarProxima( $aulaAtual ) : false;

                if ( $paginaAtual ) {

                    //É preciso registrar o inicio do módulo ( caso exista )
                    $this->_participacaoCursoDao->getControleModuloDAO()->acessar(
                        $participacaoCurso,
                        $moduloAtual
                    );

                    //É preciso registrar o inicio da aula ( caso exista )
                    $this->_participacaoCursoDao->getControleAulaDAO()->acessar(
                        $participacaoCurso,
                        $aulaAtual
                    );

                    //É preciso liberar esta página (caso exista) para que o aluno possa ve-la
                    $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                        $participacaoCurso,
                        $paginaAtual
                    );

                    $url .= '?t=' . $participacaoCurso->getTipoConteudoAtual()
                          . '&id=' . $paginaAtual->getId();
                }

                return array(
                    'pagina' => $paginaAtual,
                    'aula' => $aulaAtual,
                    'modulo' => $moduloAtual,
                    'avaliacao' => null,
                    'url' => $url
                );
        }

        return array(
            'pagina' => null,
            'aula' => null,
            'modulo' => null,
            'avaliacao' => null,
            'url' => $url
        );
    }
}
