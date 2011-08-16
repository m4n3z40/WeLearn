/* Author:

*/

window.WeLearn = {
    validarForm : function(form, url_validacao, onValidationPass, onValidationFail) {
        var $form = $(form);
        var processarResultado = function(result) {
            if ( ! result.success ) {
                var erro,
                    elems;

                for (var i = 0; i < result.errors.length; i++) {
                    erro = result.errors[i];

                    if (erro.field_name == "noField") {
                        $form.before(
                            '<p class="error"><span>' + erro.error_msg + '</span></p>'
                        );
                        continue;
                    }

                    elems = 'input[name=' + erro.field_name + '],' +
                            'select[name=' + erro.field_name + '],' +
                            'textarea[name=' + erro.field_name + ']';

                    $campos = $(elems);

                    $campos.after(
                        '<p class="validation-error">' + erro.error_msg + '</p>'
                    );

                    $campos.change(function(evt){
                        $(this).next('p.validation-error').remove();
                    });
                }

                if (onValidationFail) {
                   onValidationFail(result);
                }
            } else {
                if (onValidationPass) {
                    onValidationPass(result);
                }
            }
        };

        var $errors = $('.validation-error, .error');
        if ($errors) {
            $errors.remove();
        }

        $.post(
            url_validacao,
            $form.serialize(),
            processarResultado,
            'json'
        );
    }
};