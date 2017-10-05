<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manejo_DB extends CI_Model
{

    public function __construct()
    {
        parent:: __construct();
    }

    private function obtenerFecha()
    {
        $hoy = getdate();
        return $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
    }

    private function insertarUsuario($numero_cuenta, $estado='activo')
    {
        $datosUsuario = array('numero_cuenta' => $numero_cuenta,'estado'  => $estado);
        $dat = $this->db->insert('usuario', $datosUsuario);
        if ($dat == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    private function insertarDispositivo($mac, $modelo, $estado='activo')
    {
        $datosDispositivos = array(
            'mac' => $mac,
            'modelo' => $modelo,
            'estado' => $estado
        );
        $dat = $this->db->insert('dispositivo', $datosDispositivos);
        if ($dat == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    public function insertarPosicion($lat = null, $long = null)
    {
        $datosPosicion = array(
           'latitud' => $lat,
           'longitud' => $long,
           'fecha ' => $this->obtenerFecha()
        );
        $dat = $this->db->insert('posicion', $datosPosicion);
        if ($dat == true) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    private function crearRelacion($idU = '0', $idD ='0', $idP = '0')
    {
        $datosRelacion = array(
            'usuario_idusuario' => $idU,
            'dispositivo_iddispositivo' => $idD,
            'fecha' => $this->obtenerFecha(),
            'estado' => 'inactivo',
            'posicion_idposicion' => $idP
        );
        $dat = $this->db->insert('sesion', $datosRelacion);
        if ($dat == true) {
            return true;
        } else {
            return false;
        }
    }

    public function registro($mac, $NoC, $modelo)
    {
        $idUsuario = $this->insertarUsuario($NoC);
        $idDispositivo = $this->insertarDispositivo($mac, $modelo);
        $idPosicion = $this->insertarPosicion();
        if ($idUsuario != 0 && $idDispositivo != 0 && $idPosicion != 0) {
            $rel = $this->crearRelacion($idUsuario, $idDispositivo, $idPosicion);
            return $rel;
        } else {
            return false;
        }
    }

    public function actualizarPosicion($noC, $lat, $long)
    {
        $query = $this->db->query("select p.idposicion from posicion p inner join sesion s on p.idposicion = s.posicion_idposicion inner join usuario u on s.usuario_idusuario = u.idusuario where u.numero_cuenta = '$noC';");
        if ($query->row() != null) {
            $datosPosicion = array(
               'latitud' => $lat,
               'longitud' => $long,
               'fecha ' => $this->obtenerFecha()
            );
            $this->db->where('idposicion', $query->row()->idposicion);
            $this->db->update('posicion', $datosPosicion);
            return true;
        } else{
            return false;
        }
    }

    public function inicio($mac, $noC)
    {
        $query = $this->db->query('select * from dispositivo d inner join sesion s on d.iddispositivo = s.dispositivo_iddispositivo inner join usuario u on s.usuario_idusuario = u.idusuario where u.numero_cuenta = "'.$noC.'" and d.mac = "'.$mac.'";');
        if ($query->row() != null) {
            $respuesta = array(
                'flag' => true,
                'mensaje' => 'Inicio de secion correcto'
            );
            $data = array(
                'fecha' => $this->obtenerFecha(),
                'estado' => 'activo'
            );
            $this->db->where('idsesion', $query->row()->idsesion);
            $this->db->update('sesion', $data);
        } else {
            $respuesta = array(
                'flag' => false,
                'mensaje' => 'Usuario no registrado'
            );
        }
        return $respuesta;
    }

    public function cierre($noC)
    {
        $query = $this->db->query('select * from dispositivo d inner join sesion s on d.iddispositivo = s.dispositivo_iddispositivo inner join usuario u on s.usuario_idusuario = u.idusuario where u.numero_cuenta = "'.$noC.'";');
        if ($query->row() != null) {
            $respuesta = array(
                'flag' => true,
                'mensaje' => 'Fin de Sesion'
            );
            $data = array(
                'estado' => 'inactivo'
            );
            $this->db->where('idsesion', $query->row()->idsesion);
            $this->db->update('sesion', $data);
        } else {
            $respuesta = array(
                'flag' => false,
                'mensaje' => 'error en el Proceso'
            );
        }
        return $respuesta;
    }

    public function buscarUsuario($noC)
    {
        $this->db->where('numero_cuenta', $noC);
        $query = $this->db->get('datos_usuario');
        return $query->row_array();
    }

}

/* End of file Manejo_DB.php */
/* Location: ./application/models/Manejo_DB.php */
