<?php
class Model_login extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function login($datas, $init = FALSE)
    {
        $this->db->where($datas);
        $consulta = $this->db->get('usuario');

        if ($consulta->num_rows() > 0) {
            $consulta = $consulta->row();
            $response = array(
                "id" => $consulta->usua_id,
                "intento" => $consulta->usua_intento
            );
            if ($init == TRUE) {
                $datos_usa = array(
                    "authorized" => $consulta->usua_id,
                    "username" => ucwords(strtolower($consulta->usua_nombres)),
                    "userphoto" => $consulta->usua_foto,
                    "usua_tipo" => $consulta->usua_tipo
                );
                if ($consulta->usua_tipo == "1")
                    $datos_usa = array_merge($datos_usa, array("authorizedadmin" => "1"));
                $this->session->set_userdata($datos_usa);
                return $response;
            }
            return $response;
        } else {
            return FALSE;
        }
    }

    function guargar_registro($datas)
    {
        if (isset($datas)) {
            $this->db->trans_start();
            $this->db->set($datas);
            $this->db->insert('usuario');
            // $id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }
    function guargar_edit_registro($datas, $id)
    {

        $this->db->trans_start();
        $this->db->where('usua_id', $id);
        $this->db->update('usuario', $datas);
        // $id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
