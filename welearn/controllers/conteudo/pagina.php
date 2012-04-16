<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 13/04/12
 * Time: 20:59
 * To change this template use File | Settings | File Templates.
 */
class Pagina extends WL_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('pagina.js');
    }

    public function index($idAula)
    {
        $this->listar($idAula);
    }

    public function listar($idAula)
    {
        try {
            $aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
            $aula = $aulaDao->recuperar( $idAula );

            $curso = $aula->getModulo()->getCurso();

            $dadosView = array(

            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/pagina/listar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir index de páginas: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function criar($idAula)
    {

    }

    public function alterar($idPagina)
    {

    }

    public function salvar()
    {

    }

    public function remover($idPagina)
    {

    }

    public function _criar($post)
    {

    }

    public function _alterar($post)
    {

    }

    private function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
    {
        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao(),
            'menuContexto' => $this->template->loadPartial('menu', array('idCurso'=> $curso->getId()), 'curso/conteudo')
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
    }
}
