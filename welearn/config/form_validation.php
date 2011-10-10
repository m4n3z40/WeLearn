<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 12/08/11
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */

$config = array(
    'usuario/login' => array(
        array(
            'field' => 'login',
            'label' => 'Nome de Usuário ou Email',
            'rules' => 'required|min_length[5]|max_length[80]'
        ),
        array(
            'field' => 'password',
            'label' => 'Senha',
            'rules' => 'required|min_length[6]|max_length[24]'
        )
    ),
    'usuario/validar_cadastro' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'required|min_length[5]|max_length[45]|strip_tags'
        ),
        array(
            'field' => 'sobrenome',
            'label' => 'Sobrenome',
            'rules' => 'max_length[60]|strip_tags'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|max_length[80]|valid_email|strip_tags'
        ),
        array(
            'field' => 'nomeUsuario',
            'label' => 'Nome de usuário',
            'rules' => 'required|min_length[5]|max_length[40]|alpha_numeric'
        ),
        array(
            'field' => 'senha',
            'label' => 'Senha',
            'rules' => 'required|min_length[6]|max_length[24]'
        ),
        array(
            'field' => 'senhaConfirm',
            'label' => 'Confirme a Senha',
            'rules' => 'required|min_length[6]|max_length[24]|matches[senha]'
        ),
        array(
            'field' => 'area',
            'label' => 'Área de interesse',
            'rules' => 'required|max_length[80]'
        ),
        array(
            'field' => 'segmento',
            'label' => 'Segmento de interesse',
            'rules' => 'required|max_length[80]'
        )
    ),
    'segmento/adicionar' => array(
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'required|min_length[3]|max_length[80]|trim|strip_tags'
        )
    ),
    'area/adicionar' => array(
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'required|min_length[3]|max_length[80]|trim|strip_tags'
        )
    ),
    'sugestao/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome do Curso',
            'rules' => 'required|min_length[5]|max_length[80]|trim|strip_tags'
        ),
        array(
            'field' => 'tema',
            'label' => 'Tema do Curso',
            'rules' => 'required|min_length[5]|max_length[256]|trim|strip_tags'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'max_length[2048]|strip_tags'
        ),
        array(
            'field' => 'area',
            'label' => 'Área de Segmento',
            'rules' => 'required'
        ),
        array(
            'field' => 'segmento',
            'label' => 'Segmento do Curso',
            'rules' => 'required'
        )
    ),
    'curso/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome do Curso',
            'rules' => 'required|min_length[5]|max_length[80]|trim|strip_tags'
        ),
        array(
            'field' => 'tema',
            'label' => 'Tema do Curso',
            'rules' => 'required|min_length[5]|max_length[256]|trim|strip_tags'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'max_length[2048]|strip_tags'
        ),
        array(
            'field' => 'objetivos',
            'label' => 'Objetivos',
            'rules' => 'max_length[4096]'
        ),
        array(
            'field' => 'conteudoProposto',
            'label' => 'Conteúdo Proposto',
            'rules' => 'max_length[4096]'
        ),
        array(
            'field' => 'area',
            'label' => 'Área de Segmento',
            'rules' => 'required'
        ),
        array(
            'field' => 'segmento',
            'label' => 'Segmento do Curso',
            'rules' => 'required'
        ),
        array(
            'field' => 'tempoDuracaoMax',
            'label' => 'Tempo máximo de duração do curso',
            'rules' => 'required|integer|greater_than[0]',
        ),
        array(
            'field' => 'privacidadeConteudo',
            'label' => 'Privacidade de Conteúdo',
            'rules' => 'required'
        ),
        array(
            'field' => 'privacidadeInscricao',
            'label' => 'Permissão de Inscrição',
            'rules' => 'required'
        )
    )
);