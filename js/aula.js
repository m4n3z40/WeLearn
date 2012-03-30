(function(){

    $('#slt-aula-modulos').change(function(e){
        var idModulo = $(this).val();

        if (idModulo != '0') {
            window.location = WeLearn.url.siteURL('/curso/conteudo/aula/listar/' + idModulo);
        }
    });


})();