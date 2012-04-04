<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thiago
 * Date: 28/03/12
 * Time: 09:51
 * To change this template use File | Settings | File Templates.
 */
class Teste extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {



        for($i=10;$i<20;$i++)
        {
        $usuarioDao=WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioDestinatario=$usuarioDao->criarNovo();
        $usuarioDestinatario->setId('thiago');
        $usuarioRemente=$usuarioDao->criarNovo();
        $usuarioRemente->setId('m4n3z40');
        $mensagemDao=WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        $mensagemPessoal=$mensagemDao->criarNovo();
        $mensagemPessoal->setId('1');
        $mensagemPessoal->setMensagem('mensagem de teste'.$i);
        $mensagemPessoal->setRemetente($usuarioRemente);
        $mensagemPessoal->setDestinatario($usuarioDestinatario);
        $mensagemPessoal->setStatus(0);
        $mensagemDao->salvar($mensagemPessoal);
        }
        /*
        teste mensagem pessoal dao
              inserir
        recuperar

              $usuarioDao=WeLearn_DAO_DAOFactory::create('UsuarioDAO');
              $usuarioDestinatario=$usuarioDao->criarNovo();
              $usuarioDestinatario->setId('thiago');
                      $mensagemDao=WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
                      $resposta=$mensagemDao->recuperarListaAmigosMensagens($usuarioDestinatario);
                      print_r($resposta);
                      echo 'teste';


        $mensagemDao=WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        $chave=$mensagemDao->gerarChave('thiago','m4n3z40');
        $dados=$mensagemDao->recuperar($chave);
        print_r($dados);




        $usuarioDao=WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioDestinatario=$usuarioDao->criarNovo();
        $usuarioDestinatario->setId('thiago');
        $mensagemDao=WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
        $resposta=$mensagemDao->recuperarTodos(null,null,null);
        print_r($resposta);

*/
    }
}
