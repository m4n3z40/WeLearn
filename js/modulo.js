(function(){

    var formModulo = document.getElementById('modulo-criar-form') ||
                     document.getElementById('modulo-alterar-form');

    $('#btn-form-modulo').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formModulo,
            $(formModulo).attr('action'),
            function (res) {
                window.location = WeLearn.url.siteURL('/curso/conteudo/modulo/' + res.idCurso);
            }
        )
    });

    $('#ul-modulo-listar-lista')
        .accordion({
            header: '> li > header',
            collapsible: true,
            active: false,
            autoHeight: false
        })
       .sortable({
            placeholder: 'ui-state-highlight',
            axis: 'y',
            scroll: false,
            handle: 'header'
        })
       .disableSelection();
})();
