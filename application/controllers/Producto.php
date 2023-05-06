<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Producto extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized')) {
            redirect(base_url() . "login");
        }
        $this->load->model('Model_general', 'general');
        $this->load->model('ProductoModel', 'model');
    }

    public function s2($categoria = '')
    {
        $response = new StdClass;
        $term = $this->input->get('term');
        //$categoria = $this->input->get('cate');
        $datos = array();

        $like = ['nombre' => $term];

        if ($categoria != '') {
            $where = ['cate_id' => $categoria];

            $results = $this->general->select2("productos", $like, null, $where);
        } else {
            $results = $this->general->select2("productos", $like);
        }


        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->id,
                "text" => $value->nombre
            );
        }
        $response->total_count = $results["total_count"];
        $response->incomplete_results = false;
        $response->items = $datos;
        echo json_encode($response);
    }

    public function s2alumno()
    {
        $response = new StdClass;
        $term = $this->input->get('term');
        $datos = array();
        $like =
            [
                'pers_nombres' => $term
            ];
        $results = $this->general->select2("personas", $like);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->pers_id,
                "text" => $value->pers_nombres
            );
        }
        $response->total_count = $results["total_count"];
        $response->incomplete_results = false;
        $response->items = $datos;
        echo json_encode($response);
    }

    public function s2ByDocente($id = "")
    {
        $id =  empty($id) ? $this->session->userdata('authorized') : $id;
        echo json_encode($this->model->s2ByDocente($id));
    }

    function getById($id)
    {
        $this->load->helper('Response');
        showJSON($this->db->where("id", $id)->get("productos")->row());
    }
    function getByIdAlumnos($id)
    {
        $this->load->helper('Response');
        showJSON($this->db->where("pers_id", $id)->get("personas")->row());
    }
}
