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
        //$usuariosArray = array();
        //$listaUsuariosIndex = array();
        //$listaUsuariosIndex['usuarios'] = array();
        $usuarioDao= WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $testePerformanceIndexCF = WL_Phpcassa::getInstance()->getColumnFamily('usuarios_teste_performance');

        /*for($i=1; $i<=1000; $i++)
        {
            $usuarioNovo = $usuarioDao->criarNovo();
            $usuarioNovo->setId('Usuario' . $i);
            $usuarioNovo->setNome('Usuario' . $i);
            $usuarioNovo->setSobrenome('Sobrenome' . $i);
            $usuarioNovo->setEmail('email' . $i . '@email.com');
            $usuarioNovo->setNomeUsuario('Usuario' . $i);
            $usuarioNovo->setSenha('123456');
            $usuarioNovo->setDataCadastro(time());

            $usuariosArray[ $usuarioNovo->getId() ] = $usuarioNovo->toCassandra();
            $listaUsuariosIndex['usuarios'][ $usuarioNovo->getId() ] = '';
        }/**/

        $keys = array_keys( $testePerformanceIndexCF->get('usuarios', null, '', '', false, 1000) );

        $tempoTotal = 0;
        $usuariosArray = array();
        for ($i = 0; $i < 10; $i ++) {
            $t1 = microtime(true);

            for ($j = 0; $j < count($keys); $j++ ) {

                //$chavesRemover = array();
                //try {
                    $usuariosArray[] = $usuarioDao->getCF()->get( $keys[$i] );
                //} catch (cassandra_NotFoundException $e) {
                //    $chavesRemover[] = $keys[$i];
                //}

                //if (count($chavesRemover) > 0) {
                //    $testePerformanceIndexCF->remove('usuarios', $chavesRemover);
                //}

            }

            $t2 = microtime(true);
            $tempoTotal += $t2 - $t1;
        }
        $tempoMedio = $tempoTotal / 10;

        //$usuarioDao->getCF()->batch_insert( $usuariosArray );
        //$testePerformanceIndexCF->batch_insert( $listaUsuariosIndex );





        echo 'Tempo médio de execução:  ' . round($tempoMedio, 2) . 'ms';

        echo '<br><hr><pre>';
        print_r($usuariosArray);
        echo '</pre>';
    }
    /*
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
    }*/
}
