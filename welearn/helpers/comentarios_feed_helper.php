<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 05/06/12
 * Time: 10:05
 * To change this template use File | Settings | File Templates.
 */
function carregar_comentarios($de='',$ate='',$count,$idFeed)
{
    try{

        $filtros = array('idFeed' => $idFeed , 'count' => $count);
        $comentarioFeedDao = WeLearn_DAO_DAOFactory::create('ComentarioFeedDAO');
        $comentarios = $comentarioFeedDao->recuperarTodos($de,$ate,$filtros);
        $dadosPaginados = create_paginacao_cassandra($comentarios,2);
        $dadosPaginados= array_reverse($dadosPaginados);
        $partialListaComentarios = get_instance()->template->loadPartial(
            'lista_comentarios',
            array('comentarios' => $comentarios),
            'usuario/feed/comentario'
        );
        $dadosView = array('paginacao' => $dadosPaginados,
                            'haMaisPaginas' => $dadosPaginados['proxima_pagina'],
                            'HTMLcomentarios' => $partialListaComentarios
                           );
        return $dadosView;
    }catch(cassandra_NotFoundException $e) {
        return $dadosView = array();
    }
}