<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 15:10
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_ImagemUsuario extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_nome;

    /**
     * @var string
     */
    private $_extensao;

    /**
     * @var string
     */
    private $_diretorio;

    /**
     * @var string
     */
    private $_diretorioCompleto;

    /**
     * @var string
     */
    private $_usuarioId;

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = (string)$url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }


    /**
     * @param string $diretorio
     */
    public function setDiretorio($diretorio)
    {
        $this->_diretorio = (string)$diretorio;
    }

    /**
     * @return string
     */
    public function getDiretorio()
    {
        return $this->_diretorio;
    }

    /**
     * @param string $diretorioCompleto
     */
    public function setDiretorioCompleto($diretorioCompleto)
    {
        $this->_diretorioCompleto = (string)$diretorioCompleto;
    }

    /**
     * @return string
     */
    public function getDiretorioCompleto()
    {
        return $this->_diretorioCompleto;
    }

    /**
     * @param string $extensao
     */
    public function setExtensao($extensao)
    {
        $this->_extensao = (string)$extensao;
    }

    /**
     * @return string
     */
    public function getExtensao()
    {
        return $this->_extensao;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->_nome = (string)$nome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->_nome;
    }

    /**
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->_usuarioId = (string)$usuarioId;
    }

    /**
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->_usuarioId;
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
            'url' => $this->getUrl(),
            'nome' => $this->getNome(),
            'extensao' => $this->getExtensao(),
            'diretorio' => $this->getDiretorio(),
            'diretorioCompleto' => $this->getDiretorioCompleto(),
            'usuarioId' => $this->getUsuarioId(),
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
            'url' => $this->getUrl(),
            'nome' => $this->getNome(),
            'extensao' => $this->getExtensao(),
            'diretorio' => $this->getDiretorio(),
            'diretorioCompleto' => $this->getDiretorioCompleto(),
            'usuarioId' => $this->getUsuarioId()
        );
    }

    public function __toString()
    {
        return '<img src="' . $this->_url . '" alt="Imagem Atual do Usuário" />';
    }
}
