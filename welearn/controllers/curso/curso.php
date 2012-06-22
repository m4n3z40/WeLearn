<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curso extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            $segmento = $this->autenticacao->getUsuarioAutenticado()
                                           ->getSegmentoInteresse();

            try {
                $listaCursos = $this->_cursoDao->recuperarTodos(
                    0,
                    null,
                    array(
                        'count' => 10,
                        'segmento' => $segmento
                    )
                );
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haRecomendados' =>  !empty($listaCursos),
                'listaRecomendados' => $listaCursos
            );

            $this->_renderTemplateHome('curso/index', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir index de curso: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function exibir($id)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $id );

            $dadosViewExibir = array (
                'curso' => $curso
            );

            $this->_renderTemplateCurso($curso, 'curso/curso/exibir', $dadosViewExibir);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir o curso: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function buscar()
    {
        try {
            $this->load->helper( array('area', 'segmento', 'paginacao_mysql') );

            $count = 20;
            $buscaAtual = $this->input->get('busca');
            $tipoBuscaAtual = $this->input->get('tipo-busca');
            $areaAtual = $this->input->get('area');
            $segmentoAtual = $this->input->get('segmento');

            $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
            $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

            if ( $buscaAtual && ( $areaAtual != '0' && $segmentoAtual != '0' ) ) {

                try {
                    $segmento = $segmentoDao->recuperar( $segmentoAtual );
                    $area = $segmento->getArea();
                } catch ( cassandra_NotFoundException $e ) {
                    $segmento = null;
                    $area = null;
                }

            } elseif ( $buscaAtual && $areaAtual != '0' ) {

                try {
                    $area = $areaDao->recuperar( $areaAtual );
                } catch( cassandra_NotFoundException $e ) {
                    $area = null;
                }

                $segmento = null;
            } else {
                $area = null;
                $segmento = null;
            }

            $listaSegmentos = array();
            if ( $buscaAtual && $area != null ) {
                $opcoes = array('areaId' => $area->getId());

                try {
                    $listaSegmentos = $segmentoDao->recuperarTodos('', '', $opcoes);
                } catch (cassandra_NotFoundException $e) { }
            }

            $listaResultados = array();
            if ( $buscaAtual ) {
                try {
                    $listaResultados = $this->_recuperarResultadosBuscaCursos(
                        $buscaAtual,
                        $tipoBuscaAtual,
                        $area,
                        $segmento,
                        0,
                        $count + 1
                    );
                } catch (cassandra_NotFoundException $e) {
                    $listaResultados = array();
                }
            }

            $paginacao = create_paginacao_mysql($listaResultados, 0, $count);

            $dadosView = array(
                'formAction' => '/curso/buscar',
                'tipoBuscaAtual' => $tipoBuscaAtual,
                'areaAtual' => $area === null ? '0' : $area->getId(),
                'segmentoAtual' => $segmento === null ? '0' : $segmento->getId(),
                'dadosDropdownArea' => lista_areas_para_dados_dropdown(),
                'dadosDropdownSegmento' => lista_segmentos_para_dados_dropdown( $listaSegmentos ),
                'haResultados' => ! empty($listaResultados),
                'txtBusca' => $buscaAtual,
                'resultadosBusca' => $this->template->loadPartial(
                    'lista_busca',
                    array('listaResultados' => $listaResultados),
                    'curso'
                ),
                'haMaisPaginas' => $paginacao['proxima_pagina'],
                'inicioProxPagina' => $paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateHome('curso/buscar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir o página de busca de cursos do serviço: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function mais_resultados()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $this->load->helper('paginacao_mysql');

            $count = 20;
            $buscaAtual = $this->input->get('busca');
            $tipoBuscaAtual = $this->input->get('tipo-busca');
            $areaAtual = $this->input->get('area');
            $segmentoAtual = $this->input->get('segmento');
            $inicio = (int)$this->input->get('proximo');

            $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
            $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

            if ( $buscaAtual && ( $areaAtual != '0' && $segmentoAtual != '0' ) ) {

                try {
                    $segmento = $segmentoDao->recuperar( $segmentoAtual );
                    $area = $segmento->getArea();
                } catch ( cassandra_NotFoundException $e ) {
                    $segmento = null;
                    $area = null;
                }

            } elseif ( $buscaAtual && $areaAtual != '0' ) {

                try {
                    $area = $areaDao->recuperar( $areaAtual );
                } catch( cassandra_NotFoundException $e ) {
                    $area = null;
                }

                $segmento = null;
            } else {
                $area = null;
                $segmento = null;
            }

            $listaResultados = array();
            if ( $buscaAtual ) {
                try {
                    $listaResultados = $this->_recuperarResultadosBuscaCursos(
                        $buscaAtual,
                        $tipoBuscaAtual,
                        $area,
                        $segmento,
                        $inicio,
                        $count + 1
                    );
                } catch (cassandra_NotFoundException $e) {
                    $listaResultados = array();
                }
            }

            $paginacao = create_paginacao_mysql($listaResultados, $inicio, $count);

            $response = Zend_Json::encode(array(
                'htmlResultadosBusca' => $this->template->loadPartial(
                    'lista_busca',
                    array('listaResultados' => $listaResultados),
                    'curso'
                ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar recuperar proxima página de resultados da busca: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    private function _recuperarResultadosBuscaCursos($busca,
                                                     $tipoBusca,
                                                     $area = null,
                                                     $segmento = null,
                                                     $inicio = 0,
                                                     $count = 20)
    {
        $opcoes = array();

        switch ( $tipoBusca ) {
            case 'recomendados':
                $opcoes['segmento'] = $this->autenticacao
                                           ->getUsuarioAutenticado()
                                           ->getSegmentoInteresse();
                break;
            case 'refinada':
                if ( $segmento instanceof WeLearn_Cursos_Segmento ) {
                    $opcoes['segmento'] = $segmento;
                    break;
                } elseif ( $area instanceof WeLearn_Cursos_Area ) {
                    $opcoes['area'] = $area;
                    break;
                }
            case 'tudo':
            default:
        }

        $opcoes['busca'] = $busca;
        $opcoes['count'] = $count;

        return $this->_cursoDao->recuperarTodos( $inicio, null, $opcoes );
    }

    public function meus_cursos_criador()
    {
        try {
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

            $criador = $usuarioDao->criarGerenciadorPrincipal(
                $this->autenticacao->getUsuarioAutenticado()
            );

            try {
                $listaCursos = $this->_cursoDao->recuperarTodosPorCriador($criador, '', '', 1000000);
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos
            );

            $this->_renderTemplateHome('curso/meus_cursos_criador', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de cursos que o usuário criou: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function meus_cursos_gerenciador()
    {
        try {
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

            $gerenciador = $usuarioDao->criarGerenciadorAuxiliar(
                $this->autenticacao->getUsuarioAutenticado()
            );

            try {
                $listaCursos = $this->_cursoDao->recuperarTodosPorGerenciador(
                    $gerenciador,
                    '',
                    '',
                    1000000
                );
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos
            );

            $this->_renderTemplateHome('curso/meus_cursos_gerenciador', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de cursos em que o usuário é gerenciador auxiliar: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function meus_cursos_aluno()
    {
        try {
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

            $aluno = $usuarioDao->criarAluno(
                $this->autenticacao->getUsuarioAutenticado()
            );

            try {
                $listaCursos = $this->_cursoDao->recuperarTodosPorAluno($aluno, '', '', 1000000);
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos
            );

            $this->_renderTemplateHome('curso/meus_cursos_aluno', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de cursos em que o usuário é aluno: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function meus_cursos_em_espera()
    {
        try {
            $usuario = $this->autenticacao->getUsuarioAutenticado();

            try {
                $listaCursos = $this->_cursoDao->recuperarTodosPorInscricao(
                    $usuario,
                    '',
                    '',
                    1000000
                );
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos
            );

            $this->_renderTemplateHome('curso/meus_cursos_em_espera', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de cursos em que o usuário se inscreveu: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function meus_convites()
    {
        try {
            $usuario = $this->autenticacao->getUsuarioAutenticado();

            try {
                $listaCursos = $this->_cursoDao->recuperarTodosPorConviteGerenciador(
                    $usuario,
                    '',
                    '',
                    1000000
                );
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos
            );

            $this->_renderTemplateHome('curso/meus_convites', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de convites de curso do usuário: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function meus_certificados()
    {
        try {
            $this->template->appendJSImport('certificado_aluno.js');

            $certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

            $aluno = $usuarioDao->criarAluno( $this->autenticacao->getUsuarioAutenticado() );

            try {
                $listaCertificados = $certificadoDao->recuperarTodosPorAluno(
                    $aluno,
                    '',
                    '',
                    1000000
                );
            } catch( cassandra_NotFoundException $e ) {
                $listaCertificados = array();
            }

            $dadosView = array(
                'haCertificados' => !empty($listaCertificados),
                'totalCertificados' => count($listaCertificados),
                'listaCertificados' => $listaCertificados
            );

            $this->_renderTemplateHome('curso/meus_certificados', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de certificados do usuário: '
                . create_exception_description($e));
            show_404();
        }
    }

    public function inscrever($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $alunoDao = WeLearn_DAO_DAOFactory::create('AlunoDAO');

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $vinculo = $this->_cursoDao->recuperarTipoDeVinculo(
                $this->autenticacao->getUsuarioAutenticado(),
                $curso
            );

            if ( $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO ) {

                $this->load->helper('notificacao_js');

                if ( $curso->getConfiguracao()->getPrivacidadeInscricao() === WeLearn_Cursos_PermissaoCurso::RESTRITO ) {

                    $alunoDao->enviarRequisicaoInscricao(
                        $this->autenticacao->getUsuarioAutenticado(),
                        $curso
                    );

                    $response = Zend_Json::encode(array(
                        'atualizarPagina' => false,
                        'notificacao' => create_notificacao_array(
                            'sucesso',
                            'Sua requisição para se inscrever neste curso foi enviada com sucesso!<br>
                            Você será notificado quando ela for aceita ou recusada.'
                        ),
                        'elementoSubstituto' => '<span>Sua inscrição está sendo avaliada pelos gerenciadores.</span>'
                    ));

                } else {

                    $alunoDao->inscrever(
                        $this->autenticacao->getUsuarioAutenticado(),
                        $curso
                    );

                    $response = Zend_Json::encode(array(
                        'atualizarPagina' => true
                    ));

                    $this->session->set_flashdata(
                        'notificacoesFlash',
                        create_notificacao_json(
                            'sucesso',
                            'Sua inscrição neste curso foi efetuada com sucesso!<br>
                            Você já pode acessar o conteúdo restrido aos alunos.'
                        )
                    );

                }

                $json = create_json_feedback(true, '', $response);
            } else {
                $error = create_json_feedback_error_json(
                    'Não é possível inscrevê-lo. Você já tem um vínculo com este curso!'
                );

                $json = create_json_feedback(false, $error);
            }

        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar inscrever usuario no curso: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function sair($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $vinculo = $this->_cursoDao->recuperarTipoDeVinculo(
                $this->autenticacao->getUsuarioAutenticado(),
                $curso
            );

            $this->load->helper('notificacao_js');

            if ( $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO ) {
                $alunoDao = WeLearn_DAO_DAOFactory::create('AlunoDAO');

                $aluno = $alunoDao->criarAluno(
                    $this->autenticacao->getUsuarioAutenticado()
                );

                $alunoDao->desvincular(
                    $aluno,
                    $curso
                );

                $this->session->set_flashdata(
                    'notificacoesFlash',
                    create_notificacao_json(
                        'sucesso',
                        'Sua saída do curso foi efetuada com sucesso!<br>
                        Você pode voltar quando quiser, te esperamos :)'
                    )
                );

                $json = create_json_feedback(true);

            } elseif( $vinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR ) {

                $gerenciadorDao = WeLearn_DAO_DAOFactory::create('GerenciadorAuxiliarDAO');

                $gerenciador = $gerenciadorDao->criarGerenciadorAuxiliar(
                    $this->autenticacao->getUsuarioAutenticado()
                );

                $gerenciadorDao->desvincular( $gerenciador, $curso );

                $this->session->set_flashdata(
                    'notificacoesFlash',
                    create_notificacao_json(
                        'sucesso',
                        'Seu abandono da gerência deste curso foi efetuado com sucesso :('
                    )
                );

                $json = create_json_feedback(true);
            } else {
                $error = create_json_feedback_error_json(
                    'Não é possível sair deste curso!'
                );

                $json = create_json_feedback(false, $error);
            }

        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar inscrever usuario no curso: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function configurar($id)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $id );

            $this->_expulsarNaoAutorizados($curso);

            $this->load->helper('area');
            $listaAreas = lista_areas_para_dados_dropdown();

            $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
            $listaSegmentosObjs = $segmentoDao->recuperarTodos(
                '',
                '',
                array( 'areaId' => $curso->getSegmento()->getArea()->getId() )
            );
            $this->load->helper('segmento');
            $listaSegmentos = lista_segmentos_para_dados_dropdown($listaSegmentosObjs);

            $formDadosPrincipais = array(
                'sugestao' => '',
                'nomeAtual' => $curso->getNome(),
                'temaAtual' => $curso->getTema(),
                'descricaoAtual' => $curso->getDescricao(),
                'objetivosAtual' => $curso->getObjetivos(),
                'conteudoPropostoAtual' => $curso->getConteudoProposto(),
                'acaoForm' => 'salvarConfig',
                'listaAreas' => $listaAreas,
                'areaAtual' => $curso->getSegmento()->getArea()->getId(),
                'listaSegmentos' => $listaSegmentos,
                'segmentoAtual' => $curso->getSegmento()->getId()
            );

            $formImagem = array(
                'imagemAtual' => $curso->getImagem()
            );

            $formConfiguracoesAvancadas = array(
                'statusAtual' => $curso->getStatus(),
                'conteudoBloqueado' => WeLearn_Cursos_StatusCurso::CONTEUDO_BLOQUEADO,
                'conteudoAberto' => WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO,
                'tempoDuracaoMaxAtual' => $curso->getTempoDuracaoMax(),
                'privacidadeConteudoAtual' => $curso->getConfiguracao()->getPrivacidadeConteudo(),
                'conteudoPublico' => WeLearn_Cursos_PermissaoCurso::LIVRE,
                'conteudoPrivado' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
                'privacidadeInscricaoAtual' => $curso->getConfiguracao()->getPrivacidadeInscricao(),
                'inscricaoAutomatica' => WeLearn_Cursos_PermissaoCurso::LIVRE,
                'inscricaoRestrita' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
                'permissaoCriacaoForumAtual' => $curso->getConfiguracao()->getPermissaoCriacaoForum(),
                'criacaoForumAberta' => WeLearn_Cursos_PermissaoCurso::LIVRE,
                'criacaoForumRestrita' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
                'permissaoCriacaoEnqueteAtual' => $curso->getConfiguracao()->getPermissaoCriacaoEnquete(),
                'criacaoEnqueteAberta' => WeLearn_Cursos_PermissaoCurso::LIVRE,
                'criacaoEnqueteRestrita' => WeLearn_Cursos_PermissaoCurso::RESTRITO
            );

            $dadosViewConfigurar = array(
                'formAction' => 'curso/curso/salvar',
                'extraOpenForm' => 'id="form-curso"',
                'hiddenFormData' => array( 'acao' => 'salvarConfig', 'id' => $curso->getId() ),
                'formDadosPrincipais' => $this->template->loadPartial('form_dados_principais', $formDadosPrincipais, 'curso/curso'),
                'formImagem' => $this->template->loadPartial('form_imagem', $formImagem, 'curso/curso'),
                'formConfiguracoesAvancadas' => $this->template->loadPartial('form_configuracoes_avancadas', $formConfiguracoesAvancadas, 'curso/curso'),
                'textoBotaoSubmit' => 'Salvar alterações!'
            );

            $this->_renderTemplateCurso($curso, 'curso/curso/configurar', $dadosViewConfigurar);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir tela de configuração de curso: ' . create_exception_description($e));
            show_error(
                'Não foi possível exibir a tela de configuração de curso, tente novamente mais tarde.',
                500,
                'Ocorreu um erro inesperado'
            );
        }
    }

    public function criar()
    {
        $idSugestao = $this->input->get('s');
        $listaAreas = null;
        $listaSegmentos = null;
        $sugestaoGeradora = null;

        try {
            $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
            $sugestaoGeradora = $sugestaoDao->recuperar($idSugestao);

            $nomeAtual = $sugestaoGeradora->getNome();
            $temaAtual = $sugestaoGeradora->getTema();
            $descricaoAtual = $sugestaoGeradora->getDescricao();
            $areaAtual = $sugestaoGeradora->getSegmento()->getArea();
            $segmentoAtual = $sugestaoGeradora->getSegmento();
            $acaoForm = 'criarFromSugestao';
            $textoBotaoSubmit = 'Criar curso a partir desta Sugestão';
            $hiddenFormData = array(
                'acao' => $acaoForm,
                'area' => $areaAtual->getId(),
                'segmento' => $segmentoAtual->getId(),
                'sugestao' => $sugestaoGeradora->getId()
            );
        } catch (Exception $e) {
            $this->load->helper('area');
            $listaAreas = lista_areas_para_dados_dropdown();
            $nomeAtual = '';
            $temaAtual = '';
            $descricaoAtual = '';
            $areaAtual = '0';
            $listaSegmentos = array(
                '0' => 'Selecione uma área de segmento'
            );
            $segmentoAtual = '0';
            $acaoForm = 'criarNovo';
            $textoBotaoSubmit = 'Criar novo curso';
            $hiddenFormData = array(
                'acao' => $acaoForm
            );
        }

        $formDadosPrinciais = array(
            'sugestao' => $sugestaoGeradora,
            'nomeAtual' => $nomeAtual,
            'temaAtual' => $temaAtual,
            'descricaoAtual' => $descricaoAtual,
            'objetivosAtual' => '',
            'conteudoPropostoAtual' => '',
            'acaoForm' => $acaoForm,
            'listaAreas' => $listaAreas,
            'areaAtual' => $areaAtual,
            'listaSegmentos' => $listaSegmentos,
            'segmentoAtual' => $segmentoAtual
        );

        $formConfiguracoesGerais = array(
            'tempoDuracaoMaxAtual' => '40',
            'privacidadeConteudoAtual' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'conteudoPublico' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'conteudoPrivado' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'privacidadeInscricaoAtual' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'inscricaoAutomatica' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'inscricaoRestrita' => WeLearn_Cursos_PermissaoCurso::RESTRITO
        );

        $formImagem = array(
            'imagemAtual' => '',
        );

        $dadosCriar = array(
            'formAction' => 'curso/curso/salvar',
            'extraOpenForm' => 'id="form-curso"',
            'hiddenFormData' => $hiddenFormData,
            'formDadosPrincipais' => $this->template->loadPartial('form_dados_principais', $formDadosPrinciais, 'curso/curso'),
            'formConfiguracoesGerais' => $this->template->loadPartial('form_configuracoes_gerais', $formConfiguracoesGerais, 'curso/curso'),
            'formImagem' => $this->template->loadPartial('form_imagem', $formImagem, 'curso/curso'),
            'textoBotaoSubmit' => $textoBotaoSubmit
        );

        $this->_renderTemplateHome('curso/curso/criar', $dadosCriar);
    }

    public function salvar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $acao = $this->input->post('acao');

        $this->load->library('form_validation');

        $this->load->helper('notificacao_js');

        if ($acao == 'criarNovo' || $acao == 'criarFromSugestao') {
            $json = $this->_salvarNovoCurso();
        } elseif ($acao == 'salvarConfig') {
            $json = $this->_salvarConfiguracoes();
        }  else {
            $error = create_json_feedback_error_json('Requisição de ação inválida, atualize a página e tente novamente.');
            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar_imagem_temporaria()
    {
        $idImagem = str_replace('-', '', UUID::mint()->string);

        $upload_config = array(
            'upload_path' => TEMP_UPLOAD_DIR . 'img/',
            'allowed_types' => 'jpg|jpeg|gif|png',
            'max_size' => '2048',
            'max_width' => '2048',
            'max_height' => '1536',
            'file_name' =>  $idImagem
        );

        $this->load->library('upload', $upload_config);

        if ( ! $this->upload->do_upload('imagem') ) {
            $resultado = array(
                'success' => false,
                'error_msg' => $this->upload->display_errors('','')
            );
        } else {
            $upload_data = $this->upload->data();

            $image_config = array(
                'source_image' => $upload_data['full_path'],
                'width' => 160,
                'height' => 130
            );

            $this->load->library('image_lib', $image_config);

            if ( ! $this->image_lib->resize() ) {
                $resultado = array(
                    'success' => false,
                    'error_msg' => $this->image_lib->display_errors('','')
                );
            } else {
                $resultado = array(
                    'success' => true,
                    'upload_data' => array(
                        'imagem_id' => $idImagem,
                        'imagem_url' => site_url('/temp/img/' . $upload_data['file_name']),
                        'imagem_ext' => $upload_data['file_ext']
                    )
                );
            }
        }

        $json = Zend_Json::encode($resultado);

        echo $json;
    }

    private function _renderTemplateHome($view = '', $dados = array())
    {
        $this->_setTemplate( 'home' )
             ->_setBarraUsuarioPath( 'perfil/barra_usuario' )
             ->_setBarraEsquerdaPath( 'home/barra_lateral_esquerda' )
             ->_setBarraDireitaPath( 'home/barra_lateral_direita' )

             ->_barraEsquerdaSetVar(
                 'usuario',
                 $this->autenticacao->getUsuarioAutenticado()
             )

             ->_barraDireitaSetVar(
                'menuContexto',
                $this->template->loadPartial('menu', array(), 'curso')
             )

             ->_renderTemplate( $view, $dados );
    }

    private function _salvarNovoCurso()
    {
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            try {
                $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
                $usuarioDao  = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

                $dadosNovoCurso = $this->input->post();

                $segmentoId = $dadosNovoCurso['segmento'];
                $dadosNovoCurso['segmento'] = $segmentoDao->recuperar($segmentoId);
                $dadosNovoCurso['criador'] = $usuarioDao->criarGerenciadorPrincipal(
                    $this->autenticacao->getUsuarioAutenticado()
                );
                $dadosNovoCurso['configuracao'] = $this->_cursoDao->criarConfiguracao($dadosNovoCurso);

                //Gerando e salvando imagem, caso houver.
                if (isset($dadosNovoCurso['imagem']) && is_array($dadosNovoCurso['imagem'])) {
                    $dadosNovoCurso['imagem'] = $this->_salvarImagem( $dadosNovoCurso['imagem'] );
                }

                $novoCurso = $this->_cursoDao->criarNovo($dadosNovoCurso);
                $this->_cursoDao->salvar($novoCurso);

                if ($dadosNovoCurso['acao'] == 'criarFromSugestao') {
                    $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
                    $sugestao = $sugestaoDao->recuperar($dadosNovoCurso['sugestao']);
                    $sugestao->registrarCriacaoCurso($novoCurso, $sugestaoDao);

                    //Enviar notificação aos usuarios
                    $notificadorBatch = new WeLearn_Notificacoes_NotificadorCassandraBatch();
                    $notificadorTempoReal = new WeLearn_Notificacoes_NotificadorTempoReal();

                    //notificar criador
                    $notificacao = new WeLearn_Notificacoes_NotificacaoSugestaoCursoAceitaCriador();
                    $notificacao->setSugestao( $sugestao );
                    $notificacao->setCursoCriado( $novoCurso );
                    $notificacao->setDestinatario( $sugestao->getCriador() );
                    $notificacao->adicionarNotificador( $notificadorBatch );
                    $notificacao->adicionarNotificador( $notificadorTempoReal );
                    $notificacao->notificar();

                    //Notificar votantes
                    try {
                        $idsVotantes = $sugestaoDao->recuperarTodosIdsVotantes( $sugestao );
                    } catch ( cassandra_NotFoundException $e ) {
                        $idsVotantes = array();
                    }

                    foreach($idsVotantes as $idVotante) {
                        $votante = new WeLearn_Usuarios_Usuario();
                        $votante->setId( $idVotante );

                        $notificacao = new WeLearn_Notificacoes_NotificacaoSugestaoCursoAceitaVotante();
                        $notificacao->setSugestao( $sugestao );
                        $notificacao->setCursoCriado( $novoCurso );
                        $notificacao->setDestinatario( $votante );
                        $notificacao->adicionarNotificador( $notificadorBatch );
                        $notificacao->adicionarNotificador( $notificadorTempoReal );
                        $notificacao->notificar();
                    }
                    //fim notificação.
                }

                $notificacoesFlash = create_notificacao_json(
                    'sucesso',
                    'O novo curso foi criado com sucesso e você é o Gerenciador Principal!'.
                    '<br /> Comece a Alterar as configurações, editar o conteúdo e convidar alunos em potencial.',
                    5000
                );

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

                $extraJson = Zend_Json::encode(array(
                                                   'idNovoCurso' => $novoCurso->getId()
                                               ));

                $json = create_json_feedback(true, '', $extraJson);
            } catch (Exception $e) {
                log_message('error', 'Ocorreu um erro ao criar um curso: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.');
                $json = create_json_feedback(false, $error);
            }
        }

        return $json;
    }

    private function _salvarConfiguracoes()
    {
        if ( ! $this->form_validation->run('curso/salvar_config') ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            try {
                $dadosConfig = $this->input->post();

                $curso = $this->_cursoDao->recuperar($dadosConfig['id']);

                if( $curso->getSegmento()->getId() != $dadosConfig['segmento'] ) {
                    $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
                    $dadosConfig['segmento'] = $segmentoDao->recuperar($dadosConfig['segmento']);
                } else {
                    unset($dadosConfig['segmento']);
                }

                if( isset($dadosConfig['imagem']) && is_array($dadosConfig['imagem'])) {

                    if ( $curso->getImagem() ) {
                        unlink($curso->getImagem()->getDiretorioCompleto());
                    }

                    $dadosConfig['imagem'] = $this->_salvarImagem( $dadosConfig['imagem'] );

                    if ( $dadosConfig['imagem'] != null ) {
                        $dadosConfig['imagem']->setCursoId( $curso->getId() );
                    }
                }

                //Rotina para verificar se conteudo está sendo aberto
                $novoStatus = (int)$dadosConfig['status'];
                $avisos = array();

                if (//Se estiver sendo aberto
                    $curso->getStatus() != $novoStatus &&
                    $novoStatus === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO
                ) {

                    $totalPaginas = WeLearn_DAO_DAOFactory::create('PaginaDAO')->recuperarQtdTotalPorCurso($curso);
                    $totalAvaliacoes = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO')->recuperarQtdTotalAtivas($curso);

                    try {

                        $haCertificadoAtivo = WeLearn_DAO_DAOFactory::create('CertificadoDAO')->recuperarAtivoPorCurso($curso);

                    } catch ( cassandra_NotFoundException $e ) {

                        $haCertificadoAtivo = false;

                    }

                    if( $totalPaginas < 1 || $totalAvaliacoes < 1 || !$haCertificadoAtivo ) {//Verifica se há conteúdo mínimo para abrir

                        unset( $dadosConfig['status'] );

                        if ( $totalAvaliacoes == 0 ) {

                            $avisos[] = 'Seu curso não possui nenhuma avaliação ativa. Não é'
                                .' possível abrir o conteúdo do curso até que seja criada'
                                .' e ativada, pelo menos uma avaliação.';

                        }

                        if ( $totalPaginas == 0 ) {

                            $avisos[] = 'Seu curso não possui conteúdo para passar para os alunos.'
                                .' Não é possível abrir o conteúdo do curso até que seja criada'
                                .' pelo menos uma página de conteúdo.';

                        }

                        if ( !$haCertificadoAtivo ) {

                            $avisos[] = 'Seu curso não possui um certificado ativo.'
                                .' Não é possível abrir o conteúdo do curso até que exista'
                                .' um certificado ativo vinculado a este curso.';

                        }

                    }

                }
                //fim da rotina

                $curso->getConfiguracao()->preencherPropriedades($dadosConfig);
                $curso->preencherPropriedades($dadosConfig);
                $this->_cursoDao->salvar($curso);

                $qtdAvisos = count($avisos);
                if ( $qtdAvisos == 0 ) {//Se não houve erros

                    $notificacoesFlash = create_notificacao_json(
                        'sucesso',
                        'As alterações nas configurações do curso foram salvas com sucesso!',
                        5000
                    );

                } else {//Se ouve, mostrá-los

                    $msg = '<div><p>As alterações nas configurações do curso foram'
                        . ' salvas, mas houve alguns problemas:</p><ul>';

                    for ($i = 0; $i < $qtdAvisos; $i++) {

                        $msg .= '<li>'. $avisos[$i] .'</li>';

                    }

                    $msg .= '</ul></div>';

                    $notificacoesFlash = create_notificacao_json(
                        'aviso',
                        $msg,
                        20000
                    );

                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

                $json = create_json_feedback(true);
            } catch (Exception $e) {
                log_message(
                    'error',
                    'Ocorreu um erro ao tentar salvar as configurações do curso'
                        . create_exception_description($e)
                );

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.'
                );

                $json = create_json_feedback(false, $error);
            }
        }

        return $json;
    }

    private function _salvarImagem($dadosImagemTemp)
    {
        $arquivoImagem = $dadosImagemTemp['id'] . $dadosImagemTemp['ext'];
        $caminhoImagemTemp = TEMP_UPLOAD_DIR . 'img/' . $arquivoImagem;

        if ( file_exists($caminhoImagemTemp) ) {
            $caminhoImagemCurso = USER_IMG_DIR . 'curso/';

            rename($caminhoImagemTemp, $caminhoImagemCurso . $arquivoImagem);

            $dadosImagem = array(
                'url' => site_url(USER_IMG_URI . 'curso/' . $arquivoImagem),
                'nome' => $dadosImagemTemp['id'],
                'extensao' => $dadosImagemTemp['ext'],
                'diretorio' => $caminhoImagemCurso,
                'diretorioCompleto' => $caminhoImagemCurso . $arquivoImagem
            );

            return $this->_cursoDao->criarImagem($dadosImagem);
        } else {
            return null;
        }
    }
}