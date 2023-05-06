<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AreaAcademica extends CI_Controller
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
        $this->load->model('GrupoModel', 'grupoModel');
        $this->load->model('Model_general', 'general');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->load->helper('Response');
        $this->user_id = $this->session->userdata('authorized');
        $this->user_tipo_id = $this->session->userdata('usua_tipo');
        // error_reporting(0);
        // ini_set('display_errors', 0);        
    }
    public function index()
    {
        if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 4) {
            $this->ventanacobranza();
        } else {
            $this->alumnos();
        }
    }

    public function ventanacobranza($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/iniciocobranza");
        $this->load->view('footer');
    }

    public function implode_query($separator, $arr)
    {
        $text = "";
        foreach ($arr as $key => $value) {
            $text .= "'" . $value . "'" . $separator;
        }
        $text = substr($text, 0, -1);
        return $text;
    }
    public function asistencia($json = false)
    {
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $this->cssjs->add_js($this->jsPath . "AreaAcademica/asistencia.js?v=1.0", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/asistencia");
        $this->load->view('footer');
    }
    public function asistencia_guardar()
    {
        $grup_id = $this->input->post('grup_id');
        $sesiones = $this->input->post('sesiones');
        $asistencias = $this->input->post('asistencias');
        $data = [];

        foreach ($asistencias as $key => $asistencia) {
            $_asistencia = [];
            for ($i = 0; $i < $sesiones; $i++) {
                array_push($_asistencia, isset($asistencia[$i]) ? 1 : 0);
            }
            array_push($data, ['grup_id' => $grup_id, 'id_alumno' => $key, 'asistencias' => implode(',', $_asistencia)]);
        }

        if ($this->grupoModel->guardarAsistencias($data)) {
            $resp["exito"] = true;
            $resp["mensaje"] = "datos guardados correctamente";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "error al actualizar los datos";
        }

        echo json_encode($resp);
    }
    public function notas($json = false)
    {
        $this->cssjs->add_js($this->jsPath . "AreaAcademica/notas.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/notas");
        $this->load->view('footer');
    }

    public function nota_aprobatoria($id)
    {
        $sql = 'SELECT notaaprobatoria FROM grupos
        JOIN periodos ON periodos.peri_id = grupos.grup_peri_id
        JOIN productos ON productos.id = periodos.peri_prod_id
        WHERE grup_id = ' . $id;

        $resultado = $this->db->query($sql)->row();

        $nota = $resultado->notaaprobatoria;

        print_r($nota);
    }


    public function notas_guardar()
    {
        $grup_id = $this->input->post('grup_id');
        //$sesiones = $this->input->post('sesiones');

        $notas = $this->input->post('notas');
        $data = [];
        //echo '<pre>';
        //var_dump($notas);
        //echo '</pre>';

        foreach ($notas as $key => $nota) {
            $promeido = 0;
            foreach ($nota as $val) {
                $promeido += $val;
            }
            $promeido =  $promeido / count($nota);
            array_push(
                $data,
                [
                    'grup_id' => $grup_id,
                    'nota_id' => $key,
                    'notas' => implode(',', $nota),
                    'promedio' => round($promeido)
                ]
            );
        }

        if ($this->grupoModel->guardarNotas($data)) {
            $resp["exito"] = true;
            $resp["mensaje"] = "datos guardados correctamente";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "error al actualizar los datos";
        }

        echo json_encode($resp);
    }
    /* public function pagos($json = false)
     {
         $json = isset($_GET['json']) ? $_GET['json'] : false;
         if ($json) {
             $grupo_academico = isset($_POST['grupo_academico']) ? $_POST['grupo_academico'] : false;
             $query = "SELECT 
             alumnos.dni,
             concat(alumnos.nombre,' ',alumnos.apellidos) nombre,
             coalesce(alumnos.cuotas,0) cuotas,
             alumnos.id_alumno,
             coalesce(alumnos.observacion,'-') observacion
         FROM
             grupo_academico
                 LEFT JOIN
             grupo_academico_alumnos ON grupo_academico_alumnos.id_grupo_academico = grupo_academico.id
                 INNER JOIN
             alumnos ON alumnos.id_alumno = grupo_academico_alumnos.id_alumno
         WHERE
             grupo_academico.usuario_usua_id =  $this->user_id 
             AND grupo_academico.id = $grupo_academico
                 ";
             $data = $this->db->query($query)->result();
             echo json_encode($data);
             exit(0);
         }
         $datos["grupo_academico"] = $this->general->getOptions('grupo_academico', array("id", "nombre"), false, null, array("usuario_usua_id" => $this->user_id));
         $this->cssjs->add_js($this->jsPath . "AreaAcademica/pagos.js?v=1.1", false, false);
         $this->load->view('header');
         $this->load->view($this->controller . "/pagos", $datos);
         $this->load->view('footer');
     } */
    public function pagos()
    {
        $this->cssjs->add_js($this->jsPath . "AreaAcademica/pagos.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/pagos");
        $this->load->view('footer');
    }
    public function pagos_guardar()
    {
        $keys  = array_keys($_POST);
        if (!empty($keys)) {
            $values = "";
            foreach ($keys as $key => $value) {
                $asistencias = $_POST[$value];
                $test = "(" . $this->implode_query(',', $asistencias) . "),";
                $values .= $test;
            }
            $values = substr($values, 0, -1);

            $query = "INSERT INTO alumnos (id_alumno,observacion)
            VALUES $values
            ON DUPLICATE KEY UPDATE
               observacion = values(observacion)
            ";
            if ($this->db->query($query)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "datos guardados correctamente";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "error al actualizar los datos";
            }
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "error al actualizar los datos";
        }

        echo json_encode($resp);
    }

    public function alumnos()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;


        $porcentaje_pagado = 'CONCAT("<div class=\'barra\'>", ROUND(IFNULL(SUM(monto),0) * 100 / (productos.cuotas * costo - IFNULL(alum_descuento, 0)), 2), "</div>")';
        //$numcuotas = 'CONCAT("<div class=\'icono\'></div> ",(productos.cuotas*1))';
        $iconos = 'CONCAT("<div class=\'iconos\'>",(productos.cuotas * 1), "</div>")';

        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'pers_nombres', 'dt' => 'NOMBRES'),
            array('db' => 'pers_apellidos', 'dt' => 'APELLIDOS'),
            array('db' => 'nombre', 'dt' => 'CURSO'),
            array('db' => 'grup_nombre', 'dt' => 'GRUPO'),
            array('db' => 'CONCAT(peri_anho, "-", peri_correlativo)', 'dt' => 'PERIODO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(SUM(IFNULL(monto,0)),2))', 'dt' => 'PAGADO'),
            array('db' => 'IF(alum_es_becado, "S/. 0.00" , CONCAT("S/.", " ", ROUND( (costo * productos.cuotas - IFNULL(alum_descuento, 0))  - IFNULL(SUM(monto),0) ,2)))', 'dt' => 'DEUDA'),
            array('db' => '(productos.cuotas*1)', 'dt' => 'DT_CUOTAS'),
            array('db' => '(costo * productos.cuotas - IFNULL(alum_descuento, 0))', 'dt' => 'DT_COSTO'),
            array('db' => $iconos, 'dt' => 'ICONOS'),
            array('db' => $porcentaje_pagado, 'dt' => 'PAGADO % '),
            array('db' => 'id_alumno', 'dt' => 'DT_id'),
            array('db' => 'alum_es_becado', 'dt' => 'DT_es_becado'),
            array('db' => 'grup_id', 'dt' => 'DT_grup_id')
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {
            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'alumnos';
            $primaryKey = 'id_alumno';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );
            //IF(libre10, "MORE", "LESS")

            $condiciones = array();
            $joinQuery = "FROM alumnos
                    JOIN personas ON alum_pers_id = pers_id 
                    JOIN grupos_alumnos ON gral_id_alumno = id_alumno 
                    JOIN grupos ON gral_grup_id = grup_id 
                    JOIN periodos ON grup_peri_id = peri_id
                    JOIN productos ON productos_id = id
                    LEFT JOIN pagos ON id_alumno = alumnos_id_alumno";


            $where = "";
            $groupBy = "id_alumno";

            //$groupBy = "personas.pers_id";

            if (isset($_POST['categoria'])) {
                $condiciones[] = 'productos.cate_id = ' . $_POST['categoria'];
            }

            if (isset($_POST['persona'])) {
                $condiciones[] = 'alum_pers_id = ' . $_POST['persona'];
            }

            if (!empty($_POST['grupo_id'])) {
                $condiciones[] = "gral_grup_id = " . $_POST['grupo_id'];
            }
            if (!empty($_POST['curso_id'])) {
                //$condiciones[] = "productos.id = " . $_POST['curso_id'];
                $condiciones[] = "periodos.peri_prod_id = " . $_POST['curso_id'];
            }

            // Si es docente
            if ($this->user_tipo_id == 5) {
                $condiciones[] = "grup_docente_id = " . $this->user_id;
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";

            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupBy)
            );

            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['categorias'] =  $this->general->getOptions('categorias', array("cate_id", "cate_nombre"), 'Seleccione Categoria');
        $datos['titulo'] = "Alumnos";
        $datos['_user_tipo_id'] = $this->user_tipo_id;
        // $datos["grupo_academico"] = $this->general->getOptions('grupo_academico', array("id", "nombre"), 'Todos los grupos');

        $this->cssjs->add_js(base_url() . "assets/js/AreaAcademica/alumnos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/alumnos", $datos);
        $this->load->view('footer');
    }

    public function listarAlumnos()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $alumno = '<span class="label label-primary">SI</span>';
        $no_alumno = '<span class="label label-warning">NO</span>';
        $estado = "IF( IFNULL(id_alumno, 0) = '0','" . $no_alumno . "','" . $alumno . "')";

        $columns = array(
            array('db' => 'pers_id', 'dt' => 'ID'),
            array('db' =>  $estado, 'dt' => 'ALUMNO'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'pers_nombres', 'dt' => 'NOMBRES'),
            array('db' => 'pers_apellidos', 'dt' => 'APELLIDOS'),
            array('db' => 'pers_celular', 'dt' => 'CELULAR'),
            array('db' => 'pers_correo_electronico', 'dt' => 'E-MAIL'),

        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {
            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'personas';
            $primaryKey = 'pers_id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM personas 
            LEFT JOIN alumnos ON alum_pers_id = pers_id";

            $where = "";
            $groupBy = "pers_id";

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";

            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupBy)
            );

            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Alumnos";
        $datos['_user_tipo_id'] = $this->user_tipo_id;
        $this->cssjs->add_js(base_url() . "assets/js/AreaAcademica/listarAlumnos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/listaAlumnos", $datos);
        $this->load->view('footer');
    }

    public function detalleAlumno($id)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;


        $porcentaje_pagado = 'CONCAT("<div class=\'barra\'>", ROUND(IFNULL(SUM(monto),0) * 100 / (productos.cuotas * costo - IFNULL(alum_descuento, 0)), 2), "</div>")';
        //$numcuotas = 'CONCAT("<div class=\'icono\'></div> ",(productos.cuotas*1))';
        $iconos = 'CONCAT("<div class=\'iconos\'>",(productos.cuotas * 1), "</div>")';

        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'nombre', 'dt' => 'CURSO'),
            array('db' => 'grup_nombre', 'dt' => 'GRUPO'),
            array('db' => 'CONCAT(peri_anho, "-", peri_correlativo)', 'dt' => 'PERIODO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(SUM(IFNULL(monto,0)),2))', 'dt' => 'PAGADO'),
            array('db' => 'IF(alum_es_becado, "S/. 0.00" , CONCAT("S/.", " ", ROUND( (costo * productos.cuotas - IFNULL(alum_descuento, 0))  - IFNULL(SUM(monto),0) ,2)))', 'dt' => 'DEUDA'),
            array('db' => '(productos.cuotas*1)', 'dt' => 'DT_CUOTAS'),
            array('db' => '(costo * productos.cuotas - IFNULL(alum_descuento, 0))', 'dt' => 'DT_COSTO'),
            array('db' => $iconos, 'dt' => 'ICONOS'),
            array('db' => $porcentaje_pagado, 'dt' => 'PAGADO % '),
            array('db' => 'id_alumno', 'dt' => 'DT_id'),
            array('db' => 'alum_es_becado', 'dt' => 'DT_es_becado'),
            array('db' => 'grup_id', 'dt' => 'DT_grup_id')
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {
            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'alumnos';
            $primaryKey = 'id_alumno';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );
            $condiciones = array();
            $joinQuery = "FROM alumnos
                    JOIN personas ON alum_pers_id = pers_id 
                    LEFT JOIN grupos_alumnos ON gral_id_alumno = id_alumno 
                    LEFT JOIN grupos ON gral_grup_id = grup_id 
                    LEFT JOIN periodos ON grup_peri_id = peri_id
                    LEFT JOIN productos ON productos_id = id
                    LEFT JOIN pagos ON id_alumno = alumnos_id_alumno";


            $where = "";
            $groupBy = "id_alumno";

            $condiciones[] = "pers_id = " . $id;

            // Si es docente
            if ($this->user_tipo_id == 5) {
                $condiciones[] = "grup_docente_id = " . $this->user_id;
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";

            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupBy)
            );

            exit(0);
        }

        $sql = "
        SELECT 
                P.*,
                A.fecha_inscripcion, A.habilitado, A.alum_pers_id, A.observacion, A.id_alumno
            FROM personas P
	        LEFT JOIN alumnos A ON P.pers_id = A.alum_pers_id
	        WHERE P.pers_id = {$id}";

        $datos['persona'] = $this->db->query($sql)->row();

        $datos["departamentos"] = $this->general->getOptions('departamentos', array("iddepartamentos", "nombre"));
        $datos['columns'] = $columns;
        $datos['titulo'] = "Detalles de alumno";
        $datos['_user_tipo_id'] = $this->user_tipo_id;
        // $datos["grupo_academico"] = $this->general->getOptions('grupo_academico', array("id", "nombre"), 'Todos los grupos');

        $this->cssjs->add_js(base_url() . "assets/js/AreaAcademica/detallesAlumno.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/detalleAlumno", $datos);
        $this->load->view('footer');
    }
    public function imprimirDetalleAlumno($id)
    {

        $sql = "SELECT 
                P.pers_nombres, P.pers_apellidos, P.pers_dni, P.pers_celular, P.pers_correo_electronico, P.pers_direccion, P.pers_centro_laboral, P.pers_fecha_nacimiento, P.pers_id,   
   	            D.nombre, 
                A.id_alumno
                    FROM personas P
	                LEFT JOIN alumnos A ON P.pers_id = A.alum_pers_id
	                LEFT JOIN departamentos D ON D.iddepartamentos = P.pers_iddepartamentos
	                WHERE P.pers_id = {$id}";

        $res = $this->db->query($sql)->row();



        $query = "
        SELECT id_alumno, nombre, grup_nombre, CONCAT(peri_anho, '-', peri_correlativo) as periodo, 
		CONCAT('S/.', ' ', ROUND(SUM(IFNULL(monto,0)),2)) AS pagado, 
		IF(alum_es_becado, 'S/. 0.00' , CONCAT('S/.', ' ', ROUND( (costo * productos.cuotas - IFNULL(alum_descuento, 0))  - IFNULL(SUM(monto),0) ,2))) AS deuda
        FROM alumnos
            JOIN personas ON alum_pers_id = pers_id 
            LEFT JOIN grupos_alumnos ON gral_id_alumno = id_alumno 
            LEFT JOIN grupos ON gral_grup_id = grup_id 
			LEFT JOIN periodos ON grup_peri_id = peri_id
			LEFT JOIN productos ON productos_id = id
			LEFT JOIN pagos ON id_alumno = alumnos_id_alumno
            WHERE pers_id = $id
            GROUP BY id_alumno,nombre, grup_nombre, periodo ";
        $cursos = $this->db->query($query)->result();

        date_default_timezone_set('UTC');
        $fechaimpresion = date("d/m/Y");
        $pdf = new FPDF();

        $this->general->templatePdfA4($pdf);
        $pdf->SetY(45);
        $pdf->SetTitle('DETALLES DE ALUMNO', 1);
        $pdf->SetTextColor(45, 45, 45);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->Cell(190, 10, utf8_decode('DETALLES DEL ALUMNO'), 0, 0, 'C');
        $pdf->Ln(8);

        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(100, 10, '1.- DATOS DEL PERSONALES: ', 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(75, 5, 'Nombres', 1, 0, 'C');
        $pdf->Cell(75, 5, utf8_decode('Apellidos'), 1, 0, 'C');
        $pdf->Cell(40, 5, utf8_decode('DNI'), 1, 0, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->Cell(75, 5, utf8_decode($res->pers_nombres), 0, 0, 'C');
        $pdf->Cell(75, 5, utf8_decode($res->pers_apellidos), 0, 0, 'C');
        $pdf->Cell(40, 5, utf8_decode($res->pers_dni), 0, 0, 'C');
        $pdf->Ln(6);


        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(50, 5, 'Fecha de nacimiento', 1, 0, 'C');
        $pdf->Cell(50, 5, utf8_decode('Celular'), 1, 0, 'C');
        $pdf->Cell(90, 5, utf8_decode('Correo electronico'), 1, 0, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->Cell(50, 5, utf8_decode($res->pers_fecha_nacimiento), 0, 0, 'C');
        $pdf->Cell(50, 5, utf8_decode($res->pers_celular), 0, 0, 'C');
        $pdf->Cell(90, 5, utf8_decode($res->pers_correo_electronico), 0, 0, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(40, 5, 'Departamento', 1, 0, 'C');
        $pdf->Cell(75, 5, utf8_decode('Dirección'), 1, 0, 'C');
        $pdf->Cell(75, 5, utf8_decode('Centro Laboral'), 1, 0, 'C');
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->Cell(40, 5, utf8_decode($res->nombre ), 0, 0, 'C');
        $pdf->Cell(75, 5, utf8_decode($res->pers_direccion ), 0, 0, 'C');
        $pdf->Cell(75, 5, utf8_decode($res->pers_centro_laboral ), 0, 0, 'C');
        $pdf->Ln(6);

        $pdf->Cell(190, 2, "________________________________________________________________________________________" , 0, 0, 'C');


        $pdf->Ln(5);

        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(95, 8, '2.- CURSO COMPRADOS: ', 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 6, 'ID', 1, 0, 'C', 1);
        $pdf->Cell(75, 6, utf8_decode('CURSO'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('GRUPO'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('RERIODO'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('DEUDA'), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('PAGADO'), 1, 0, 'C', 1);
        $pdf->Ln(6);

        $pdf->SetTextColor(10, 10, 10);
        foreach($cursos as $curso){
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(15, 6, $curso->id_alumno, 1, 0, 'C');
            $pdf->Cell(75, 6, utf8_decode($curso->nombre), 1, 0, 'C');
            $pdf->Cell(25, 6, utf8_decode($curso->grup_nombre), 1, 0, 'C');
            $pdf->Cell(25, 6, utf8_decode($curso->periodo), 1, 0, 'C');
            $pdf->Cell(25, 6, utf8_decode($curso->deuda), 1, 0, 'C');
            $pdf->Cell(25, 6, utf8_decode($curso->pagado), 1, 0, 'C');
            $pdf->Ln(6);
        }
        
        $pdf->SetY(250);
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->Cell(185, 20, utf8_decode('Emisión: ') . $fechaimpresion, 0, 0, 'R');


        $pdf->Output();
    }
    public function eliminarAlumno($id)
    {

        $tiene_grupo = $this->db->where("gral_id_alumno", $id)->get("grupos_alumnos")->row();

        if ($tiene_grupo != NULL) {

            $json["exito"] = false;
            $json["mensaje"] = "No se puede eliminar tiene un grupo asignado";
        } else {
            $this->db->trans_start();
            $this->general->delete_data("alumnos", array("id_alumno" => $id));
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $json["exito"] = false;
                $json["mensaje"] = "Error al tratar de eliminar el lector";
            } else {
                $json["exito"] = true;
                $json["mensaje"] = "Eliminado con exito";
            }
        }

        echo json_encode($json);
    }

    public function eliminarPersona($id)
    {
        $this->db->trans_start();
        $this->general->delete_data("personas", array("pers_id" => $id));
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $json["exito"] = false;
            $json["mensaje"] = "Error al tratar de eliminar perosna";
        } else {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        }
        echo json_encode($json);
    }
    //* FiltRar por: Categoria -> Curso -> Periodo -> Grupo  */

    public function s2GetCategorias()
    {
        $responese = new StdClass;
        $term = isset($_GET['term']) ? $_GET['term'] : '';
        $datos = array();
        $like =
            [
                'cate_nombre' => $term,
            ];

        $results = $this->general->select2("categorias", $like, null, null);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->cate_id,
                "text" => $value->cate_nombre
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }

    public function getNivelForm($nive_id = '')
    {
        if (empty($nive_id)) {
            $nivel = new stdClass();
            $nivel->nive_id = "";
            $nivel->nive_nombre = "";
            $nivel->nive_cantidad_notas = "2";
        } else {
            $nivel = $this->db->where("nive_id", $nive_id)->get("niveles")->row();
        }

        $data["nivel"] = $nivel;
        $this->load->view($this->router->fetch_class() . "/form_nivel", $data);
    }
    public function guardarNivel($id = '')
    {
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('integer', 'El campo %s solo deben ser números');
        $this->form_validation->set_message('greater_than', 'El %s debe ser mayor a %s');

        $this->form_validation->set_rules('nive_nombre', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('nive_cantidad_notas', 'Cantidad de notas', 'integer|trim|required|greater_than[0]');
        $this->form_validation->set_rules('grupo_id', 'Grupo ID', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            showError(validation_errors());
        }

        $nivel = $this->input->post();
        $grupo = $this->input->post('grupo_id');
        $cant_notas = $this->input->post('nive_cantidad_notas');
        $nombre_nivel = $this->input->post('nive_nombre');

        if (empty($id)) {
            //$this->general->dieMsg(array('exito' => false, 'mensaje' => 'Sin nivel ID'));
            $id_nivel = $this->general->save_data('niveles', $nivel);

            if ($id_nivel == false) {
                showError('Ocurrió un error al crear el nivel');
            } else {

                $sql = "SELECT gral_id FROM grupos_alumnos WHERE gral_grup_id = $grupo ";
                $alumnos = $this->db->query($sql)->result();

                $notas = '';
                for ($i = 0; $i  < $cant_notas; $i++) {
                    $i == ($cant_notas - 1) ? $notas .= '0' : $notas .= '0,';
                }

                foreach ($alumnos as $alumno) {
                    $nota = [
                        'gral_id' => $alumno->gral_id,
                        'nive_id' => $id_nivel,
                        'nota_promedio' => '00',
                        'nota_notas' => $notas,
                    ];
                    $this->general->save_data('notas', $nota);
                }
                $this->general->dieMsg(['exito' => true, 'mensaje' => 'Nivel creado', 'nivel' => $id_nivel, 'nivel_nombre' => $nombre_nivel]);
            }
        } else { //EDITAR

            $old_nivel = $this->db->where("nive_id", $id)->get("niveles")->row();
            $old_cant_notas = $old_nivel->nive_cantidad_notas;
            $condicion = array("nive_id" => $id);

            if ($cant_notas == $old_cant_notas) {
                if ($this->general->update_data("niveles", $nivel, $condicion) == false) {
                    showError('Ocurrió un error al editar el nivel');
                } else {
                    $this->general->dieMsg(['exito' => true, 'mensaje' => 'Nivel Editado.', 'nivel' => $id, 'nivel_nombre' => $nombre_nivel]);
                }
            } else {

                if ($this->general->update_data("niveles", $nivel, $condicion) == false) {
                    showError('Ocurrió un error al editar el nivel');
                } else {

                    $sql = "SELECT * FROM notas WHERE nive_id = $id";
                    $notas_editar = $this->db->query($sql)->result();

                    foreach ($notas_editar as $nota) {
                        $notas = $nota->nota_notas;

                        $old_notas = explode(",", $notas);

                        if ($old_cant_notas < $cant_notas) { //mayor
                            $diferencia = $cant_notas - $old_cant_notas;
                            for ($i = 0; $i <  abs($diferencia); $i++) {
                                $notas .= ',0';
                            }
                        } else { //menor
                            $i = 0;
                            $notas = '';
                            foreach ($old_notas as $item) {
                                if ($i  < $cant_notas) {
                                    $i == ($cant_notas - 1) ? $notas .= $item : $notas .= ($item . ',');
                                }
                                $i++;
                            }
                        }

                        $notas_array = explode(",", $notas);
                        $promedio = 0;
                        foreach ($notas_array as $not) {
                            $promedio +=  (int)$not;
                        }

                        $promedio = $promedio / $cant_notas;

                        $nota_editar = [
                            'nota_promedio' => round($promedio),
                            'nota_notas' => $notas,
                        ];

                        $condicion_nota = array("nota_id" => $nota->nota_id);
                        $this->general->update_data("notas", $nota_editar, $condicion_nota);
                    }

                    $this->general->dieMsg(['exito' => true, 'mensaje' => 'Nivel Editado.', 'nivel' => $id, 'nivel_nombre' => $nombre_nivel]);
                }
            }
        }
    }

    public function eliminarNivel($id = '')
    {
        $this->db->trans_start();
        $this->general->delete_data("niveles", array("nive_id" => $id));
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $json["exito"] = false;
            $json["mensaje"] = "Error al tratar de eliminar el lector";
        } else {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        }
        echo json_encode($json);
    }
}
