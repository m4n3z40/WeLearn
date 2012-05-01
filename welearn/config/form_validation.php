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
    ),
    'categoria/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome da categoria',
            'rules' => 'required|min_length[5]|max_length[80]'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descricao da categoria',
            'rules' => 'max_length[512]'
        )
    ),
    'forum/salvar' => array(
        array(
            'field' => 'titulo',
            'label' => 'Título',
            'rules' => 'required|min_length[5]|max_length[80]'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'max_length[512]'
        )
    ),
    'post/salvar' => array(
        array(
            'field' => 'titulo',
            'label' => 'Título',
            'rules' => 'min_length[5]|max_length[80]'
        ),
        array(
            'field' => 'conteudo',
            'label' => 'Conteúdo',
            'rules' => 'required|max_length[2048]'
        )
    ),
    'enquete/salvar' => array(
        array(
            'field' => 'questao',
            'label' => 'Questão',
            'rules' => 'required|max_length[1024]'
        ),
        array (
            'field' => 'dataExpiracao',
            'label' => 'Data de Expiração',
            'rules' => 'required|exact_length[10]'
        ),
        array (
            'field' => 'alternativas',
            'label' => 'Alternativas',
            'rules' => 'required|callback__validarQtdAlternativas'
        )
    ),
    'enquete/votar' => array(
        array(
            'field' => 'alternativaEscolhida',
            'label' => 'Alternativa',
            'rules' => 'required'
        )
    ),
    'modulo/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'required|min_length[5]|max_length[125]'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'required|max_length[1024]'
        ),
        array(
            'field' => 'objetivos',
            'label' => 'Objetivos',
            'rules' => 'required|max_length[1024]'
        )
    ),
    'aula/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'required|min_length[5]|max_length[125]'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'required|max_length[1024]'
        )
    ),
    'recurso/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'required|min_length[5]|max_length[125]'
        ),
        array(
            'field' => 'descricao',
            'label' => 'Descrição',
            'rules' => 'required|max_length[1024]'
        ),
        array(
            'field' => 'tipo',
            'label' => 'Tipo',
            'rules' => 'required'
        ),
        array(
            'field' => 'upload',
            'label' => 'Arquivo',
            'rules' => 'required'
        )
    ),
    'pagina/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'required|min_length[5]|max_length[125]'
        ),
        array(
            'field' => 'conteudo',
            'label' => 'Conteúdo',
            'rules' => 'required'
        )
    ),
    'mensagem/criar' => array(
        array(
            'field'=>'mensagem',
            'label'=>'Mensagem',
            'rules'=>'required|max_length[1024]'
        )
    ),
    'avaliacao/salvar' => array(
        array(
            'field' => 'nome',
            'label' => 'Nome',
            'rules' => 'required|max_length[155]'
        ),
        array(
            'field' => 'notaMinima',
            'label' => 'Nota Mínima',
            'rules' => 'required|numeric|greater_than[-1]|less_than[11]'
        ),
        array(
            'field' => 'tempoDuracaoMax',
            'label' => 'Tempo Máximo de Duração',
            'rules' => 'required|numeric|greater_than[-1]|less_than[181]'
        ),
        array(
            'field' => 'qtdTentativasPermitidas',
            'label' => 'Qtd. de Tentativas Permitidas',
            'rules' => 'required|numeric|greater_than[-1]|less_than[6]'
        ),
    ),
    'usuario/buscar' => array(
        array(
            'field' => 'txt-search',
            'label' => 'txt-search',
            'rules' => 'required|max_length[50]'
        )
    ),

    'convite/enviar' => array(
        array(
            'field'=>'txt-convite',
            'label'=>'txt-convite',
            'rules'=>'required|max_length[125]'
        )
    )
);


/************************************
 *
 *           CONCATENAÇÕES
 *
 ***********************************/

$config['curso/salvar_config'] = array_merge(
    $config['curso/salvar'],
    array(
        array(
            'field' => 'status',
            'label' => 'Status do Conteúdo do Curso',
            'rules' => 'required'
        ),
        array(
            'field' => 'permissaoCriacaoForum',
            'label' => 'Permissão de Criação de Fóruns',
            'rules' => 'required'
        ),
        array(
            'field' => 'permissaoCriacaoEnquete',
            'label' => 'Permissão de Criação de Enquetes',
            'rules' => 'required'
        )
    )
);