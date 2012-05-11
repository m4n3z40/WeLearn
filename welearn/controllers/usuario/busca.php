<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 20/04/12
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */
class busca extends Home_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template->appendJSImport('busca.js');
    }

    public function index()
    {

    }

    public function buscar()
    {
        $count = 10;
        $texto = $this->input->post('txt-search');

        $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

        $filtros = array( 'busca' => $texto, 'count' => $count + 1 );

        $listaUsuarios = $usuarioDao->recuperarTodos( 0, null, $filtros );

        $this->load->helper('paginacao_mysql');
        $dadosPaginados = create_paginacao_mysql($listaUsuarios,0, $count);

        $partialBuscarUsuario = $this->template->loadPartial(
            'lista_busca',
            array( 'ResultadoBusca' => $listaUsuarios),
            'usuario/home/busca'
        );

        if( ! $listaUsuarios ) {
            $success = false;
        } else {
            $success = true;
        }

        $dadosView = array(
           'listaUsuarios' => $partialBuscarUsuario,
            'paginacao' => $dadosPaginados,
            'texto' => $texto,
            'success' => $success
        );

        $this->_renderTemplateHome('usuario/home/busca/listar', $dadosView);
    }





    public function proxima_pagina($texto,$inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        $usuarioDao= WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $count=10;
        $filtros=array('id'=>$texto,'qtd'=>$count+1);
        try{
            $listaUsuarios=$usuarioDao->recuperarTodos($inicio,null,$filtros);
        }catch(cassandra_NotFoundException $e)
        {
            $listaUsuarios=array();
        }

        $this->load->helper('paginacao_mysql');
        $dadosPaginados = create_paginacao_mysql($listaUsuarios,0, $count);

        $response = array(
            'success' => true,
            'htmlListaUsuarios' => $this->template->loadPartial(
                'lista_busca',
                array(
                    'texto' => $texto,
                    'haMensagens'=> !empty($dadosPaginados),
                    'ResultadoBusca' => $listaUsuarios,
                    'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina']
                ),
                'usuario/home/busca'
            ),
            'paginacao' => $dadosPaginados
        );

        $json = Zend_Json::encode($response);
        echo $json;

    }
}
