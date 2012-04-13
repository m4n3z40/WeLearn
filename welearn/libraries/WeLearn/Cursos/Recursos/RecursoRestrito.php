<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 03/04/12
 * Time: 17:22
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Cursos_Recursos_RecursoRestrito extends WeLearn_Cursos_Recursos_Recurso
{
    /**
     * @var WeLearn_Cursos_Conteudo_Aula
     */
    private $_aula;

    public function __construct(array $dados = null)
    {
        parent::__construct($dados);

        $this->setTipo( WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO );
    }

    /**
     * @param \WeLearn_Cursos_Conteudo_Aula $aula
     */
    public function setAula(WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $this->_aula = $aula;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Aula
     */
    public function getAula()
    {
        return $this->_aula;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        if ( $this->_aula instanceof WeLearn_Cursos_Conteudo_Aula ) {
            $selfArray['aula'] = $this->getAula()->toArray();
        }

        return $selfArray;
    }

    public function toCassandra()
    {
        $selfArray = parent::toCassandra();

        if ( $this->_aula instanceof WeLearn_Cursos_Conteudo_Aula ) {
            $selfArray['aula'] = $this->getAula()->getId();
        }

        return $selfArray;
    }
}
