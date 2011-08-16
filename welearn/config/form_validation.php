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
            'rules' => 'required|min_length[5]|max_length[45]'
        ),
        array(
            'field' => 'sobrenome',
            'label' => 'Sobrenome',
            'rules' => 'max_length[60]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|max_length[80]|valid_email'
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
            'rules' => 'required|trim'
        )
    ),
    'area/adicionar' => array(
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'required|trim'
        )
    ),
);