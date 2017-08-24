<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct() {
      parent:: __construct();
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->load->model('RegistroUsuario');
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


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()	{
		$this->load->view('welcome_message');
	}
}
