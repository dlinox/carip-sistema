<?php
class ProductoModel extends CI_Model
{

    static $table = 'productos';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_general', 'general');
    }

    public function s2()
    {
        $id = 'id';
        $text = 'nombre';
        $query = 'FROM productos WHERE nombre LIKE "%' . $_GET['term'] . '%"';
        $response = $this->general->select2Query($id, $text, $query);
        echo json_encode($response);
    }

    public function s2ByDocente($docente_id)
    {
        $term = is_null($this->input->get('term')) ? '' : $this->input->get('term');
        $id = 'id';
        $text = 'nombre';
        $query = '  FROM    grupos 
                    JOIN    periodos 
                    ON      grup_peri_id = peri_id 
                    JOIN    productos 
                    ON      peri_prod_id = id' . ' 
                    WHERE   grup_docente_id = ' . $docente_id . '
                    AND     nombre LIKE "%' . $term . '%"';
        $response = $this->general->select2Query($id, $text, $query);
        return $response;
    }

    public function getByGrupo($grup_id)
    {
        $this->db->select('productos.*');
        $this->db->from('grupos');
        $this->db->join('periodos', 'grup_peri_id = peri_id');
        $this->db->join('productos', 'peri_prod_id = id');
        $this->db->where(['grup_id' => $grup_id]);
        return $this->db->get()->row();
    }

    public function getById($id)
    {
        $this->db->from(self::$table);
        $this->db->where(['id' => $id]);
        return $this->db->get()->row();
    }
    
}
