<?php
class UsuarioModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_general', 'general');
    }

    public function getLideres0()
    {
        $sql = 'SELECT  usua_id AS id, 
                        CONCAT(usua_nombres, " ", usua_apellidos, " (", usua_dni, ")") AS label 
                FROM    usuario 
                WHERE   usua_id IN 
                (
                    SELECT DISTINCT usuario_usua_id 
                    FROM            usuario 
                    WHERE           usuario_usua_id IS NOT NULL
                )';

        $results = $this->db->query($sql)->result();
        $lideres = [];
        $lideres[''] = 'Todos los usuarios';

        foreach($results as $result) { $lideres[$result->id] = $result->label; }

        return $lideres;
    }

    public function getLideres()
    {
        $sql = 'SELECT  usua_id AS id, 
                        CONCAT(usua_nombres, " ", usua_apellidos, " (", usua_dni, ")") AS label  
                FROM    usuario 
                WHERE   usuario_usua_id IS NULL 
                AND     usua_tipo = 1';

        $results = $this->db->query($sql)->result();
        $lideres = [];
        $lideres[''] = 'Todos los usuarios';

        foreach($results as $result) { $lideres[$result->id] = $result->label; }

        return $lideres;
    }

    public function getNoAsesorados()
    {
        $sql = 'SELECT  usua_id AS id, 
                        CONCAT(usua_nombres, " ", usua_apellidos, " (", usua_dni, ")") AS label 
                FROM    usuario 
                WHERE   usua_tipo = 1 
                AND 	usuario_usua_id IS NULL 
                AND     usua_id NOT IN 
                (
                    SELECT DISTINCT usuario_usua_id 
                    FROM            usuario 
                    WHERE           usua_tipo = 1 
                    AND 			usuario_usua_id IS NOT NULL
                );';

        $results = $this->db->query($sql)->result();
        $no_asesorados = [];
        $no_asesorados[''] = 'Seleccione un usuario';

        foreach($results as $result) { $no_asesorados[$result->id] = $result->label; }

        return $no_asesorados;
    }

    public function tieneAsesorados($id)
    {
        $sql = 'SELECT COUNT(*) AS total FROM usuario WHERE usuario_usua_id = ' . $id;
        $result = $this->db->query($sql)->row();
        return $result->total > 0 ? true : false;
    }
}
