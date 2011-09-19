/**
 * Created by JetBrains PhpStorm.
 * User: Allan Marques (allan.marques@ymail.com)
 * Date: 10/09/11
 * Time: 10:15
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){
    $('#btn-sugerir').click(function(e){
        e.preventDefault();
        var $form = $('#form-sugestao'),
            urlValidacao = $form.attr('action'),
            $sltsSegmentos = $form.find('#slt-area option:selected,#slt-segmento option:selected');

        $sltsSegmentos.each(function(){
            if($(this).val() == 0) {
                $(this).val('');
            }
        });

        WeLearn.validarForm(
            $form,
            urlValidacao,
            function(res) {
                var $dialogo =  $('<p class="success">A sugestão foi salva com sucesso.</p>');
                
                $dialogo.dialog({
                    title: 'Criar Sugestão',
                    modal: true,
                    resizable: false,
                    close: function() {
                        window.location = 'http://welearn.com/curso/sugestao';
                    },
                    buttons: {
                        Ok: function() {
                            $(this).dialog('close');
                        }
                    }
                });
            },
            function(res) {
                var $dialogo = $('<p class="error ui-state-error">Ocorreram erros ao salvar sua sugestão, verifique o formulário.</p>');

                $dialogo.dialog({
                    title: 'Criar Sugestão',
                    resizable: false,
                    buttons: {
                        Ok: function() {
                            $(this).dialog('close');
                        }
                    }
                });
            }
        )
    });

    var divListaSugestoes = document.getElementById('lista-sugestoes');
    if(divListaSugestoes) {
        var $divListaSugestoes = $(divListaSugestoes);
        $divListaSugestoes.accordion({collapsible: true, active: false});

        $('#prox-pagina > a').click(function(e){
            e.preventDefault();

            $.get(
                'http://welearn.com/curso/sugestao/proxima_pagina/' + $(this).data('proximo'),
                null,
                function(res){
                    if (res.success) {
                        var htmlListaSugestoes = '';
                        for (var i = 0, sugestoes = res.sugestoes; i < sugestoes.length; i++) {
                            htmlListaSugestoes += '<h3><a href="#">' + sugestoes[i].nome + '</a></h3>' +
                                '<div>' +
                                    '<table>' +
                                        '<tr><td>Nome</td><td>' + sugestoes[i].nome + '</td></tr>' +
                                        '<tr><td>Tema</td><td>' + sugestoes[i].tema + '</td></tr>' +
                                        '<tr><td>Descrição</td><td>' + sugestoes[i].descricao + '</td></tr>' +
                                        '<tr><td>Área de Segmento</td><td>' + sugestoes[i].segmento.area.descricao + '</td></tr>' +
                                        '<tr><td>Segmento</td><td>' + sugestoes[i].segmento.descricao + '</td></tr>' +
                                        '<tr><td>Data de Criação</td><td>' + sugestoes[i].dataCriacao + '</td></tr>' +
                                        '<tr><td>Criador da Sugestão</td>' +
                                            '<td>' +
                                                '<a href="http://welearn.com/usuario/perfil/' +
                                                sugestoes[i].criador.id + '">' + sugestoes[i].criador.nome + '</a>' +
                                            '</td>' +
                                        '</tr>' +
                                        '<tr>' +
                                            '<td colspan="2">' +
                                                '<a href="#">Criar curso à partir desta sugestão</a>' +
                                            '</td>' +
                                        '</tr>' +
                                    '</table>' +
                                '</div>';
                        }

                        $divListaSugestoes.append(htmlListaSugestoes)
                                          .accordion('destroy')
                                          .accordion({collapsible: true, active: false});
                    }
                }
            );
        });
    }
});