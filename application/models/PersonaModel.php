<?php
class PersonaModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_general', 'general');
    }

    public function save($data)
    {
        if(is_array($data))
        {
            $persona_id = $this->general->save_data("personas", $data);
            if($persona_id == false) { $this->db->trans_rollback(); return false; }
        }
        else { $persona_id = $data; }

        return $persona_id;
    }
}
