<?php
class RegistroUsuario extends CI_Model {
   private $res;

   public function __construct() {
      parent:: __construct();
   }

   private function insertarUsuario($numero_cuenta, $estado='1' ) {
      $id = 0;
      $datosUsuario = array('numero_cuenta' => $numero_cuenta,'estado'  => $estado);
      $dat = $this->db->insert('usuario', $datosUsuario);
      if($dat==true){
         $this->db->select('idusuario');
         $this->db->where('numero_cuenta',$numero_cuenta);
         $id = $this->db->get('usuario');
         return $id->row_array();
         //return $id->row()->idusuario;
      }
      else{
         return 0;
      }
   }

   private function insertarDispositivo($mac, $modelo, $estado='1' ) {
      $id = 0;
      $datosDispositivos = array(
         'mac' => $mac,
         'modelo' => $modelo,
         'estado' => 1
      );
      $dat = $this->db->insert('dispositivo', $datosDispositivos);
      if($dat==true){
         $this->db->select('iddispositivo');
         $this->db->where('mac', $mac);
         $id = $this->db->get('dispositivo');
         return $id->row_array();
         //return $id->row()->iddispositivo;
      }
      else{
         return 0;
      }
   }

   public function registro($mac,$NoC,$modelo) {
      $idUsuario = $this->insertarUsuario($NoC,1);
      $idDispositivo = $this->insertarDispositivo($mac, $modelo,1 );
      // $datosPosicion = array(
      //    'latitud' => 0,
      //    'longitud' => 0,
      //    'fecha ' => '2107-08-17'
      // );
      // $this->db->insert('dispositivo', $datosDispositivos);
      return array_merge($idUsuario, $idDispositivo);
      // $datosRelacion = array(
      //    'usuario_idusuario' => ,
      //    'dispositivo_iddispositivo' => ,
      //    'fecha' => ,
      //    'estado' => ,
      //    'posicion_idposicion' => ,
      //
   }
}
?>
