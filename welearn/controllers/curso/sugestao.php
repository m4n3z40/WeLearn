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
        $this->listar();
    }

    public function listar()
    {
        try {
            $areaAtual = '0';
            $segmentoAtual = '0';
            $areaAtualObj = null;

            $filtro = $this->input->get('f');
            $areaId = $this->input->get('a');
            $segmentoId = $this->input->get('s');

            $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
            $listaAreasObjs = $areaDao->recuperarTodos();

            $this->load->helper(array('area', 'segmento'));
            $listaAreas = lista_areas_para_dados_dropdown($listaAreasObjs);
            $listaSegmentos = array('0' => 'Selecione uma área de segmento');

            if ( ! empty($areaId) ) {
                try {
                    $areaAtualObj = $areaDao->recuperar($areaId);
                    $areaAtual = $areaAtualObj->getId();

                    try {
                        $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
                        $listaSegmentosObjs = $segmentoDao->recuperarTodos('','', array('areaId' => $areaAtualObj->getId()));
                    } catch(cassandra_NotFoundException $e) {//Area requisitada não possui segmentos se chegar aqui.
                        $listaSegmentosObjs = null;
                    }
                } catch (cassandra_NotFoundException $e) { //Area requisitada não existe se chegar aqui.
                    $listaSegmentosObjs = null;
                } catch (cassandra_InvalidRequestException $e) { //Codigo da área vazio se chegar aqui.
                    $listaSegmentosObjs = null;
                }

                $listaSegmentos = lista_segmentos_para_dados_dropdown($listaSegmentosObjs);
            }

            if ( ! empty($segmentoId) && in_array( $segmentoId, array_keys($listaSegmentos) ) ) {
                $segmentoAtual = $segmentoId;
            }

            $count = 10;

            try {
                $sugestoes = $this->_recuperar_lista($filtro, '', '', $count, $areaAtualObj);
            } catch (cassandra_NotFoundException $e) {
                $sugestoes = array();
            }

            if ($filtro == 'pop') {
                $this->load->helper('paginacao_mysql');
                $paginacao = create_paginacao_mysql($sugestoes, 0, $count);
            } else {
                $this->load->helper('paginacao_cassandra');
                $paginacao = create_paginacao_cassandra($sugestoes, $count);
            }

            $filtravelPorAreaOuSegmento = ($filtro == 'meu' || $filtro == 'rec') ? false : true;

            $minhasSugestoes = $filtro == 'meu';
            $minhasSugestoesEmEspera = ($minhasSugestoes && $this->input->get('st') != 'acc') ? true : false;
            $minhasSugestoesAceitas = ($minhasSugestoes && $this->input->get('st') == 'acc') ? true : false;

            $tituloLista = $this->_gerar_titulo_lista($filtro);

            $dadosView = array(
                'filtravelPorAreaOuSegmento' => $filtravelPorAreaOuSegmento,
                'minhasSugestoesEmEspera' => $minhasSugestoesEmEspera,
                'minhasSugestoesAceitas' => $minhasSugestoesAceitas,
                'listaAreas' => $listaAreas,
                'areaAtual' => $areaAtual,
                'listaSegmentos' => $listaSegmentos,
                'segmentoAtual' => $segmentoAtual,
                'haSugestoes' => !empty($sugestoes),
                'tituloLista' => $tituloLista,
                'listaSugestoes' => $this->template->loadPartial('lista', array('sugestoes'=>$sugestoes), 'curso/sugestao'),
                'haProximos' => $paginacao['proxima_pagina'],
                'primeiroProximos' => $paginacao['inicio_proxima_pagina']
            );

            $this->template->render('curso/sugestao/lista', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao retornar outra página de sugestões de curso: '
                                 . create_exception_description($e));

            echo '<pre>' . create_exception_description($e) . '</pre>';
        }
    }

    private function _gerar_titulo_lista($filtro)
    {
        switch($filtro)
        {
            case 'pop':
                if ( $this->input->get('a') && !$this->input->get('s') ) {
                    return 'Sugestões da área por popularidade';
                }

                if ( $this->input->get('a') && $this->input->get('s') ) {
                    return 'Sugestões do segmento por popularidade';
                }

                return 'Sugestões por popularidade';

            case 'are':
                return 'Sugestões da área recentes';
            case 'seg':
                return 'Sugestões do segmento recentes';
            case 'rec':
                return 'Sugestões recomendadas a você';
            case 'acc':
                $idArea = $this->input->get('a');
                $idSegmento = $this->input->get('s');
                if ( ! empty($idArea) && empty($idSegmento) ) {
                    return 'Sugestões da área que geraram cursos';
                } elseif ( ! empty($idArea) && ! empty($idSegmento) ) {
                    return 'Sugestões do segmento que geraram cursos';
                }

                return 'Sugestões recentes que geraram cursos';
            case 'meu':
                if ($this->input->get('st') == 'acc') {
                    return 'Suas sugestões que geraram cursos';
                }

                return 'Suas sugestões';
            case 'new':
            default:
                return 'Sugestões recentes';
        }
    }

    private function _recuperar_lista($filtro = '', $de = '', $ate = '', $count = 10, $area = null)
    {
        $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
        switch ($filtro) {
            case 'pop':
                $popFiltros = array();
                if ( $idArea = $this->input->get('a') ) {
                    $popFiltros['area_id'] = $idArea;
                }

                if ( $idSegmento = $this->input->get('s') ) {
                    $popFiltros['segmento_id'] = $idSegmento;
                }

                return $sugestaoDao->recuperarTodosPorPopularidade(
                    $de, $count + 1, $popFiltros
                );

            case 'are':
                if ( !($area instanceof WeLearn_Cursos_Area) ) {
                    $area = $this->input->get('a');
                    if ( ! empty($area) ) {
                        try {
                            $area = WeLearn_DAO_DAOFactory::create('AreaDAO')->recuperar($area);
                        } catch (cassandra_InvalidRequestException $e) {
                            return array();
                        }
                    } else {
                        return array();
                    }
                }

                return $sugestaoDao->recuperarTodosPorArea(
                    $area,
                    $de,
                    $ate,
                    $count + 1
                );
            case 'seg':
                $segmento = $this->input->get('s');

                if ( empty( $segmento ) ) {
                    return array();
                }

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
                try {
                    return $sugestaoDao->recuperarTodosPorSegmento(
                        $this->autenticacao->getUsuarioAutenticado()->getSegmentoInteresse(),
                        $de,
                        $ate,
                        $count + 1
                    );
                } catch (cassandra_NotFoundException $e) {
                    return $sugestaoDao->recuperarTodosPorArea(
                        $this->autenticacao->getUsuarioAutenticado()->getSegmentoInteresse()->getArea(),
                        $de,
                        $ate,
                        $count + 1
                    );
                }
            case 'acc':
                $idArea = $this->input->get('a');
                $idSegmento = $this->input->get('s');
                if ( ! empty($idArea) && empty($idSegmento) ) {
                    return $sugestaoDao->recuperarTodosAceitosPorArea(
                        WeLearn_DAO_DAOFactory::create('AreaDAO')->recuperar($idArea),
                        $de,
                        $ate,
                        $count + 1
                    );
                } elseif ( ! empty($idArea) && ! empty($idSegmento) ) {
                    return $sugestaoDao->recuperarTodosAceitosPorSegmento(
                        WeLearn_DAO_DAOFactory::create('SegmentoDAO')->recuperar($idSegmento),
                        $de,
                        $ate,
                        $count + 1
                    );
                }

                return $sugestaoDao->recuperarTodosAceitosRecentes(
                    $de,
                    $ate,
                    $count + 1
                );
            case 'meu':
                if ($this->input->get('st') == 'acc') {
                    return $sugestaoDao->recuperarTodosAceitosPorUsuario(
                        $this->autenticacao->getUsuarioAutenticado(),
                        $de,
                        $ate,
                        $count + 1
                    );
                }

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

            if ($filtro == 'pop') {
                $this->load->helper('paginacao_mysql');
                $paginacao = create_paginacao_mysql($listaSugestoes, $inicio, $count);
            } else {
                $this->load->helper('paginacao_cassandra');
                $paginacao = create_paginacao_cassandra($listaSugestoes, $count);
            }

            $arrResultado = array(
                'success' => true,
                'sugestoesHtml' => $this->template->loadPartial('lista', array('sugestoes'=>$listaSugestoes), 'curso/sugestao'),
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

    public function votar($sugestaoId)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        set_json_header();

        try {
            $sugestaoDao = WeLearn_DAO_DAOFactory::create('SugestaoCursoDAO');
            $sugestao = $sugestaoDao->recuperar($sugestaoId);
            $qtdVotos = $sugestaoDao->votar($sugestao, $this->autenticacao->getUsuarioAutenticado());

            $json = create_json_feedback(true, '', array('qtdVotos' => $qtdVotos));
        } catch (WeLearn_Cursos_UsuarioJaVotouException $e) {
            $error = create_json_feedback_error_json('Você já votou nesta sugestão. Não é possível votar novamente.'
                                                    .'<br/>Aguarde um pouco, quando esta sugestão gerar um curso, nós lhe avisamos!');

            $json = create_json_feedback(false, $error);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao salvar um voto de sugestão de curso: '
                                 . create_exception_description($e));
            $error = create_json_feedback_error_json('Ocorreu um erro desconhecido ao registrar seu voto. Já estamos averiguando.'
                                                    .'<br/>Tente novamente em breve!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
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