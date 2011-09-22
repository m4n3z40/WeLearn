<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sugestao extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('curso.js')
                ->appendJSImport('sugestao_curso.js');
    }

	public function index()
	{
        
    }

    public function listar()
    {
        try {
            $filtro = $this->input->get('f');

            $this->load->helper(array('area', 'segmento'));
            $listaAreas = lista_areas_para_dados_dropdown();
            $listaSegmentos = lista_segmentos_para_dados_dropdown();

            $count = 10;

            $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
            $sugestoesRecentes = $sugestaoDao->recuperarTodos('','', array('count' => $count + 1));

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($sugestoesRecentes, $count);

            $dadosView = array(
                'listaAreas' => $listaAreas,
                'areaAtual' => '0',
                'listaSegmentos' => $listaSegmentos,
                'segmentoAtual' => '0',
                'sugestoes' => $sugestoesRecentes,
                'haProximos' => $paginacao['proxima_pagina'],
                'primeiroProximos' => $paginacao['inicio_proxima_pagina']
            );

            $this->template->render('curso/sugestao/lista', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao retornar outra página de sugestões de curso: '
                                 . create_exception_description($e));


        }
    }

    public function proxima_pagina($inicio)
    {
        if( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 10;

            $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
            $listaSugestoes = $sugestaoDao->recuperarTodos($inicio, '', array('count' => $count + 1));

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaSugestoes, $count);
            if ($paginacao['proxima_pagina']) {
                $paginacao['inicio_proxima_pagina'] = $paginacao['inicio_proxima_pagina']->getId();
            }

            $listaSugestoesArr = array();
            foreach ($listaSugestoes as $sugestao) {
                $listaSugestoesArr[] = $sugestao->toArray();
            }

            $arrResultado = array(
                'success' => true,
                'sugestoes' => $listaSugestoesArr,
                'paginacao' => $paginacao
            );

            echo Zend_Json::encode($arrResultado);
        } catch (Exception $e) {
            log_message('error', 'Erro ao retornar outra página de sugestões de curso: '
                                 . create_exception_description($e));

            $erro = create_json_feedback_error_json('Algo deu errado ao retornar a'
                                                    . ' próxima página, tente novamente mais tarde.');

            echo create_json_feedback(false, $erro);
        }
    }

    public function criar()
    {
        $this->load->helper('area');
        $listaAreas = lista_areas_para_dados_dropdown();

        $listaSegmentos = array(
            '0' => 'Selecione uma área de segmento'
        );

        $dadosFormPartial = array(
            'formAction' => '/curso/sugestao/salvar',
            'extraOpenForm' => 'id="form-sugestao"',
            'tituloForm' => 'Descreva a sua Sugestão',
            'nomeAtual' => '',
            'temaAtual' => '',
            'descricaoAtual' => '',
            'listaAreas' => $listaAreas,
            'areaAtual' => '0',
            'listaSegmentos' => $listaSegmentos,
            'segmentoAtual' => '0'
        );

        $formCriar = $this->template->loadPartial('form', $dadosFormPartial, 'curso/sugestao');

        $this->template->render('curso/sugestao/criar', array('formCriar' => $formCriar));
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');
        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors_json();

            $json = create_json_feedback(false, $errors);
        } else {
            try {
                $dadosForm = $this->input->post();

                $area = new WeLearn_Cursos_Area($dadosForm['area']);
                $segmento = new WeLearn_Cursos_Segmento($dadosForm['segmento'], '', $area);
                $dadosForm['segmento'] = $segmento;

                $sugestaoCursoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
                $novaSugestao = $sugestaoCursoDao->criarNovo($dadosForm);

                $novaSugestao->setCriador($this->autenticacao->getUsuarioAutenticado());

                $sugestaoCursoDao->salvar($novaSugestao);

                $json = create_json_feedback(true);
            } catch(Exception $e) {
                log_message('error', 'Erro ao salvar sugestão de curso: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Não foi possível criar a sugestão de curso, tente novamente mais tarde.');

                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }
}

/* End of file sugestao.php */
/* Location: ./application/controllers/curso/sugestao.php */