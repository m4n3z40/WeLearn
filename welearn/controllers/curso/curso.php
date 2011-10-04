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
        $this->load->helper('area');
        $listaAreas = lista_areas_para_dados_dropdown();

        $listaSegmentos = array(
            '0' => 'Selecione uma área de segmento'
        );

        $dadosFormCriar = array(
            'formAction' => 'curso/curso/salvar',
            'extraOpenForm' => 'id="form-curso"',
            'nomeAtual' => '',
            'temaAtual' => '',
            'descricaoAtual' => '',
            'objetivosAtual' => '',
            'conteudoPropostoAtual' => '',
            'listaAreas' => $listaAreas,
            'areaAtual' => '0',
            'listaSegmentos' => $listaSegmentos,
            'segmentoAtual' => '0',
            'tempoDuracaoMaxAtual' => '',
            'privacidadeConteudoAtual' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'conteudoPublico' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'conteudoPrivado' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'privacidadeInscricaoAtual' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'inscricaoAutomatica' => WeLearn_Cursos_PermissaoCurso::LIVRE,
            'inscricaoRestrita' => WeLearn_Cursos_PermissaoCurso::RESTRITO,
            'imagemAtual' => '',
            'acaoForm' => 'criar',
            'textoBotaoSubmit' => 'Criar Novo Curso!'
        );

        $formCriar = $this->template->loadPartial('form', $dadosFormCriar, 'curso/curso');

        $this->template->render('curso/curso/criar', array('formCriar' => $formCriar));
    }

    public function salvar()
    {
        var_dump($this->input->post());
    }
}