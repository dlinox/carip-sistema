<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Empresa extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized')) {
            redirect(base_url() . "login");
        }
        if ($this->session->userdata('usua_tipo') != 2) {
            redirect(base_url());
        }

        $this->load->model('Model_general', 'general');
        $this->load->model('ProductoModel', 'model');

        $this->load->library('Cssjs');
        $this->load->library('form_validation');
        $this->load->library('Ssp');
        $this->jsPath = base_url() . "assets/js/";
        $this->load->model('Model_general', 'general');

        $this->load->helper('Response');

        $this->load->helper('Functions');
        $this->user_id = $this->session->userdata('authorized');
    }

    public function index()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $this->load->library('user_agent');

        $datos['titulo'] = "Mi Empresa";

        $empresa = $this->db->get("empresa")->row();

        $datos["empresa"] = $empresa;

        $this->cssjs->add_js($this->jsPath . "configuracion/empresa.js", false, false);
        $this->load->view('header');
        $this->load->view("configuracion/empresa", $datos);
        $this->load->view('footer');
    }

    public function guardar($id = '1')
    {
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('max_length', 'El campo %s no puede tener mas de  %s caracteres');
        $this->form_validation->set_message('valid_email', 'El correo no es valido (ejemplo@gmail.com)');
        $this->form_validation->set_message('exact_length', 'El campo %s debe tener %s caracteres');
        $this->form_validation->set_message('integer', 'El campo %s solo deben ser números');

        $this->form_validation->set_rules('empr_nombre', 'nombre', 'trim|required|max_length[180]');
        $this->form_validation->set_rules('empr_ruc', 'ruc', 'trim|required|exact_length[11]|integer');
        $this->form_validation->set_rules('empr_ubicacion', 'ubicacion', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('empr_direccion', 'direccion', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('empr_correo', 'correo', 'trim|required|max_length[100]|valid_email');
        $this->form_validation->set_rules('empr_numero', 'numero', 'trim|required|max_length[20]');

        if ($this->form_validation->run() == FALSE) {

            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $datos = $this->input->post();
        $datos['empr_ultima_actualizacion'] = date("Y-m-d H:i:s");
        $datos['usua_actualizo'] = $this->user_id;
        $datos['usua_ip_actualizo'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'local';
        $condicion = array("empr_id" => $id);
        if ($this->general->update_data("empresa", $datos, $condicion)  == false) {
            showError('Ocurrió un error al editar los datos');
        } else {
            showSuccess('Datos editados correctamente.');
        }
    }

    public function logo($id = '1')
    {
        if (empty($id)) {
            $empresa = new stdClass();
            $empresa->empr_id = "";
            $empresa->empr_logo = "";
        } else {
            $empresa = $this->db->where("empr_id", $id)->get("empresa")->row();
        }
        $data["empresa"] = $empresa;
        $this->load->view("configuracion/form_emprlogo", $data);
    }

    public function logoGuardar($id = "1")
    {

        $pathFoto = 'assets/img/';
        $config['upload_path']          = $pathFoto;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 2000;
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = FALSE;
        $config['remove_spaces'] = TRUE;
        $config['file_name'] = 'logo';

        $response["file"] = true;

        if (!empty($_FILES["foto"]["name"])) {

            $this->load->library('upload',  $config);

            if (!$this->upload->do_upload('foto')) {

                $error = array('error' => $this->upload->display_errors());
                $response['exito'] = true;
                $response['file'] = false;
                $response['file_mensaje'] = $error;
                echo json_encode($response);
                die();
            } else {
                $data = array('upload_data' => $this->upload->data());
                $logo_empresa = $data['upload_data']['file_name'];
                $empresa['empr_logo'] = $logo_empresa;
            }
        }

        $condicion = array("empr_id" => $id);

        if ($this->general->update_data("empresa", $empresa, $condicion)) {
            $response['logo'] = $logo_empresa;
            $response['exito'] = true;
            echo json_encode($response);
            die();
        } else {
            showError('Ocurrió un error al editar los datos');
        }
    }

    public function getNotificaciones()
    {
        if ($this->session->userdata('usua_tipo') == 2) {
            $query = "SELECT COUNT(id_alumno) AS cantidad
                FROM alumnos
                WHERE alum_completado = 0";
            $query2 = "SELECT   COUNT(id_alumno) AS cantidad
                FROM alumnos JOIN personas ON alum_pers_id = pers_id 
                LEFT JOIN grupos_alumnos ON gral_id_alumno = id_alumno
                WHERE gral_grup_id IS NULL";
            $query3 = "SELECT  IFNULL(SUM(importe_flujo), 0) AS total
                FROM flujocaja
                WHERE fecha_flujo = CURDATE()";

            $data['sin_completar'] = $this->db->query($query)->row();
            $data['sin_grupo'] = $this->db->query($query2)->row();
            $data['gastos_dia'] = $this->db->query($query3)->row();

            $responese["exito"] = true;
            $responese["notificaiones"] = $data;
        }
        else{
            $responese["exito"] = false;
            $responese["mensaje"] = 'No autorizado';
        }
        echo json_encode($responese);
    }
}
