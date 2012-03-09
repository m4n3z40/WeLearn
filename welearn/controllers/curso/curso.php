<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curso extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('curso.js');
    }

    public function index()
    {
        $this->template->render();
    }

    public function exibir($id)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar(CassandraUtil::import($id)->bytes);

            $dadosViewExibir = array (
                'curso' => $curso
            );

            $this->_renderTemplateCurso($curso, 'curso/curso/exibir', $dadosViewExibir);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir o curso: ' . create_exception_description($e));
            show_404();
        }
    }

    public function configurar($id)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar(CassandraUtil::import($id)->bytes);

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
        $this->template->setTemplate('perfil');

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

        $this->template->render('curso/curso/criar', $dadosCriar);
    }

    public function salvar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $acao = $this->input->post('acao');

        $this->load->library('form_validation');

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

    private function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
    {
        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao()
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }

    private function _salvarNovoCurso()
    {
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            try {
                $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
                $usuarioDao  = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
                $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');

                $dadosNovoCurso = $this->input->post();

                $segmentoId = $dadosNovoCurso['segmento'];
                $dadosNovoCurso['segmento'] = $segmentoDao->recuperar($segmentoId);
                $dadosNovoCurso['criador'] = $usuarioDao->criarGerenciadorPrincipal(
                    $this->autenticacao->getUsuarioAutenticado()
                );
                $dadosNovoCurso['configuracao'] = $cursoDao->criarConfiguracao($dadosNovoCurso);

                //Gerando e salvando imagem, caso houver.
                if (isset($dadosNovoCurso['imagem']) && is_array($dadosNovoCurso['imagem'])) {
                    $dadosNovoCurso['imagem'] = $this->_salvarImagem($dadosNovoCurso['imagem'], $cursoDao);
                }

                $novoCurso = $cursoDao->criarNovo($dadosNovoCurso);
                $cursoDao->salvar($novoCurso);

                if ($dadosNovoCurso['acao'] == 'criarFromSugestao') {
                    $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
                    $sugestao = $sugestaoDao->recuperar($dadosNovoCurso['sugestao']);
                    $sugestao->registrarCriacaoCurso($novoCurso, $sugestaoDao);
                }

                $notificacoesFlash = Zend_Json::encode(array(
                                                           'msg'=> 'O novo curso foi criado com sucesso e você é o Gerenciador Principal!'.
                                                                   '<br /> Comece a Alterar as configurações, editar o conteúdo e convidar alunos em potencial.',
                                                           'nivel' => 'sucesso',
                                                           'tempo' => '15000'
                                                       ));

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
                $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');

                $dadosConfig = $this->input->post();

                $curso = $cursoDao->recuperar($dadosConfig['id']);

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

                    $dadosConfig['imagem'] = $this->_salvarImagem($dadosConfig['imagem'], $cursoDao);

                    if ( $dadosConfig['imagem'] != null ) {
                        $dadosConfig['imagem']->setCursoId( $curso->getId() );
                    }
                }

                $curso->getConfiguracao()->preencherPropriedades($dadosConfig);
                $curso->preencherPropriedades($dadosConfig);
                $cursoDao->salvar($curso);

                $notificacoesFlash = Zend_Json::encode(array(
                                                           'msg'=> 'As alterações nas configurações do curso'.
                                                                   ' foram salvas com sucesso!',
                                                           'nivel' => 'sucesso',
                                                           'tempo' => '15000'
                                                       ));
                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

                $json = create_json_feedback(true);
            } catch (Exception $e) {
                log_message('error', 'Ocorreu um erro ao tentar salvar as configurações do curso' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.');
                $json = create_json_feedback(false, $error);
            }
        }

        return $json;
    }

    private function _salvarImagem($dadosImagemTemp, CursoDAO $cursoDao)
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

            return $cursoDao->criarImagem($dadosImagem);
        } else {
            return null;
        }
    }
}