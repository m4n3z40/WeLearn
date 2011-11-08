<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:12
 *
 * Description:
 *
 */

/**
 *
 */
abstract class WeLearn_DTO_AbstractDTO implements WeLearn_DTO_IDTO
{

    /**
     * @var boolean Indica se a o DTO foi ou não persistido no BD
     */
    protected $_persistido;

    /**
     * Inicializa o DTO com os dados passados por parâmetro
     *
     * @param array|null $dados Array que representa os valores das propriedades do objeto DTO
     */
    public function __construct(array $dados = null)
    {
        $this->_persistido = false;
        $this->preencherPropriedades($dados);
    }

    function __get($name)
    {
        $prefixos = array('is', 'get');
        $nome = ucfirst($name);

        foreach ($prefixos as $prefixo ) {
            $propriedade = $prefixo . $nome;
            if (method_exists($this, $propriedade)) {
                return $this->$propriedade();
            }
        }
        throw new WeLearn_DTO_PropriedadeInvalidaException($name);
    }

    function __set($name, $value)
    {
        $propriedade = 'set' . ucfirst($name);
        if (method_exists($this, $propriedade)) {
            $this->$propriedade($value);
        }
        throw new WeLearn_DTO_PropriedadeInvalidaException($name);
    }

    /**
     * Atribui os valores das propriedades do objeto de acordo com a relação chave => valor do array
     * Ex.:
     * $dados['propriedadeUm'] = 'Valor1';
     *
     * equivale á chamada ao metodo "setPropridedadeUm('Valor1')" da classe.
     *
     * @param array|null $dados Array que representa os valores das propriedades do objeto DTO
     */
    public function preencherPropriedades(array $dados = null)
    {
        if (!empty($dados)) {
            foreach ($dados as $campo => $valor) {

                $metodoSet = 'set' . ucfirst((string)$campo);

                if (method_exists($this, $metodoSet)) {

                    $this->$metodoSet($valor);

                }
            }
        }
    }

    /**
     * Retorna se o Data Transfer Object já foi persistido ou não no Banco de Dados.
     *
     * @return boolean
     */
    public function isPersistido()
    {
        return $this->_persistido;
    }

    /**
     * Seta se o objeto é ou não persistido no Banco de Dados.
     *
     * @param $persistido boolean O indicador se o objeto é persistido ou não.
     * @return void
     */
    public function setPersistido($persistido)
    {
        $this->_persistido = (boolean)$persistido;
    }

    /**
     * Converte os dados das propriedades do objeto para uma string representando
     * um objeto JSON.
     *
     * @return string
     */
    public function toJSON()
    {
        return Zend_Json::encode($this->toArray(), true);
    }

    /**
     * Preenche os dados das propriedades do objeto com um array de dados criado pelo PHPCassa
     *
     * @param array $dados
     * @return void
     */
    public function fromCassandra(array $dados)
    {
        $this->preencherPropriedades($dados);
        $this->setPersistido(true);
    }
}
