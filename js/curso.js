/**
 * Created by JetBrains PhpStorm.
 * User: Allan Baptista (allan.marques@ymail.com)
 * Date: 08/09/11
 * Time: 13:13
 */
(function(){
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
        $('#fil-imagem').live('change', function(   ){
            var url = WeLearn.url.siteURL('curso/curso/salvar_imagem_temporaria/');

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

        var divConfigCursoContainer = document.getElementById('curso-config-form-container');
        if (divConfigCursoContainer != null) {
            var $divConfigCursoContainer = $(divConfigCursoContainer),
                $fdsFormConfigCurso = $divConfigCursoContainer.find('fieldset'),
                $ulFormConfigCursoTabLinks = $divConfigCursoContainer.find('#curso-config-form-tab').find('a'),
                $btnConfigCurso = $('#btn-config-curso:hidden'),
                $formElems = $divConfigCursoContainer.find('input, textarea, select'),
                $fdsAtivo = null;

            $formElems.change(function() {
                $btnConfigCurso.show();
            });

            $fdsFormConfigCurso.hide(); $('#curso-config-form-wraper').show();

            $ulFormConfigCursoTabLinks.click(function(e) {
                e.preventDefault();

                var $fdsAtual = $($(this).attr('href')),
                    exibirAtual = function($atual) {
                        log($atual.data('ativo'));
                        if (typeof $atual.data('ativo') == 'undefined' || $atual.data('ativo') === 0) {

                            if ($fdsAtivo != null) {
                                $fdsAtivo.data('ativo', 0);
                            }

                            $fdsAtivo = $atual;
                            $atual.data('ativo', 1)
                                  .slideDown();
                        } else {
                            $atual.data('ativo', 0);
                        }
                    };

                if ($fdsAtivo != null) {
                    $fdsAtivo.slideUp(function() { exibirAtual($fdsAtual) });
                } else {
                    exibirAtual($fdsAtual);
                }
            });
        }

        $('#btn-form-curso, #btn-config-curso').click(function(e){
            e.preventDefault();

            var url = WeLearn.url.siteURL('curso/curso/salvar'),
                $sltsSegmentos = $(formCurso).find('#slt-area option:selected,#slt-segmento option:selected');

            $sltsSegmentos.each(function(){
                if( $(this).val() == '0' ) {
                    $(this).val('');
                }
            });

            WeLearn.validarForm(formCurso, url, function(res){
                if (typeof res.idNovoCurso != 'undefined') {
                    window.location = WeLearn.url.siteURL('/curso/' + res.idNovoCurso);
                } else {
                    window.location.reload();
                }
            });
        });
    }

    $('#a-curso-inscrever').click(function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            $this.attr('href'),
            {},
            function(res) {
                if (res.success) {

                    if ( res.atualizarPagina ) {

                        window.location.reload();

                    } else {

                        WeLearn.notificar( res.notificacao );

                        $this.replaceWith( res.elementoSubstituto )

                    }

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

    var $divConfirmacaoDesvincular = $('<div id="dialogo-confirmacao-dexvincular-curso">' +
                                       '<p>Tem certeza que deseja abandonar este curso?<br/>' +
                                       'Talvez você queira pensar mais um pouco sobre isso...</p></div>');
    $('#a-curso-desvincular').click(function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoDesvincular.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {

                                $divConfirmacaoDesvincular.dialog('close');

                                window.location.reload();

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

    $('a.a-aceitar-convite-gerenciamento').click(function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            $this.attr('href'),
            {},
            function(res){
                if (res.success) {

                    window.location = res.urlCurso;

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

    var $emTotalConvites = $('#em-total-convite-gerenciamento'),
        $divConfirmacaoRecusar = $('<div id="dialogo-confirmacao-recusar-convite-gerenciamento">' +
                                   '<p>Tem certeza que deseja recusar este convite?<br/>' +
                                   'Talvez você queira pensar mais um pouco sobre isso...</p></div>');
    $('a.a-recusar-convite-gerenciamento').click(function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoRecusar.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res){
                            if (res.success) {

                                $this.parent().parent().parent().fadeOut(function(){
                                    $(this).remove();
                                });

                                WeLearn.notificar(res.notificacao);

                                $emTotalConvites.text( parseInt( $emTotalConvites.text() - 1 ) );

                                $divConfirmacaoRecusar.dialog('close');

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

})();
