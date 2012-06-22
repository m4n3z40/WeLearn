<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 20/04/12
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */
class Configuracao extends Home_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport( 'configuracao_usuario.js' );
    }

    public function index()
    {
        try {
            $this->_renderTemplateHome( 'usuario/configuracao/index', array() );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir index de configuração de usuario: '
                . create_exception_description($e));

            show_404();
        }
    }

   public function dados_principais()
    {
        try {
            $this->template->appendJSImport( 'dados_profissionais.js' );

            $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

            $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

            $this->load->helper(array('area', 'segmento'));
            $listaAreas = lista_areas_para_dados_dropdown();
            $listaSegmentos = lista_segmentos_para_dados_dropdown(
                $segmentoDao->recuperarTodos(
                    '',
                    '',
                    array(
                        'areaId' => $usuarioAtual->getSegmentoInteresse()->getArea()->getId()
                    )
                )
            );

            unset($listaAreas['0'], $listaSegmentos['0']);


            $dadosViewDadosPrincipais = array(
                'formAction' => '/usuario/salvar_dados_principais',
                'extraOpenForm' => 'id="form-dados-principais"',
                'nome' => $usuarioAtual->getNome(),
                'sobreNome' => $usuarioAtual->getSobreNome(),
                'senha' => $usuarioAtual->getSenha(),
                'areaAtual' => $usuarioAtual->getSegmentoInteresse()->getArea()->getId(),
                'segmentoAtual' => $usuarioAtual->getSegmentoInteresse()->getId(),
                'listaAreas' => $listaAreas,
                'listaSegmentos' => $listaSegmentos
             );

            $dadosView = array(
                'formDadosPrincipais' => $this->template->loadPartial(
                    'form_dados_principais',
                    $dadosViewDadosPrincipais,
                    'usuario'
                )
            );

            $this->_renderTemplateHome( 'usuario/configuracao/dados_principais', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir alteração de dados principais: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function dados_pessoais()
    {
        try {
            $this->template->appendJSImport( 'dados_pessoais.js' );

            try {
                $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

                $dadosPessoaisDao = WeLearn_DAO_DAOFactory::create('DadosPessoaisUsuarioDAO');

                $dadosPessoais = $dadosPessoaisDao->recuperar( $usuarioAtual->getId() );
            } catch (cassandra_NotFoundException $e) {
                $dadosPessoais = null;
            }

            $possuiDadosPessoais = ( $dadosPessoais instanceof WeLearn_Usuarios_DadosPessoaisUsuario );

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

            if ( $possuiDadosPessoais && $dadosPessoais->getPais() ) {

                $listaEstado = $paisEstadoDao->recuperarEstadosDeUmPaisSimplificado(
                        $dadosPessoais->getPais()
                );

            }

            $listaDeRS = array();

            if ( $possuiDadosPessoais && ( $qtdRS = count( $dadosPessoais->getListaDeRS() ) ) > 0 ) {

                $listaDeRSObj = $dadosPessoais->getListaDeRS();

                for ($i = 0; $i < $qtdRS; $i++) {

                    $listaDeRS[] = array(
                        'rs'        => form_label('Rede Social', 'txt-rs-' . $i) .
                                       form_input('rsId[]', $listaDeRSObj[$i]->getDescricaoRS(), 'id="txt-rs-'. $i . '"'),
                        'rsUsuario' => form_label('Usuario na Rede Social', 'txt-rs-usuario-' . $i) .
                                       form_input('rsUsuario[]', $listaDeRSObj[$i]->getUrlUsuarioRS(), 'id="txt-rs-usuario-' . $i . '"')
                    );

                }

            } else {

                $listaDeRS[] = array(
                    'rs'        => form_label('Rede Social', 'txt-rs-0') .
                                   form_input('rsId[]', '', 'id="txt-rs-0"'),
                    'rsUsuario' => form_label('Usuario na Rede Social', 'txt-rs-usuario-0') .
                                   form_input('rsUsuario[]', '', 'id="txt-rs-usuario-0"')
                );

            }

            $listaDeIM = array();

            if ( $possuiDadosPessoais && ( $qtdIM = count( $dadosPessoais->getListaDeIM() ) ) > 0 ) {

                $listaDeIMObj = $dadosPessoais->getListaDeIM();

                for ($i = 0; $i < $qtdIM; $i++) {

                    $listaDeIM[] = array(
                       'im'        => form_label('Mensageiro Instantâneo (IM)', 'txt-im-' . $i) .
                                      form_input('imId[]', $listaDeIMObj[$i]->getDescricaoIM(), 'id="txt-im-' . $i . '"'),
                       'imUsuario' => form_label('Usuario no Mensageiro Instantâneo (IM)', 'txt-im-usuario-' . $i) .
                                      form_input('imUsuario[]', $listaDeIMObj[$i]->getDescricaoUsuarioIM(), 'id="txt-im-usuario-' . $i . '"')
                    );

                }

            } else {

                $listaDeIM[] = array(
                   'im'        => form_label('Mensageiro Instantâneo (IM)', 'txt-im-0') .
                                  form_input('imId[]', '', 'id="txt-im-0"'),
                   'imUsuario' => form_label('Usuario no Mensageiro Instantâneo (IM)', 'txt-im-usuario-0') .
                                  form_input('imUsuario[]', '', 'id="txt-im-usuario-0"')
                );

            }

            $dadosViewDadosPessoais = array(
                'formAction' => '/usuario/salvar_dados_pessoais',
                'extraOpenForm' => 'id="form-dados-pessoais"',
                'listaSexo' => $listaSexo,
                'sexoAtual' => $possuiDadosPessoais ? $dadosPessoais->getSexo() : WeLearn_Usuarios_Sexo::NAO_EXIBIR ,
                'dataNascimentoAtual' => $possuiDadosPessoais ? $dadosPessoais->getDataNascimento() : '',
                'listaPais' => $listaPais,
                'paisAtual' => $possuiDadosPessoais ? $dadosPessoais->getPais() : '0',
                'listaEstado' => $listaEstado,
                'estadoAtual' => $possuiDadosPessoais ? $dadosPessoais->getEstado() : '0',
                'cidadeAtual' => $possuiDadosPessoais ? $dadosPessoais->getCidade() : '',
                'enderecoAtual' => $possuiDadosPessoais ? $dadosPessoais->getEndereco() : '',
                'descricaoPessoalAtual' => $possuiDadosPessoais ? $dadosPessoais->getDescricaoPessoal() : '',
                'telAtual' => $possuiDadosPessoais ? $dadosPessoais->getTel() : '',
                'telAlternativoAtual' => $possuiDadosPessoais ? $dadosPessoais->getTelAlternativo() : '',
                'listaDeIM' => $listaDeIM,
                'listaDeRS' => $listaDeRS,
                'homePageAtual' => $possuiDadosPessoais ? $dadosPessoais->getHomePage() : ''
            );

            $dadosView = array(
                'formDadosPessoais' => $this->template->loadPartial(
                    'form_dados_pessoais',
                    $dadosViewDadosPessoais,
                    'usuario'
                )
            );

            $this->_renderTemplateHome( 'usuario/configuracao/dados_pessoais', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir alteração de dados pessoais: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function dados_profissionais()
    {
        try {
            $this->template->appendJSImport( 'dados_profissionais.js' );

            try {
                $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

                $dadosProfissionaisDAO = WeLearn_DAO_DAOFactory::create('DadosProfissionaisUsuarioDAO');

                $dadosProfissionais = $dadosProfissionaisDAO->recuperar( $usuarioAtual->getId() );
            } catch(cassandra_NotFoundException $e) {
                $dadosProfissionais = null;
            }

            $possuiDadosProfissionais = ( $dadosProfissionais instanceof WeLearn_Usuarios_DadosProfissionaisUsuario );

            $this->load->helper(array('area', 'segmento'));
            $listaAreas = lista_areas_para_dados_dropdown();

            if ( $possuiDadosProfissionais
              && $dadosProfissionais->getSegmentoTrabalho() instanceof WeLearn_Cursos_Segmento ) {

                $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

                $listaSegmentos = lista_segmentos_para_dados_dropdown(
                    $segmentoDao->recuperarTodos('', '', array(
                        'areaId' => $dadosProfissionais->getSegmentoTrabalho()->getArea()->getId()
                    ))
                );

            } else {
                $listaSegmentos = lista_segmentos_para_dados_dropdown();
            }

            $dadosViewDadosProfissionais = array(
                'formAction' => '/usuario/salvar_dados_profissionais',
                'extraOpenForm' => 'id="form-dados-profissionais"',
                'escolaridadeAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getEscolaridade() : '',
                'escolaAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getEscola() : '',
                'faculdadeAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getFaculdade() : '',
                'cursoAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getCurso() : '',
                'diplomaAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getDiploma() : '',
                'anoAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getAno() : '',
                'profissaoAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getProfissao() : '',
                'listaAreas' => $listaAreas,
                'areaAtual' => $possuiDadosProfissionais && ( $dadosProfissionais->getSegmentoTrabalho() instanceof WeLearn_Cursos_Segmento)
                             ? $dadosProfissionais->getSegmentoTrabalho()->getArea()->getId() : '0',
                'listaSegmentos' => $listaSegmentos,
                'segmentoAtual' => $possuiDadosProfissionais && ( $dadosProfissionais->getSegmentoTrabalho() instanceof WeLearn_Cursos_Segmento)
                                 ? $dadosProfissionais->getSegmentoTrabalho()->getId() : '0',
                'empresaAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getEmpresa() : '',
                'siteEmpresaAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getSiteEmpresa() : '',
                'cargoAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getCargo() : '',
                'descricaoTrabalhoAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getDescricaoTrabalho() : '',
                'habilidadesProfissionaisAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getHabilidadesProfissionais() : '',
                'interessesProfissionaisAtual' => $possuiDadosProfissionais ? $dadosProfissionais->getInteressesProfissionais() : '',
            );

            $dadosView = array(
                'formDadosProfissionais' => $this->template->loadPartial(
                    'form_dados_profissionais',
                    $dadosViewDadosProfissionais,
                    'usuario'
                )
            );

            $this->_renderTemplateHome( 'usuario/configuracao/dados_profissionais', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir alteração de dados profissionais:'
                . create_exception_description($e));

            show_404();
        }
    }

    public function imagem()
    {
        try {
            $this->template->appendJSImport( 'imagem_usuario.js' );

            $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

            $dadosViewImagem = array(
                'formAction' => '/usuario/salvar_imagem',
                'extraOpenForm' => 'id="form-imagem-usuario"',
                'imagemUsuarioAtual' => ( $usuarioAtual->getImagem() instanceof WeLearn_Usuarios_ImagemUsuario )
                                        ? $usuarioAtual->getImagem() : ''
            );

            $dadosView = array(
                'formImagem' => $this->template->loadPartial(
                    'form_imagem_perfil',
                    $dadosViewImagem,
                    'usuario'
                )
            );

            $this->_renderTemplateHome( 'usuario/configuracao/imagem', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir escolha de imagem de usuario:'
                . create_exception_description($e));

            show_404();
        }
    }

    public function privacidade()
    {
        try {
            $usuario = $this->autenticacao->getUsuarioAutenticado();

            $dadosViewPrivacidade = array(
                'formAction' => '/usuario/salvar_configuracao',
                'extraOpenForm' => 'id="form-privacidade"',
                'privacidadePerfilAtual' => $usuario->getConfiguracao()->getPrivacidadePerfil(),
                'privacidadeMPAtual' => $usuario->getConfiguracao()->getPrivacidadeMP(),
                'privacidadeConvitesAtual' => $usuario->getConfiguracao()->getPrivacidadeConvites(),
                'privacidadeCompartilhamentoAtual' => $usuario->getConfiguracao()->getPrivacidadeCompartilhamento(),
                'privacidadeNotificacoesAtual' => $usuario->getConfiguracao()->getPrivacidadeNotificacoes()
            );

            $dadosView = array(
                'formPrivacidade' => $this->template->loadPartial(
                    'form_configuracao',
                    $dadosViewPrivacidade,
                    'usuario'
                )
            );

            $this->_renderTemplateHome( 'usuario/configuracao/privacidade', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar configuração de privacidade usuário:'
                . create_exception_description($e));

            show_404();
        }
    }

    protected function _renderTemplateHome($view = '', $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                array(),
                'usuario/configuracao'
            )
        );

        parent::_renderTemplateHome($view, $dados);
    }
}
