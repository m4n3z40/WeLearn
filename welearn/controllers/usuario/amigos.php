<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 01/05/12
 * Time: 23:11
 * To change this template use File | Settings | File Templates.
 */
class Amigos extends Home_Controller
{
    private static $_count=30;
    function __construct()
    {
        parent::__construct();
        $this->template->appendJSImport('home.js')
                       ->appendJSImport('amizade.js');
    }

    public function index()
    {
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        $amigosDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $filtros= array('count' => self::$_count+1 , 'opcao' => 'amigos' , 'usuario' => $usuarioAutenticado);
        try{
            $totalAmigos = $amigosDao->recuperarQtdTotalAmigos($usuarioAutenticado);
            $listaAmigos= $amigosDao->recuperarTodos('','',$filtros);
            $this->load->helper('paginacao_cassandra');
            $dadosPaginados=create_paginacao_cassandra($listaAmigos,self::$_count);
            $partialListaAmigos=$this->template->loadPartial('lista',
                array('listaAmigos' => $listaAmigos,
                      'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                      'haAmigos' => $dadosPaginados['proxima_pagina']),
                'usuario/amigos'
            );
            $dadosView= array('success' => true,'partialListaAmigos' => $partialListaAmigos, 'totalAmigos' => $totalAmigos);
        }catch(cassandra_NotFoundException $e){
            $dadosView= array('success' => false, 'totalAmigos' => 0);
        }
        $this->_renderTemplateHome('usuario/amigos/index', $dadosView);
    }




    public function proxima_pagina($inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }



            $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
            $amigosDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
            $filtros= array('count' => self::$_count+1,'usuario' => $usuarioAutenticado, 'opcao'=>'amigos');
            try {
                $listaAmigos = $amigosDao->recuperarTodos($inicio,'',$filtros);
                $this->load->helper('paginacao_cassandra');
                $dadosPaginados = create_paginacao_cassandra($listaAmigos, self::$_count);
                $response = array(
                    'success' => true,
                    'htmlListaAmigos' => $this->template->loadPartial(
                        'lista',
                        array(
                            'haAmigos'=> $dadosPaginados['proxima_pagina'],
                            'listaAmigos' => $listaAmigos,
                            'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina']
                        ),
                        'usuario/amigos'
                    ),
                    'paginacao' => $dadosPaginados
                );


            } catch(cassandra_NotFoundException $e) {
                $response = array('success' => false);
            }

        $json = Zend_Json::encode($response);
        echo $json;
    }

    public function remover($idAmigo)
    {
        try{
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $amizadeDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
            $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
            $amigo = $usuarioDao->recuperar($idAmigo);
            $idAmizade=$amizadeDao->gerarIdAmizade($usuarioAutenticado,$amigo);
            $amizadeRemovida=$amizadeDao->remover($idAmizade);
            print_r($amizadeRemovida->toArray());
            $this->load->helper('notificacao_js');
            $resposta=array('success' => true);
            $notificacoesFlash = create_notificacao_json(
                'sucesso',
                'Amizade Removida com Sucesso!'
            );
            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
        }catch(cassandra_NotFoundException $e){
            $resposta=array('success' => false);
            $notificacoesFlash = create_notificacao_json(
                'erro',
                'Falha Ao Remover Amizade!'
            );
            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
        }
        echo $resposta;

    }

    public function buscar()
    {

        $listaResultados=null;
        $buscaAtual = $this->input->get('busca');
        if($buscaAtual)
        {
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $filtros = array( 'busca' => $buscaAtual, 'count' => self::$_count + 1 );
            $listaResultados = $usuarioDao->recuperarTodos( 0, null, $filtros );
            $this->load->helper('paginacao_mysql');
            $paginacao = create_paginacao_mysql($listaResultados,0, self::$_count);
        }



        $dadosView = array(
            'formAction' => 'usuario/amigos/buscar',
            'txtBusca' => $buscaAtual,
            'haResultados' => ! empty($listaResultados),
            'resultadosBusca' => $this->template->loadPartial(
                'lista_busca',
                array( 'listaResultados' => $listaResultados),
                'usuario/amigos'
            )
        );

        if($buscaAtual)
        {
            $dadosView['haMaisPaginas'] = $paginacao['proxima_pagina'];
            $dadosView['inicioProxPagina'] = $paginacao['inicio_proxima_pagina'];
        }

        $this->_renderTemplateHome('usuario/amigos/buscar', $dadosView);
    }



    public function mais_resultados($inicio)
    {

        try{
            $texto= $this->input->get('busca');
            $listaResultados=null;
            $buscaAtual = $texto;
            $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $filtros = array( 'busca' => $buscaAtual, 'count' => self::$_count + 1 );
            $listaResultados = $usuarioDao->recuperarTodos( $inicio, null, $filtros );
            $this->load->helper('paginacao_mysql');
            $paginacao = create_paginacao_mysql($listaResultados,$inicio, self::$_count);


            $response = Zend_Json::encode(array(
                'htmlResultadosBusca' => $this->template->loadPartial(
                    'lista_busca',
                    array('listaResultados' => $listaResultados),
                    'usuario/amigos'
                ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {

            log_message('error', 'Ocorreu um erro ao tentar recuperar proxima página de resultados da busca: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;

    }



    protected function _renderTemplateHome($view = '', $dados = array())
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                $dados,
                'usuario/amigos'
            )
        );

        parent::_renderTemplateHome($view, $dados);
    }
}
