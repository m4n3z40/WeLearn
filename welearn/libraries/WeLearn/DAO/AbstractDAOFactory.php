<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 02:52
 *
 * Description:
 *
 */

abstract class WeLearn_DAO_AbstractDAOFactory implements WeLearn_DAO_IDAOFactory
{
    /**
     * @static
     * @throws Exception
     *         |WeLearn_Base_LoaderNaoIniciadoException
     *         |WeLearn_Base_ParametroInvalidoException
     *         |WeLearn_DAO_DAOInvalidaException
     *         |WeLearn_DAO_DAONaoEncontradaException
     *         |WeLearn_DAO_CFNaoDefinidaException
     * @param string $nomeDao Nome da classe DAO
     * @param array|null $opcoes Opções que serão passadas como parâmetro
     *                           na inicialização da classe DAO e sua Column Family
     * @param boolean $DaoPadrao Indicador se a DAO à ser carregada extende a DAO padrão ou não.
     *
     * @return mixed
     */
    public static function create($nomeDao, array $opcoes = null, $DaoPadrao = true)
    {
        //Criação de DAO não funciona se o autoload não estiver iniciado
        if (!WeLearn_Base_AutoLoader::hasInitiated()) {
            throw new WeLearn_Base_LoaderNaoIniciadoException();
        }

        if (is_string($nomeDao) && $nomeDao !== '') {

            $classeDAO = ucfirst($nomeDao);

            //Se a classe não terminar com 'DAO', inválido, mas aqui é adicionado automaticamente
            if ('DAO' !== substr($classeDAO, -3)) {
                $classeDAO = $classeDAO . 'DAO';
            }

        } elseif (is_object($nomeDao) && is_subclass_of($nomeDao, 'WeLearn_DTO_IDTO')) {

            $nsSseparator = WeLearn_Base_Loader::getInstance()->getNamespaceSeparator();

            $classeComNamespace = explode($nsSseparator, get_class($nomeDao));

            //Ultimo elemento do array é o nome da classe
            $classeSomenteNome = $classeComNamespace[count($classeComNamespace) - 1];

            $classeDAO = $classeSomenteNome . 'DAO';

        } else {
            throw new WeLearn_Base_ParametroInvalidoException();
        }

        //Se a classe não foi definida
        if (!class_exists($classeDAO)) {
            spl_autoload_call($classeDAO); //Tenta carregar

            //Se ainda não estiver sido definida
            if (!class_exists($classeDAO)) {
                throw new WeLearn_DAO_DAONaoEncontradaException($classeDAO);
            }
        }

        //Se foi definida mas não extende a classe DAO padrão
        if ($DaoPadrao && !is_subclass_of($classeDAO, 'WeLearn_DAO_AbstractDAO')) {
            throw new WeLearn_DAO_DAOInvalidaException($classeDAO);
        }


        if ( ! $classeDAO::isSingletonInstanciado() ) {
            $DAOObject = $classeDAO::getInstanciaSingleton($classeDAO);

            if ($DaoPadrao) {
                //Se o nome da Column Family da DAO não foi definido, não continua
                if (is_null($DAOObject->getNomeCF())) {
                    throw new WeLearn_DAO_CFNaoDefinidaException($classeDAO);
                }

                //Rotina para criar o objeto que representa a Column Family (pode ser modificado)
                $CF = WL_Phpcassa::getInstance()->getColumnFamily($DAOObject->getNomeCF(), $opcoes);

                $DAOObject->setCF($CF);
            }
        } else {
            $DAOObject = $classeDAO::getInstanciaSingleton($classeDAO);
        }

        return $DAOObject;
    }
}