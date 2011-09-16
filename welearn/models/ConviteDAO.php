<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 11:08
 * To change this template use File | Settings | File Templates.
 */
 
class ConviteDAO extends WeLearn_DAO_AbstractDAO
{
         /**
         * @param mixed $id
         * @return WeLearn_DTO_IDTO
         */
        public function recuperar($id)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param mixed $de
         * @param mixed $ate
         * @param array|null $filtros
         * @return array
         */
        public function recuperarTodos($de = null, $ate = null, array $filtros = null)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param mixed $de
         * @param mixed $ate
         * @return int
         */
        public function recuperarQtdTotal($de = null, $ate = null)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param mixed $id
         * @return WeLearn_DTO_IDTO
         */
        public function remover($id)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param array|null $dados
         * @return WeLearn_DTO_IDTO
         */
        public function criarNovo(array $dados = null)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param WeLearn_DTO_IDTO $dto
         * @return boolean
         */
        protected function _atualizar(WeLearn_DTO_IDTO $dto)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param WeLearn_DTO_IDTO $dto
         * @return boolean
         */
        protected function _adicionar(WeLearn_DTO_IDTO &$dto)
        {
            // TODO: Implementar este metodo
        }

        public function salvar(WeLearn_DTO_IDTO &$dto)
        {
            return parent::salvar($dto);
        }

        public function getNomeCF()
        {
            return parent::getNomeCF();
        }

        public function getInfoColunas()
        {
            return parent::getInfoColunas();
        }

        public function getCf()
        {
            return parent::getCf();
        }

        public function setCf($cf)
        {
            parent::setCf($cf);
        }

        /**
         * @param ArrayofUsuarios $usuarios
         * @param array $dadosConvite
         * @return void
         */
        public function enviarCadastradoCurso(ArrayofUsuarios $usuarios, array $dadosConvite)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param array $usuarios
         * @param WeLearn_Cursos_Curso $Curso
         * @return void
         */
        public function retirarUsuariosVinculadosAoCurso(Array $usuarios, WeLearn_Cursos_Curso $Curso)
        {
            // TODO: Implementar este metodo
        }


        /**
         * @param array $visitantes
         * @param array $dadosConvite
         * @return void
         */
        public function enviarVisitanteCurso(Array $visitantes, Array $dadosConvite)
        {
            // TODO: Implementar este metodo
        }

        /**
         * @param array $visitantes
         * @return void
         */
        public function retirarUsuariosAtivosNoServico(Array $visitantes)
        {
            // TODO: Implementar este metodo
        }

}
