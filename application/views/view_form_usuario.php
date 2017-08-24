<h1>Nuevo contacto</h1>
<br />
<?php
   $input_nombre  = array(
      'name' => 'numero_cuenta',
      'id'  => 'numero_cuenta',
      'maxlength' => '6'
   );
   $opciones = array(
      '1' => 'activo',
      '0' => 'inactivo'
   );
   // echo validation_errors();
   echo form_open();
   echo form_label('No.cuenta ','nombre');
   echo form_input($input_nombre);
   echo form_error('numero_cuenta');
   echo "<br/><br/>";
   echo form_label('Estado ','estado');
   echo form_dropdown("estado",$opciones);
   echo "<br/><br/>";
   echo form_submit('btn_submit', 'Guardar');
   echo form_close();
?>
