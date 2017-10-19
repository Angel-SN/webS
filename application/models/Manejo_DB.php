<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manejo_DB extends CI_Model
{
    public function __construct()
    {
        parent:: __construct();
    }

    public function obtenerFecha()
    {
        date_default_timezone_set("America/Mexico_City");
        $hoy = getdate();
        return $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"]." ".$hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
    }

    private function insertarUsuario($numero_usuario, $descripción_usuario = null)
    {
        $datosUsuario = array(
            'numero_usuario' => $numero_usuario,
            'descripción_usuario'  => $descripción_usuario
        );
        $data = $this->db->insert('usuario', $datosUsuario);
        if ($data == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    private function insertarDispositivo($mac, $modelo, $idUsuario)
    {
        $datosDispositivos = array(
            'mac' => $mac,
            'modelo' => $modelo,
            'usuario_idusuario' => $idUsuario
        );
        $data = $this->db->insert('dispositivo', $datosDispositivos);
        if ($data == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    public function insertarPosicion($lat = null, $long = null , $idSesion)
    {
        $datosPosicion = array(
           'latitud' => $lat,
           'longitud' => $long,
           'fecha ' => $this->obtenerFecha(),
           'sesion_idsesion' => $idSesion
        );
        $data = $this->db->insert('posicion', $datosPosicion);
        if ($data == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    public function insertarRuta($nombre){
        $datosRuta = array(
            'nombre' => $nombre,
        );
        $data = $this->db->insert('ruta', $datosRuta);
        if ($data == true) {
            return $this->db->insert_id();
        }
        else{
            return 0;
        }

    }

    private function crearSesion($idUsuario = '0', $idRuta = '0')
    {
        $datosSesion = array(
            'usuario_idusuario' => $idUsuario,
            'inicio_sesion' => $this->obtenerFecha(),
            'fin_sesion' => null,
            'estado' => 'Inactivo',
            'ruta_ruta' => $idRuta
        );
        $dat = $this->db->insert('sesion', $datosSesion);
        if ($dat == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }


    public function registroUsuario($mac, $NoC, $modelo)
    {
        $idUsuario = $this->insertarUsuario($NoC);
        $idDispositivo = $this->insertarDispositivo($mac, $modelo, $idUsuario);
        if($idUsuario != 0 && $idDispositivo != 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function buscarUsuario($noC)
    {
        $this->db->select('idusuario');
        $this->db->from('usuario');
        $this->db->join('dispositivo', 'usuario.idusuario = dispositivo.usuario_idusuario', 'inner');
        $this->db->where('usuario.numero_usuario', $noC);
        $Usuario = $this->db->get();
        return $Usuario->row();
    }

    public function buscarSesion($numero_usuario)
    {
        $this->db->where('usuario_idusuario', $this->buscarUsuario($numero_usuario)->idusuario);
        $query = $this->db->get('sesion');
        return $query->last_row();
    }

    public function inicioSesion($mac, $noC ,$nombreRuta)
    {
        $idUsuario = $this->buscarUsuario($noC)->idusuario;
        $this->db->select('ruta');
        $this->db->where('nombre', $nombreRuta);
        $idRuta = $this->db->get('ruta')->row()->ruta;
        $sesion = $this->crearSesion($idUsuario,$idRuta);
        $respuesta = ($sesion != 0) ? true : false ;
        return $respuesta;
    }

    public function cierreSesion($noC)
    {
        $idSesion = $this->buscarSesion($noC)->idsesion;
        $data = array(
            'fin_sesion' => $this->obtenerFecha(),
            'estado' => "Inactivo"
        );
        $this->db->where('idsesion', $idSesion);
        $query = $this->db->update('sesion', $data);
        return $query;
    }
}

/* End of file Manejo_DB.php */
/* Location: ./application/models/Manejo_DB.php */
