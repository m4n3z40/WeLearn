(function(){

    var formImagemUsuario = document.getElementById('form-etapa-3')
                         || document.getElementById('form-imagem-usuario');
    $('#fil-imagem').live('change', function(   ){
        var url = WeLearn.url.siteURL('/usuario/usuario/upload_imagem/');

        $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: $(this).attr('id'),
            dataType: 'json',
            timeout: 60 * 1000,
            success: function (res) {
                if (res.success) {
                    WeLearn.notificar({
                        nivel: 'success',
                        msg: 'Sua imagem foi carregada com sucesso!',
                        tempo: 5000
                    });

                    var $imagem = $('<figure><img src="'+ res.upload_data.imagem_url +'" /><figcaption>Pre-visualização da Imagem escolhida</figcaption></figure>'),
                        $img_holder = $('#upload-img-holder'),
                        hdn_imagem_container = document.getElementById('hdn-imagem-container'),
                        hdn_imagem_html = '<input type="hidden" name="imagem[id]" value="' + res.upload_data.imagem_id + '" />' +
                                          '<input type="hidden" name="imagem[ext]" value="' + res.upload_data.imagem_ext + '" />';

                    if ( hdn_imagem_container == null ) {
                        $('<div id="hdn-imagem-container" />').addClass('hidden').appendTo(formImagemUsuario);
                        hdn_imagem_container = document.getElementById('hdn-imagem-container');
                    } else {
                        $(hdn_imagem_container).empty();
                    }

                    $(hdn_imagem_container).append($(hdn_imagem_html));

                    $img_holder.hide('slow', function(){
                        $(this).empty()
                               .append($imagem)
                               .show('fast');
                    });
                } else {
                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.error_msg,
                        tempo: 5000
                    });
                }
            },
            error: function () {
                WeLearn.notificar({
                    nivel: 'error',
                    msg: 'Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.',
                    tempo: 5000
                });
            }
        });
    });

})();