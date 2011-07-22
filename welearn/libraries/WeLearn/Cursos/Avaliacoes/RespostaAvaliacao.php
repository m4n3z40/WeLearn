<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:00
 *
 * Description:
 *
 */

class WeLearn_Cursos_Avaliacoes_RespostaAvaliacao extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var WeLearn_Cursos_Avaliacoes_ControleAvaliacao
     */
    private $_controleAvaliacao;

    /**
     * @var WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao
     */
    private $_alternativa;

    /**
     * @param null|WeLearn_Cursos_Avaliacoes_ControleAvaliacao $controleAvaliacao
     * @param null|WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativa
     */
    public function __construct(WeLearn_Cursos_Avaliacoes_ControleAvaliacao $controleAvaliacao = null,
        WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativa = null)
    {
        $dados = array(
            'controleAvaliacao' => $controleAvaliacao,
            'alternativa' => $alternativa
        );

        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativa
     */
    public function setAlternativa(WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativa)
    {
        $this->_alternativa = $alternativa;
    }

    /**
     * @return \WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao
     */
    public function getAlternativa()
    {
        return $this->_alternativa;
    }

    /**
     * @param \WeLearn_Cursos_Avaliacoes_ControleAvaliacao $controleAvaliacao
     */
    public function setControleAvaliacao(WeLearn_Cursos_Avaliacoes_ControleAvaliacao $controleAvaliacao)
    {
        $this->_controleAvaliacao = $controleAvaliacao;
    }

    /**
     * @return \WeLearn_Cursos_Avaliacoes_ControleAvaliacao
     */
    public function getControleAvaliacao()
    {
        return $this->_controleAvaliacao;
    }


}
