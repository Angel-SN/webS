<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Act extends CI_Controller {
   public function __construct() {
      parent:: __construct();
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->load->model('RegistroUsuario');
   }

   public function index(){
      $data = array('flag' => true, 'mensaje' =>'hola' );
      header('Content-Type: application/json');
      echo json_encode($data);
   }

   public function registro(){
      $input_data = json_decode(trim(file_get_contents('php://input')), true);
      $macAddress = $input_data['MacAddress'];
      $numCuenta = $input_data['NumCuenta'];
      $modelo = $input_data['Model'];
      $data = $this->RegistroUsuario->registro($macAddress, $numCuenta, $modelo);
      header('Content-Type: application/json');
      echo json_encode($data);
   }
}

/*End of act.php*/
