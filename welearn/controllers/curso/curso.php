<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curso extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('curso.js');
    }

    public function index()
    {
        $this->template->render();
    }

    public function exibir($id)
    {
        echo $id;
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

        $dadosFormCriar = array(
            'formAction' => 'curso/curso/salvar',
            'extraOpenForm' => 'id="form-curso"',
            'hiddenFormData' => $hiddenFormData,
            'sugestao' => $sugestaoGeradora,
            'nomeAtual' => $nomeAtual,
            'temaAtual' => $temaAtual,
            'descricaoAtual' => $descricaoAtual,
            'objetivosAtual' => '',
            'conteudoPropostoAtual' => '',
            'listaAreas' => $listaAreas,
            'areaAtual' => $areaAtual,
            'listaSegmentos' => $listaSegmentos,
            'segmentoAtual' => $segmentoAtual,
            'tempoDuracaoMaxAtual' => '40',
            'privacidadeConteudoAtual' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'conteudoPublico' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'conteudoPrivado' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'privacidadeInscricaoAtual' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'inscricaoAutomatica' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'inscricaoRestrita' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'imagemAtual' => '',
            'acaoForm' => $acaoForm,
            'textoBotaoSubmit' => $textoBotaoSubmit
        );

        $formCriar = $this->template->loadPartial('form', $dadosFormCriar, 'curso/curso');

        $this->template->render('curso/curso/criar', array('formCriar' => $formCriar));
    }

    public function salvar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');
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
                    $dadosImagemTemp = $dadosNovoCurso['imagem'];
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

                        $dadosNovoCurso['imagem'] = $cursoDao->criarImagem($dadosImagem);
                    }
                }

                $novoCurso = $cursoDao->criarNovo($dadosNovoCurso);
                $cursoDao->salvar($novoCurso);

                if ($dadosNovoCurso['acao'] == 'criarFromSugestao') {
                    $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
                    $sugestao = $sugestaoDao->recuperar($dadosNovoCurso['sugestao']);
                    $sugestao->registrarCriacaoCurso($novoCurso, $sugestaoDao);
                }

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
}