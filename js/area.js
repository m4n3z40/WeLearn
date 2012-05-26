/**
 * Created with JetBrains PhpStorm.
 * User: gevigier
 * Date: 24/05/12
 * Time: 19:32
 * To change this template use File | Settings | File Templates.
 */
(function(){

    var formArea = document.getElementById('form-criar-area')
                || document.getElementById('form-alterar-area');
    $('#btn-form-area').click(function(e){
        e.preventDefault();

        var url = $(formArea).attr('action');

        if (formArea != null) {
            WeLearn.validarForm(formArea, url, function(res) {
                window.location = WeLearn.url.siteURL('administracao/area/listar/');
            });
        }
    });
    $('.a-remover-categoria-forum').live('click', function (e) {
        e.preventDefault();

        var $this = $(this),
            $divConfirmacao = $('<div id="dialogo-confirmacao-remover-categoria-forum">' +
                '<p>Tem certeza que deseja remover esta categoria?' +
                '<br/>Essa ação <strong>NÃO</strong> poderá ser desfeita. ' +
                '<br/><strong>TODOS</strong> os fóruns e posts vinculados' +
                ' à esta categoria também serão removidos.</p></div>');

        $divConfirmacao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar': function(){
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);
                                $this.parent().parent().fadeOut('slow', function(){
                                    $(this).remove();
                                });
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
                                });
                            }
                        }
                    );

                    $( this ).dialog('close');
                },
                'Cancelar': function(){
                    $( this ).dialog('close');
                }
            }
        });
    });


})();


