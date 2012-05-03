<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 01/05/12
 * Time: 23:54
 * To change this template use File | Settings | File Templates.
 */
?>
<?php if ($success==true): ?>
<div>
    <?php if($haConvites): ?>
            <a href="#" data-proximo="<?php echo $inicioProxPagina ?>">convites mais antigos</a>
            <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
    <?php endif;?>
</div>
<?=$partialListaConvites?>
<?php else: ?>
    <h3>Voce não possui nenhum convite pendente</h3>
<?php endif;?>