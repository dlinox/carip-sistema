<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Persona extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized')) {
            redirect(base_url() . "login");
        }
        $this->load->library('form_validation');
        $this->load->helper('Response');
        $this->load->model('Model_general', 'general');
    }

    function getById($id)
    {
        $this->load->helper('Response');
        showJSON($this->db->where("pers_id", $id)->get("personas")->row());
    }

    public function s2()
    {
        $responese = new StdClass;
        $term = $_GET['term'];
        $datos = array();
        $like =
            [
                'pers_nombres' => $term,
                'pers_apellidos' => $term,
                'pers_dni' => $term,
            ];
        $where = isset($_GET['except']) ? 'pers_id != ' . $_GET['except'] : null;
        $results = $this->general->select2("personas", $like, null, $where);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->pers_id,
                "text" => $value->pers_nombres . ' ' . $value->pers_apellidos . ' (' . $value->pers_dni . ')'
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }

    public function getPersona($id = "")
    {

        $query = $this->db->query("SELECT rubros_id, tipo_alumnos_id, condiciones_id FROM alumnos WHERE alum_pers_id = $id LIMIT 1");
        if ($query->row()) {
            $data['datos'] = $query->row();
            $data['exito'] = true;
        } else {
            $data['exito'] = false;
        }
        echo json_encode($data);
    }

    public function getPersonaApiSunat($dni)
    {

        $token = "2db69874338802b5702221142884116f5a96b446d05e56c69abf12e45104e094";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apiperu.dev/api/dni/$dni?api_token=$token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo  $response;
        }
    }
    public function editarPersona($id)
    {
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('max_length', 'El campo %s no puede tener mas de  %s caracteres');
        $this->form_validation->set_message('valid_email', 'El correo no es valido (ejemplo@gmail.com)');
        $this->form_validation->set_message('exact_length', 'El campo %s debe tener %s caracteres');
        $this->form_validation->set_message('integer', 'El campo %s solo deben ser números');

        $this->form_validation->set_rules('pers_nombres', 'nombre', 'trim|required|max_length[180]');
        $this->form_validation->set_rules('pers_apellidos', 'ruc', 'trim|required');
        $this->form_validation->set_rules('pers_dni', 'ubicacion', 'trim|required|max_length[80]|integer');
        $this->form_validation->set_rules('pers_celular', 'direccion', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $datos = $this->input->post();

        $condicion = array("pers_id" => $id);
        if ($this->general->update_data("personas", $datos, $condicion)  == false) {
            showError('Ocurrió un error al editar los datos');
        } else {
            showSuccess('Datos editados correctamente.');
        }
    }
}
