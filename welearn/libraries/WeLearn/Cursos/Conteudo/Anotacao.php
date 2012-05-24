<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:24
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_Anotacao extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_conteudo;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_usuario;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_pagina;

    /**
     * @param null $dados
     */
    public function __construct($dados = null)
    {
        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $usuario
     */
    public function setUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        $this->_usuario = $usuario;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getUsuario()
    {
        return $this->_usuario;
    }

    /**
     * @param string $conteudo
     */
    public function setConteudo($conteudo)
    {
        $this->_conteudo = (string)$conteudo;
    }

    /**
     * @return string
     */
    public function getConteudo()
    {
        return $this->_conteudo;
    }

    /**
     * @param \WeLearn_Cursos_Conteudo_Pagina $pagina
     */
    public function setPagina(WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $this->_pagina = $pagina;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Pagina
     */
    public function getPagina()
    {
        return $this->_pagina;
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'conteudo' => $this->getConteudo(),
            'usuario' => $this->getUsuario()->toArray(),
            'pagina' => $this->getPagina()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @return array
     */
    public function toCassandra()
    {
        return array(
            $this->getUsuario()->getId() => $this->getConteudo()
        );
    }
}
