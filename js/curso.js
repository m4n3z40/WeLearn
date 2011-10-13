/**
 * Created by JetBrains PhpStorm.
 * User: Allan Baptista (allan.marques@ymail.com)
 * Date: 08/09/11
 * Time: 13:13
 */

$(document).ready(function(){
    $('#slt-area').change(function(e){
        var vazio = '<option value="0">Selecione uma área de segmento</option>';

        $(this).next('p.error').remove();

        if ($(this).val() == '0' || $(this).val() == '' ) {
            $('#slt-segmento').html(vazio);
            return;
        }

        $.get(
            WeLearn.url.siteURL('segmento/recuperar_lista/' + $(this).val()),
            null,
            function(res){
                if (res.success) {
                    var html = WeLearn.segmento.gerarOpcoesHTML(res.segmentos);

                    $('#slt-segmento').html(html);
                } else {
                    $('#slt-segmento').html(vazio);

                    var errors = res.errors;

                    for (var i = 0; i < errors.length; i++) {
                        $('select[name='+errors[i].field_name+']').after(
                            '<p class="error">'+errors[i].error_msg+'</p>'
                        );
                    }
                }
            }
        );
    });

    var formCurso = document.getElementById('form-curso');
    if (formCurso != null) {
        $('#fil-imagem').live('change', function(e){
            var url = WeLearn.url.siteURL('curso/curso/salvar_imagem_temporaria/');

            $.ajaxFileUpload({
                url: url,
                secureuri: false,
                fileElementId: $(this).attr('id'),
                dataType: 'json',
                timeout: 60 * 1000,
                success: function (res, status) {
                    if (res.success) {
                        WeLearn.notificar({
                            nivel: 'sucesso',
                            msg: 'Sua imagem foi carregada com sucesso!',
                            tempo: 5000
                        });

                        var $imagem = $('<figure><img src="'+ res.upload_data.imagem_url +'" /><figcaption>Pre-visualização da Imagem escolhida</figcaption></figure>'),
                            $img_holder = $('#upload-img-holder'),
                            hdn_imagem_container = document.getElementById('hdn-imagem-container'),
                            hdn_imagem_html = '<input type="hidden" name="imagem[id]" value="' + res.upload_data.imagem_id + '" />' +
                                              '<input type="hidden" name="imagem[ext]" value="' + res.upload_data.imagem_ext + '" />';

                        if ( hdn_imagem_container == null ) {
                            $('<div id="hdn-imagem-container" />').addClass('hidden').appendTo(formCurso);
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
                            nivel: 'erro',
                            msg: res.error_msg,
                            tempo: 10000
                        });
                    }
                },
                error: function (res, status) {
                    WeLearn.notificar({
                        nivel: 'erro',
                        msg: 'Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.',
                        tempo: 10000
                    });
                }
            });
        });

        $('#btn-form-curso').click(function(e){
            e.preventDefault();

            var url = WeLearn.url.siteURL('curso/curso/salvar'),
                $sltsSegmentos = $(formCurso).find('#slt-area option:selected,#slt-segmento option:selected');

            $sltsSegmentos.each(function(){
                if( $(this).val() == '0' ) {
                    $(this).val('');
                }
            });

            WeLearn.validarForm(formCurso, url, function(res){
                window.location = WeLearn.url.siteURL('/curso/' + res.idNovoCurso);
            });
        });
    }
});
