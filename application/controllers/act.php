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
      $data = array('flag' => TRUE , 'mensaje' =>'hola' );
      header('Content-Type: application/json');
      echo json_encode($data);
   }

   public function registro(){
      $_POST = json_decode(file_get_contents("php://input"), TRUE );
      $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'required');
      $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'required|numeric|is_unique[usuario.numero_cuenta]');
      $this->form_validation->set_rules('Model', 'modelo', 'required');
      if ($this->form_validation->run() == FALSE){
         $data = array('flag' => FALSE);
         header('Content-Type: application/json');
         echo json_encode(array_merge($data,$this->form_validation->error_array()));
      }
      else {
         $macAddress = $this->input->post('MacAddress');
         $numCuenta = $this->input->post('NumCuenta');
         $modelo = $this->input->post('Model');
         $data = array('flag' => TRUE , 'mensaje' =>'Datos insertados correctamente' );
         $data2 = $this->RegistroUsuario->registro($macAddress, $numCuenta, $modelo);
         header('Content-Type: application/json');
         echo json_encode(array_merge($data, $data2));
      }
   }

   public function agregar() {
      if ($this->input->post()) {
         $this->form_validation->set_rules('numero_cuenta', 'Numero de cuenta', 'required|numeric|is_unique[usuario.numero_cuenta]');
         if ($this->form_validation->run() == FALSE){
            $this->load->view('view_form_usuario');
         }
         else{
            echo "informacion recibida";
            print_r($this->input->post());
         }
      } else {
         $this->load->view('view_form_usuario');
      }
   }
}

/*End of act.php*/
