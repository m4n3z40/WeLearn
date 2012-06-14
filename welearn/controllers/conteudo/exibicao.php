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
     * @var ControleAvaliacaoDAO
     */
    private $_controleAvaliacaoDao;

    /**
     * @var AnotacaoDAO
     */
    private $_anotacaoDao;

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
        $this->_avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
        $this->_anotacaoDao = WeLearn_DAO_DAOFactory::create('AnotacaoDAO');
        $this->_controleAvaliacaoDao = WeLearn_DAO_DAOFactory::create('ControleAvaliacaoDAO');
        $this->_questaoAvaliacaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');

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
            );

            if ( $participacaoCurso->getSituacao() === WeLearn_Cursos_SituacaoParticipacaoCurso::INATIVO
              || $participacaoCurso->getSituacao() === WeLearn_Cursos_SituacaoParticipacaoCurso::INSCRICAO_EM_ESPERA) {
                show_404();
                return;
            }

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
                                           ->recuperarQtdTotalPaginasVistas(
                                               $participacaoCurso->getCFKey()
                                           );
                $iniciouCurso = $totalPaginasVistas > 0;

                $conteudoAtual = $this->_recuperarConteudoAtual( $participacaoCurso );

                $moduloAtual = $conteudoAtual['modulo'];
                $aulaAtual = $conteudoAtual['aula'];
                $paginaAtual = $conteudoAtual['pagina'];
                $avaliacaoAtual = $conteudoAtual['avaliacao'];
                $srcIframeConteudo  = $conteudoAtual['url'];

            } else {

                $moduloAtual = false;
                $aulaAtual = false;
                $paginaAtual = false;
                $avaliacaoAtual = false;
                $srcIframeConteudo  = site_url( 'curso/conteudo/exibicao/exibir/' . $curso->getId() );

            }

            $dadosViewSalaDeAula = array(
                'conteudoAberto' => $conteudoAberto,
                'tipoConteudo' => $participacaoCurso->getTipoConteudoAtual(),
                'idCurso' => $curso->getId(),
                'idModulo' => $moduloAtual ? $moduloAtual->getId() : '',
                'idAula' => $aulaAtual ? $aulaAtual->getId() : '',
                'idPagina' => $paginaAtual ? $paginaAtual->getId() : '',
                'idAvaliacao' => $avaliacaoAtual ? $avaliacaoAtual->getId() : '',
                'isAulaInicial' => $aulaAtual && $aulaAtual->getNroOrdem() === 1,
                'isPaginaInicial' => $paginaAtual && $paginaAtual->getNroOrdem() === 1,
                'srcIframeConteudo' => $srcIframeConteudo,
                'htmlSectionAnotacao' => $this->_loadSectionAnotacaoView( $paginaAtual ),
                'htmlSectionComentarios' => $this->_loadSectionComentariosView( $paginaAtual ),
                'htmlSectionInfoEtapa' => $moduloAtual ? $this->_loadSectionInfoEtapaView( $moduloAtual, $aulaAtual, $paginaAtual, $avaliacaoAtual ) : '',
                'htmlSectionRecursos' => $this->_loadSectionRecursosView()
            );

            $dadosView = array(
                'iniciouCurso' => $iniciouCurso,
                'paginaAtual' => $paginaAtual,
                'aulaAtual' => $aulaAtual,
                'moduloAtual' => $moduloAtual,
                'avaliacaoAtual' => $avaliacaoAtual,
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
            log_message('error', 'Erro ao tentar exibir index do modulo de visualização de conteudo do curso.');

            show_404();
        }
    }

    public function avaliacoes ( $idCurso )
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $curso
            );

            try {
                $listaControlesAvaliacoes = $this->_controleAvaliacaoDao->recuperarTodosPorParticipacao(
                    $participacaoCurso
                );
            } catch (cassandra_NotFoundException $e) {
                $listaControlesAvaliacoes = array();
            }

            $dadosView = array(
                'haAvaliacoesDisponiveis' => count( $listaControlesAvaliacoes ) > 0,
                'listaControlesAvaliacoes' => $listaControlesAvaliacoes
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/exibicao/avaliacoes',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir avaliações disponíveis do usuário.');

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

            switch ( $tipoConteudo ) {
                case WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA:
                    $conteudo = $this->_exibirPagina( $participacaoCurso );
                    break;
                case WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO:
                    $conteudo = $this->_aplicarAvaliacao( $participacaoCurso );
                    break;
                case WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM:
                default:
                    throw new WeLearn_Base_Exception('Tipo de conteúdo incorreto!');
            }            
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir conteúdo para aluno na sala de aula: '
                . create_exception_description($e));

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

    public function aplicar_avaliacao ( $idAvaliacao )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $avaliacao = $this->_avaliacaoDao->recuperar( $idAvaliacao );

            $avaliacao->setQuestoes(
                $this->_questaoAvaliacaoDao->recuperarTodosPorAvaliacao( $avaliacao )
            );

            $response = Zend_Json::encode(array(
                'htmlAvaliacao' => $this->template->loadPartial(
                    'aplicacao_avaliacao',
                    array( 'avaliacao' => $avaliacao ),
                    'curso/conteudo/exibicao'
                ),
                'avaliacao' => $avaliacao->toCassandra()
            ));

            $json = create_json_feedback(true, '', $response);
        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar aplicação de avaliação: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function ir_para ( $idPagina )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $pagina = $this->_paginaDao->recuperar( $idPagina );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $pagina->getAula()->getModulo()->getCurso()
            );

            try {

                $controlePagina = $this->_participacaoCursoDao->getControlePaginaDAO()->recuperar(
                    $pagina,
                    $participacaoCurso
                );

                if (   $controlePagina->getStatus() != WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO ) {

                    $this->_participacaoCursoDao->getControleModuloDAO()->acessar(
                        $participacaoCurso,
                        $pagina->getAula()->getModulo()
                    );

                    $this->_participacaoCursoDao->getControleAulaDAO()->acessar(
                        $participacaoCurso,
                        $pagina->getAula()
                    );

                    $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                        $participacaoCurso,
                        $pagina
                    );

                    $json = $this->_retornarJSONPagina( $participacaoCurso, $pagina );

                } else {

                    $error = create_json_feedback_error_json(
                        'Desculpe, o conteúdo que está tentando acessar está bloqueado para você :(
                        <br> Entre em contato com os gerenciadores.'
                    );

                    $json = create_json_feedback(false, $error);

                }

            } catch ( cassandra_NotFoundException $e ) {

                $error = create_json_feedback_error_json(
                    'Desculpe, o conteúdo que está tentando acessar está indisponível para você :(
                    <br> Você deve respeitar a sequência das aulas, nenhuma página, aula ou módulo pode ser pulado .'
                );

                $json = create_json_feedback(false, $error);

            }
        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar conteúdo da aula anterior: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function aula_anterior ( $idPaginaAtual )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $paginaAtual = $this->_paginaDao->recuperar( $idPaginaAtual );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $paginaAtual->getAula()->getModulo()->getCurso()
            );

            $aulaAnterior = $this->_aulaDao->recuperarAnterior(
                $paginaAtual->getAula()->getModulo(),
                $paginaAtual->getAula()->getNroOrdem()
            );

            $this->_participacaoCursoDao->getControleAulaDAO()->acessar(
                $participacaoCurso,
                $aulaAnterior
            );

            $paginaInicial = $this->_paginaDao->recuperarProxima(
                $aulaAnterior
            );

            $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                $participacaoCurso,
                $paginaInicial
            );

            $json = $this->_retornarJSONPagina( $participacaoCurso, $paginaInicial );
        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar conteúdo da aula anterior: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json(
                'Desculpe, o conteúdo que está tentando acessar está indisponível no momento :(
                <br> Entre em contato com os gerenciadores.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function inicio_aula( $idPaginaAtual )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $paginaAtual = $this->_paginaDao->recuperar( $idPaginaAtual );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $paginaAtual->getAula()->getModulo()->getCurso()
            );

            $paginaInicial = $this->_paginaDao->recuperarProxima(
                $paginaAtual->getAula()
            );

            $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                $participacaoCurso,
                $paginaInicial
            );

            $json = $this->_retornarJSONPagina( $participacaoCurso, $paginaInicial );
        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar conteúdo inicial de aula: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json(
                'Desculpe, o conteúdo que está tentando acessar está indisponível no momento :(
                <br> Entre em contato com os gerenciadores.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function acessar_anterior( $idPaginaAtual )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $paginaAtual = $this->_paginaDao->recuperar( $idPaginaAtual );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $paginaAtual->getAula()->getModulo()->getCurso()
            );

            $paginaAnterior = $this->_paginaDao->recuperarAnterior(
                $paginaAtual->getAula(),
                $paginaAtual->getNroOrdem()
            );

            $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                $participacaoCurso,
                $paginaAnterior
            );

            $json = $this->_retornarJSONPagina( $participacaoCurso, $paginaAnterior );
        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar conteúdo anterior de aula: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json(
                'Desculpe, o conteúdo que está tentando acessar está indisponível no momento :(
                <br> Entre em contato com os gerenciadores.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
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

            $json = $this->_recuperarProximoConteudo( $participacaoCurso );
        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar proximo conteúdo de aula: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json(
                'Desculpe, o conteúdo que está tentando acessar está indisponível no momento :(
                <br> Entre em contato com os gerenciadores.'
            );

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

    private function _loadSectionAnotacaoView($pagina = null)
    {
        try {
            if ($pagina instanceof WeLearn_Cursos_Conteudo_Pagina) {
                $anotacaoAtual = $this->_anotacaoDao->recuperarPorUsuario(
                    $pagina,
                    $this->_alunoAtual
                );
            } else {
                $anotacaoAtual = null;
            }
        } catch (cassandra_NotFoundException $e) {
            $anotacaoAtual = null;
        }

        $dadosAnotacaoView = array(
            'pagina' => $pagina,
            'formAction' => '/curso/conteudo/exibicao/salvar_anotacao',
            'extraOpenForm' => 'id="exibicao-conteudo-anotacao-form"',
            'formHidden' => array(),
            'anotacaoAtual' => $anotacaoAtual
        );

        return $this->template->loadPartial(
            'section_anotacao',
            $dadosAnotacaoView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionComentariosView($pagina = null)
    {
        $dadosFormComentario = array(
            'formAction' => 'conteudo/comentario/salvar',
            'extraOpenForm' => 'id="form-comentario-criar"',
            'formHidden' => array('acao' => 'criar', 'paginaId' => $pagina ? $pagina->getId() : ''),
            'assuntoAtual' => '',
            'txtComentarioAtual' => '',
            'idBotaoEnviar' => 'btn-form-comentario-criar',
            'txtBotaoEnviar' => 'Postar Comentário!'
        );

        $dadosComentariosView = array(
            'pagina' => $pagina,
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

    private function _loadSectionInfoEtapaView(WeLearn_Cursos_Conteudo_Modulo $modulo,
                                               $aula = null,
                                               $pagina = null,
                                               $avaliacao = null)
    {
        $this->load->helper(array('modulo', 'aula', 'pagina'));

        try {
            $listaModulos = $this->_moduloDao->recuperarTodosPorCurso(
                $modulo->getCurso()
            );
        } catch ( cassandra_NotFoundException $e ) {
            $listaModulos = array();
        }

        try {
            $listaAulas = $this->_aulaDao->recuperarTodosPorModulo(
                $modulo
            );
        } catch (cassandra_NotFoundException $e) {
            $listaAulas = array();
        }


        try {
            $listaPaginas = $aula ? $this->_paginaDao->recuperarTodosPorAula(
                $aula
            ) : array();
        } catch ( cassandra_NotFoundException $e ) {
            $listaPaginas = array();
        }

        $dadosInfoEtapaView = array(
            'modulo' => $modulo,
            'aula' => $aula,
            'pagina' => $pagina,
            'avaliacao' => $avaliacao,
            'selectModulos' => $this->template->loadPartial(
                'select_modulos',
                array(
                    'listaModulos' => lista_modulos_para_dados_dropdown( $listaModulos ),
                    'moduloSelecionado' => $modulo->getId(),
                    'extra' => 'id="slt-modulos"'
                ),
                'curso/conteudo'
            ),
            'selectAulas' => $this->template->loadPartial(
                'select_aulas',
                array(
                    'listaAulas' => lista_aulas_para_dados_dropdown( $listaAulas ),
                    'aulaSelecionada' => $aula ? $aula->getId() : '0',
                    'extra' => 'id="slt-aulas"'
                ),
                'curso/conteudo'
            ),
            'selectPaginas' => $this->template->loadPartial(
                'select_paginas',
                array(
                    'listaPaginas' => lista_paginas_para_dados_dropdown( $listaPaginas ),
                    'paginaSelecionada' => $pagina ? $pagina->getId() : '0',
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
        return $this->template->loadPartial(
            'section_recursos',
            array(),
            'curso/conteudo/exibicao'
        );
    }

    private function _exibirPagina( WeLearn_Cursos_ParticipacaoCurso $participacaocurso )
    {
        $modulo = $participacaocurso->getModuloAtual();
        $aula   = $participacaocurso->getAulaAtual();
        $pagina = $participacaocurso->getPaginaAtual();

        $moduloDisponivel = $modulo ? $this->_participacaoCursoDao
                                      ->getControleModuloDAO()
                                      ->isDisponivel( $participacaocurso, $modulo )
                                    : false;

        $aulaDisponivel   = $aula   ? $this->_participacaoCursoDao
                                      ->getControleAulaDAO()
                                      ->isDisponivel( $participacaocurso, $aula )
                                    : false;

        $paginaDisponivel = $pagina ? $this->_participacaoCursoDao
                                      ->getControlePaginaDAO()
                                      ->isDisponivel( $participacaocurso, $pagina )
                                    : false;

        if ( $moduloDisponivel && $aulaDisponivel && $paginaDisponivel ) {

            return $this->template->loadPartial(
                'conteudo_pagina',
                array( 'pagina' => $pagina ),
                'curso/conteudo/exibicao'
            );

        }

        return $this->template->loadPartial(
            'conteudo_indisponivel',
            array(),
            'curso/conteudo/exibicao'
        );
    }

    private function _aplicarAvaliacao( WeLearn_Cursos_ParticipacaoCurso $participacaocurso )
    {
        $avaliacao = $participacaocurso->getAvaliacaoAtual();

        $avaliacao->setQtdQuestoes(
            $this->_questaoAvaliacaoDao->recuperarQtdTotalPorAvaliacao( $avaliacao )
        );

        if (
            ( $avaliacao->getQtdQuestoesExibir() > 0 ) &&
            ( $avaliacao->getQtdQuestoes() > 0 )
        ) {

            $controleAvaliacao = $this->_controleAvaliacaoDao->recuperarPorParticipacao(
                $participacaocurso,
                $avaliacao
            );

            $dadosAplicacaoAvaliacaoView = array(
                'controleAvaliacao' => $controleAvaliacao
            );

            return $this->template->loadPartial(
                'avaliacao',
                $dadosAplicacaoAvaliacaoView,
                'curso/conteudo/exibicao'
            );

        }

        return $this->template->loadPartial(
            'conteudo_indisponivel',
            array(),
            'curso/conteudo/exibicao'
        );
    }

    private function _recuperarConteudoAtual(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        switch ( $participacaoCurso->getTipoConteudoAtual() ) {
            case WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO:
                if ( $participacaoCurso->getAvaliacaoAtual() instanceof WeLearn_Cursos_Avaliacoes_Avaliacao ) {

                    $conteudoAtual =  array(
                        'pagina' => false,
                        'aula' => false,
                        'modulo' => $participacaoCurso->getAvaliacaoAtual()->getModulo(),
                        'avaliacao' => $participacaoCurso->getAvaliacaoAtual()
                    );

                } else {

                    $conteudoAtual = $this->_recuperarConteudoAtualTratandoNaoEncontrados(
                        $participacaoCurso
                    );

                }
                break;
            case WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA:
            case WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM;
            default:
                $conteudoAtual = $this->_recuperarConteudoAtualTratandoNaoEncontrados(
                    $participacaoCurso
                );
        }

        $url = site_url(
            'curso/conteudo/exibicao/exibir/' . $participacaoCurso->getCurso()->getId()
        );

        $conteudoAtual['url'] = $url . '?t=' . $participacaoCurso->getTipoConteudoAtual();

        return $conteudoAtual;
    }

    private function _recuperarConteudoAtualTratandoNaoEncontrados(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso)
    {
        if ( $participacaoCurso->getPaginaAtual() instanceof WeLearn_Cursos_Conteudo_Pagina ) {

            $moduloAtual = $participacaoCurso->getModuloAtual();
            $aulaAtual   = $participacaoCurso->getAulaAtual();
            $paginaAtual = $participacaoCurso->getPaginaAtual();

        } elseif ( $participacaoCurso->getAulaAtual() instanceof WeLearn_Cursos_Conteudo_Aula ) {

            $moduloAtual = $participacaoCurso->getModuloAtual();
            $aulaAtual   = $participacaoCurso->getAulaAtual();
            $paginaAtual = $this->_recuperarPaginaAtualTratandoNaoEncontrada(
                $participacaoCurso,
                $aulaAtual
            );

        } elseif ( $participacaoCurso->getModuloAtual() instanceof WeLearn_Cursos_Conteudo_Modulo ) {

            $moduloAtual = $participacaoCurso->getModuloAtual();
            $aulaAtual   = $this->_recuperarAulaAtualTratandoNaoEncontrada(
                $participacaoCurso,
                $moduloAtual
            );
            $paginaAtual = $this->_recuperarPaginaAtualTratandoNaoEncontrada(
                $participacaoCurso,
                $aulaAtual
            );

        } else {

            $moduloAtual = $this->_recuperarModuloAtualTratandoNaoEncontrado(
                $participacaoCurso
            );
            $aulaAtual = $this->_recuperarAulaAtualTratandoNaoEncontrada(
                $participacaoCurso,
                $moduloAtual
            );
            $paginaAtual = $this->_recuperarPaginaAtualTratandoNaoEncontrada(
                $participacaoCurso,
                $aulaAtual
            );

        }

        return array(
            'pagina' => $paginaAtual,
            'aula' => $aulaAtual,
            'modulo' => $moduloAtual,
            'avaliacao' => false
        );
    }

    private function _recuperarModuloAtualTratandoNaoEncontrado(
        WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso
    ) {
        $moduloAtual = $this->_moduloDao->recuperarProximo( $participacaoCurso->getCurso() );

        if ( $moduloAtual ) {

            //É preciso registrar o inicio do módulo ( caso exista )
            $this->_participacaoCursoDao->getControleModuloDAO()->acessar(
                $participacaoCurso,
                $moduloAtual
            );

        }

        return $moduloAtual;
    }

    private function _recuperarAulaAtualTratandoNaoEncontrada(
        WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
        $moduloAtual = false
    ) {
        $aulaAtual = $moduloAtual ? $this->_aulaDao->recuperarProxima( $moduloAtual ) : false;

        if ( $aulaAtual ) {

            $this->_participacaoCursoDao->getControleAulaDAO()->acessar(
                $participacaoCurso,
                $aulaAtual
            );

        }

        return $aulaAtual;
    }

    private function _recuperarPaginaAtualTratandoNaoEncontrada(
            WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
            $aulaAtual = false
    ) {
        $paginaAtual = $aulaAtual ? $this->_paginaDao->recuperarProxima( $aulaAtual ) : false;

        if ( $paginaAtual ) {

            $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                $participacaoCurso,
                $paginaAtual
            );

        }

        return $paginaAtual;
    }

    private function _recuperarProximoConteudo(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso)
    {
        switch ( $participacaoCurso->getTipoConteudoAtual() ) {
            case WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA:
                $paginaAnterior = $participacaoCurso->getPaginaAtual();
                $this->_participacaoCursoDao->getControlePaginaDAO()->finalizar( $participacaoCurso, $paginaAnterior );

                $proximaPagina = $this->_paginaDao->recuperarProxima(
                    $paginaAnterior->getAula(),
                    $paginaAnterior->getNroOrdem()
                );

                if ( $proximaPagina ) {

                    return $this->_retornarJSONProximaPagina( $participacaoCurso, $proximaPagina );

                } else {

                    return $this->_virarAula( $participacaoCurso, $paginaAnterior->getAula() );

                }
            case WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO:

            case WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM:
            default:
        }
    }

    private function _retornarJSONPagina(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                         WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $url = site_url(
            'curso/conteudo/exibicao/exibir/' . $participacaoCurso->getCurso()->getId()
                                              . '?t=' . $participacaoCurso->getTipoConteudoAtual()
        );

        try {

            $anotacao = $this->_anotacaoDao->recuperarPorUsuario(
                $pagina,
                $this->_alunoAtual
            )->getConteudo();

        } catch( cassandra_NotFoundException $e ) {

            $anotacao = '';

        }

        $response = Zend_Json::encode(array(
            'tipoConteudoAtual' => $participacaoCurso->getTipoConteudoAtual(),
            'moduloAtual'       => $pagina->getAula()->getModulo()->toCassandra(),
            'aulaAtual'         => $pagina->getAula()->toCassandra(),
            'paginaAtual'       => $pagina->toCassandra(),
            'avaliacaoAtual'    => '',
            'anotacaoAtual'     => $anotacao,
            'urlConteudoAtual'  => $url
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _retornarJSONProximaPagina( WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso, $proximaPagina )
    {
        if ( $proximaPagina ) {

            $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                $participacaoCurso,
                $proximaPagina
            );

            return $this->_retornarJSONPagina( $participacaoCurso, $proximaPagina );

        }

        $error = create_json_feedback_error_json(
            'A aula seguinte não possui páginas para serem exibidas.
            O curso não pode prosseguir, contate um gerenciador!'
        );

        return create_json_feedback(false, $error);
    }

    private function _virarAula(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aulaAnterior)
    {
        $this->_participacaoCursoDao->getControleAulaDAO()->finalizar( $participacaoCurso, $aulaAnterior );

        $proximaAula = $this->_aulaDao->recuperarProxima( $aulaAnterior->getModulo(), $aulaAnterior->getNroOrdem() );

        if ( $proximaAula ) {

            return $this->_retornarJSONProximaAula( $participacaoCurso, $proximaAula );

        } else {

            return $this->_virarModulo( $participacaoCurso, $aulaAnterior->getModulo() );

        }
    }

    private function _retornarJSONProximaAula( WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso, $proximaAula )
    {
        if ( $proximaAula ) {

            $this->_participacaoCursoDao->getControleAulaDAO()->acessar( $participacaoCurso, $proximaAula );

            $proximaPagina = $this->_paginaDao->recuperarProxima( $proximaAula );

            return $this->_retornarJSONProximaPagina( $participacaoCurso, $proximaPagina );

        }

        $error = create_json_feedback_error_json(
            'O módulo seguinte não possui aulas para serem aplicadas.
            O curso não pode proseguir, contate um gerenciador!'
        );

        return create_json_feedback(false, $error);
    }

    private function _virarModulo(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Modulo $moduloAnterior)
    {
        $this->_participacaoCursoDao->getControleModuloDAO()->finalizar(
            $participacaoCurso,
            $moduloAnterior
        );

        try {

            $avaliacao = $this->_avaliacaoDao->recuperar( $moduloAnterior->getId() );

            if ( $avaliacao->getQtdQuestoesExibir() > 0 ) {

                try {

                    $controleAvaliacao = $this->_controleAvaliacaoDao->recuperarPorParticipacao(
                        $participacaoCurso,
                        $avaliacao
                    );

                } catch (cassandra_NotFoundException $e) {

                    $controleAvaliacao = new WeLearn_Cursos_Avaliacoes_ControleAvaliacao();
                    $controleAvaliacao->setParticipacaoCurso( $participacaoCurso );
                    $controleAvaliacao->setAvaliacao( $avaliacao );

                    $this->_controleAvaliacaoDao->salvar( $controleAvaliacao );

                }

                if (
                    $controleAvaliacao->isStatusFinalizada() &&
                    $controleAvaliacao->isSituacaoAprovado()
                ) {

                    return $this->_retornarJSONProximoModulo(
                        $participacaoCurso,
                        $moduloAnterior
                    );

                } elseif ( $controleAvaliacao->isStatusBloqueada() ) {

                    $error = create_json_feedback_error_json(
                        'Para acessar o conteúdo do módulo seguinte, você precisa primeiro
                        ser aprovado na avaliação do módulo em que está. Por favor, refaça a
                        avaliação quando ela estiver disponível.'
                    );

                    return create_json_feedback(false, $error);

                } elseif ( $controleAvaliacao->isStatusDesativada() ) {

                    $error = create_json_feedback_error_json(
                        'Você foi reprovado muitas vezes na avaliação
                        deste módulo, você não poderá mais avançar no curso.'
                    );

                    return create_json_feedback(false, $error);

                } else {

                    return $this->_retornarJSONAvaliacao(
                        $participacaoCurso,
                        $avaliacao
                    );

                }

            } else {

                $error = create_json_feedback_error_json(
                    'Este módulo possui uma avaliação, mas ela não está ativa.
                    Você só poderá avançar no curso quando esta avaliação for ativada ou removida.
                    Contate os Gerenciadores do Curso.'
                );

                return create_json_feedback(false, $error);

            }

        } catch (cassandra_NotFoundException $e) {

            return $this->_retornarJSONProximoModulo(
                $participacaoCurso,
                $moduloAnterior
            );

        }
    }

    private function _retornarJSONProximoModulo(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                                    WeLearn_Cursos_Conteudo_Modulo $moduloAnterior)
    {
        $proximoModulo = $this->_moduloDao->recuperarProximo(
            $moduloAnterior->getCurso(),
            $moduloAnterior->getNroOrdem()
        );

        if ( $proximoModulo ) {

            $this->_participacaoCursoDao->getControleModuloDAO()->acessar( $participacaoCurso, $proximoModulo );

            $proximaAula = $this->_aulaDao->recuperarProxima( $proximoModulo );

            return $this->_retornarJSONProximaAula( $participacaoCurso, $proximaAula );

        } else {

            return $this->_retornarJSONCursoFinalizado( $participacaoCurso );

        }
    }

    private function _retornarJSONAvaliacao(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                            WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao)
    {
        $participacaoCurso->setAulaAtual(null);
        $participacaoCurso->setPaginaAtual(null);
        $participacaoCurso->setTipoConteudoAtual( WeLearn_Cursos_Conteudo_TipoConteudo::AVALIACAO );
        $participacaoCurso->setAvaliacaoAtual( $avaliacao );

        $this->_participacaoCursoDao->salvar( $participacaoCurso );

        $url = $url = site_url(
            'curso/conteudo/exibicao/exibir/' . $participacaoCurso->getCurso()->getId()
                                              . '?t=' . $participacaoCurso->getTipoConteudoAtual()
        );

        $response = Zend_Json::encode(array(
            'tipoConteudoAtual' => $participacaoCurso->getTipoConteudoAtual(),
            'moduloAtual'       => $avaliacao->getModulo()->toCassandra(),
            'aulaAtual'         => '',
            'paginaAtual'       => '',
            'avaliacaoAtual'    => $avaliacao->toCassandra(),
            'urlConteudoAtual'  => $url
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _retornarJSONCursoFinalizado(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso)
    {
        //TODO: Retornar finalização do curso. (Ultimo módulo finalizado)
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
                'curso/conteudo/exibicao'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
