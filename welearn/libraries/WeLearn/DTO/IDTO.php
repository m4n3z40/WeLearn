<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:06
 *
 * Description:
 *
 */

/**
 *
 */
interface WeLearn_DTO_IDTO
{
    /**
     * Retorna se o Data Transfer Object já foi persistido ou não no Banco de Dados.
     *
     * @abstract
     * @return boolean
     */
    public function isPersistido();

    /**
     * Seta se o objeto é ou não persistido no Banco de Dados.
     *
     * @param $persistido boolean O indicador se o objeto é persistido ou não.
     * @return void
     */
    public function setPersistido($persistido);

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @abstract
     * @return array
     */
    public function toArray();

    /**
     * Converte os dados das propriedades do objeto para uma string representando
     * um objeto JSON.
     *
     * @return string
     */
    public function toJSON();

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @abstract
     * @return array
     */
    public function toCassandra();

    /**
     * Preenche os dados das propriedades do objeto com um array de dados criado pelo PHPCassa
     *
     * @abstract
     * @param array $dados
     * @return void
     */
    public function fromCassandra(array $dados);
}