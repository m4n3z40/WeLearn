<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quickstart extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->template->appendJSImport('quickstart.js')
                       ->render('quickstart/quickstart');
    }

    public function carregar_etapa($etapa)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        switch($etapa) {
            case 1: case 2: case 3: case 4: case 5:
                $actionEtapa = '_etapa' . $etapa;
                $this->$actionEtapa();
                break;
            default:
                show_404();
        }
    }

    public function salvar_etapa($etapa)
    {
        if( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        switch($etapa) {
            case 1: case 2: case 3: case 4: case 5:
                $actionSalvarEtapa = '_salvar_etapa' . $etapa;
                $this->$actionSalvarEtapa($this->input->post());
                break;
            default:
                $error = create_json_feedback_error_json('Parâmetro de etapa ou dados de posts inválidos. Não foi possível salvar esta etapa');
                echo create_json_feedback(false, $error);
        }
    }

    public function finalizar_quickstart()
    {
        
    }

    private function _etapa1()
    {
        $listaSexo = array(
            WeLearn_Usuarios_Sexo::NAO_EXIBIR => WeLearn_Usuarios_Sexo::getDescricao(WeLearn_Usuarios_Sexo::NAO_EXIBIR),
            WeLearn_Usuarios_Sexo::MASCULINO => WeLearn_Usuarios_Sexo::getDescricao(WeLearn_Usuarios_Sexo::MASCULINO),
            WeLearn_Usuarios_Sexo::FEMININO => WeLearn_Usuarios_Sexo::getDescricao(WeLearn_Usuarios_Sexo::FEMININO)
        );

        $paisEstadoDao = WeLearn_DAO_DAOFactory::create('PaisEstadoDAO', null, false);
        $listaPais = array();
        $listaPais[0] = 'Selecione um país';
        $listaPais = array_merge($listaPais, $paisEstadoDao->recuperarTodosPaisesSimplificado());

        $listaEstado = array(
            0 => 'Selecione um país'
        );

        $listaDeRS = array();
        $listaDeRS[] = array(
            'rs'        => form_label('Rede Social', 'txt-rs-0') .
                           form_input('rsId[]', '', 'id="txt-rs-0"'),
            'rsUsuario' => form_label('Usuario na Rede Social', 'txt-rs-usuario-0') .
                           form_input('rsUsuario[]', '', 'id="txt-rs-usuario-0"')
        );

        $listaDeIM = array();
        $listaDeIM[] = array(
            'im'        => form_label('Mensageiro Instantâneo (IM)', 'txt-im-0') .
                           form_input('imId[]', '', 'id="txt-im-0"'),
            'imUsuario' => form_label('Usuario no Mensageiro Instantâneo (IM)', 'txt-im-usuario-0') .
                           form_input('imUsuario[]', '', 'id="txt-im-usuario-0"')
         );

        $dadosEtapa1 = array(
            'extraOpenForm' => 'id="form-etapa-1" class="quickstart-form"',
            'listaSexo' => $listaSexo,
            'sexoAtual' => WeLearn_Usuarios_Sexo::NAO_EXIBIR,
            'dataNascimentoAtual' => '',
            'listaPais' => $listaPais,
            'paisAtual' => '0',
            'listaEstado' => $listaEstado,
            'estadoAtual' => '0',
            'cidadeAtual' => '',
            'enderecoAtual' => '',
            'descricaoPessoalAtual' => '',
            'telAtual' => '',
            'telAlternativoAtual' => '',
            'listaDeIM' => $listaDeIM,
            'listaDeRS' => $listaDeRS,
            'homePageAtual' => ''
        );


        echo $this->template->loadPartial('form_dados_pessoais', $dadosEtapa1, 'usuario');
    }

    private function _salvar_etapa1($dados_post)
    {
        $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

        $dadosPessoaisDao = WeLearn_DAO_DAOFactory::create('DadosPessoaisUsuarioDAO');
        $dadosPessoais = $dadosPessoaisDao->criarNovo($dados_post);

        for ($i = 0; $i < count($dados_post['rsId']); $i++) {
            if ( $dados_post['rsId'][$i] ) {
                $dadosRS = array(
                    'usuarioId' => $usuarioAtual->getId(),
                    'descricaoRS' => $dados_post['rsId'][$i],
                    'urlUsuarioRS' => $dados_post['rsUsuario'][$i]
                );

                $dadosPessoais->adicionarRS( $dadosPessoaisDao->criarNovoRS($dadosRS) );
            }
        }

        for ($i = 0; $i < count($dados_post['imId']); $i++) {
            if ( $dados_post['imId'][$i] ) {
                $dadosIM = array(
                    'usuarioId' => $usuarioAtual->getId(),
                    'descricaoIM' => $dados_post['imId'][$i],
                    'descricaoUsuarioIM' => $dados_post['imUsuario'][$i]
                );

                $dadosPessoais->adicionarIM( $dadosPessoaisDao->criarNovoIM($dadosIM) );
            }
        }

        $usuarioAtual->setDadosPessoais( $dadosPessoais );

        $usuarioAtual->salvarDadosPessoais();
        echo '{"success":true}';
    }

    private function _etapa2()
    {
        $this->load->helper(array('area', 'segmento'));
        $listaAreas = lista_areas_para_dados_dropdown();

        $listaSegmentos = lista_segmentos_para_dados_dropdown();

        $dadosEtapa2 = array(
            'extraOpenForm' => 'id="form-etapa-2" class="quickstart-form"',
            'escolaridadeAtual' => '',
            'escolaAtual' => '',
            'faculdadeAtual' => '',
            'cursoAtual' => '',
            'diplomaAtual' => '',
            'anoAtual' => '',
            'profissaoAtual' => '',
            'listaAreas' => $listaAreas,
            'areaAtual' => '0',
            'listaSegmentos' => $listaSegmentos,
            'segmentoAtual' => '0',
            'empresaAtual' => '',
            'siteEmpresaAtual' => '',
            'cargoAtual' => '',
            'descricaoTrabalhoAtual' => '',
            'habilidadesProfissionaisAtual' => '',
            'interessesProfissionaisAtual' => ''
        );

        echo $this->template->loadPartial('form_dados_profissionais',  $dadosEtapa2, 'usuario');
    }

    private function _salvar_etapa2($dados_post)
    {
        $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

        $dadosProfissionaisDao = WeLearn_DAO_DAOFactory::create('DadosProfissionaisUsuarioDAO');
        $dadosProfissionais = $dadosProfissionaisDao->criarNovo($dados_post);
        $usuarioAtual->setDadosProfissionais( $dadosProfissionais );

        $usuarioAtual->salvarDadosProfissionais();
        echo '{"success":true}';
    }

    private function _etapa3()
    {
        $dadosEtapa3 = array(
            'extraOpenForm' => 'id="form-etapa-3" class="quickstart-form"',
            'imagemUsuarioAtual' => ''
        );

        echo $this->template->loadPartial('form_imagem_perfil', $dadosEtapa3, 'usuario');
    }

    private function _salvar_etapa3($dados_post)
    {

       echo '{"success":true}';
    }

    private function _etapa4()
    {

    }

    private function _salvar_etapa4($dados_post)
    {

    }

    private function _etapa5()
    {

    }

    private function _salvar_etapa5($dados_post)
    {

    }



}

/* End of file quickstart.php */
/* Location: ./application/controllers/quickstart.php */