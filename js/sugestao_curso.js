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
});