$(document).ready(function() {

    $('#feed-submit').click(
        function(e){
           e.preventDefault();
           var form= document.getElementById('form-criar-feed');
           var url=$(form).attr('action');
           WeLearn.validarForm(form,url,function(res){
               if(res.success){
                   WeLearn.notificar(res.notificacao);
               }
           });
        }
    );


    $("input[name='tipo-feed']").change(
        function()
        {
            if($(this).val()==0)
            {
                $('#descricao-feed').fadeOut();
            }
            if($(this).val()==1)
            {
                $('#descricao-feed').attr({placeholder:'Descreva O Seu Link'});
                $('#descricao-feed').fadeIn();
            }
            if($(this).val()==2)
            {
                $('#descricao-feed').attr({placeholder:'Descreva Sua Imagem'});
                $('#descricao-feed').fadeIn();
            }
            if($(this).val()==3)
            {
                $('#descricao-feed').attr({placeholder:'Descreva Seu Video'});
                $('#descricao-feed').fadeIn();
            }
        }
    );


} );