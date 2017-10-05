<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Android_Cliente extends CI_Controller
{
    public function __construct()
    {
        parent:: __construct();
        $this->load->library('form_validation');
        $this->load->model('Manejo_DB');
    }

    public function registro()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'trim|required|is_unique[dispositivo.mac]');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric|is_unique[usuario.numero_cuenta]');
        $this->form_validation->set_rules('Model', 'modelo', 'required');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            header('Content-Type: application/json');
            echo json_encode(array_merge($data, $this->form_validation->error_array()));
        } else {
            $macAddress = $this->input->post('MacAddress');
            $numCuenta = $this->input->post('NumCuenta');
            $modelo = $this->input->post('Model');
            $reg = $this->Manejo_DB->registro($macAddress, $numCuenta, $modelo);
            $data = ($reg) ? array('flag' => true , 'mensaje' =>'Datos insertados correctamente' ) : array('flag' => false , 'mensaje' =>'error' );
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }

    public function inicioSesion($MacAddress, $NumCuenta)
    {
        $in = array(
            'MacAddress' => $MacAddress,
            'NumCuenta' => $NumCuenta
        );
        $this->form_validation->set_data($in);
        $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'trim|required');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            header('Content-Type: application/json');
            echo json_encode(array_merge($data, $this->form_validation->error_array()));
        } else {
            $data = $this->Manejo_DB->inicio($MacAddress, $NumCuenta);
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }

    public function cierreSesion($NumCuenta)
    {
        $in = array(
            'NumCuenta' => $NumCuenta
        );
        $this->form_validation->set_data($in);
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            header('Content-Type: application/json');
            echo json_encode(array_merge($data, $this->form_validation->error_array()));
        } else {
            $data = $this->Manejo_DB->cierre($NumCuenta);
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }

    public function actualizaPosicion()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('Latitud', 'Latitud', 'trim|required');
        $this->form_validation->set_rules('Longitud', 'Longitud', 'trim|required');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            header('Content-Type: application/json');
            echo json_encode(array_merge($data, $this->form_validation->error_array()));
        } else {
            $numCuenta = $this->input->post('NumCuenta');
            $latitud = $this->input->post('Latitud');
            $longitud = $this->input->post('Longitud');
            $actPos = $this->Manejo_DB->actualizarPosicion($numCuenta, $latitud, $longitud);
            $data = ($actPos) ? array('flag' => true , 'mensaje' =>'posicion Actualizada' ) : array('flag' => false , 'mensaje' =>'error' );
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
}


/* End of file Android_Cliente.php */
/* Location: ./application/controllers/Android_Cliente.php */
