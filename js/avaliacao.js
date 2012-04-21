(function(){

    var formAvaliacao = document.getElementById('form-avaliacao-criar')
                     || document.getElementById('form-avaliacao-alterar');

    $('#btn-form-avaliacao').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formAvaliacao,
            $(formAvaliacao).attr('action'),
            function(res) {
                window.location = WeLearn.url.siteURL('/curso/conteudo/avaliacao/exibir/' + res.idAvaliacao);
            }
        )
    });

})();