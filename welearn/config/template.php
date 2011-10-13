<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Allan Marques
 * Date: 07/08/11
 * Time: 03:20
 *
 */
 
$config['template_dir'] = APPPATH . 'templates/';

$config['default_template'] = 'perfil';

$config['template_data'] = array(
    'default' => array(
        'formLoginOpen' => form_open(),
        'formLoginClose' => form_close(),
    ),
    'perfil' => array(
        '_defaultPartials' => array('perfil/barra_usuario')
    ),
    'curso' => array(
        '_defaultPartials' => array(
            'perfil/barra_usuario',
            'curso/barra_lateral_esquerda',
            'curso/barra_lateral_direita'
        )
    )
);

$config['default_curso_img_uri'] = '/img/defaults/curso/header.jpg';

/* End of file template.php */
/* Location: ./application/config/template.php */