<?php

function carregar_anotacao_view($alunoAtual, $pagina = null)
{
    $ci =& get_instance();

    $anotacaoDao = WeLearn_DAO_DAOFactory::create('AnotacaoDAO');

    try {
        if ($pagina instanceof WeLearn_Cursos_Conteudo_Pagina) {
            $anotacaoAtual = $anotacaoDao->recuperarPorUsuario(
                $pagina,
                $alunoAtual
            );
        } else {
            $anotacaoAtual = null;
        }
    } catch (cassandra_NotFoundException $e) {
        $anotacaoAtual = null;
    }

    $dadosAnotacaoView = array(
        'pagina' => $pagina,
        'formAction' => '/curso/conteudo/exibicao/salvar_anotacao',
        'extraOpenForm' => 'id="exibicao-conteudo-anotacao-form"',
        'formHidden' => array(),
        'anotacaoAtual' => $anotacaoAtual
    );

    return $ci->template->loadPartial(
        'section_anotacao',
        $dadosAnotacaoView,
        'curso/conteudo/exibicao'
    );
}

function carragar_comentarios_view($pagina = null)
{
    $ci =& get_instance();

    $dadosFormComentario = array(
        'formAction' => 'conteudo/comentario/salvar',
        'extraOpenForm' => 'id="form-comentario-criar"',
        'formHidden' => array('acao' => 'criar', 'paginaId' => $pagina ? $pagina->getId() : ''),
        'assuntoAtual' => '',
        'txtComentarioAtual' => '',
        'idBotaoEnviar' => 'btn-form-comentario-criar',
        'txtBotaoEnviar' => 'Postar Comentário!'
    );

    $dadosComentariosView = array(
        'pagina' => $pagina,
        'formCriar' => $ci->template->loadPartial(
            'form',
            $dadosFormComentario,
            'curso/conteudo/comentario'
        )
    );

    return $ci->template->loadPartial(
        'section_comentarios',
        $dadosComentariosView,
        'curso/conteudo/exibicao'
    );
}

function carregar_infoetapa_view(WeLearn_Cursos_Conteudo_Modulo $modulo,
                                 $aula = null,
                                 $pagina = null,
                                 $avaliacao = null)
{
    $ci =& get_instance();
    $moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
    $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
    $paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');

    $ci->load->helper(array('modulo', 'aula', 'pagina'));

    try {
        $listaModulos = $moduloDao->recuperarTodosPorCurso(
            $modulo->getCurso()
        );
    } catch ( cassandra_NotFoundException $e ) {
        $listaModulos = array();
    }

    try {
        $listaAulas = $aulaDao->recuperarTodosPorModulo(
            $modulo
        );
    } catch (cassandra_NotFoundException $e) {
        $listaAulas = array();
    }


    try {
        $listaPaginas = $aula ? $paginaDao->recuperarTodosPorAula(
            $aula
        ) : array();
    } catch ( cassandra_NotFoundException $e ) {
        $listaPaginas = array();
    }

    $dadosInfoEtapaView = array(
        'modulo' => $modulo,
        'aula' => $aula,
        'pagina' => $pagina,
        'avaliacao' => $avaliacao,
        'selectModulos' => $ci->template->loadPartial(
            'select_modulos',
            array(
                'listaModulos' => lista_modulos_para_dados_dropdown( $listaModulos ),
                'moduloSelecionado' => $modulo->getId(),
                'extra' => 'id="slt-modulos"'
            ),
            'curso/conteudo'
        ),
        'selectAulas' => $ci->template->loadPartial(
            'select_aulas',
            array(
                'listaAulas' => lista_aulas_para_dados_dropdown( $listaAulas ),
                'aulaSelecionada' => $aula ? $aula->getId() : '0',
                'extra' => 'id="slt-aulas"'
            ),
            'curso/conteudo'
        ),
        'selectPaginas' => $ci->template->loadPartial(
            'select_paginas',
            array(
                'listaPaginas' => lista_paginas_para_dados_dropdown( $listaPaginas ),
                'paginaSelecionada' => $pagina ? $pagina->getId() : '0',
                'extra' => 'id="slt-paginas"'
            ),
            'curso/conteudo'
        )
    );

    return $ci->template->loadPartial(
        'section_info_etapa',
        $dadosInfoEtapaView,
        'curso/conteudo/exibicao'
    );
}

function carregar_recursos_view()
{
    $ci =& get_instance();

    return $ci->template->loadPartial(
        'section_recursos',
        array(),
        'curso/conteudo/exibicao'
    );
}