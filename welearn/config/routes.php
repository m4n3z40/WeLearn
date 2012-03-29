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
$route['curso/configurar/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'curso/curso/configurar/$1';
$route['curso/([a-z_]+)'] = 'curso/curso/$1';
$route['curso/sugestao'] = 'curso/sugestao/index';
$route['curso/sugestao/([a-z_]+)'] = 'curso/sugestao/$1';
$route['curso/forum/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/forum/index/$1';
$route['curso/forum/categoria/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/categoria/index/$1';
$route['curso/forum/categoria/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/categoria/$1/$2';
$route['curso/forum/post/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/post/index/$1';
$route['curso/forum/post/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/post/$1/$2';
$route['curso/forum/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'forum/forum/$1/$2';
$route['curso/forum/([a-z_]+)'] = 'forum/forum/$1';
$route['curso/enquete/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'enquete/enquete/index/$1';
$route['curso/enquete/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'enquete/enquete/$1/$2';
$route['curso/enquete/([a-z_]+)'] = 'enquete/enquete/$1';
$route['curso/conteudo/modulo/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'conteudo/modulo/index/$1';
$route['curso/conteudo/modulo/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] ='conteudo/modulo/$1/$2';
$route['curso/conteudo/aula/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'conteudo/aula/index/$1';
$route['curso/conteudo/aula/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] ='conteudo/aula/$1/$2';
$route['curso/conteudo/recurso/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'conteudo/recurso/index/$1';
$route['curso/conteudo/recurso/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] ='conteudo/recurso/$1/$2';
$route['curso/conteudo/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'conteudo/conteudo/index/$1';
$route['curso/conteudo/([a-z_]+)/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})'] = 'conteudo/conteudo/$1/$2';
$route['usuario/perfil'] = 'usuario/perfil/index';
$route['usuario/perfil/([a-z_]+)'] = 'usuario/perfil/$1';
$route['usuario/([a-z_]+)'] = 'usuario/usuario/$1';
/* End of file routes.php */
/* Location: ./application/config/routes.php */