/**
 * Created by JetBrains PhpStorm.
 * User: Allan Marques Baptista
 * Date: 30/08/11
 * Time: 15:50
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    var $sltPais = $('#slt-pais'),
        $btnAddIM = $('#btn-add-im'),
        $btnRemoveIM = $('#btn-remove-im'),
        $btnAddRS = $('#btn-add-rs'),
        $btnRemoveRS = $('#btn-remove-rs');

    $sltPais.change(function(e){
        var $sltEstado = $('#slt-estado'),
            $error = $sltEstado.next('p.error'),
            paisId = $(this).val();
        
        if($error) {
            $error.remove();
        }

        if(parseInt(paisId) == 0) {
            $sltEstado.html('<option value="0">Selecione um País</option>');
        } else {
            $.get(
                'http://welearn.com/pais_estado/recuperar_lista_estados/' + paisId,
                null,
                function(res) {
                    if (res.success) {
                        var estados = res.estados,
                            options = '<option value="0">Selecione um Estado</option>';

                        for(var i = 0; i < estados.length; i++) {
                            options += '<option value="' + estados[i].id + '">' + estados[i].descricao + '</option>';
                        }

                        $sltEstado.html(options);
                    } else {
                        $sltEstado.after('<p class="error">' + res.errors[0].error_msg + '</p>');
                    }
                },
                'json'
            );
        }
    });

    $btnAddIM.click(function(){
        var $tblListaIMbody = $('#tbl-lista-im').children('tbody'),
            $copiaUltimo = $tblListaIMbody.children('tr').last().clone(),
            tdIM = $copiaUltimo.children('td').first(),
            tdIMUsuario = $copiaUltimo.children('td').last(),
            idTxtIM = tdIM.children('input').attr('id').split('-'),
            idTxtIMUsuario = tdIMUsuario.children('input').attr('id').split('-');

        idTxtIM[idTxtIM.length - 1] = parseInt(idTxtIM[idTxtIM.length - 1]) + 1;
        idTxtIM = idTxtIM.join('-');

        idTxtIMUsuario[idTxtIMUsuario.length - 1] = parseInt(idTxtIMUsuario[idTxtIMUsuario.length -1]) + 1;
        idTxtIMUsuario = idTxtIMUsuario.join('-');

        tdIM.children('input').attr('id', idTxtIM).val('');
        tdIM.children('label').attr('for', idTxtIM);

        tdIMUsuario.children('input').attr('id', idTxtIMUsuario).val('');
        tdIMUsuario.children('label').attr('for', idTxtIMUsuario);

        $tblListaIMbody.append($copiaUltimo);
    });

    $btnRemoveIM.click(function(){
        var $tblListaIMtr = $('#tbl-lista-im').children('tbody').children('tr');

        if( $tblListaIMtr.length > 1 ) {
            $tblListaIMtr.last().remove();
        }
    });

    $btnAddRS.click(function(){
        var $tblListaRSbody = $('#tbl-lista-rs').children('tbody'),
            $copiaUltimo = $tblListaRSbody.children('tr').last().clone(),
            tdRS = $copiaUltimo.children('td').first(),
            tdRSUsuario = $copiaUltimo.children('td').last(),
            idTxtRS = tdRS.children('input').attr('id').split('-'),
            idTxtRSUsuario = tdRSUsuario.children('input').attr('id').split('-');

        idTxtRS[idTxtRS.length - 1] = parseInt(idTxtRS[idTxtRS.length - 1]) + 1;
        idTxtRS = idTxtRS.join('-');

        idTxtRSUsuario[idTxtRSUsuario.length - 1] = parseInt(idTxtRSUsuario[idTxtRSUsuario.length - 1]) + 1;
        idTxtRSUsuario = idTxtRSUsuario.join('-');

        tdRS.children('input').attr('id', idTxtRS).val('');
        tdRS.children('label').attr('for', idTxtRS);

        tdRSUsuario.children('input').attr('id', idTxtRSUsuario).val('');
        tdRSUsuario.children('label').attr('for', idTxtRSUsuario);

        $tblListaRSbody.append($copiaUltimo);
    });

    $btnRemoveRS.click(function(){
        var $tblListaRStr = $('#tbl-lista-rs').children('tbody').children('tr');

        if ($tblListaRStr.length > 1) {
            $tblListaRStr.last().remove();
        }
    });
});
