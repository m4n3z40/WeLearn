/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 16/08/11
 * Time: 17:47
 * To change this template use File | Settings | File Templates.
 */

(function(){
    var $btnLogout = $('.logoutButton');

    $btnLogout.click(function(e){
        e.preventDefault();

        $.post(
            WeLearn.url.siteURL('usuario/usuario/logout'),
            function(result) {
                if (result.success) {
                   location.reload();
                }
            },
            'json'
        );
    });
})();