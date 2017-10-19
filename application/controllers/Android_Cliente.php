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

    public function usuarioExistente($str)
    {
        $result = ($this->Manejo_DB->buscarUsuario($str) != null) ? true : false ;
        return $result;
    }

    public function registro()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'trim|required|is_unique[dispositivo.mac]');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric|is_unique[usuario.numero_usuario]');
        $this->form_validation->set_rules('Model', 'modelo', 'required');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            $data = array_merge($data, $this->form_validation->error_array());
            $this->output->set_status_header(400);
        } else {
            $macAddress = $this->input->post('MacAddress');
            $numCuenta = $this->input->post('NumCuenta');
            $modelo = $this->input->post('Model');
            $reg = $this->Manejo_DB->registroUsuario($macAddress, $numCuenta, $modelo);
            $data = ($reg) ? array('flag' => true , 'mensaje' =>'Datos insertados correctamente' ) : array('flag' => false , 'mensaje' =>'error' );
            $this->output->set_status_header(201);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function inicioSesion($MacAddress, $numCuenta)
    {
        $in = array(
            'MacAddress' => $MacAddress,
            'NumCuenta' => $numCuenta
        );
        $this->form_validation->set_data($in);
        $this->form_validation->set_rules('MacAddress', 'Dirrecion Mac', 'trim|required');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric|callback_usuarioExistente');
        $this->form_validation->set_message('usuarioExistente','El usuario no Existe');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            $data = array_merge($data, $this->form_validation->error_array());
            $this->output->set_status_header(400);
        }elseif($this->Manejo_DB->buscarSesion($numCuenta)->fin_sesion != null) {
            $result = $this->Manejo_DB->inicioSesion($MacAddress, $numCuenta , "ruta");
            $data = ($result) ? array('flag' => true,'mensaje' => 'Inicio de sesion correcto') : array('flag' => false , 'mensaje' =>'error en el proseso');
            $this->output->set_status_header(200);
        }else {
            $data = array(
                'flag' => false,
                'mensaje' => "sesion ya abierta"
            );
            $this->output->set_status_header(400);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function cierreSesion($numCuenta)
    {
        $in = array(
            'NumCuenta' => $numCuenta
        );
        $this->form_validation->set_data($in);
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|required|numeric|callback_usuarioExistente');
        $this->form_validation->set_message('usuarioExistente','El usuario no Existe');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            $data = array_merge($data, $this->form_validation->error_array());
            $this->output->set_status_header(400);
        }elseif($this->Manejo_DB->buscarSesion($numCuenta)->fin_sesion == null){
            $result = $this->Manejo_DB->cierreSesion($numCuenta);
            $data = ($result) ? array('flag' => true , 'mensaje' =>'Sesion cerrada' ) : array('flag' => false , 'mensaje' =>'error en el proseso');
            $this->output->set_status_header(201);
        }
        else {
            $data = array(
                'flag' => false,
                'mensaje' => "Sesion ya cerrada"
            );
            $this->output->set_status_header(400);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function insertarPosicion()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->form_validation->set_rules('Latitud', 'Latitud', 'trim|required');
        $this->form_validation->set_rules('Longitud', 'Longitud', 'trim|required');
        $this->form_validation->set_rules('NumCuenta', 'Numero de cuenta', 'trim|numeric|required|callback_usuarioExistente');
        $this->form_validation->set_message('usuarioExistente','El usuario no Existe');
        if ($this->form_validation->run() == false) {
            $data = array('flag' => false);
            $data = array_merge($data, $this->form_validation->error_array());
            $this->output->set_status_header(400);
        } else {
            $numCuenta = $this->input->post('NumCuenta');
            $latitud = $this->input->post('Latitud');
            $longitud = $this->input->post('Longitud');
            $idSesion = $this->Manejo_DB->buscarSesion($numCuenta)->idsesion;
            $inPos = $this->Manejo_DB->insertarPosicion($latitud,$longitud,$idSesion);
            $data = ($inPos != 0) ? array('flag' => true , 'mensaje' =>'posicion enviada' ) : array('flag' => false , 'mensaje' =>'error en el proseso');
            $this->output->set_status_header(201);
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}

/* End of file Android_Cliente.php */
/* Location: ./application/controllers/Android_Cliente.php */
