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

    $( "#enviarConvite" ).click(function(e) {
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
                                    }
                                }
        )});
})();