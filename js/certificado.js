(function(){
    var $imagemSelecionada = $('#div-imagem-selecionada'),
        gerarHtmlPreview = function (upload) {
            var htmlPreview = '',
                url = upload.urlTemporaria;

            for (var prop in upload) {
                if ( upload.hasOwnProperty(prop) ) {
                    htmlPreview += '<input type="hidden" name="' + prop + '" value="' + upload[prop] + '">';
                }
            }
            htmlPreview += '<a href="#" id="a-trocar-imagem-certificado">Trocar Imagem</a>';
            htmlPreview += '<figure><img width="560" height="400" src="' + url +
                '" alt="Imagem Selecionada." title="Imagem Selecionada.">' +
                '<figcaption>Imagem Selecionada</figcaption></figure>';

            return htmlPreview;
        },
        $filImagem = $('#fil-imagem'),
        $dlContainerImagem = $filImagem.parent().parent();

    $filImagem.live('change', function(e){
        $.ajaxFileUpload({
            url: WeLearn.url.siteURL('/curso/certificado/salvar_upload_temporario'),
            secureuri: false,
            fileElementId: $(this).attr('id'),
            dataType: 'json',
            timeout: 60 * 1000,
            success: function (res, status) {
                if (res.success) {
                    WeLearn.notificar(res.notificacao);

                    $imagemSelecionada.data('assinatura-upload', res.upload.file_name)
                                      .hide()
                                      .html( gerarHtmlPreview(res.upload) )
                                      .fadeIn();

                    $dlContainerImagem.hide();
                } else {
                    WeLearn.exibirErros(res.errors);
                }
            },
            error: function (res, status) {
                WeLearn.notificar({
                    nivel: 'error',
                    msg: 'Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.',
                    tempo: 5000
                });
            }
        });

    });

    $('#a-trocar-imagem-certificado').live('click', function(e){
        e.preventDefault();

        $.get(
            WeLearn.url.siteURL('/curso/certificado/remover_upload_temporario'),
            { assinatura: $imagemSelecionada.data('assinatura-upload') },
            function(res) {
                if (res.success) {
                    $imagemSelecionada.fadeOut(function(){
                        $imagemSelecionada.empty();
                        $dlContainerImagem.fadeIn();
                    });
                } else {
                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.errors[0].error_msg,
                        tempo: 5000
                    });
                }
            }
        )
    });

    var formCertificado = document.getElementById('certificado-form-criar')
                       || document.getElementById('certificado-form-alterar');
    $('#btn-form-certificado').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formCertificado,
            $(formCertificado).attr('action'),
            function(res) {
                window.location = WeLearn.url.siteURL('/curso/certificado/' + res.idCurso);
            }
        )
    });

    var $divConfimacaoRemover = $('<div id="dialogo-confirmacao-remover-certificado">' +
                                  '<p>Tem certeza que deseja remover este certificado?<br/>' +
                                  'Esta ação <strong>NÃO</strong> poderá ser desfeita!'),
        $emTotalCertificados = $('#em-total-certificados');
    $('a.a-remover-certificado').click(function(e){
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href');

        $divConfimacaoRemover.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        url,
                        {},
                        function(res) {
                            if (res.success) {
                                $this.parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .fadeOut(function(){
                                        $(this).remove();
                                        $emTotalCertificados.text( parseInt( $emTotalCertificados.text() ) - 1 );
                                    });

                                WeLearn.notificar(res.notificacao);

                                $divConfimacaoRemover.dialog('close');
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 5000
                                });
                            }
                        }
                    );
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

    var $divExibicaoCertificado = $(
            '<div/>',
            {id : 'div-dialogo-exibicao-certificado'}
        ).dialog({
            autoOpen: false,
            resizable: false,
            width: 610,
            height: 600,
            title: 'Exibição de Certificado',
            beforeClose: function(){
                $(this).empty();
            },
            buttons: {
                Fechar: function() {
                    $(this).dialog('close');
                }
            }
        });

    $('a.a-exibir-certificado').click(function(e){
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href');

        $.get(
            url,
            {},
            function(res) {
                if (res.success) {
                        $divExibicaoCertificado.html(res.htmlExibicao)
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