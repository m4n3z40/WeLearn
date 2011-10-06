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
            'tempoDuracaoMaxAtual' => '40',
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
        var_dump($_FILES);
    }

    public function salvar_imagem_temporaria()
    {
        $idImagem = str_replace('-', '', UUID::mint()->string);

        $upload_config = array(
            'upload_path' => realpath(APPPATH . '../temp/img/'),
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
                    'success' => true,
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