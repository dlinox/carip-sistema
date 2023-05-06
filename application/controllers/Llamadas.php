<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LLamadas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized') && $this->session->userdata('usua_tipo')!= 2) {
            redirect(base_url() . "login");
        }
        $this->load->library('Cssjs');
        $this->load->library('form_validation');
        $this->load->library('Ssp');
        $this->jsPath = base_url() . "assets/js/";
        $this->load->model('Model_general', 'general');
        $this->load->model('LlamadaModel', 'model');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->load->helper('Response');
        $this->user_id = $this->session->userdata('authorized');
        $this->user_tipo_id = $this->session->userdata('usua_tipo');
        error_reporting(0);
        ini_set('display_errors', 0);
    }
    public function index()
    {
        $this->lista();
    }
    public function crear($id = "")
    {
        $datos = [];
        $datos["estado"] = $this->general->getOptions('respuestas', array("id", "nombre"));

        if (empty($id)) {
            $llamada = new stdClass();
            $llamada->id_llamada = "";
            $llamada->dni = "";
            $llamada->nombre = "";
            $llamada->apellidos = "";
            $llamada->celular = "";
            $llamada->respuestas_id = "";
        } else {
            $llamada = $this->db->where("id_llamada", $id)->get("llamadas")->row();
        }
        $datos["llamada"] = $llamada;

        $this->cssjs->add_js($this->jsPath . "Llamadas/form.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();
        $this->load->view('header', $script);
        $this->load->view($this->controller . "/formulario", $datos);
        $this->load->view('footer');
    }
    public function guardar($id = null)
    {
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_rules('respuestas_id', 'Respuesta', 'required');
        $this->form_validation->set_rules('prod_id', 'Producto', 'required');

        if(isset($_POST['agregar_persona']))
        {
            $this->form_validation->set_rules('dni', 'Dni', 'required');
            $this->form_validation->set_rules('nombre', 'Nombre', 'required');
            $this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
            $this->form_validation->set_rules('celular', 'Celular', 'required');
        }
        else { $this->form_validation->set_rules('persona_id', 'Datos del alumno', 'required'); }

        if ($this->form_validation->run() == FALSE)
        {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        if(isset($_POST['agregar_persona']))
        {
            $dni = $this->input->post('dni');
            $nombre = $this->input->post('nombre');
            $apellidos = $this->input->post('apellidos');
            $celular = $this->input->post('celular');
        }

        $respuestas_id = $this->input->post('respuestas_id');
        $producto_id = $this->input->post('prod_id');

        $persona_data = !isset($_POST['agregar_persona']) ? $_POST['persona_id'] : 
        [
            "pers_dni" => $dni,
            "pers_nombres" => $nombre,
            "pers_apellidos" => $apellidos,
            "pers_celular" => $celular,
        ];
        
        $data = array
        (
            "respuestas_id" => $respuestas_id,
            "usuario_usua_id" => $this->user_id, 
            'llam_prod_id' => $producto_id, 
            'llam_concretado' => false
        );

        if($id != null)
        {
            $condicion = array("id_llamada" => $id);
            if($this->model->update($condicion, $data, $persona_data) == false)
            {
                showError('Ocurrió un error editando la llamada');
            }
            else { showSuccess('Llamada editada correctamente'); }
        }
        else
        {
            if($this->model->save($data, $persona_data) == false)
            {
                showError('Ocurrió un error guardando la llamada');
            }
            else { showSuccess('Llamada registrada correctamente'); }
        }
        echo json_encode($resp);
        // redirect(base_url() . "Llamadas");
    }
    public function lista($json = false)
    {
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : false;
        $columns = array(
            array('db' => 'id_llamada', 'dt' => 'DT_RowId'),
            array('db' => 'id_llamada', 'dt' => 'ID'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'pers_nombres', 'dt' => 'NOMBRES'),
            array('db' => 'pers_apellidos', 'dt' => 'APELLIDOS'),
            array('db' => 'pers_celular', 'dt' => 'N° CELULAR'),
            // array('db' => 'respuestas.nombre', 'dt' => 'Estado'),
            array('db' => 'CONCAT(rptas.nombre)', 'dt' => 'ESTADO'),
            array('db' => 'DATE_FORMAT(fecha, "%d/%m/%Y a las %h:%i %p")', 'dt' => 'FECHA DE LLAMADA'),
            array('db' => 'IF(llam_concretado, "SI", "NO")', 'dt' => 'VENTA CONCRETADA'),
        );
        if($this->user_tipo_id == 2)
        {
            $index = 7;
            array_splice($columns, $index, 0, [[]]);
            $columns[$index]['db'] = 'CONCAT(usua_nombres, " ", usua_apellidos)';
            $columns[$index]['dt'] = 'VENDEDOR';
        }
        foreach ($columns as &$item) { $item['field'] = $item['db']; }

        $datos["estado"] = $this->general->getOptions('respuestas', array("id", "nombre"), 'Seleccione una respuesta');

        if($json)
        {
            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'llamadas';
            $primaryKey = 'id_llamada';
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );
            
            $joinQuery = "
            FROM llamadas 
            JOIN personas ON llam_pers_id = pers_id 
            JOIN respuestas AS rptas on rptas.id = respuestas_id 
            JOIN productos ON llam_prod_id = productos.id 
            JOIN usuario ON llamadas.usuario_usua_id = usua_id";

            $condiciones = array();
            $condiciones[] = 'llam_concretado = ' . $_POST['concretado'];

            $where = "";
            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            if($this->input->post('cate_id')){
                $condiciones[] = 'productos.cate_id = ' . $this->input->post('cate_id');
            }

            if (!empty($_POST['respuestas_id'])) { $condiciones[] = "rptas.id =" . $_POST['respuestas_id']; }

            // Si no es un usuario administrador ...
            if($this->user_tipo_id != 2) { $condiciones[] = "llamadas.usuario_usua_id =" . $this->user_id; }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            $groupby = "";
            if ($pdf) {
                unset($_POST['start']);
                $data = $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupby);
                return $data['data'];
            } else {
                echo json_encode(
                    $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupby)
                );
                exit(0);
            }
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Llamadas";
        // $datos["proveedores"] = $this->general->getOptions("proveedor", array("prov_id", "prov_nombre"), "* Proveedor");

        $this->cssjs->add_js($this->jsPath . $this->controller."/lista.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/lista", $datos);
        $this->load->view('footer');
    }
    public function getcomisiones()
    {
        $fecha = explode('-', $_POST['mes']);
        //comision por asesores
        $cantidad_ventas =  $this->db->query(
            "SELECT 
                COUNT(alumnos.id_alumno) cantidad_ventas
            FROM
                usuario
                    LEFT JOIN
                alumnos ON alumnos.usuario_usua_id = usuario.usua_id
                    LEFT JOIN
                productos ON productos.id = alumnos.productos_id
            WHERE
                usuario.usuario_usua_id = {$this->user_id}
                AND YEAR(fecha_inscripcion) = {$fecha[0]}
                AND MONTH(fecha_inscripcion) = {$fecha[1]}
                "
        )->row()->cantidad_ventas;
        $datos['comision_asesor'] = $cantidad_ventas * 5;
        $datos['comision_directa'] =  $this->db->query(
            "SELECT 
                sum(productos.comision) comision_directa
            FROM
                usuario
                    LEFT JOIN
                alumnos ON alumnos.usuario_usua_id = usuario.usua_id
                    LEFT JOIN
                productos ON productos.id = alumnos.productos_id
            WHERE
                usuario.usua_id = {$this->user_id}
                AND YEAR(fecha_inscripcion) = {$fecha[0]}
                AND MONTH(fecha_inscripcion) = {$fecha[1]}
                "
        )->row()->comision_directa;
        $datos['comision_directa'] = $datos['comision_directa']!=null?$datos['comision_directa']:0;
        $datos['comision_total'] = $datos['comision_asesor'] + $datos['comision_directa'];
        echo json_encode($datos);
    }
}
