/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 16/08/11
 * Time: 15:39
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    var $btnLogin = $('button[name=submitLogin]');

    $btnLogin.click(function(e){
        e.preventDefault();

        var form = document.forms[0];

        WeLearn.validarForm(
            form,
            'http://welearn.com/usuario/login',
            function(result) {
                window.location = 'http://welearn.com/home';
            }
        );
    });
});