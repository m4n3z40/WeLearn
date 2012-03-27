<section id="home-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <?php
    echo '<div>'.$dados_usuario->getNome().' '.$dados_usuario->getSobrenome().'</div>';
    echo '<div>'.$dados_usuario->getEmail().'</div>';
    ?>

    <div id='menu-lateral'>
        <a href="#">localizar amigos</a>
        <?php
        if($dados_usuario->getId()!=$nomeUsuario)// controle para mudar as opçoes do menu,de acordo com o perfil que esta sendo visualizado
        {
            echo '<a href="#" id="enviar-mp">enviar uma mensagem</a>';
        }else
        {
             echo '<a href="#" id="exibir-mp">mensagens</a>';
        }

        ?>
    </div>
</section>