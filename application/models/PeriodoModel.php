<?php
class PeriodoModel extends CI_Model
{
    static $table = 'periodos';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getByCursoId($id)
    {
        $this->db->select('peri_id AS id, CONCAT(peri_anho, "-", peri_correlativo) AS label');
        $this->db->from(self::$table);
        $this->db->order_by('peri_anho', 'ASC');
        $this->db->order_by('peri_correlativo', 'ASC');
        $this->db->where(['peri_prod_id' => $id]);
        $results = $this->db->get()->result();
        if(sizeof($results) == 0)
        {
            array_push($results, ['id' => 0, 'label' => date('Y') . '-1']);
        }
        return $results;
    }

    public function save($data)
    {
        if($this->get($data)) { return false; }
        else
        {
            $this->db->insert(self::$table, $data);
            if ($this->db->affected_rows() > 0) { return $this->db->insert_id(); }
            else { return false; }
        }
    }

    public function get($where)
    {
        $this->db->from(self::$table);
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
