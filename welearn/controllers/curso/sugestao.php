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
        $indexCF = WL_Phpcassa::getInstance()->getColumnFamily('cursos_sugestao_por_area');

        print_r(array_keys($indexCF->get('_todos', null, '', '', true, 11)));

        $this->template->render();
    }

    public function listar()
    {
        try {
            $filtro = $this->input->get('f');

            $this->load->helper(array('area', 'segmento'));
            $listaAreas = lista_areas_para_dados_dropdown();
            $listaSegmentos = lista_segmentos_para_dados_dropdown();

            $count = 10;

            $sugestoesRecentes = $this->_recuperar_lista($filtro);

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

            echo create_exception_description($e);
        }
    }

    private function _recuperar_lista($filtro = '', $de = '', $ate = '', $count = 10)
    {
        $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
        switch ($filtro) {
            case 'pop':

            case 'are':
                $area = WeLearn_DAO_DAOFactory::create('AreaDAO')->recuperar(
                    $this->input->get('a')
                );
                return $sugestaoDao->recuperarTodosPorArea(
                    $area,
                    $de,
                    $ate,
                    $count + 1
                );
            case 'seg':
                $segmento = WeLearn_DAO_DAOFactory::create('SegmentoDAO')->recuperar(
                    $this->input->get('s')
                );
                return $sugestaoDao->recuperarTodosPorSegmento(
                    $segmento,
                    $de,
                    $ate,
                    $count + 1
                );
            case 'rec':
                return $sugestaoDao->recuperarTodosPorSegmento(
                    $this->autenticacao->getUsuarioAutenticado()->getSegmentoInteresse(),
                    $de,
                    $ate,
                    $count + 1
                );
            case 'acc':

            case 'meu':
                return $sugestaoDao->recuperarTodosPorUsuario(
                    $this->autenticacao->getUsuarioAutenticado(),
                    $de,
                    $ate,
                    $count + 1
                );
            case 'new':
            default:
                return $sugestaoDao->recuperarTodos($de, $ate, array('count' => $count + 1));
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

            $filtro = $this->input->get('f');

            $listaSugestoes = $this->_recuperar_lista($filtro, $inicio, '', $count);

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

                $notificacoesFlash = Zend_Json::encode(array(
                                                           'msg'=> 'A sugestão de curso foi enviada'.
                                                                   ' com sucesso! Obrigado pela participação!',
                                                           'nivel' => 'sucesso',
                                                           'tempo' => '15000'
                                                       ));
                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);

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