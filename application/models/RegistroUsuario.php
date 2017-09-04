<?php
class RegistroUsuario extends CI_Model
{
    private $res;

    public function __construct()
    {
        parent:: __construct();
    }

    private function obtenerFecha()
    {
        $hoy = getdate();
        return $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];
    }

    private function insertarUsuario($numero_cuenta, $estado='1')
    {
        $id = 0;
        $datosUsuario = array('numero_cuenta' => $numero_cuenta,'estado'  => $estado);
        $dat = $this->db->insert('usuario', $datosUsuario);
        if ($dat == true) {
            $this->db->select('idusuario');
            $this->db->where('numero_cuenta', $numero_cuenta);
            $id = $this->db->get('usuario');
            return $id->row()->idusuario;
        } else {
            return 0;
        }
    }

    private function insertarDispositivo($mac, $modelo, $estado='1')
    {
        $id = 0;
        $datosDispositivos = array(
            'mac' => $mac,
            'modelo' => $modelo,
            'estado' => 1
        );
        $dat = $this->db->insert('dispositivo', $datosDispositivos);
        if ($dat == true) {
            $this->db->select('iddispositivo');
            $this->db->where('mac', $mac);
            $id = $this->db->get('dispositivo');
            return $id->row()->iddispositivo;
        } else {
            return 0;
        }
    }

    public function insertarPosicion($lat = '0', $long = '0')
    {
        $datosPosicion = array(
           'latitud' => $lat,
           'longitud' => $long,
           'fecha ' => $this->obtenerFecha()
        );
        $dat = $this->db->insert('posicion', $datosPosicion);
        if ($dat == true) {
            $this->db->select('idposicion');
            $this->db->where('latitud', $lat);
            $this->db->where('longitud', $long);
            $busqueda = $this->db->get('posicion');
            return $busqueda->row()->idposicion;
            // return $this->db->insert_id();
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
            'estado' => 0,
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
        $idUsuario = $this->insertarUsuario($NoC, 1);
        $idDispositivo = $this->insertarDispositivo($mac, $modelo, 1);
        $idPosicion = $this->insertarPosicion($idUsuario, $idDispositivo);
        if ($idUsuario != 0 && $idDispositivo != 0 && $idPosicion != 0) {
            $rel = ($this->crearRelacion($idUsuario, $idDispositivo, $idPosicion)) ? true : false ;
            return $rel;
        } else {
            return false;
        }
    }

    public function inicio($mac, $noC)
    {
        $query = $this->db->query('select * from dispositivo d inner join sesion s on d.iddispositivo = s.dispositivo_iddispositivo inner join usuario u on s.usuario_idusuario = u.idusuario where u.numero_cuenta = "'.$noC.'" and d.mac = "'.$mac.'";');
        if ($query->row() != null) {
            $respuesta = array(
                'flag' => true,
                'mensaje' => 'inicio de secion correcto'
            );
            $data = array(
                'fecha' => $this->obtenerFecha(),
                'estado' => 1
            );
            $this->db->where('idsesion', $query->row()->idsesion);
            $this->db->update('sesion', $data);
        } else {
            $respuesta = array(
                'flag' => false,
                'mensaje' => 'usuario no registrado'
            );
        }
        return $respuesta;
    }
}

/* End of file RegistroUsuario.php */
/* Location: ./application/models/RegistroUsuario.php */
