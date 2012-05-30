<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thiago
 * Date: 28/03/12
 * Time: 09:51
 * To change this template use File | Settings | File Templates.
 */
class Teste extends WL_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
        $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

        $area1 = $areaDao->criarNovo(array(
            'descricao' => 'Administração'
        ));

        $area2 = $areaDao->criarNovo(array(
            'descricao' => 'Informática'
        ));

        $segmento1 = $segmentoDao->criarNovo(array(
            'descricao' => 'Gestão de Pequenas Empresas',
            'area' => $area1
        ));

        $segmento2 = $segmentoDao->criarNovo(array(
            'descricao' => 'Empreendedorismo',
            'area' => $area1
        ));

        $segmento3 = $segmentoDao->criarNovo(array(
            'descricao' => 'Análise de Sistemas',
            'area' => $area2
        ));

        $segmento4 = $segmentoDao->criarNovo(array(
            'descricao' => 'Redes de Computadores',
            'area' => $area2
        ));

        $areaDao->salvar( $area1 );
        $areaDao->salvar( $area2 );

        $segmentoDao->salvar( $segmento1 );
        $segmentoDao->salvar( $segmento2 );
        $segmentoDao->salvar( $segmento3 );
        $segmentoDao->salvar( $segmento4 );
    }
}
