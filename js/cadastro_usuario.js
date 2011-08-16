/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 13/08/11
 * Time: 14:18
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    var $btnCadastrar = $('button[name=cadastrar]');

    $btnCadastrar.click(function(e){
        e.preventDefault();

        var form = document.forms[1];

        WeLearn.validarForm(
            form,
            'http://welearn.com/usuario/validar_cadastro',
            function(result) {
                window.location = 'http://welearn.com/usuario/quickstart';
            }
        );
    });
});