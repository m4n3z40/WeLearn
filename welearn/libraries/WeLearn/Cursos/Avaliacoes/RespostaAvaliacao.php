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
     * @var string
     */
    private $_controleAvaliacaoId;

    /**
     * @var WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao
     */
    private $_alternativa;

    /**
     * @param string $controleAvaliacaoId
     * @param null|WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativa
     */
    public function __construct($controleAvaliacaoId = '',
        WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativa = null)
    {
        $dados = array(
            'controleAvaliacaoId' => $controleAvaliacaoId,
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
     * @param string $controleAvaliacaoId
     */
    public function setControleAvaliacaoId($controleAvaliacaoId)
    {
        $this->_controleAvaliacaoId = (string)$controleAvaliacaoId;
    }

    /**
     * @return string
     */
    public function getControleAvaliacaoId()
    {
        return $this->_controleAvaliacaoId;
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
            'controleAvaliacao' => $this->getControleAvaliacaoId(),
            'alternativa' => $this->getAlternativa()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
