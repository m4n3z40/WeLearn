<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 16:22
 *
 * Description:
 *
 */

class WeLearn_Usuarios_GerenciadorAuxiliar extends WeLearn_Usuarios_Aluno
{
    protected $_nivelAcesso = WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR;
}
