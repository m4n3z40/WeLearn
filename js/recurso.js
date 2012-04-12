(function(){

    var $sltModulos = $('#slt-modulos'),
        $sltAulas = $('#slt-aulas'),
        $sltTipo = $('#slt-tipo');

    $sltTipo.change(function(e){
        e.preventDefault();

        var $this = $(this);

        if ( $this.val() == '' ) {
            $ddArquivoRecurso.hide();
            $dtArquivoRecurso.hide();

            return;
        }

        if ( $this.val() == 0 ) {
            $sltModulos.children()
                       .first()
                       .attr('selected','selected');

            $sltModulos.parent().hide()
                       .next().hide();

            $sltAulas.empty();

            $dtArquivoRecurso.fadeIn();
            $ddArquivoRecurso.fadeIn();

            return;
        }

        if ( $this.val() == 1 ) {
            $ddArquivoRecurso.hide();
            $dtArquivoRecurso.hide();

            $sltModulos.parent().fadeIn();
        }
    });

    $sltModulos.change(function(e){
        e.preventDefault();

        var $liAulas = $sltAulas.parent();

        if ( $(this).val() == '0' ) {
            $liAulas.hide();

            return;
        }

        $.get(
            WeLearn.url.siteURL('/conteudo/aula/recuperar_lista/' + $(this).val()),
            {},
            function (res) {
                if (res.success) {
                    var htmlOptionsAulas = '',
                        i,
                        aulas = res.aulas;

                    if ( aulas.length < 1 ) {
                        htmlOptionsAulas += '<option value="0">Não exitem aulas no módulo escolhido.</option> ';

                        if ( $sltTipo.length > 0 ) { //Se estiver na pagina de criação / alteração
                            $ddArquivoRecurso.hide();
                            $dtArquivoRecurso.hide();
                        }
                    } else {
                        htmlOptionsAulas += '<option value="0">Selecione uma aula...</option>';

                        for( i = 0; i < res.aulas.length; i++ ) {
                            htmlOptionsAulas += '<option value="' + aulas[i].value + '">' + aulas[i].name + '</option>';
                        }
                    }

                    $sltAulas.html(htmlOptionsAulas);

                    if ( ! $liAulas.is(':visible') ) {
                        $liAulas.fadeIn();
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

    var $divContainerModulosDataTable = $('#div-container-recurso-listar-datatable');
    $sltAulas.change(function(e){
        e.preventDefault();

        var $this = $(this);

        if ( $sltTipo.length > 0 ) { //Se estiver na pagina de criação / alteração
            if ( $this.val() == '0' ) {
                $ddArquivoRecurso.hide();
                $dtArquivoRecurso.hide();
            } else {
                $dtArquivoRecurso.fadeIn();
                $ddArquivoRecurso.fadeIn();
            }
        } else if ( $divContainerModulosDataTable.length > 0 ) { //Se estiver na pagina de listagem de recursos restritos

            if ( $this.val() != '0' ) {
                $.get(
                    WeLearn.url.siteURL('/conteudo/recurso/recuperar_lista_restrita/' + $this.val() ),
                    {},
                    function (res) {

                        if (res.success) {
                            $divContainerModulosDataTable
                                .fadeOut(function(){
                                    $divContainerModulosDataTable
                                        .html(res.htmlListaRecursos)
                                        .fadeIn();
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
            }

        }
    });

    $(document).ready(function(){
        //Se estiver na página de listagem de recursos restritos

        if ( $divContainerModulosDataTable.length > 0 ) {
            $sltAulas.trigger('change');
        }
    });

    var $divUploadPreview = $('#recurso-upload-preview'),
        gerarHtmlPreview = function(dadosUpload) {
            var html = '', key, $html;

            for (key in dadosUpload) {
                if ( dadosUpload.hasOwnProperty(key) ) {
                    html += '<input type="hidden" name="upload[' + key + ']" value="'
                         + dadosUpload[key] + '" />';
                }
            }

            $html = $(html
                   + '<dl class="' + gerarClasseUpload(dadosUpload.file_ext) + '">'
                       + '<dt>Nome do arquivo:</dt>'
                       + '<dd>' + dadosUpload.orig_name + '</dd>'
                       + '<dt>Tamanho do arquivo:</dt>'
                       + '<dd>' + dadosUpload.file_size + ' KB</dd>'
                       + '<dt>Tipo do arquivo:</dt>'
                       + '<dd>' + dadosUpload.file_type + '</dd>'
                   + '</dl><a href="#" id="a-trocar-upload-recurso">Trocar arquivo</a>');

            return $html;
        }, gerarClasseUpload = function(extensao) {
            return 'filetype-' + extensao.replace('.', '');
        },
        $divArquivoRecursoContainer = $('#div-arquivo-recurso-container'),
        $ddArquivoRecurso = $divArquivoRecursoContainer.parent(),
        $dtArquivoRecurso = $ddArquivoRecurso.prev();


    $('#a-trocar-upload-recurso').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            idRecurso = $divUploadPreview.data('id-recurso'),
            exibirInputUpload = function(){
                $this.empty();
                $divArquivoRecursoContainer.fadeIn();
            };

        if ( ! idRecurso ) {
            $divUploadPreview.fadeOut(exibirInputUpload);

            return;
        }

        $.get(
            WeLearn.url.siteURL('/conteudo/recurso/remover_upload/' + idRecurso),
            {},
            function(res) {
                $divUploadPreview.fadeOut(exibirInputUpload);
            }
        )
    });

    $('#fil-arquivo-recurso').live('change', function(e){
        e.preventDefault();

        var url = WeLearn.url.siteURL('conteudo/recurso/salvar_upload_temporario');

        $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: $(this).attr('id'),
            dataType: 'json',
            timeout: 60 * 1000,
            success: function (res, status) {
                if (res.success) {
                    WeLearn.notificar(res.notificacao);

                    $divUploadPreview.data('id-recurso', res.upload.recursoId)
                                     .hide()
                                     .html( gerarHtmlPreview(res.upload) )
                                     .fadeIn();

                   $divArquivoRecursoContainer.hide();
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

    var formRecurso = document.getElementById('recurso-criar-form') ||
                      document.getElementById('recurso-alterar-form');

    $('#btn-form-recurso').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formRecurso,
            $(formRecurso).attr('action'),
            function(res) {
                if (res.tipoRecurso == 1) {
                    window.location = WeLearn.url.siteURL('/curso/conteudo/recurso/restrito/' + res.idCurso);
                } else {
                    window.location = WeLearn.url.siteURL('/curso/conteudo/recurso/geral/' + res.idCurso);
                }
            }
        )
    });

    var recuperarProximaPagina = function($this, url) {
            var $tblRecursoDataTable = $('#recurso-listar-datatable'),
                $emQtdRecursosExibidos = $('#em-recurso-qtdexibindo'),
                qtdExibidosAtual = parseInt( $emQtdRecursosExibidos.text() );


            $.get(
                url,
                {},
                function(res) {
                    $tblRecursoDataTable.append(res.htmlListaRecursos);
                    $emQtdRecursosExibidos.text( qtdExibidosAtual + res.qtdRecuperados );

                    if ( res.paginacao.proxima_pagina ) {
                        $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $this
                            .parent()
                            .html('<h4>Não há mais recursos a serem exibidos no momento.</h4>');
                    }
                }
            )
        };

    $('#paginacao-recurso-geral').children('a').click(function(e){
        e.preventDefault();

        var $this = $(this),
            url = WeLearn.url.siteURL('/conteudo/recurso/recuperar_proxima_pagina/0/'
                                      + $this.data('id-curso') + '/'
                                      + $this.data('proximo'));

        recuperarProximaPagina($this, url);
    });

    $(document).delegate('#paginacao-recurso-restrito > a', 'click', function(e){
        e.preventDefault();

        var $this = $(this),
            url = WeLearn.url.siteURL('/conteudo/recurso/recuperar_proxima_pagina/1/'
                                      + $this.data('id-aula') + '/'
                                      + $this.data('proximo'));

        recuperarProximaPagina($this, url);
    });

    var $divConfirmacaoRemover = $('<div id="dialogo-confirmacao-remover-recurso">' +
                                   '<p>Tem certeza que deseja remover este recurso?<br/>' +
                                   'Esta ação <strong>NÃO</strong> poderá ser desfeita!</p></div>');
    $('a.a-remover-recurso').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href');

        $divConfirmacaoRemover.dialog({
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
                            if(res.success) {
                                var $emQtdRecursosExibidos = $('#em-recurso-qtdexibindo'),
                                    $emQtdRecursosTotais = $('#em-recurso-qtdtotal'),
                                    qtdExibindoAtual = parseInt( $emQtdRecursosExibidos.text() ),
                                    qtdTotalAtual = parseInt( $emQtdRecursosTotais.text() );

                                WeLearn.notificar(res.notificacao);

                                $this.parent()
                                     .parent()
                                     .fadeOut(function(){
                                        $(this).remove();

                                        $emQtdRecursosExibidos.text( qtdExibindoAtual - 1 );
                                        $emQtdRecursosTotais.text( qtdTotalAtual - 1 );
                                     });
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 5000
                                });
                            }
                        }
                    );

                    $( this ).dialog('close');
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

})();