<?php
class LlamadaModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_general', 'general');
    }

    public function save($data_llamada, $data_persona)
    {
        $this->db->trans_start();
        $persona_id = $this->personaModel->save($data_persona);
        $data_llamada['llam_pers_id'] = $persona_id;
        $llamada_id = $this->general->save_data("llamadas", $data_llamada);
        if($llamada_id == false) { $this->db->trans_rollback(); return false; }
        $this->db->trans_complete();
        return $llamada_id;
    }

    public function update($condition, $data_llamada, $data_persona)
    {
        $this->db->trans_start();
        $persona_id = $this->personaModel->save($data_persona);
        $data_llamada['llam_pers_id'] = $persona_id;
        $llamada_id = $this->general->update_data("llamadas", $data_llamada, $condition);
        if($llamada_id == false) { $this->db->trans_rollback(); return false; }
        $this->db->trans_complete();
        return $llamada_id;
    }
}