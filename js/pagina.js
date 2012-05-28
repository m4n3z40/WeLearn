(function(){

    var $ulAlterarAula = $('#ul-pagina-alterar-aula');
    $('#a-pagina-alterar-aula').click(function(e){
        e.preventDefault();

        $ulAlterarAula.fadeToggle();
    });

    var $sltAulas = $('#slt-aulas');
    $('#slt-modulos').change(function(e){
        e.preventDefault();

        var $this = $(this);

        if ( $this.val() == '0') {
            return;
        }

        $.get(
            WeLearn.url.siteURL('/conteudo/aula/recuperar_lista/' + $this.val()),
            {},
            function (res) {
                if (res.success) {
                    var htmlOptionsAulas = '',
                        i,
                        aulas = res.aulas;

                    if ( aulas.length < 1 ) {
                        htmlOptionsAulas += '<option value="0">Não exitem aulas no módulo escolhido.</option> ';
                    } else {
                        htmlOptionsAulas += '<option value="0">Selecione uma aula...</option>';

                        for( i = 0; i < res.aulas.length; i++ ) {
                            htmlOptionsAulas += '<option value="' + aulas[i].value + '">' + aulas[i].name + '</option>';
                        }
                    }

                    $sltAulas.html(htmlOptionsAulas);
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

    $sltAulas.change(function(e){
        e.preventDefault();

        var $this = $(this);

        if ( $this.val() == '0' ) {
            return;
        }

        window.location = WeLearn.url.siteURL('/curso/conteudo/pagina/' + $this.val());
    });

    var $qtdTotal = $('#p-pagina-listar-qtdTotal').find('strong').first(),
        $divSalvarPosicoes = $('div.div-pagina-gerenciar-posicoes'),
        $btnSalvarPosicoes = $divSalvarPosicoes.children('button'),
        $ulListarPaginas = $('#ul-pagina-listar-lista').sortable({
            handle: 'h4',
            cursor: 'crosshair',
            placeholder: 'ui-state-highlight',
            update: function() {
                if ( ! $divSalvarPosicoes.first().is(':visible') ) {
                    $divSalvarPosicoes.show();
                }

                atualizarNroOrdem( $(this) );
            }
        }).disableSelection(),
        $dialogoFormPagina = $(
            '<div/>',
            {id: "div-dialogo-gerenciar-pagina"}
        ).dialog({
            autoOpen: false,
            resizable: false,
            width: 965,
            height: 640,
            beforeClose: function(){
                removerTinyMCE();
                $(this).empty();
            }
        }),
        atualizarNroOrdem = function($ulLista) {
            $ulLista
                .children('li')
                .find('> h4 > em')
                .each(function(index, el){
                   $(el).text(index + 1);
                });
        },
        adicionarPagina = function(){
            var formPagina = document.getElementById('pagina-criar-form'),
                $this = $dialogoFormPagina;

            atualizarConteudoForm();

            WeLearn.validarForm(
                formPagina,
                $(formPagina).attr('action'),
                function(res) {
                    $this.dialog('close');

                    if ( res.primeiroAdicionado ) {
                        window.location.reload();
                        return;
                    }

                    var $htmlItemLista = $(res.htmlNovaPagina).hide(),
                        intQtdTotal = parseInt( $qtdTotal.text() );

                    $ulListarPaginas.append($htmlItemLista);

                    $htmlItemLista.fadeIn();

                    $qtdTotal.text( intQtdTotal + 1 );

                    WeLearn.notificar(res.notificacao);
                }
            );
        },
        carregarTinyMCE = function() {
            tinyMCE.init({
                // General options
                mode: 'exact',
                elements: 'txt-conteudo',
                theme : "advanced",
                width: "800",
                height: "500",
                plugins : "table,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras",
                language: 'pt',

                // Theme options
                theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,|,ltr,rtl",
                theme_advanced_buttons4 : "cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "center",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true//,

                // Example content CSS (should be your site CSS)
                //content_css : "css/content.css",
            });
        },
        removerTinyMCE = function() {
            tinyMCE.get('txt-conteudo').remove();
        },
        atualizarConteudoForm = function(){
            var $txtConteudo = $('#txt-conteudo'),
                conteudo = tinyMCE.get('txt-conteudo').getContent();

            $txtConteudo.val( conteudo );
        };

    $("a.a-adicionar-pagina").click(function(e){
        e.preventDefault();

        $.get(
            $(this).attr('href'),
            {},
            function(res) {
                if (res.success) {

                    $dialogoFormPagina
                        .html(res.htmlFormAdicionar)
                        .dialog('option', 'title', 'Adicionar Página')
                        .dialog('option', 'buttons', {
                            "Adicionar" : adicionarPagina,
                            "Cancelar" : function() { $(this).dialog('close'); }
                        })
                        .dialog('open');

                    carregarTinyMCE();
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

    $btnSalvarPosicoes.click(function(e){
        e.preventDefault();

        $ulListarPaginas.sortable('refresh');

        $btnSalvarPosicoes
            .addClass('disabled')
            .attr('disabled', 'disabled');

        var urlSalvarPosicoes = WeLearn.url.siteURL(
                '/conteudo/pagina/salvar_posicoes/' + $ulListarPaginas.data('id-aula')
            ),
            parametrosGet = WeLearn.helpers.sortableToURLParamPosicoes( $ulListarPaginas );

        $.get(
            urlSalvarPosicoes,
            parametrosGet,
            function(res) {
                if (res.success) {
                    $btnSalvarPosicoes
                        .removeClass('disabled')
                        .removeAttr('disabled')
                    .parent().hide();

                    WeLearn.notificar(res.notificacao);
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

    $('a.a-editar-pagina').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            $elNome = $this.parent()
                           .parent()
                           .parent()
                           .prev();

        $.get(
            $this.attr('href'),
            {},
            function(res) {
                if (res.success) {

                    $dialogoFormPagina
                        .html(res.htmlFormAlterar)
                        .dialog('option', 'title', 'Página Página')
                        .dialog('option', 'buttons', {
                            "Salvar" : function(){
                                var formPagina = document.getElementById('pagina-alterar-form'),
                                    $this = $dialogoFormPagina;

                                atualizarConteudoForm();

                                WeLearn.validarForm(
                                    formPagina,
                                    $(formPagina).attr('action'),
                                    function(res) {
                                        $this.dialog('close');

                                        $elNome.text('"' + res.novoNome + '"');

                                        WeLearn.notificar(res.notificacao);
                                    }
                                );
                            },
                            "Cancelar" : function() { $(this).dialog('close'); }
                        })
                        .dialog('open');

                    carregarTinyMCE();

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


    var $divConfirmacaoRemover = $('<div id="dialogo-confirmacao-remover-pagina">' +
                                   '<p>Tem certeza que deseja remover esta página?<br/>' +
                                   'Esta ação <strong>NÃO</strong> poderá ser desfeita!</div>');
    $('a.a-remover-pagina').live('click', function(e){
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
                    $this.parent()
                         .parent()
                         .parent()
                         .parent()
                         .fadeOut(function(){
                             $(this).remove();

                             $ulListarPaginas.sortable('refresh');
                             atualizarNroOrdem( $ulListarPaginas );
                             $qtdTotal.text( parseInt( $qtdTotal.text() ) - 1 );

                             $.get(
                                 url,
                                 WeLearn.helpers.sortableToURLParamPosicoes( $ulListarPaginas ),
                                 function(res) {
                                     if (res.success) {
                                         WeLearn.notificar(res.notificacao);
                                     } else {
                                         WeLearn.notificar({
                                             nivel: 'error',
                                             msg: res.errors[0].error_msg,
                                             tempo: 5000
                                         });
                                     }
                                 }
                             );

                             if ( $divSalvarPosicoes.first().is(':visible') ) {
                                 $divSalvarPosicoes.hide();
                             }
                         });

                    $( this ).dialog('close');
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

    var $dialogoVisualizarPagina = $(
        '<div/>',
        {id: "div-dialogo-visualizar-pagina"}
    ).dialog({
        autoOpen: false,
        resizable: false,
        width: 965,
        height: 640,
        beforeClose: function(){
            $(this).empty();
        }
    });
    
    $('a.a-visualizar-pagina').live('click', function(e){
        e.preventDefault();
        
        $.get(
            $(this).attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $dialogoVisualizarPagina
                        .html(res.htmlVisualizacao)
                        .dialog('option', 'title', 'Visualização da Página "' + res.nome + '"')
                        .dialog('option', 'buttons', {
                            "Fechar" : function(){
                                $(this).dialog('close');
                            }
                        })
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