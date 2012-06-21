<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 15:14
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_Usuario extends WeLearn_DTO_AbstractDTO
                               implements Serializable, WeLearn_Usuarios_Autorizacao_Papel
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_nome;

    /**
     * @var string
     */
    protected $_sobrenome;

    /**
     * @var string
     */
    protected $_email;

    /**
     * @var string
     */
    protected $_nomeUsuario;

    /**
     * @var string
     */
    protected $_senha;

    /**
     * @var string
     */
    protected $_dataCadastro;

    /**
     * @var int
     */
    protected $_nivelAcesso = WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO;

    /**
     * @var WeLearn_Usuarios_ImagemUsuario
     */
    protected $_imagem;

    /**
     * @var WeLearn_Usuarios_DadosPessoaisUsuario
     */
    protected $_dadosPessoais;

    /**
     * @var WeLearn_Usuarios_DadosProfissionaisUsuario
     */
    protected $_dadosProfissionais;

    /**
     * @var WeLearn_Cursos_Segmento
     */
    protected $_segmentoInteresse;

    /**
     * @var WeLearn_Usuarios_ConfiguracaoUsuario
     */
    protected $_configuracao;

    /**
     * @param \WeLearn_Usuarios_ConfiguracaoUsuario $configuracao
     */
    public function setConfiguracao(WeLearn_Usuarios_ConfiguracaoUsuario $configuracao)
    {
        $this->_configuracao = $configuracao;
    }

    /**
     * @return \WeLearn_Usuarios_ConfiguracaoUsuario
     */
    public function getConfiguracao()
    {
        return $this->_configuracao;
    }

    /**
     * @param \WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function setDadosPessoais(WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais)
    {
        $this->_dadosPessoais = $dadosPessoais;
    }

    /**
     * @return \WeLearn_Usuarios_DadosPessoaisUsuario
     */
    public function getDadosPessoais()
    {
        return $this->_dadosPessoais;
    }

    /**
     * @param \WeLearn_Usuarios_DadosProfissionaisUsuario $dadosProfissionais
     */
    public function setDadosProfissionais(WeLearn_Usuarios_DadosProfissionaisUsuario $dadosProfissionais)
    {
        $this->_dadosProfissionais = $dadosProfissionais;
    }

    /**
     * @return \WeLearn_Usuarios_DadosProfissionaisUsuario
     */
    public function getDadosProfissionais()
    {
        return $this->_dadosProfissionais;
    }

    /**
     * @param string $dataCadastro
     */
    public function setDataCadastro($dataCadastro)
    {
        $this->_dataCadastro = (string)$dataCadastro;
    }

    /**
     * @return string
     */
    public function getDataCadastro()
    {
        return $this->_dataCadastro;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = (string)$email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param \WeLearn_Usuarios_ImagemUsuario $imagem
     */
    public function setImagem(WeLearn_Usuarios_ImagemUsuario $imagem)
    {
        $this->_imagem = $imagem;
    }

    /**
     * @return \WeLearn_Usuarios_ImagemUsuario
     */
    public function getImagem()
    {
        return $this->_imagem;
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
     * @param string $nomeUsuario
     */
    public function setNomeUsuario($nomeUsuario)
    {
        $this->_nomeUsuario = (string)$nomeUsuario;
    }

    /**
     * @return string
     */
    public function getNomeUsuario()
    {
        return $this->_nomeUsuario;
    }

    /**
     * @param \WeLearn_Cursos_Segmento $segmentoInteresse
     */
    public function setSegmentoInteresse(WeLearn_Cursos_Segmento $segmentoInteresse)
    {
        $this->_segmentoInteresse = $segmentoInteresse;
    }

    /**
     * @return \WeLearn_Cursos_Segmento
     */
    public function getSegmentoInteresse()
    {
        return $this->_segmentoInteresse;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha)
    {
        $this->_senha = (string)$senha;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->_senha;
    }

    /**
     * @param string $sobrenome
     */
    public function setSobrenome($sobrenome)
    {
        $this->_sobrenome = (string)$sobrenome;
    }

    /**
     * @return string
     */
    public function getSobrenome()
    {
        return $this->_sobrenome;
    }

    /**
     * @return int
     */
    public function getNivelAcesso()
    {
        return $this->_nivelAcesso;
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
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'sobrenome' => $this->getSobrenome(),
            'email' => $this->getEmail(),
            'nomeUsuario' => $this->getNomeUsuario(),
            'senha' => $this-> getSenha(),
            'dataCadastro' => $this->getDataCadastro(),
            'nivelAcesso' => $this->getNivelAcesso(),
            'imagem' => empty($this->_imagem) ? '' : $this->getImagem()->toArray(),
            'dadosPessoais' => empty($this->_dadosPessoais) ? '' : $this->getDadosPessoais()->toArray(),
            'dadosProfissionais' => empty($this->_dadosProfissionais) ? '' : $this->getDadosProfissionais()->toArray(),
            'segmentoInteresse' => empty($this->_segmentoInteresse) ? '' : $this->getSegmentoInteresse()->toArray(),
            'configuracao' => empty($this->_configuracao) ? '' : $this->getConfiguracao()->toArray(),
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
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'sobrenome' => $this->getSobrenome(),
            'email' => $this->getEmail(),
            'nomeUsuario' => $this->getNomeUsuario(),
            'senha' => $this->getSenha(),
            'dataCadastro' => $this->getDataCadastro(),
            'segmentoInteresse' => ($this->_segmentoInteresse instanceof WeLearn_Cursos_Segmento)
                                   ? $this->getSegmentoInteresse()->getId() : ''
        );
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or &null;
     */
    public function serialize()
    {
        $arrayUsuario = array(
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'sobrenome' => $this->getSobrenome(),
            'email' => $this->getEmail(),
            'nomeUsuario' => $this->getNomeUsuario(),
            'senha' => $this->getSenha(),
            'dataCadastro' => $this->getDataCadastro(),
            'nivelAcesso' => $this->getNivelAcesso(),
            'configuracao' => ($this->_configuracao instanceof WeLearn_Usuarios_ConfiguracaoUsuario)
                              ? $this->getConfiguracao()->toArray() : '',
            'segmentoInteresse' => ($this->_segmentoInteresse instanceof WeLearn_Cursos_Segmento)
                                   ? $this->getSegmentoInteresse()->toArray() : '',
            'imagem' => ($this->_imagem instanceof WeLearn_Usuarios_ImagemUsuario)
                        ? $this->getImagem()->toArray() : '',
            'persistido' => $this->isPersistido(),
        );

        return serialize($arrayUsuario);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return mixed the original value unserialized.
     */
    public function unserialize($serialized)
    {
        $arrayUsuario = unserialize($serialized);

        $area = new WeLearn_Cursos_Area();
        $area->preencherPropriedades($arrayUsuario['segmentoInteresse']['area']);
        $arrayUsuario['segmentoInteresse']['area'] = $area;

        $segmento = new WeLearn_Cursos_Segmento();
        $segmento->preencherPropriedades($arrayUsuario['segmentoInteresse']);
        $arrayUsuario['segmentoInteresse'] = $segmento;

        $configuracao = new WeLearn_Usuarios_ConfiguracaoUsuario($arrayUsuario['configuracao']);
        $arrayUsuario['configuracao'] = $configuracao;

        if ( isset( $arrayUsuario['imagem'] ) && is_array( $arrayUsuario['imagem'] ) ) {

            $imagem = new WeLearn_Usuarios_ImagemUsuario($arrayUsuario['imagem']);
            $arrayUsuario['imagem'] = $imagem;

        } else {

            unset( $arrayUsuario['imagem'] );

        }

        $this->__construct($arrayUsuario);

        $this->_nivelAcesso = $arrayUsuario['nivelAcesso'];
    }

    /**
     *
     */
    public function salvarImagem()
    {
        if ( ! $this->getImagem()->getUsuarioId() ) {
            $this->getImagem()->setUsuarioId( $this->getId() );
        }

        WeLearn_DAO_DAOFactory::create('UsuarioDAO')->salvarImagem( $this->getImagem() );
    }

    /**
     *
     */
    public function salvarDadosPessoais()
    {
        if ( ! $this->getDadosPessoais()->getUsuarioId() ) {
            $this->getDadosPessoais()->setUsuarioId( $this->getId() );
        }

        WeLearn_DAO_DAOFactory::create('UsuarioDAO')->salvarDadosPessoais( $this->getDadosPessoais() );
    }

    /**
     *
     */
    public function salvarDadosProfissionais()
    {
        if ( ! $this->getDadosProfissionais()->getUsuarioId() ) {
            $this->getDadosProfissionais()->setUsuarioId( $this->getId() );
        }

        WeLearn_DAO_DAOFactory::create('UsuarioDAO')->salvarDadosProfissionais( $this->getDadosProfissionais() );
    }

    /**
     *
     */
    public function salvarConfiguracao()
    {
        if ( ! $this->getConfiguracao()->getUsuarioId() ) {
            $this->getConfiguracao()->setUsuarioId( $this->getId() );
        }

        WeLearn_DAO_DAOFactory::create('UsuarioDAO')->salvarConfiguracao( $this->getConfiguracao() );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toHTML('imagem_grande');
    }

    /**
     * @param bool $pequena
     * @return string
     */
    private function _htmlImagem($tamanho = 'grande')
    {
        $imagemUrl = ( $this->_imagem instanceof WeLearn_Usuarios_ImagemUsuario )
                     ? $this->getImagem()->getUrl()
                     : site_url( get_instance()->config->item('default_userpic_img_uri') );

        $alt = $this->getNome() . ' ' . $this->getSobrenome();

        switch ( $tamanho ) {
            case 'mini':
                return "<img src=\"{$imagemUrl}\" alt=\"{$alt}\" title=\"{$alt}\" width=\"40\" height=\"40\">";
            case 'pequeno':
                return "<img src=\"{$imagemUrl}\" alt=\"{$alt}\" title=\"{$alt}\" width=\"80\" height=\"80\">";
            case 'grande':
            default:
                return "<img src=\"{$imagemUrl}\" alt=\"{$alt}\" title='{$alt}'>";
        }
    }

    /**
     * @param string $tamanho
     * @param string $linkPara
     * @return string
     */
    private function _htmlUsuario($tamanho = 'grande', $linkPara = '')
    {
        $htmlImagem = $this->_htmlImagem( $tamanho );
        $nomeCompleto = $this->getNome() . ' ' . $this->getSobrenome();

        if ( $linkPara ) {
            return "<figure class=\"fig-usuario\"><a href=\"{$linkPara}\" title=\"{$nomeCompleto}\">{$htmlImagem}<span>{$nomeCompleto}</span></a></figure>";
        } else {
            return "<figure class=\"fig-usuario\">{$htmlImagem}<span>{$nomeCompleto}</span></figure>";
        }
    }

    /**
     * @return string
     */
    private function _htmlLinkUsuario()
    {
        $nomeCompleto = $this->getNome() . ' ' . $this->getSobrenome();
        $id = $this->getId();
        $urlPerfil = site_url( '/perfil/' . $id );

        return "<a href=\"{$urlPerfil}\" title=\"{$nomeCompleto}\">{$nomeCompleto}</a>";
    }

    /**
     * @param string $tipo
     * @return string
     */
    public function toHTML($tipo = 'imagem_grande')
    {
        switch ( $tipo ) {
            case 'imagem_mini_sem_link':
                return $this->_htmlUsuario( 'mini' );
            case 'imagem_pequena_sem_link':
                return $this->_htmlUsuario( 'pequeno' );
            case 'imagem_grande_sem_link':
                return $this->_htmlUsuario( 'grande' );
            case 'imagem_mini_link_home':
                return $this->_htmlUsuario( 'mini', site_url( '/home' ) );
            case 'imagem_pequena_link_home':
                return $this->_htmlLinkUsuario( 'pequeno', site_url( '/home' ) );
            case 'imagem_grande_link_home':
                return $this->_htmlUsuario( 'grande', site_url( '/home' ) );
            case 'imagem_mini':
                return $this->_htmlUsuario( 'mini', site_url( '/perfil/' . $this->getId() ) );
            case 'imagem_pequena':
                return $this->_htmlUsuario( 'pequeno', site_url( '/perfil/' . $this->getId() ) );
            case 'somente_link':
                return $this->_htmlLinkUsuario();
            case 'imagem_grande':
            default:
                return $this->_htmlUsuario( 'grande', site_url( '/perfil/' . $this->getId() ) );
        }
    }

    /**
     * @return array
     */
    public function toMySQL()
    {
        return array(
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'sobrenome' => $this->getSobrenome(),
            'email' => $this->getEmail(),
            'segmento_id' => ($this->_segmentoInteresse instanceof WeLearn_Cursos_Segmento)
                             ? $this->getSegmentoInteresse()->getId() : '',
            'area_id' => ($this->_segmentoInteresse instanceof WeLearn_Cursos_Segmento)
                         && ($this->_segmentoInteresse->getArea() instanceof WeLearn_Cursos_Area)
                         ? $this->getSegmentoInteresse()->getArea()->getId() : ''
        );
    }
}