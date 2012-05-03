/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 25/04/12
 * Time: 17:55
 * To change this template use File | Settings | File Templates.
 */
(function(){



    $( "#convite-form" ).dialog({
        autoOpen: false,
        show: "blind",
        width: 400,
        height: 170
    });

    $( "#enviar-convite" ).click(function(e) {
        e.preventDefault();
        $( "#convite-form" ).dialog( "open");
        return false;
    });

    var formpost=document.getElementById('form-enviar-convite');
    $('#btn-form-convite').click(function(e){
            e.preventDefault();
            WeLearn.validarForm(formpost,
                                $(formpost).attr('action'),
                                function(res)
                                {
                                    if(res.success)
                                    {
                                       $('#convite-form').dialog("close");
                                       //slocation.reload();
                                    }
                                }
        )});


    $('.remover-convite').click(
        function(e)
        {
            e.preventDefault();
            var idConvite=$(this).parent().children('.id-convite').val();
            var idRemetente=$(this).parent().children('.id-remetente').val();
            var url=$(this).attr('href');
            url+='/'+idConvite;
            url+='/'+idRemetente;
            $.post(
                WeLearn.url.siteURL(url),
                function(result) {
                    if (result.success) {
                       alert('teste');
                       //location.reload();
                    } else {

                    }

                },
                'json'
            );
        }
    );
})();