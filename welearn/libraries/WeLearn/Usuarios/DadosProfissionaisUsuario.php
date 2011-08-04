<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 15:15
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_DadosProfissionaisUsuario extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_usuarioId;

    /**
     * @var string
     */
    private $_escolaridade;

    /**
     * @var string
     */
    private $_escola;

    /**
     * @var string
     */
    private $_faculdade;

    /**
     * @var string
     */
    private $_curso;

    /**
     * @var string
     */
    private $_diploma;

    /**
     * @var int
     */
    private $_ano;

    /**
     * @var string
     */
    private $_profissao;

    /**
     * @var string
     */
    private $_empresa;

    /**
     * @var string
     */
    private $_siteEmpresa;

    /**
     * @var string
     */
    private $_cargo;

    /**
     * @var string
     */
    private $_descricaoTrabalho;

    /**
     * @var string
     */
    private $_habilidadesProfissionais;

    /**
     * @var string
     */
    private $_interessesProfissionais;

    /**
     * @var WeLearn_Cursos_Segmento
     */
    private $_segmentoTrabalho;

    /**
     * @param $ano int
     * @return void
     */
    public function setAno($ano)
    {
        $this->_ano = (int)$ano;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->_ano;
    }

    /**
     * @param $cargo string
     * @return void
     */
    public function setCargo($cargo)
    {
        $this->_cargo = (string)$cargo;
    }

    /**
     * @return string
     */
    public function getCargo()
    {
        return $this->_cargo;
    }

    /**
     * @param $curso string
     * @return void
     */
    public function setCurso($curso)
    {
        $this->_curso = (string)$curso;
    }

    /**
     * @return string
     */
    public function getCurso()
    {
        return $this->_curso;
    }

    /**
     * @param $descricaoTrabalho string
     * @return void
     */
    public function setDescricaoTrabalho($descricaoTrabalho)
    {
        $this->_descricaoTrabalho = (string)$descricaoTrabalho;
    }

    /**
     * @return string
     */
    public function getDescricaoTrabalho()
    {
        return $this->_descricaoTrabalho;
    }

    /**
     * @param $diploma string
     * @return void
     */
    public function setDiploma($diploma)
    {
        $this->_diploma = (string)$diploma;
    }

    /**
     * @return string
     */
    public function getDiploma()
    {
        return $this->_diploma;
    }

    /**
     * @param $empresa string
     * @return void
     */
    public function setEmpresa($empresa)
    {
        $this->_empresa = (string)$empresa;
    }

    /**
     * @return string
     */
    public function getEmpresa()
    {
        return $this->_empresa;
    }

    /**
     * @param $escola string
     * @return void
     */
    public function setEscola($escola)
    {
        $this->_escola = (string)$escola;
    }

    /**
     * @return string
     */
    public function getEscola()
    {
        return $this->_escola;
    }

    /**
     * @param $escolaridade string
     * @return void
     */
    public function setEscolaridade($escolaridade)
    {
        $this->_escolaridade = (string)$escolaridade;
    }

    /**
     * @return string
     */
    public function getEscolaridade()
    {
        return $this->_escolaridade;
    }

    /**
     * @param $faculdade string
     * @return void
     */
    public function setFaculdade($faculdade)
    {
        $this->_faculdade = (string)$faculdade;
    }

    /**
     * @return string
     */
    public function getFaculdade()
    {
        return $this->_faculdade;
    }

    /**
     * @param $habilidadesProfissionais string
     * @return void
     */
    public function setHabilidadesProfissionais($habilidadesProfissionais)
    {
        $this->_habilidadesProfissionais = (string)$habilidadesProfissionais;
    }

    /**
     * @return string
     */
    public function getHabilidadesProfissionais()
    {
        return $this->_habilidadesProfissionais;
    }

    /**
     * @param $interessesProfissionais string
     * @return void
     */
    public function setInteressesProfissionais($interessesProfissionais)
    {
        $this->_interessesProfissionais = (string)$interessesProfissionais;
    }

    /**
     * @return string
     */
    public function getInteressesProfissionais()
    {
        return $this->_interessesProfissionais;
    }

    /**
     * @param $profissao string
     * @return void
     */
    public function setProfissao($profissao)
    {
        $this->_profissao = (string)$profissao;
    }

    /**
     * @return string
     */
    public function getProfissao()
    {
        return $this->_profissao;
    }

    /**
     * @param WeLearn_Cursos_Segmento $segmentoTrabalho
     * @return void
     */
    public function setSegmentoTrabalho(WeLearn_Cursos_Segmento $segmentoTrabalho)
    {
        $this->_segmentoTrabalho = $segmentoTrabalho;
    }

    /**
     * @return WeLearn_Cursos_Segmento
     */
    public function getSegmentoTrabalho()
    {
        return $this->_segmentoTrabalho;
    }

    /**
     * @param string $siteEmpresa
     * @return void
     */
    public function setSiteEmpresa($siteEmpresa)
    {
        $this->_siteEmpresa = (string)$siteEmpresa;
    }

    /**
     * @return string
     */
    public function getSiteEmpresa()
    {
        return $this->_siteEmpresa;
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

    public function toArray()
    {
        return array(
            'usuarioId' => $this->getUsuarioId(),
            'escolaridade' => $this->getEscolaridade(),
            'escola' => $this->getEscola(),
            'faculdade' => $this->getFaculdade(),
            'curso' => $this->getCurso(),
            'diploma' => $this->getDiploma(),
            'ano' => $this->getAno(),
            'profissao' => $this->getProfissao(),
            'empresa' => $this->getEmpresa(),
            'siteEmpresa' => $this->getSiteEmpresa(),
            'cargo' => $this->getCargo(),
            'descricaoTrabalho' => $this->getDescricaoTrabalho(),
            'habilidadesProfissionais' => $this->getHabilidadesProfissionais(),
            'interessesProfissionais' => $this->getInteressesProfissionais(),
            'segmentoTrabalho' => $this->getSegmentoTrabalho()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
