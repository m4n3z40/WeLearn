<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 12/08/11
 * Time: 10:18
 * To change this template use File | Settings | File Templates.
 */

class SegmentoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_segmento';

    /**
     * @var \ColumnFamily
     */
    protected $_segmentosEmAreaCF;

    /**
     * @var null|\WeLearn_DAO_AbstractDAO
     */
    protected $_areaDAO;

    public function __construct()
    {
        $this->_segmentosEmAreaCF = WL_Phpcassa::getInstance()->getColumnFamily('cursos_segmento_em_area');
        $this->_areaDAO = WeLearn_DAO_DAOFactory::create('AreaDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $dto->setId($this->_createSegmentoId($dto->getArea()->getId() . '__' . $dto->getDescricao()));

        $this->_cf->insert($dto->getId(), $dto->toCassandra());
        $this->_segmentosEmAreaCF->insert($dto->getArea()->getId(), array($dto->getId() => ''));
        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->_cf->insert($dto->getId(), $dto->toCassandra());
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        $count = (isset($filtros['count']) && is_int($filtros['count']))
                     ? $filtros['count']
                     : ColumnFamily::DEFAULT_COLUMN_COUNT;

        //Todos os segmentos em uma área específfica
        if (is_array($filtros) && isset($filtros['areaId'])) {
            $segmentos_em_area = $this->_segmentosEmAreaCF->get($filtros['areaId'], null, $de, $ate, false, $count);

            if (!empty($segmentos_em_area)) {
                $dados_segmentos = $this->_cf->multiget(array_keys($segmentos_em_area));

                if (!empty($dados_segmentos)) {
                    $area = $this->_areaDAO->recuperar($filtros['areaId']);

                    $listaSegmentos = array();
                    foreach ($dados_segmentos as $key => $columns) {
                        $columns['area'] = $area;

                        $segmento = new WeLearn_Cursos_Segmento();
                        $segmento->fromCassandra($columns);
                        $segmento->setPersistido(true);

                        $listaSegmentos[] = $segmento;
                    }

                    return $listaSegmentos;
                }
            }
        } else {//Todos os segmentos cadastrados no serviço.

            $dados_segmentos = $this->_cf->get_range($de, $ate, $count);

            if ( ! empty($dados_segmentos) ) {
               $listaSegmentos = array();
                foreach ($dados_segmentos as $key => $columns) {
                    $columns['area'] = $this->_areaDAO->recuperar($columns['area']);
                    
                    $segmento = new WeLearn_Cursos_Segmento();
                    $segmento->fromCassandra($columns);
                    $segmento->setPersistido(true);

                    $listaSegmentos[] = $segmento;
                }

                return $listaSegmentos;
            }
        }

        return false;
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $dados_segmento = $this->_cf->get($id);
        $dados_segmento['area'] = $this->_areaDAO->recuperar($dados_segmento['area']);

        $segmento = new WeLearn_Cursos_Segmento();
        $segmento->fromCassandra($dados_segmento);
        $segmento->setPersistido(true);
        
        return $segmento;
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
        // TODO: Implement remover() method.
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        $segmento = new WeLearn_Cursos_Segmento();
        $segmento->preencherPropriedades($dados);
        return $segmento;
    }

    private function _createSegmentoId($str)
    {
        if ( ! function_exists('convert_accented_characters') ) {
           get_instance()->load->helper('text');
        }

        return url_title(convert_accented_characters($str), 'underscore', true);
    }
}