<?php
class AlumnoModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_general', 'general');
    }

    public function save($data_alumno, $data_persona, $data_titular, $llamada_id)
    {
        $this->db->trans_start();

        if(is_array($data_persona))
        {
            $persona_id = $this->general->save_data("personas", $data_persona);
            if($persona_id == false) { $this->db->trans_rollback(); return false; }
        }
        else { $persona_id = $data_persona; }

        $titular_id = null;
        if(is_array($data_titular))
        {
            $titular_id = $this->general->save_data("personas", $data_titular);
            if($titular_id == false) { $this->db->trans_rollback(); return false; }
            $data_alumno['alum_apoderado_id'] = $titular_id;
        }
        else if(is_string($data_titular)) { $titular_id = $data_titular; }

        $data_alumno['alum_pers_id'] = $persona_id;
        $data_alumno['alum_fecha'] = empty($data_alumno['alum_fecha']) ? date('Y-m-d H:i:s') : date($data_alumno['alum_fecha'] . " H:i:s");

        $alumno_id = $this->general->save_data("alumnos", $data_alumno);
        if($alumno_id == false) { $this->db->trans_rollback(); return false; }

        if($llamada_id != '0') { $this->general->update_data('llamadas', ['llam_concretado' => true], 'id_llamada = ' . $llamada_id); }

        $this->db->trans_complete();
        return $alumno_id;
    }

    public function update($condition, $data_alumno, $data_persona, $data_titular)
    {
        $this->db->trans_start();

        if(is_array($data_persona))
        {
            $persona_id = $this->general->save_data("personas", $data_persona);
            if($persona_id == false) { $this->db->trans_rollback(); return false; }
        }
        else { $persona_id = $data_persona; }

        $titular_id = null;
        if(is_array($data_titular))
        {
            $titular_id = $this->general->save_data("personas", $data_titular);
            if($titular_id == false) { $this->db->trans_rollback(); return false; }
        }
        else if(is_string($data_titular)) { $titular_id = $data_titular; }

        $data_alumno['alum_pers_id'] = $persona_id;
        $data_alumno['alum_apoderado_id'] = $titular_id;
        $alumno_id = $this->general->update_data("alumnos", $data_alumno, $condition);
        if($alumno_id == false) { $this->db->trans_rollback(); return false; }

        $this->db->trans_complete();
        return $alumno_id;
    }

    private function getComisionDirecta($usuario_id, $fecha_inicial, $fecha_final)
    {
        $sql = 'SELECT  IFNULL(SUM(comision), 0) AS total, 
                        IFNULL(GROUP_CONCAT(id_alumno), "") AS ids 
                FROM    alumnos 
                JOIN    productos ON productos_id = id
                JOIN    pagos ON alumnos.id_alumno= pagos.alumnos_id_alumno
                WHERE   habilitado
                AND     usuario_usua_id = ' . $usuario_id . ' ' . 
                'AND    DATE(alum_fecha) >= "' . $fecha_inicial . '" ' . 
                'AND    DATE(alum_fecha) <= "' . $fecha_final . '"';

        return $this->db->query($sql)->row();
    }

    private function getComisionPorAsesorado($usuario_id, $fecha_inicial, $fecha_final)
    {
        $sql = 'SELECT  IFNULL(SUM(comision_asesor), 0) AS total, 
                        IFNULL(GROUP_CONCAT(id_alumno), "") AS ids 
                FROM    alumnos 
                JOIN    productos 
                ON      productos_id = id 
                WHERE   habilitado 
                AND     usuario_usua_id IN (SELECT usua_id FROM usuario WHERE usuario_usua_id = ' . $this->user_id . ')' .
                'AND    DATE(alum_fecha) >= "' . $fecha_inicial . '" ' . 
                'AND    DATE(alum_fecha) <= "' . $fecha_final . '"';

        return $this->db->query($sql)->row();
    }

    public function getComisiones($usuario_id, $fecha_inicial, $fecha_final)
    {
        $directo = $this->getComisionDirecta($usuario_id, $fecha_inicial, $fecha_final);
        $asesorado = $this->getComisionPorAsesorado($usuario_id, $fecha_inicial, $fecha_final);
        return compact('directo', 'asesorado');
    }
}