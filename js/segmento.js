/**
 * Created with JetBrains PhpStorm.
 * User: gevigier
 * Date: 29/05/12
 * Time: 21:14
 * To change this template use File | Settings | File Templates.
 */
(function(){

    var formSegmento = document.getElementById('form-criar-segmento');
    $('#btn-form-segmento').click(function(e){
        e.preventDefault();

        var url = $(formSegmento).attr('action');

        if (formSegmento != null) {
            WeLearn.validarForm(formSegmento, url, function(res) {
                window.location = WeLearn.url.siteURL('administracao/segmento/listar/');
            });
        }
    })();