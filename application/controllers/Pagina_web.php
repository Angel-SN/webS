<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagina_web extends CI_Controller {

	public function __construct()
    {
        parent:: __construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('Manejo_DB');
    }

    public function validate_member($str)
    {
        if ($this->Manejo_DB->buscarUsuario($str) != null) {
            return true;
        } else {
            return false;
        }
    }

    public function index()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('numero_cuenta', 'Numero de cuenta', 'required|numeric|callback_validate_member');
            $this->form_validation->set_message('validate_member','El usuario no Existe');
            if ($this->form_validation->run() == false) {
                $this->load->view('view_form_usuario');
            } else {
                $noC = $this->input->post('numero_cuenta');
                $data = $this->Manejo_DB->buscarUsuario($noC);
                $this->load->view('view_usuario_posicion', $data);
            }
        } else {
            $this->load->view('view_form_usuario');
        }
    }

}

/* End of file Pagina_web.php */
/* Location: ./application/controllers/Pagina_web.php */
