(function(){

    var enviarForm = function(form) {
            WeLearn.validarForm(
                form,
                $(form).attr('action'),
                function(res) {
                    if (res.success) {

                        $(document).scrollTop(0);
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
        formDadosPessoais = document.getElementById('form-dados-pessoais'),
        formDadosProfissionais = document.getElementById('form-dados-profissionais'),
        formImagem = document.getElementById('form-imagem-usuario'),
        formPrivacidade = document.getElementById('form-privacidade');

    $('#btn-salvar-dados-pessoais').click(function(e){
        e.preventDefault();

        enviarForm( formDadosPessoais );
    });

    $('#btn-salvar-dados-profissionais').click(function(e){
        e.preventDefault();

        enviarForm( formDadosProfissionais );
    });

    $('#btn-salvar-imagem').click(function(e){
        e.preventDefault();

        enviarForm( formImagem );
    });

    $('#btn-salvar-privacidade').click(function(e){
        e.preventDefault();

        enviarForm( formPrivacidade );
    });

})();
