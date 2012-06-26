<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 28/05/12
 * Time: 12:00
 * To change this template use File | Settings | File Templates.
 */
class Aplicacao_avaliacao extends Curso_Controller
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
        $this->_controleAvaliacaoDao = WeLearn_DAO_DAOFactory::create('ControleAvaliacaoDAO');
        $this->_questaoAvaliacaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');
        $this->_alternativaAvaliacaoDao = WeLearn_DAO_DAOFactory::create('AlternativaAvaliacaoDAO');

        $this->_alunoAtual = $this->_alunoDao->criarAluno(
            $this->autenticacao->getUsuarioAutenticado()
        );

        $this->template->appendCSS('sala_de_aula.css')
                       ->appendJSImport('aplicacao_avaliacao.js');
    }

    public function index ( $idCurso )
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $this->_expulsarNaoAutorizados($curso);

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $curso
            );

            try {
                $listaControlesAvaliacoes = $this->_controleAvaliacaoDao->recuperarTodosPorParticipacao(
                    $participacaoCurso
                );

                $qtdAvaliacoes = count( $listaControlesAvaliacoes );
            } catch (cassandra_NotFoundException $e) {
                $listaControlesAvaliacoes = array();

                $qtdAvaliacoes = 0;
            }

            //Liberar avaliações bloqueadas que já passaram de 24h
            for ($i = 0; $i < $qtdAvaliacoes; $i++) {

                if ( $listaControlesAvaliacoes[$i]->bloqueioExpirado() ) {

                    $this->_controleAvaliacaoDao->salvar( $listaControlesAvaliacoes[$i] );

                }

            }
            //Fim da rotina de desploqueio

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'conteudoBloqueado' => $curso->getStatus() === WeLearn_Cursos_StatusCurso::CONTEUDO_BLOQUEADO,
                'haAvaliacoesDisponiveis' => $qtdAvaliacoes > 0,
                'listaControlesAvaliacoes' => $listaControlesAvaliacoes
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/aplicacao_avaliacao/index',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir avaliações disponíveis do usuário.');

            show_404();
        }
    }

    public function aplicar ( $idAvaliacao )
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
                    array(
                        'avaliacao' => $avaliacao,
                        'formAction' => '/curso/conteudo/aplicacao_avaliacao/finalizar',
                        'extraOpenForm' => 'id="form-aplicacao-avaliacao"',
                        'formHidden' => array( 'avaliacaoId' => $avaliacao->getId() )
                    ),
                    'curso/conteudo/aplicacao_avaliacao'
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

    public function finalizar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $avaliacao = $this->_avaliacaoDao->recuperar(
                $this->input->post('avaliacaoId')
            );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $avaliacao->getModulo()->getCurso()
            );

            $controleAvaliacao = $this->_controleAvaliacaoDao->recuperarPorParticipacao(
                $participacaoCurso,
                $avaliacao
            );

            $alternativasEscolhidas = $this->input->post('alternativaEscolhida');

            $idsRespostas = array();
            if ( $alternativasEscolhidas ) {

                foreach( $alternativasEscolhidas as $idAlternativa ) {

                    $idsRespostas[] = UUID::import( $idAlternativa )->bytes;

                }

            }

            $respostas = $this->_alternativaAvaliacaoDao->recuperarTodosPorUUIDs(
                $idsRespostas
            );

            $controleAvaliacao->setRespostas( $respostas );
            $controleAvaliacao->setTempoDecorrido(
                round($avaliacao->getTempoDuracaoMax() - (float)$this->input->post('tempoDeProva'), 2)
            );
            $controleAvaliacao->setDataAplicacao( time() );
            $controleAvaliacao->calcularResultados();

            if(
                $controleAvaliacao->isStatusFinalizada() ||
                $controleAvaliacao->isStatusDesativada()
            ) {

                $totalAvaliacoesDisponiveis = $this->_controleAvaliacaoDao
                                                   ->recuperarQtdTotalPorParticipacao(
                                                       $participacaoCurso
                                                   );

                $participacaoCurso->atualizarCR(
                    $controleAvaliacao->getNota(),
                    $totalAvaliacoesDisponiveis <= 1
                );

            }

            if ( $controleAvaliacao->isSituacaoAprovado() ) {

                $this->_registrarAprovacaoEmAvaliacao(
                    $participacaoCurso,
                    $avaliacao
                );

            }

            $response = Zend_Json::encode(array(
                'htmlMsgResultado' => $this->template->loadPartial(
                    'msg_resultado_avaliacao',
                    array( 'controleAvaliacao' => $controleAvaliacao ),
                    'curso/conteudo/aplicacao_avaliacao'
                )
            ));

            //Salvando depois da exibicao da msg pra evitar um possivel bug do php.
            $this->_controleAvaliacaoDao->salvar( $controleAvaliacao );

            $json = create_json_feedback(true, '', $response);

        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar finalizar aplicação de avaliação: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function exibir_resultados( $idAvaliacao )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $avaliacao = $this->_avaliacaoDao->recuperar($idAvaliacao);

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $avaliacao->getModulo()->getCurso()
            );

            $controleAvaliacao = $this->_controleAvaliacaoDao->recuperarPorParticipacao(
                $participacaoCurso,
                $avaliacao
            );

            $questoesAvaliacao = array();
            foreach ($controleAvaliacao->getRespostas() as $resposta) {

                $questao = $this->_questaoAvaliacaoDao->recuperar( $resposta->getQuestaoId() );

                if ( $resposta->isCorreta() ) {

                    $arrayQuestao = array(
                        'questao' => $questao,
                        'respostas' => array( $resposta )
                    );

                } else {

                    $arrayQuestao = array(
                        'questao' => $questao,
                        'respostas' => array( $questao->getAlternativaCorreta(), $resposta )
                    );

                }

                $questoesAvaliacao[] = $arrayQuestao;

            }

            $response = Zend_Json::encode(array(
                'htmlResultado' => $this->template->loadPartial(
                    'avaliacao_resultados',
                    array(
                        'controleAvaliacao' => $controleAvaliacao,
                        'questoesRealizadas' => $questoesAvaliacao,
                        'questoesEmBranco' => $controleAvaliacao->getAvaliacao()->getQtdQuestoesExibir() - count($questoesAvaliacao)
                    ),
                    'curso/conteudo/aplicacao_avaliacao'
                )
            ));

            $json = create_json_feedback(true, '', $response);

        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar exibir resultados de avaliação: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    private function _registrarAprovacaoEmAvaliacao(
        WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
    ) {
        $participacaoCurso->setAvaliacaoAtual(null);
        $participacaoCurso->setTipoConteudoAtual(WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA);

        $proximoModulo = $this->_moduloDao->recuperarProximo(
            $participacaoCurso->getCurso(),
            $avaliacao->getModulo()->getNroOrdem()
        );

        if ( $proximoModulo ) {

            $this->_participacaoCursoDao->getControleModuloDAO()->acessar(
                $participacaoCurso,
                $proximoModulo
            );

            $proximaAula = $this->_aulaDao->recuperarProxima( $proximoModulo );

            if ( $proximaAula ) {

                $this->_participacaoCursoDao->getControleAulaDAO()->acessar(
                    $participacaoCurso, $proximaAula
                );

                $proximaPagina = $this->_paginaDao->recuperarProxima( $proximaAula );

                if ( $proximaPagina ) {

                    $this->_participacaoCursoDao->getControlePaginaDAO()->acessar(
                        $participacaoCurso, $proximaPagina
                    );

                }

            }

        } else {

            $participacaoCurso->setModuloAtual(null);
            $participacaoCurso->setAulaAtual(null);
            $participacaoCurso->setPaginaAtual(null);
            $participacaoCurso->setAvaliacaoAtual(null);
            $participacaoCurso->setTipoConteudoAtual( WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM );

            if ( $participacaoCurso->getSituacao() != WeLearn_Cursos_SituacaoParticipacaoCurso::CURSO_CONCLUIDO ) {

                $this->_participacaoCursoDao->finalizarCurso( $participacaoCurso );

            }

        }
    }

    protected function _renderTemplateCurso(WeLearn_Cursos_Curso $curso,
                                            $view = '',
                                            array $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu_frontend',
                array(
                    'papelUsuarioAtual' => $this->_getPapel( $curso ),
                    'idCurso' => $curso->getId()
                ),
                'curso/conteudo'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
