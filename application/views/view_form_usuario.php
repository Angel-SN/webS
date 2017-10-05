<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo base_url() ?>css/estilos.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <title>buscar usuario</title>
</head>
<body class="inicio">
    <div class="card">
        <div class="userimg">
            <img src="img/userico.png" alt="">
        </div>
        <h2>Buscar usuario</h2>

        <?php
            $input_nombre  = array(
                'name' => 'numero_cuenta',
                'id'  => 'numero_cuenta',
                'maxlength' => '6',
                'value' => set_value('numero_cuenta'), 
                'placeholder' =>"Numero de cuenta"
            );
            $btn_submit_v = array(
                'name' => 'btn_submit',
                'value' => 'Buscar'
            );
            echo form_open();
        ?>
        <?php
            echo form_input($input_nombre);
            echo form_error('numero_cuenta','<small id="Errores" class="Error">', '</small>');
        ?>
        <?php
            echo form_submit($btn_submit_v);
            echo form_close();
        ?>
    </div>
</body>
</html>
