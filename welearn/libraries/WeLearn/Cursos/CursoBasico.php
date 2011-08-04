<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:57
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_CursoBasico extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_nome;

    /**
     * @var string
     */
    protected $_tema;

    /**
     * @var string
     */
    protected $_descricao;

    /**
     * @var string
     */
    protected $_dataCriacao;

    /**
     * @var int
     */
    protected $_status;

    /**
     * @var WeLearn_Cursos_Segmento
     */
    protected $_segmento;

    /**
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->_dataCriacao = (string)$dataCriacao;
    }

    /**
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->_dataCriacao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->_descricao = (string)$descricao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
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
     * @param \WeLearn_Cursos_Segmento $segmento
     */
    public function setSegmento(WeLearn_Cursos_Segmento $segmento)
    {
        $this->_segmento = $segmento;
    }

    /**
     * @return \WeLearn_Cursos_Segmento
     */
    public function getSegmento()
    {
        return $this->_segmento;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = (int)$status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $tema
     */
    public function setTema($tema)
    {
        $this->_tema = (string)$tema;
    }

    /**
     * @return string
     */
    public function getTema()
    {
        return $this->_tema;
    }

    /**
     * @return void
     */
    public function alterarStatus()
    {
        //@TODO: Implementar este método!!
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
            'tema' => $this->getTema(),
            'descricao' => $this->getDescricao(),
            'dataCriacao' => $this->getDataCriacao(),
            'status' => $this->getStatus(),
            'segmento' => $this->getSegmento()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
