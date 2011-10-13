<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 18:15
 * 
 * Description:
 *
 */
 
class UsuarioDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'usuarios_usuario';

    /**
     * @var ConfiguracaoUsuarioDAO
     */
    protected $_configuracaoDao;

    /**
     * @var SegmentoDAO
     */
    protected $_segmentoDao;

    /**
     * @var ColumnFamily
     */
    protected $_emailUsuarioCF;

    public function __construct()
    {
        $this->_emailUsuarioCF = WL_Phpcassa::getInstance()->getColumnFamily('usuarios_email_usuario');
        $this->_configuracaoDao = WeLearn_DAO_DAOFactory::create('ConfiguracaoUsuarioDAO');
        $this->_segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $dto->setId($dto->getNomeUsuario()); //Id = Nome de usuário
        $dto->setSenha(md5($dto->getSenha())); //Senha necessita ser encriptada.
        $dto->setDataCadastro(time());

        $this->_cf->insert($dto->getId(), $dto->toCassandra());

        //Adiciona o index do email para verificação no cadastro de usuarios
        $indexEmail = array(
            'usuarioId' => $dto->getId()
        );
        $this->_emailUsuarioCF->insert($dto->getEmail(), $indexEmail);

        //Salva a configuração padrão do usuário recem cadastrado.
        if ($dto->getConfiguracao()) {
            $this->_configuracaoDao->salvar($dto->getConfiguracao());
        }

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        if (strlen($dto->getSenha()) <= 24) { //Se a senha foi modificada, necessita encriptar novamente.
           $dto->setSenha(md5($dto->getSenha()));
        }

        $this->_cf->insert($dto->getId(), $dto->toCassandra());
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implement recuperarTodos() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $dados_usuario = $this->_cf->get($id);
        $dados_usuario['segmentoInteresse'] = $this->_segmentoDao->recuperar($dados_usuario['segmentoInteresse']);
        $dados_usuario['configuracao'] = $this->_configuracaoDao->recuperar($dados_usuario['id']);

        $usuario = new WeLearn_Usuarios_Usuario();
        $usuario->fromCassandra($dados_usuario);

        $usuario->setPersistido(true);
        return $usuario;
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implement recuperarQtdTotal() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $usuarioRemovido = $this->recuperar($id);

        $this->_cf->remove($id);

        return $usuarioRemovido;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Usuarios_Usuario($dados);
    }

    public function criarGerenciadorPrincipal($dados)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_GerenciadorPrincipal($dados);
        }

        return new WeLearn_Usuarios_GerenciadorPrincipal($dados);
    }

    public function criarGerenciadorAuxiliar($dados)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_GerenciadorAuxiliar($dados);
        }

        return new WeLearn_Usuarios_GerenciadorAuxiliar($dados);
    }

    public function criarModerador($dados)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_Moderador($dados);
        }

        return new WeLearn_Usuarios_Moderador($dados);
    }

    public function criarInstrutor($dados)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_Instrutor($dados);
        }

        return new WeLearn_Usuarios_Instrutor($dados);
    }

    public function criarAluno($dados)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_Aluno($dados);
        }

        return new WeLearn_Usuarios_Aluno($dados);
    }

    public function usuarioCadastrado($usuario)
    {
        try {
            $this->_cf->get((string)$usuario);
            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }

    public function emailCadastrado($email)
    {
        try {
            $this->_emailUsuarioCF->get((string)$email);
            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }

    public function autenticar($usuario, $senha)
    {
        $usuario = (string)$usuario;
        $senha = (string)$senha;

        try {
            //Se for um email.
            if (filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
                $emailUsuario = $this->_emailUsuarioCF->get($usuario);
                $objUsuario = $this->recuperar($emailUsuario['usuarioId']);
            } else {
                $objUsuario = $this->recuperar($usuario);
            }

            //Caso a senha for menor que 24 caracteres não é md5, necessário encriptar.
            $senha = (strlen($senha) <= 24) ? md5($senha) : $senha;
            
            if ($objUsuario->getSenha() == $senha) {
                return $objUsuario;
            } else {
                throw new WeLearn_Usuarios_AutenticacaoSenhaInvalidaException($senha);
            }
        } catch (cassandra_NotFoundException $e) {
            throw new WeLearn_Usuarios_AutenticacaoLoginInvalidoException($usuario);
        }
    }

    /**
     * @return \ConfiguracaoUsuarioDAO
     */
    public function getConfiguracaoDao()
    {
        return $this->_configuracaoDao;
    }

    private function _extrairDadosUsuarioParaArray(WeLearn_Usuarios_Usuario $usuario)
    {
        $usuarioArray =  array(
            'id' => $usuario->getId(),
            'nome' => $usuario->getNome(),
            'sobrenome' => $usuario->getSobrenome(),
            'email' => $usuario->getEmail(),
            'nomeUsuario' => $usuario->getNomeUsuario(),
            'senha' => $usuario->getSenha(),
            'dataCadastro' => $usuario->getDataCadastro(),
            'persistido' => $usuario->isPersistido()
        );

        if ( $usuario->getImagem() ) {
            $usuarioArray['imagem'] = $usuario->getImagem();
        }

        if ( $usuario->getDadosPessoais() ) {
            $usuarioArray['dadosPessoais'] = $usuario->getDadosPessoais();
        }

        if ( $usuario->getDadosProfissionais() ) {
            $usuarioArray['dadosProfissionais'] = $usuario->getDadosProfissionais();
        }

        if ( $usuario->getSegmentoInteresse() ) {
            $usuarioArray['segmentoInteresse'] = $usuario->getSegmentoInteresse();
        }

        if ( $usuario->getConfiguracao() ) {
            $usuarioArray['configuracao'] = $usuario->getConfiguracao();
        }

        return $usuarioArray;
    }
}