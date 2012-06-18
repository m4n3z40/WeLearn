(function(){

    var $divJanelaCertificado = $(
        '<div/>',
        {id: 'div-janela-visualizacao-certificado'}).dialog({
            autoOpen: false,
            resizable: false,
            draggable: false,
            modal: true,
            width: 610,
            title: 'Visualização de Certificado',
            position: 'top',
            show: 'fade',
            hide: 'fade',
            buttons: {
                'Sair' : function(){
                    $(this).dialog('close');
                }
            },
            beforeClose: function(){
                $(this).empty();
            }
        });

    $('a.a-exibir-certificado').click(function(e){
        e.preventDefault();

        var url = WeLearn.url.siteURL('/curso/certificado/exibir_aluno/' + $(this).data('id'));

        $.get(
            url,
            {},
            function(res) {
                if(res.success) {

                    $divJanelaCertificado
                        .html(res.htmlExibicao)
                        .dialog('option', 'height', $(window).height())
                        .dialog('open');

                } else {
                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.errors[0].error_msg,
                        tempo: 5000
                    });
                }
            }
        );
    });
})();