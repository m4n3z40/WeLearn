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

    private $_mysql_tbl_name = 'usuarios';

    /**
     * @var ImagemUsuarioDAO
     */
    protected $_imagemDao;

    /**
     * @var DadosPessoaisUsuarioDAO
     */
    protected $_dadosPessoaisDao;

    /**
     * @var DadosProfissionaisUsuarioDAO
     */
    protected $_dadosProfissionaisDao;

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
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_emailUsuarioCF = $phpCassa->getColumnFamily('usuarios_email_usuario');

        $this->_imagemDao = WeLearn_DAO_DAOFactory::create('ImagemUsuarioDAO');
        $this->_dadosPessoaisDao = WeLearn_DAO_DAOFactory::create('DadosPessoaisUsuarioDAO');
        $this->_dadosProfissionaisDao = WeLearn_DAO_DAOFactory::create('DadosProfissionaisUsuarioDAO');
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
            $this->salvarConfiguracao( $dto->getConfiguracao() );
        }

        get_instance()->db->insert( $this->_mysql_tbl_name, $dto->toMySQL() );

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

        get_instance()->db->where( 'id', $dto->getId() )
                          ->update( $this->_mysql_tbl_name, $dto->toMySQL() );
    }

    /**
     * @param WeLearn_Usuarios_ImagemUsuario $imagem
     */
    public function salvarImagem(WeLearn_Usuarios_ImagemUsuario &$imagem)
    {
        $this->_imagemDao->salvar($imagem);
    }

    /**
     * @param WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function salvarDadosPessoais(WeLearn_Usuarios_DadosPessoaisUsuario &$dadosPessoais)
    {
        $this->_dadosPessoaisDao->salvar($dadosPessoais);
    }

    /**
     * @param WeLearn_Usuarios_DadosProfissionaisUsuario $dadosProfissionais
     */
    public function salvarDadosProfissionais(WeLearn_Usuarios_DadosProfissionaisUsuario &$dadosProfissionais)
    {
        $this->_dadosProfissionaisDao->salvar($dadosProfissionais);
    }

    /**
     * @param WeLearn_Usuarios_ConfiguracaoUsuario $configuracao
     */
    public function salvarConfiguracao(WeLearn_Usuarios_ConfiguracaoUsuario &$configuracao)
    {
        $this->_configuracaoDao->salvar($configuracao);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        $db = get_instance()->db;

        $termo = '';
        if ( isset( $filtros['busca'] ) ) {
            $termo = $filtros['busca'];
        }

        $count = 10;
        if ( isset( $filtros['count'] ) ) {
            $count = $filtros['count'];
        }

        $db->like( 'id', $termo )
           ->or_like( 'nome', $termo )
           ->or_like( 'sobrenome', $termo )
           ->or_like( 'email', $termo )
           ->select( 'id' )
           ->order_by( 'nome', 'desc' )
           ->order_by( 'data_cadastro', 'desc' )
           ->limit( $count, $de );

        if ( isset( $filtros['segmento'] ) ) {
            $db->where( 'segmento_id', $filtros['segmento']->getId() )
               ->group_by( 'segmento_id' );
        }

        if ( isset( $filtros['area'] ) ) {
            $db->where( 'area_id', $filtros['area']->getId() )
               ->group_by( 'area_id' );
        }

        $sqlData = $db->get( $this->_mysql_tbl_name )->result();

        $idArray = array();
        foreach ($sqlData as $row) {
            $idArray[] = $row->id;
        }

        $columns = $this->_cf->multiget( $idArray );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $column = $this->_cf->get($id);

        $usuario = $this->_criarFromCassandra( $column );

        return $usuario;
    }



    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     */
    public function recuperarImagem(WeLearn_Usuarios_Usuario &$usuario)
    {
        $usuario->setImagem( $this->_imagemDao->recuperar( $usuario->getId() ) );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     */
    public function recuperarDadosPessoais(WeLearn_Usuarios_Usuario &$usuario)
    {
        $usuario->setDadosPessoais( $this->_dadosPessoaisDao->recuperar( $usuario->getId() ) );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     */
    public function recuperarDadosProfissionais(WeLearn_Usuarios_Usuario &$usuario)
    {
        $usuario->setDadosProfissionais( $this->_dadosProfissionaisDao->recuperar( $usuario->getId() ) );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     */
    public function recuperarConfiguracao(WeLearn_Usuarios_Usuario &$usuario)
    {
        $usuario->setConfiguracao( $this->_configuracaoDao->recuperar( $usuario->getId() ) );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return get_instance()->db->count_all( $this->_mysql_tbl_name );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $usuarioRemovido = $this->recuperar($id);

        $this->_cf->remove($id);
        $usuarioRemovido->setImagem( $this->_imagemDao->remover( $id ) );
        $usuarioRemovido->setDadosPessoais( $this->_dadosPessoaisDao->remover( $id ) );
        $usuarioRemovido->setDadosProfissionais( $this->_dadosProfissionaisDao->remover( $id ) );
        $usuarioRemovido->setConfiguracao( $this->_configuracaoDao->remover( $id ) );

        $usuarioRemovido->setPersistido(false);

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

    /**
     * @param $dados
     * @return WeLearn_Usuarios_GerenciadorPrincipal
     */
    public function criarGerenciadorPrincipal($dados = null)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_GerenciadorPrincipal($dados);
        }

        return new WeLearn_Usuarios_GerenciadorPrincipal($dados);
    }

    /**
     * @param $dados
     * @return WeLearn_Usuarios_GerenciadorAuxiliar
     */
    public function criarGerenciadorAuxiliar($dados = null)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_GerenciadorAuxiliar($dados);
        }

        return new WeLearn_Usuarios_GerenciadorAuxiliar($dados);
    }

    /**
     * @param $dados
     * @return WeLearn_Usuarios_Aluno
     */
    public function criarAluno($dados = null)
    {
        if ($dados instanceof WeLearn_Usuarios_Usuario) {
            $dados = $this->_extrairDadosUsuarioParaArray($dados);
            return new WeLearn_Usuarios_Aluno($dados);
        }

        return new WeLearn_Usuarios_Aluno($dados);
    }

    /**
     * @param string $usuario
     * @return bool
     */
    public function usuarioCadastrado($usuario)
    {
        try {
            $this->_cf->get((string)$usuario);
            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public function emailCadastrado($email)
    {
        try {
            $this->_emailUsuarioCF->get((string)$email);
            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     */
    public function registrarPassouPeloQuickstart(WeLearn_Usuarios_Usuario $usuario)
    {
        $this->_cf->insert( $usuario->getId(), array('passouPeloQuickstart' => 1) );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return bool
     */
    public function passouPeloQuickstart(WeLearn_Usuarios_Usuario $usuario)
    {
        try {
            $this->_cf->get( $usuario->getId(), array('passouPeloQuickstart') );
            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }

    /**
     * @param string $usuario
     * @param string $senha
     * @return WeLearn_DTO_IDTO
     * @throws WeLearn_Usuarios_AutenticacaoLoginInvalidoException|WeLearn_Usuarios_AutenticacaoSenhaInvalidaException
     */
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

    /**
     * @return \DadosPessoaisUsuarioDAO
     */
    public function getDadosPessoaisDao()
    {
        return $this->_dadosPessoaisDao;
    }

    /**
     * @return \DadosProfissionaisUsuarioDAO
     */
    public function getDadosProfissionaisDao()
    {
        return $this->_dadosProfissionaisDao;
    }

    /**
     * @return \ImagemUsuarioDAO
     */
    public function getImagemDao()
    {
        return $this->_imagemDao;
    }

    /**
     * @return \SegmentoDAO
     */
    public function getSegmentoDao()
    {
        return $this->_segmentoDao;
    }

    /**
     * @param $column
     * @return WeLearn_DTO_IDTO
     */
    protected function _criarFromCassandra( $column )
    {
        $column['segmentoInteresse'] = $this->_segmentoDao->recuperar(
            $column['segmentoInteresse']
        );

        $usuario = $this->criarNovo();

        $usuario->fromCassandra( $column );

        try {
            $this->recuperarConfiguracao( $usuario );
            $this->recuperarImagem( $usuario );
        } catch (cassandra_NotFoundException $e) { }

        return $usuario;
    }

    /**
     * @param $columns
     * @return array
     */
    protected function _criarVariosFromCassandra( $columns )
    {
        $listaUsuarios = array();

        foreach ($columns as $column) {
            $listaUsuarios[] = $this->_criarFromCassandra( $column );
        }

        return $listaUsuarios;
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return array
     */
    private function _extrairDadosUsuarioParaArray(WeLearn_Usuarios_Usuario $usuario)
    {
        $usuarioArray = array(
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