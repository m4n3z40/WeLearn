/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 19/10/11
 * Time: 02:15
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){
    var btnFormCategoriaForum = document.getElementById('btn-form-categoria-forum');
    if (btnFormCategoriaForum != null) {
        $(btnFormCategoriaForum).click(function(e){
            e.preventDefault();

            var formCategoria = document.getElementById('form-criar-categoria-forum'),
                url = WeLearn.url.siteURL('forum/categoria/salvar');
            if (formCategoria != null) {
                WeLearn.validarForm(formCategoria, url, function(res) {
                   if ( res.success ) {
                       window.location = WeLearn.url.siteURL('curso/' + res.idCurso + '/forum/categoria/listar');
                   }
                });
            }
        });
    }
});