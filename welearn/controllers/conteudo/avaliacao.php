<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 18/04/12
 * Time: 21:20
 * To change this template use File | Settings | File Templates.
 */
class Avaliacao extends WL_Controller
{
    /**
     * @var CursoDAO
     */
    var $_cursoDao;

    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('avaliacao.js');

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }

    public function index ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

            try {
                $listaModulos = $moduloDao->recuperarTodosPorCurso( $curso );
                $totalModulos = count( $listaModulos );

                $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

                foreach ($listaModulos as $modulo) {
                    $modulo->setExisteAvaliacao(
                        $avaliacaoDao->existeAvaliacao( $modulo )
                    );
                }
            } catch (cassandra_NotFoundException $e) {
                $listaModulos = array();
                $totalModulos = 0;
            }

            $dadosView = array(
                'haModulos' => $totalModulos > 0,
                'totalModulos' => $totalModulos,
                'listaModulos' => $listaModulos,
                'idCurso' => $idCurso
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/avaliacao/index',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir index de avaliações de curso: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function exibir ($idModulo)
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar( $idModulo );

            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
            $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');

            $modulo->setExisteAvaliacao(
                $avaliacaoDao->existeAvaliacao( $modulo )
            );

            $avaliacao = null;
            $listaQuestoes = array();
            if ( $modulo->getExisteAvaliacao() ) {
                $avaliacao = $avaliacaoDao->recuperar( $modulo->getId() );

                $avaliacao->setQtdQuestoes(
                    $questaoDao->recuperarQtdTotalPorAvaliacao( $avaliacao )
                );

                if ($avaliacao->getQtdQuestoes() > 0) {
                    $avaliacao->setQuestoes(
                        $questaoDao->recuperarTodosPorAvaliacao( $avaliacao )
                    );

                    $listaQuestoes = $avaliacao->getQuestoes();
                }
            }

            $dadosListaQuestao = array(
                'listaQuestoes' => $listaQuestoes
            );

            $dadosView = array(
                'modulo' => $modulo,
                'avaliacao' => $avaliacao,
                'maxQuestoes' => QuestaoAvaliacaoDAO::MAX_QUESTOES,
                'listaQuestoes' => $this->template->loadPartial(
                    'lista',
                    $dadosListaQuestao,
                    'curso/conteudo/avaliacao'
                )
            );

            $this->_renderTemplateCurso(
                $modulo->getCurso(),
                'curso/conteudo/avaliacao/exibir',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir avaliação de um módulo: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function criar ( $idModulo )
    {
        try {
            $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
            $modulo = $moduloDao->recuperar( $idModulo );

            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

            $modulo->setExisteAvaliacao(
                $avaliacaoDao->existeAvaliacao( $modulo )
            );

            if ( $modulo->getExisteAvaliacao() ) {
                //Se já houver uma avaliação cadastrada, redirecionar para visualização da mesma
                redirect('/curso/conteudo/avaliacao/exibir/' . $modulo->getId());
            }

            $dadosForm = array(
                'formAction' => '/conteudo/avaliacao/salvar',
                'extraOpenForm' => 'id="form-avaliacao-criar"',
                'formHidden' => array(
                    'acao' => 'criar',
                    'moduloId' => $modulo->getId()
                ),
                'nomeAtual' => '',
                'notaMinimaAtual' => '',
                'tempoDuracaoMaxAtual' => '',
                'qtdTentativasPermitidasAtual' => '',
                'txtBotaoEnviar' => 'Criar!'
            );

            $dadosView = array(
                'modulo' => $modulo,
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/conteudo/avaliacao'
                )
            );

            $this->_renderTemplateCurso(
                $modulo->getCurso(),
                'curso/conteudo/avaliacao/criar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de avaliação: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ( $idAvaliacao )
    {
        try {
            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
            $avaliacao = $avaliacaoDao->recuperar( $idAvaliacao );

            $dadosForm = array(
                'formAction' => '/conteudo/avaliacao/salvar',
                'extraOpenForm' => 'id="form-avaliacao-alterar"',
                'formHidden' => array(
                    'acao' => 'alterar',
                    'avaliacaoId' => $avaliacao->getId()
                ),
                'nomeAtual' => $avaliacao->getNome(),
                'notaMinimaAtual' => $avaliacao->getNotaMinima(),
                'tempoDuracaoMaxAtual' => $avaliacao->getTempoDuracaoMax(),
                'qtdTentativasPermitidasAtual' => $avaliacao->getQtdTentativasPermitidas(),
                'txtBotaoEnviar' => 'Salvar!'
            );

            $dadosView = array(
                'avaliacao' => $avaliacao,
                'form' => $this->template->loadPartial(
                    'form',
                    $dadosForm,
                    'curso/conteudo/avaliacao'
                )
            );

            $this->_renderTemplateCurso(
                $avaliacao->getModulo()->getCurso(),
                'curso/conteudo/avaliacao/alterar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de avaliação: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function remover($idAvaliacao)
    {
        if ( ! $this->input->is_ajax_request() ) {
                    show_404();
        }
        
        set_json_header();
        
        try {            
            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
            
            $avaliacaoRemovida = $avaliacaoDao->remover( $idAvaliacao );
            
            $this->load->helper('notificacao_js');
            
            $this->session->set_flashdata(
                'notificacoesFlash', 
                create_notificacao_json(
                    'sucesso',
                    'A avaliação <em>"' . $avaliacaoRemovida->getNome()
                        . '"</em> e os dados vinculados a ela foram removidos com sucesso do módulo '
                        . $avaliacaoRemovida->getModulo()->getNroOrdem() . '.'
                )
            );
            
            $response = Zend_Json::encode(array(
                'idCurso' => $avaliacaoRemovida->getModulo()->getCurso()->getId()
            ));
            
            $json = create_json_feedback(true, '', $response);            
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover avaliacao de módulo: ' .
                create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }
        
        echo $json;
    }

    public function salvar ()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');
        
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            $this->load->helper('notificacao_js');
            try {
                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criar( $this->input->post() );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception(
                            'Opção inválida ao salvar avaliação.'
                        );
                }
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar avaliacao de módulo: ' .
                    create_exception_description($e));

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos tentando resolver.
                    Tente novamente mais tarde!'
                );

                $json = create_json_feedback(false, $error);
            }
        }
        
        echo $json;
    }

    public function exibir_questao ( $idQuestao )
    {
       if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');
            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');

            $questao = $questaoDao->recuperar( $idQuestao );
            $avaliacao = $avaliacaoDao->recuperar( $questao->getAvaliacaoId() );

            $dadosExibicao = array(
                'questao' => $questao
            );

            $dadosView = array(
                'avaliacao' => $avaliacao,
                'exibicaoQuestao' => $this->template->loadPartial(
                    'exibicao_questao',
                    $dadosExibicao,
                    'curso/conteudo/avaliacao'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlExibirQuestao' => $this->load->view(
                    'curso/conteudo/avaliacao/exibir_questao',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir questão de uma avaliação: ' .
                create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function adicionar_questao ( $idAvaliacao )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
            $avaliacao = $avaliacaoDao->recuperar( $idAvaliacao );

            //Se limite de questões foi atingido não deixa adicionar outro.
            $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');
            if ( $questaoDao->recuperarQtdTotalPorAvaliacao( $avaliacao )
                 >= QuestaoAvaliacaoDAO::MAX_QUESTOES ) {

                $error = create_json_feedback_error_json(
                    'O limite máximo de questões contidas em uma avaliação foi atingido!'
                );

                echo create_json_feedback(false, $error);
                return;
            }
            // Senão exibe formulário normalmente...

            $dadosForm = array(
                'formAction' => '/conteudo/avaliacao/salvar_questao',
                'extraOpenForm' => 'id="form-questao-criar"',
                'formHidden' => array(
                    'avaliacaoId' => $avaliacao->getId(),
                    'acao' => 'criar'
                ),
                'enunciadoAtual' => '',
                'qtdAlternativasExibirAtual' => 2,
                'alternativaCorretaAtual' => null,
                'alternativasIncorretasAtuais' => array()
            );

            $dadosView = array(
                'avaliacao' => $avaliacao,
                'form' => $this->template->loadPartial(
                    'form_questao',
                    $dadosForm,
                    'curso/conteudo/avaliacao'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlFormCriarQuestao' => $this->load->view(
                    'curso/conteudo/avaliacao/criar_questao',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de questão: ' .
                create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function alterar_questao ($idQuestao)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');
            $questao = $questaoDao->recuperar( $idQuestao );

            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
            $avaliacao = $avaliacaoDao->recuperar( $questao->getAvaliacaoId() );

            $dadosForm = array(
                'formAction' => '/conteudo/avaliacao/salvar_questao',
                'extraOpenForm' => 'id="form-questao-alterar"',
                'formHidden' => array(
                    'questaoId' => $questao->getId(),
                    'acao' => 'alterar'
                ),
                'enunciadoAtual' => $questao->getEnunciado(),
                'qtdAlternativasExibirAtual' => $questao->getQtdAlternativasExibir(),
                'alternativaCorretaAtual' => $questao->getAlternativaCorreta()->getTxtAlternativa(),
                'alternativasIncorretasAtuais' => $questao->getAlternativasIncorretas()
            );

            $dadosView = array(
                'avaliacao' => $avaliacao,
                'form' => $this->template->loadPartial(
                    'form_questao',
                    $dadosForm,
                    'curso/conteudo/avaliacao'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlFormAlterarQuestao' => $this->load->view(
                    'curso/conteudo/avaliacao/alterar_questao',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de alteração de questão: ' .
                create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function remover_questao ($idQuestao)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');

            $questaoDao->remover( $idQuestao );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A questão foi removida com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover questão de uma avaliação: ' .
                create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar_questao()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');

        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            $this->load->helper('notificacao_js');
            try {
                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criar_questao( $this->input->post() );
                        break;
                    case 'alterar':
                        $json = $this->_alterar_questao( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception(
                            'Opção inválida ao salvar questão de avaliação.'
                        );
                }
            } catch (Exception $e) {
                log_message('error', 'Erro ao tentar salvar questão de avaliacao: ' .
                    create_exception_description($e));

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos tentando resolver.
                    Tente novamente mais tarde!'
                );

                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }
    
    public function salvar_qtd_questoes_exibir ($idAvaliacao)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();
        
        try {
            $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
            $avaliacao = $avaliacaoDao->recuperar( $idAvaliacao );
            
            $avaliacao->setQtdQuestoesExibir( $this->input->get('qtd') );
            $avaliacaoDao->salvar( $avaliacao );
            
            $this->load->helper('notificacao_js');
            
            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A quantidade de questões à serem aplicadas para avaliação <em>"'
                        . $avaliacao->getNome() . '"</em> foi alterada com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar salvar nova qtd de questões aplicadas de uma avaliacao: ' .
                create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }
        
        echo $json;
    }

    private function _criar ( $post )
    {
        $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
        $modulo = $moduloDao->recuperar( $post['moduloId'] );

        $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
        $avaliacao = $avaliacaoDao->criarNovo( $post );
        $avaliacao->setModulo( $modulo );

        $avaliacaoDao->salvar( $avaliacao );

        $notificacoesFlash = create_notificacao_json(
            'sucesso',
            'A avaliação <em>"' . $avaliacao->getNome()
                . '"</em> foi criada com sucesso no módulo  '
                . $modulo->getNroOrdem() . '!'
        );

        $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

        $response = Zend_Json::encode(array(
            'idAvaliacao' => $avaliacao->getId()
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _alterar ( $post )
    {
        $avaliacaoDao = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
        $avaliacao = $avaliacaoDao->recuperar( $post['avaliacaoId'] );

        $avaliacao->preencherPropriedades( $post );

        $avaliacaoDao->salvar( $avaliacao );

        $notificacoesFlash = create_notificacao_json(
            'sucesso',
            'A avaliação <em>"' . $avaliacao->getNome()
                . '"</em> foi salva com sucesso!'
        );

        $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

        $response = Zend_Json::encode(array(
            'idAvaliacao' => $avaliacao->getId()
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _criar_questao ( $post )
    {
        $UUIDQuestao = UUID::mint();

        $alternativaDao = WeLearn_DAO_DAOFactory::create('AlternativaAvaliacaoDAO');
        $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');

        $alternativaCorreta = $alternativaDao->criarNovaAlternativaCorreta(
            $post['alternativaCorreta'],
            $UUIDQuestao->string
        );

        $alternativasIncorretas = $alternativaDao->criarVariasAlternativasIncorretas(
            $post['alternativaIncorreta'],
            $UUIDQuestao->string
        );

        unset( $post['alternativaCorreta'], $post['alternativaIncorreta'] );

        $novaQuestao = $questaoDao->criarNovo( $post );

        $novaQuestao->setId( $UUIDQuestao->string );
        $novaQuestao->setAlternativaCorreta( $alternativaCorreta );
        $novaQuestao->setAlternativasIncorretas( $alternativasIncorretas );
        $novaQuestao->setQtdAlternativas( count( $alternativasIncorretas ) + 1 );

        if ( $novaQuestao->getQtdAlternativasExibir() > $novaQuestao->getQtdAlternativas() ) {
            $novaQuestao->setQtdAlternativasExibir( $novaQuestao->getQtdAlternativas() );
        }

        $questaoDao->salvar( $novaQuestao );

        $alternativaDao->salvar( $novaQuestao->getAlternativaCorreta() );

        foreach ($novaQuestao->getAlternativasIncorretas() as $alternativaIncorreta) {
            $alternativaDao->salvar( $alternativaIncorreta );
        }

        $response = Zend_Json::encode(array(
            'notificacao' => create_notificacao_array(
                'sucesso',
                'A nova questão foi adicionada com sucesso à avaliação!'
            ),
            'htmlNovaQuestao' => $this->template->loadPartial(
                'lista',
                array('listaQuestoes' => array($novaQuestao)),
                'curso/conteudo/avaliacao'
            )
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _alterar_questao( $post )
    {
        $questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');
        $alternativaDao = WeLearn_DAO_DAOFactory::create('AlternativaAvaliacaoDAO');

        $questao = $questaoDao->recuperar( $post['questaoId'] );

        $questao->getAlternativaCorreta()
                ->setTxtAlternativa( $post['alternativaCorreta'] );

        unset( $post['alternativaCorreta'] );

        $questao->preencherPropriedades( $post );

        $alternativaDao->removerTodosPorLista( $questao->getAlternativasIncorretas() );

        $alternativasIncorretas = $alternativaDao->criarVariasAlternativasIncorretas(
            $post['alternativaIncorreta'],
            $questao->getId()
        );

        $questao->setAlternativasIncorretas( $alternativasIncorretas );
        $questao->setQtdAlternativas( count( $alternativasIncorretas ) + 1 );

        if ( $questao->getQtdAlternativasExibir() > $questao->getQtdAlternativas() ) {
            $questao->setQtdAlternativasExibir( $questao->getQtdAlternativas() );
        }

        $questaoDao->salvar( $questao );

        $alternativaDao->salvar( $questao->getAlternativaCorreta() );

        foreach ($questao->getAlternativasIncorretas() as $alternativaIncorreta) {
            $alternativaDao->salvar( $alternativaIncorreta );
        }

        $response = Zend_Json::encode(array(
            'notificacao' => create_notificacao_array(
                'sucesso',
                'A questão foi alterada com sucesso!'
            ),
            'novoEnunciado' => ( strlen($questao->getEnunciado()) > 155 )
                                ? '"' . substr($questao->getEnunciado(), 0, 155) . '..."'
                                : '"' . $questao->getEnunciado() . '"'
        ));

        return create_json_feedback(true, '', $response);
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

    public function _validarQtdAlternativasIncorretas($alternativas)
    {
        if ( (count($alternativas) >= 1) && (count($alternativas) <= 11) ) {
            foreach ($alternativas as $alternativa) {
                if ( empty($alternativa) ) {
                    $this->form_validation->set_message(
                        '_validarQtdAlternativasIncorretas',
                        'Alternativas Incorretas não podem permanecer vazias!'
                    );

                    return false;
                }
            }

            return true;
        }

        $this->form_validation->set_message(
            '_validarQtdAlternativasIncorretas',
            'A questão deve conter pelo menos 1 Alternativa Incorreta!'
        );

        return false;
    }
}
