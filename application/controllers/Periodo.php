<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Periodo extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized')) {
            redirect(base_url() . "login");
        }
        $this->load->model('PeriodoModel', 'model');
        $this->load->helper('Response');
        $this->load->model('Model_general', 'general');
    }

    public function getByCurso($id)
    {
        showData($this->model->getByCursoId($id));
    }

    public function s2GetByCurso($id)
    {
        $responese = new StdClass;
        $where = "peri_prod_id = $id";

        $results = $this->general->select2("periodos", null, null, $where);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->peri_id,
                "text" => $value->peri_anho . " - " . $value->peri_correlativo
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }
}
