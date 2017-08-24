<?php
class dispositivo extends CI_Model {
   private $res;

   public function __construct() {
      parent:: __construct();

   }

   public function getdispositivo(){
      $query = $this->db->get('user');
      return $query->result_array();
   }

   public function getdispositivoById($id){
      $this->db->where('id',$id);
      $query = $this->db->get('user');
      return $query->result_array();
   }

   public function setdispositivo($NoC) {
      $res  = array(
         'flag' => true ,
         'mes' => 'accion terminada'
      );
      $data = array(
        'NoCuenta' => $NoC,
        'estado'  => 'prueba'
      );
      //   'longitude'  => $lon,
      //   'latitude' => $lat
      $query = $this->db->insert('usuario', $data);
      return $res;
   }

}
?>
