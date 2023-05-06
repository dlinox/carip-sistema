<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grupos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized') && $this->session->userdata('usua_tipo') != 2) {
            redirect(base_url() . "login");
        }
        $this->load->library('Cssjs');
        $this->load->library('form_validation');
        $this->load->library('Ssp');
        $this->jsPath = base_url() . "assets/js/";
        $this->cssPath = base_url() . "assets/css/";
        $this->load->model('Model_general', 'general');
        $this->load->model('GrupoModel', 'model');
        $this->load->model('ProductoModel', 'model_producto');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->load->helper('Response');
        $this->user_id = $this->session->userdata('authorized');
    }
    public function index()
    {
        $this->lista();
    }

    public function crear($id = "")
    {
        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

        if (empty($id)) {
            $grupo = new stdClass();
            $grupo->grup_id = "";
            $grupo->peri_id = "";
            $grupo->grup_docente_id = "";
            $grupo->grup_nombre = "";
            $grupo->usuario_usua_id = "";
            $grupo->id = "";
            $grupo->grup_dias = ['0', '0', '0', '0', '0', '0', '0'];
            $grupo->grup_hora = '';
            $grupo->grup_fechacrea = date('Y-m-d');
        } else {
            $grupo = $this->model->getById($id);
            $grupo->grup_dias = explode(',', $grupo->grup_dias);
        }

        $this->cssjs->add_css($this->cssPath . "bootstrap-multiselect.min");
        $this->cssjs->add_js($this->jsPath . 'bootstrap-multiselect.min.js', false, false);
        $this->cssjs->add_js($this->jsPath . "grupos/form.js?v=2", false, false);

        $script['js'] = $this->cssjs->generate_js();
        $this->load->view('header', $script);
        $this->load->view("grupos/formulario", compact('grupo', 'dias'));
        $this->load->view('footer');
    }

    public function guardar($id = null)
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_rules('curso', 'Curso', 'required');
        $this->form_validation->set_rules('periodo', 'Periodo', 'required');
        $this->form_validation->set_rules('nombre', 'Nombre del grupo', 'required');
        $this->form_validation->set_rules('docente_id', 'Docente', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $nombre = $this->input->post('nombre');
        $curso = $this->input->post('curso');
        $periodo = $this->input->post('periodo');
        $docente_id = $this->input->post('docente_id');
        $hora = $this->input->post('hora');
        $fecha_ini = $this->input->post('fecha_ini');

        $dias = [];
        for ($i = 0; $i < 7; $i++) {
            $dias[$i] = isset($_POST['dias'][$i]) ? 1 : 0;
        }
        $dias = implode(',', $dias);

        if ($periodo == 0) {
            $periodo_data =
                [
                    'peri_anho' => date('Y'),
                    'peri_correlativo' => '1',
                    'peri_prod_id' => $curso,
                ];
        } else {
            $periodo_data = $periodo;
        }

        $data = array(
            "grup_docente_id" => $docente_id,
            "grup_nombre" => $nombre,
            'grup_dias' => $dias,
            'grup_hora' => $hora,
            'grup_fechacrea' => $fecha_ini
        );

        if ($id != null) {
            $condicion = array("grup_id" => $id);
            if ($this->model->update($data, $periodo_data, $condicion)) {
                showSuccess('Alumno editado correctamente');
            } else {
                showError('Ocurrió un error editando al alumno');
            }
        } else {
            $data['grup_finalizado'] = false;
            if ($this->model->save($data, $periodo_data) == false) {
                showError('Ocurrió un error guardando al alumno');
            } else {
                showSuccess('Alumno registrado correctamente');
            }
        }
        //echo json_encode($resp);

        // redirect(base_url() . "Llamadas");
    }

    public function lista($json = false)
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : false;
        $columns = array(
            array('db' => 'grup_id', 'dt' => 'DT_RowId'),
            array('db' => 'nombre', 'dt' => 'CURSO'),
            array('db' => 'CONCAT(peri_anho, "-", peri_correlativo)', 'dt' => 'PERIODO'),
            array('db' => 'grup_correlativo', 'dt' => 'GRUPO'),
            array('db' => 'grup_nombre', 'dt' => 'NOMBRE'),
            array('db' => 'CONCAT(usua_nombres, " ", usua_apellidos)', 'dt' => 'DOCENTE'),
            array('db' => 'IF(grup_finalizado, "Finalizado", "Abierto")', 'dt' => 'ESTADO CURSO'),
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }


        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'grupos';
            $primaryKey = 'grup_id';
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );
            $condiciones = array();
            $joinQuery = "FROM grupos 
                        JOIN usuario ON grup_docente_id = usua_id 
                        JOIN periodos ON grup_peri_id = peri_id 
                        JOIN productos ON peri_prod_id = id";
            $where = "";
            // if (!empty($_POST['mes'])) {
            //     $fecha = explode('-', $_POST['mes']);
            //     //$condiciones[] = "year(fecha_creacion) ='" . $fecha[0] . "' AND month(fecha_creacion) = '" . $fecha[1] . "'";
            // }



            if (isset($_POST['categoria'])) {
                $condiciones[] = 'productos.cate_id = ' . $_POST['categoria'];
            }

            if (isset($_POST['persona'])) {
                $condiciones[] = 'alum_pers_id = ' . $_POST['persona'];
            }

            if (!empty($_POST['grupo_id'])) {
                $condiciones[] = "grup_id = " . $_POST['grupo_id'];
            }
            if (!empty($_POST['curso_id'])) {
                $condiciones[] = "productos.id = " . $_POST['curso_id'];
            }

            // $condiciones[] = "usuario_usua_id =" . $this->user_id;

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
        $datos['titulo'] = "GRUPOS";
        $this->cssjs->add_js($this->jsPath . $this->controller . "/lista.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/lista", $datos);
        $this->load->view('footer');
    }


    public function detalle($id)
    {
        $sql = "
        SELECT 
            G.grup_id, G.grup_nombre, G.grup_finalizado, G.grup_correlativo, G.grup_dias, G.grup_hora, G.grup_fechacrea,
            U.usua_nombres, U.usua_apellidos, U.usua_movil, U.usua_dni,
            P.peri_anho, P.peri_correlativo,
            PR.nombre, PR.costo
        FROM grupos G
        LEFT JOIN usuario U ON G.grup_docente_id = U.usua_id
        LEFT JOIN periodos P ON G.grup_peri_id = P.peri_id
        INNER JOIN productos PR ON  P.peri_prod_id =  PR.id
        WHERE G.grup_id = {$id}";

        $resultado = $this->db->query($sql)->row();


        $dias_semana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];


        $_dias = "";
        $val_dias = explode(',', $resultado->grup_dias);

        $i = 0;
        foreach ($val_dias as $element) {
            $element == '1' ? $_dias .=  $dias_semana[$i] . ", " : '';
            $i++;
        }
        $_dias = substr($_dias, 0, -2);

        //$datos = ['pers_nombres', 'pers_apellidos', 'pers_dni', 'pers_celular'];

        /*$where = [
            "dni" => $dni,

        ];*/

        $alumons = $this->model->getAlumnosInfo($id);

        //$this->general->getData('personas', $datos, $where = null, $order = null);


        $this->load->view('header');
        $this->load->view('grupos/ver_detalle', compact('resultado', '_dias', 'alumons'));
        // $this->load->view('Ventas/completar', compact('venta'));
        $this->load->view('footer');
    }

    public function imprimirDetalleGrupo($id)
    {


        $sql = "
        SELECT 
            G.grup_id, G.grup_nombre, G.grup_finalizado, G.grup_correlativo, G.grup_dias, G.grup_hora, G.grup_fechacrea,
            U.usua_nombres, U.usua_apellidos, U.usua_movil, U.usua_dni,
            P.peri_anho, P.peri_correlativo,
            PR.nombre, PR.costo
        FROM grupos G
        LEFT JOIN usuario U ON G.grup_docente_id = U.usua_id
        LEFT JOIN periodos P ON G.grup_peri_id = P.peri_id
        INNER JOIN productos PR ON  P.peri_prod_id =  PR.id
        WHERE G.grup_id = {$id}";

        $res = $this->db->query($sql)->row();

        $dias_semana = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        //echo $resultado;
        date_default_timezone_set('UTC');
        // Imprime algo como: Monday
        $fechaimpresion = date("d/m/Y");

        $nombre =  $res->nombre;
        $periodo =  $res->peri_anho . " - " .  $res->peri_correlativo;
        $grupo =  $res->grup_nombre . " - " . $res->grup_correlativo;
        $hora =  $res->grup_hora;
        $costo =  $res->costo;
        //$nota_aprobatoria =  $res->notaaprobatoria;

        $_dias = "";
        $val_dias = explode(',', $res->grup_dias);

        $i = 0;
        foreach ($val_dias as $element) {
            $element == '1' ? $_dias .=  $dias_semana[$i] . ", " : '';
            $i++;
        }
        $_dias = substr($_dias, 0, -2);

        $pdf = new FPDF();

        $this->general->templatePdfA4($pdf);
        $pdf->SetY(45); 
        $pdf->SetTitle('DETALLES DEL GRUPO', 1);


        $pdf->Cell(60);
        $pdf->SetTextColor(45, 45, 45);
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(70, 10, utf8_decode('DETALLES DEL GRUPO'), 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->SetLineWidth(0.1);
        $pdf->SetDrawColor(39, 55, 70);
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 10, '1.- DATOS DEL GRUPO: ', 0, 1, 'L');

        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);

        $pdf->Cell(180, 5, 'Curso', 1, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(180, 7, utf8_decode($nombre), 0, 0, 'C');

        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(60, 5, 'Fecha de inicio', 1, 0, 'C');
        $pdf->Cell(60, 5, 'Periodo', 1, 0, 'C');
        $pdf->Cell(60, 5, utf8_decode('Nombre del grupo'), 1, 0, 'C');


        $pdf->Ln(5);
        $pdf->SetFont('courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(60, 7, $res->grup_fechacrea, 0, 0, 'C');
        $pdf->Cell(60, 7, $periodo, 0, 0, 'C');
        $pdf->Cell(60, 7, $grupo, 0, 0, 'C');

        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(100, 5, utf8_decode('Días'), 1, 0, 'C');
        $pdf->Cell(40, 5, utf8_decode('Hora'), 1, 0, 'C');
        $pdf->Cell(40, 5, 'Costo', 1, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(100, 7, utf8_decode($_dias), 0, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode($hora), 0, 0, 'C');
        $pdf->Cell(40, 7, $costo, 0, 0, 'C');

        $pdf->Ln(10);


        $pdf->SetLineWidth(0.1);
        $pdf->SetDrawColor(39, 55, 70);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(95, 10, '2.- DATOS DEL DOCENTE: ', 0, 1, 'L');
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(140, 5, 'Nombre', 1, 0, 'C');
        $pdf->Cell(40, 5, utf8_decode('DNI'), 1, 0, 'C');


        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(140, 7, utf8_decode($res->usua_nombres) . " " . utf8_decode($res->usua_apellidos), 0, 0, 'C');
        $pdf->Cell(40, 7, $res->usua_dni, 0, 0, 'C');


        $pdf->Ln(10);


        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(95, 10, '3.- ALUMNOS: ', 0, 1, 'L');
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(140, 5, 'Nombre(s) y  Apellidos', 1, 0, 'C');
        $pdf->Cell(40, 5, utf8_decode('DNI'), 1, 0, 'C');

        $alumons = $this->model->getAlumnosInfo($id);


        foreach ($alumons["alumnos"] as $alumno) :

            $pdf->Ln(5);
            $pdf->SetFont('Courier', 'B', 13);
            $pdf->Cell(5);
            $pdf->Cell(140, 7, utf8_decode($alumno->nombres) . " " . utf8_decode($alumno->apellidos), 0, 0, 'L');
            $pdf->Cell(40, 7, $alumno->dni, 0, 0, 'C');

            $pdf->Line(15, 265, 195, 265);

        endforeach;


        $pdf->SetY(250);
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->Cell(185, 20, utf8_decode('Emisión: ') . $fechaimpresion, 0, 0, 'R');

        $pdf->Output();
    }

    public function grupos_alumnos()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $nombre = "CONCAT(pers_nombres, ', ', pers_apellidos)";
        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';
        $estado = "IF(usua_habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";
        $tipo = "UPPER(tipo_denominacion)";
        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => $nombre, 'dt' => 'NOMBRES'),
            array('db' => 'nombre', 'dt' => 'CURSO'),
            array('db' => 'grup_id', 'dt' => 'DT_grup_id'),
            // array('db' => 'id', 'dt' => 'DT_RowId'),
            // array('db' => 'fecha_inscripcion', 'dt' => 'fecha_inscripcion'),
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'alumnos';
            $primaryKey = 'fecha_inscripcion';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM
            alumnos 
            JOIN personas ON alum_pers_id = pers_id 
            JOIN grupos_alumnos ON gral_id_alumno = id_alumno 
            JOIN grupos ON gral_grup_id = grup_id 
            JOIN periodos ON grup_peri_id = peri_id 
            JOIN productos ON peri_prod_id = id";

            $where = "";

            // if (!empty($_POST['id_grupo_academico']))
            //     $condiciones[] = "id_grupo_academico = " . $_POST['id_grupo_academico'];

            $grupo_id = isset($_POST['grupo_id']) ? $_POST['grupo_id'] : 0;
            $condiciones[] = "gral_grup_id = " . $grupo_id;

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "GRUPOS";
        // $datos["grupo_academico"] = $this->general->getOptions('grupo_academico', array("id", "nombre"), 'Todos los grupos');

        $this->cssjs->add_js(base_url() . "assets/js/grupos/grupos_alumnos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/grupos_alumnos", $datos);
        $this->load->view('footer');
    }

    public function buscar_alumnos()
    {
        $response = new StdClass();

        $curso_id = $_GET['curso_id'] ? $_GET['curso_id'] : 0;

        $termino = isset($_GET['term'])
            ? $_GET['term']
            : false;

        $where = $termino
            ?  "AND ( pers_nombres LIKE '%$termino%' OR pers_apellidos LIKE '%$termino%' OR pers_dni LIKE '%$termino%' )"
            : '';

        $producto = $this->model_producto->getById($curso_id);

        $libre = false;

        if ($producto) {
            $libre = $producto->libre == 0 ? false : true;
        }

        if ($libre) {

            $cate_id = $producto->cate_id;

            $sql = "SELECT      alumnos.id_alumno AS id,
                            CONCAT('DNI:', pers_dni, ' - ', pers_nombres,'  ', pers_apellidos) AS text
                FROM        alumnos 
                JOIN        personas ON alum_pers_id = pers_id 
                LEFT JOIN   grupos_alumnos ON gral_id_alumno = id_alumno
                LEFT JOIN   productos P ON P.id = productos_id
                LEFT JOIN   categorias C ON C.cate_id = P.cate_id
                WHERE       C.cate_id = " . $cate_id . " 
                AND         gral_grup_id IS NULL
                $where";
        } else {

            $sql = "SELECT      alumnos.id_alumno AS id,
                            CONCAT('DNI:', pers_dni, ' - ', pers_nombres) AS text
                FROM        alumnos 
                JOIN        personas ON alum_pers_id = pers_id 
                LEFT JOIN   grupos_alumnos ON gral_id_alumno = id_alumno
                WHERE       productos_id = " . $curso_id . " 
                AND         gral_grup_id IS NULL 
                $where";
        }

        $alumnos = $this->db->query($sql)->result();
        $response->total_count = count($alumnos);
        $response->incomplete_results = false;
        $response->items = $alumnos;
        $response->curso = $libre;

        echo json_encode($response);
    }

    public function asignar_grupo()
    {

        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_rules('id_alumno', 'Alumno', 'required');
        $this->form_validation->set_rules('grupo_id', 'Grupo', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $id_alumno = $this->input->post('id_alumno');
        $id_grupo_academico = $this->input->post('grupo_id');

        $sql = 'SELECT productos.sesiones
                FROM periodos
                JOIN grupos ON periodos.peri_id = grupos.grup_peri_id
                JOIN productos ON periodos.peri_prod_id = productos.id
                WHERE grupos.grup_id = ' . $id_grupo_academico;

        $resultado = $this->db->query($sql)->row();

        $sesiones = intval($resultado->sesiones) - 1;

        $asistencias = '';
        for ($i = 0; $i < $sesiones; $i++) {
            $asistencias = $asistencias . '0' . ',';
        }

        $data = array(
            "gral_id_alumno" => $id_alumno,
            "gral_grup_id" => $id_grupo_academico,
            "gral_asistencias" => $asistencias
        );

        $gral_id = $this->general->save_data("grupos_alumnos", $data);

        if ($gral_id === false) {

            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        } else {

            $sql = "SELECT nive_id, nive_nombre, nive_cantidad_notas 
                        FROM niveles WHERE grupo_id = $id_grupo_academico ";
            $niveles = $this->db->query($sql)->result();

            foreach ($niveles as $nivel) {
                $notas = '';
                for ($i = 0; $i  < $nivel->nive_cantidad_notas; $i++) {
                    $i == ($nivel->nive_cantidad_notas - 1) ? $notas .= '0' : $notas .= '0,';
                }
                $nota = [
                    'gral_id' => $gral_id,
                    'nive_id' => $nivel->nive_id,
                    'nota_promedio' => '00',
                    'nota_notas' => $notas,
                ];
                if ($this->general->save_data('notas', $nota) == false) {
                    $resp["exito"] = false;
                    $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
                }
            }

            $resp["exito"] = true;
            $resp["mensaje"] = "alumno registrado con exito";
        }
        echo json_encode($resp);
    }
    public function eliminar($id)
    {
        $resp = [];
        $alumnos = $this->db->get_where('grupos_alumnos', ['gral_grup_id' => $id])->result();
        if (sizeof($alumnos) > 0) {
            $resp["exito"] = false;
            $resp["mensaje"] = "El grupo contiene alumnos, eliminelos para eliminar el grupo";
        } else {
            $this->db->trans_start();
            $this->general->delete_data("grupos", array("grup_id" => $id));
            $this->db->trans_complete();

            $resp = [];
            if ($this->db->trans_status() === false) {
                $resp["error"] = false;
                $resp["mensaje"] = "Error al tratar de eliminar la venta";
            } else {
                $resp["exito"] = true;
                $resp["mensaje"] = "Eliminado con exito";
            }
        }
        echo json_encode($resp);
    }
    public function finalizar($id)
    {
        if ($this->general->update_data('grupos', ['grup_finalizado' => 1], array("grup_id" => $id))) {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        } else {
            $json["exito"] = false;
            $json["mensaje"] = "Error al tratar de eliminar el alumno";
        }
        echo json_encode($json);
    }
    public function eliminar_grupo_alumno($grupo_id, $id)
    {
        $data = array(
            "gral_grup_id" => $grupo_id,
            'gral_id_alumno' => $id,
        );
        if ($this->general->delete_data('grupos_alumnos', $data)) {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        } else {
            $json["exito"] = false;
            $json["mensaje"] = "Error al tratar de eliminar el alumno";
        }
        echo json_encode($json);
    }

    public function getByPeriodo($id, $docente_id = 0)
    {
        if ($this->session->userdata('usua_tipo') == 5) {
            $docente_id = $this->session->userdata('authorized');
        }
        showData($this->model->getByPeriodoId($id, $docente_id));
    }

    public function s2GetByPeriodo($id, $docente_id = 0)
    {

        $responese = new StdClass;
        $where = "grup_peri_id = $id AND grup_docente_id = $docente_id";

        $results = $this->general->select2("grupos", null, null, $where);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->grup_id,
                "text" => $value->grup_nombre . " - " . $value->grup_correlativo
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }

    public function s2NivelesByGrupo($id)
    {

        $responese = new StdClass;
        $where = "grupo_id = $id";

        //$niveles = $this->db->where("grupo_id", $id)->get("niveles")->();
        $results = $this->general->select2("niveles", null, null, $where);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->nive_id,
                "text" => $value->nive_nombre
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }

    public function getByPeriodoAndDocente($id)
    {
        showData($this->model->getByPeriodoId($id, $this->session->userdata('authorized')));
    }

    public function getAsistencias($grupo_id)
    {
        showData($this->model->getAlumnosInfo($grupo_id));
    }
    public function getNotas($nivele, $grupo_id)
    {
        showData($this->model->getAlumnosInfoNotas($nivele, $grupo_id));
    }
    public function getPagos($grupo_id)
    {
        showData($this->model->getAlumnosInfo($grupo_id));
    }

    public function getNotasNiveles($grupo_id)
    {
        showData($this->model->getAlumnosInfoPromedios($grupo_id));
    }

    /***
     * AGREGAR Y ELIMINAR ASISTENCIAS
     */

    public function Asistencia($accion, $grupo)
    {
        try {
            $asistencias = $this->db->where("gral_grup_id", $grupo)->get("grupos_alumnos")->result();
            if (!empty($asistencias)) {

                foreach ($asistencias as $alumno) {
                    $alm_asistencia = explode(',', $alumno->gral_asistencias);
                    if ($accion == 'agregar') {
                        array_push($alm_asistencia, (string)"0");
                    } else {
                        array_pop($alm_asistencia);
                    }
                    $alm_asistencia = implode(",", $alm_asistencia);

                    $condicion = ['gral_grup_id' => $grupo, 'gral_id_alumno' => $alumno->gral_id_alumno];
                    $data = ['gral_asistencias' => $alm_asistencia];

                    $this->general->update_data('grupos_alumnos', $data, $condicion);
                }
            }

            $responese["exito"] = true;
            $responese["mensaje"] = "Exito";
            echo json_encode($responese);
        } catch (\Throwable $th) {
            $responese["exito"] = false;
            $responese["mensaje"] = $th;
            echo json_encode($responese);
        }
    }
}
