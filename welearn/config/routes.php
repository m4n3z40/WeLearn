<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'Bem_vindo';
$route['404_override'] = '';

$route['curso'] = 'curso/curso/index';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'curso/curso/exibir/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/configurar'] = 'curso/curso/configurar/$1';
$route['curso/([a-z_]+)'] = 'curso/curso/$1';
$route['curso/sugestao'] = 'curso/sugestao/index';
$route['curso/sugestao/([a-z_]+)'] = 'curso/sugestao/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/forum'] = 'forum/forum/index/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/forum/categoria'] = 'forum/categoria/index/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/forum/categoria/([a-z_]+)'] = 'forum/categoria/$2/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/forum/post'] = 'forum/post/index/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/forum/post/([a-z_]+)'] = 'forum/post/$2/$1';
$route['curso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/forum/([a-z_]+)'] = 'forum/forum/$2/$1';
$route['curso/forum/listar/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/forum/listar/$1';
$route['curso/forum/criar/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/forum/criar/$1';
$route['curso/forum/([a-z_]+)'] = 'forum/forum/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */