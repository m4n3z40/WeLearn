<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quickstart extends Home_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport( 'quickstart.js' )
                       ->appendJSImport( 'dados_pessoais.js' )
                       ->appendJSImport( 'dados_profissionais.js' )
                       ->appendJSImport( 'imagem_usuario.js' );
    }

    public function index()
    {
        $dadosView = array(
            'formEtapa1' => $this->_partialEtapa1(),
            'formEtapa2' => $this->_partialEtapa2(),
            'formEtapa3' => $this->_partialEtapa3(),
            'formEtapa4' => $this->_partialEtapa4()
        );

        $this->_renderTemplateHome( 'quickstart/quickstart', $dadosView );
    }

    public function finalizar()
    {
        
    }

    private function _partialEtapa1()
    {
        $listaSexo = array(
            WeLearn_Usuarios_Sexo::NAO_EXIBIR => WeLearn_Usuarios_Sexo::getDescricao(WeLearn_Usuarios_Sexo::NAO_EXIBIR),
            WeLearn_Usuarios_Sexo::MASCULINO => WeLearn_Usuarios_Sexo::getDescricao(WeLearn_Usuarios_Sexo::MASCULINO),
            WeLearn_Usuarios_Sexo::FEMININO => WeLearn_Usuarios_Sexo::getDescricao(WeLearn_Usuarios_Sexo::FEMININO)
        );

        $paisEstadoDao = WeLearn_DAO_DAOFactory::create('PaisEstadoDAO', null, false);
        $listaPais = array( 0 => 'Selecione um país' );
        $listaPais = array_merge(
            $listaPais,
            $paisEstadoDao->recuperarTodosPaisesSimplificado()
        );

        $listaEstado = array( 0 => 'Selecione um país acima' );

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
            'formAction' => '/usuario/salvar_dados_pessoais',
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


        return $this->template->loadPartial(
            'form_dados_pessoais',
            $dadosEtapa1,
            'usuario'
        );
    }

    private function _partialEtapa2()
    {
        $this->load->helper(array('area', 'segmento'));
        $listaAreas = lista_areas_para_dados_dropdown();

        $listaSegmentos = lista_segmentos_para_dados_dropdown();

        $dadosEtapa2 = array(
            'formAction' => '/usuario/salvar_dados_profissionais',
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

        return $this->template->loadPartial(
            'form_dados_profissionais',
            $dadosEtapa2,
            'usuario'
        );
    }

    private function _partialEtapa3()
    {
        $dadosEtapa3 = array(
            'formAction' => '/usuario/salvar_imagem',
            'extraOpenForm' => 'id="form-etapa-3" class="quickstart-form"',
            'imagemUsuarioAtual' => ''
        );

        return $this->template->loadPartial(
            'form_imagem_perfil',
            $dadosEtapa3,
            'usuario'
        );
    }

    private function _partialEtapa4()
    {
        $usuario = $this->autenticacao->getUsuarioAutenticado();

        $dadosEtapa4 = array(
            'formAction' => '/usuario/salvar_configuracao',
            'extraOpenForm' => 'id="form-etapa-4" class="quickstart-form"',
            'privacidadePerfilAtual' => $usuario->getConfiguracao()->getPrivacidadePerfil(),
            'privacidadeMPAtual' => $usuario->getConfiguracao()->getPrivacidadeMP(),
            'privacidadeConvitesAtual' => $usuario->getConfiguracao()->getPrivacidadeConvites(),
            'privacidadeCompartilhamentoAtual' => $usuario->getConfiguracao()->getPrivacidadeCompartilhamento(),
            'privacidadeNotificacoesAtual' => $usuario->getConfiguracao()->getPrivacidadeNotificacoes()
        );

        return $this->template->loadPartial(
            'form_configuracao',
            $dadosEtapa4,
            'usuario'
        );
    }
}

/* End of file quickstart.php */
/* Location: ./application/controllers/quickstart.php */