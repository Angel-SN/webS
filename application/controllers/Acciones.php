<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Acciones extends CI_Controller
{
    public function __construct()
    {
        parent:: __construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('RegistroUsuario');
    }

    public function index()
    {
        $data = array('flag' => true , 'mensaje' =>'hola' );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getposicion()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $latitud = $this->input->post('lat');
        $longitud = $this->input->post('long');
        $data = $this->RegistroUsuario->insertarPosicion($latitud, $longitud);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function registro()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'required|is_unique[dispositivo.mac]');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'required|numeric|is_unique[usuario.numero_cuenta]');
        $this->form_validation->set_rules('Model', 'modelo', 'required');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            header('Content-Type: application/json');
            echo json_encode(array_merge($data, $this->form_validation->error_array()));
        } else {
            $macAddress = $this->input->post('MacAddress');
            $numCuenta = $this->input->post('NumCuenta');
            $modelo = $this->input->post('Model');
            $reg = $this->RegistroUsuario->registro($macAddress, $numCuenta, $modelo);
            $data = ($reg) ? array('flag' => true , 'mensaje' =>'Datos insertados correctamente' ) : array('flag' => false , 'mensaje' =>'error' );
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }

    public function agregar()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('numero_cuenta', 'Numero de cuenta', 'required|numeric|is_unique[usuario.numero_cuenta]');
            if ($this->form_validation->run() == false) {
                $this->load->view('view_form_usuario');
            } else {
                echo "informacion recibida";
                print_r($this->input->post());
            }
        } else {
            $this->load->view('view_form_usuario');
        }
    }

    public function inicioSesion($MacAddress, $NumCuenta)
    {
        $in = array(
            'MacAddress' => $MacAddress,
            'NumCuenta' => $NumCuenta
        );
        $this->form_validation->set_data($in);
        $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'required');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'required|numeric');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            header('Content-Type: application/json');
            echo json_encode(array_merge($data, $this->form_validation->error_array()));
        } else {
            $data = $this->RegistroUsuario->inicio($MacAddress, $NumCuenta);
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
}


/* End of file Acciones.php */
/* Location: ./application/controllers/Acciones.php */
