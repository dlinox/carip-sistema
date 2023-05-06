<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contabilidad extends CI_Controller
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
        $this->load->model('ContabilidadModel', 'model');
        $this->load->model('AlumnoModel', 'alumno_model');
        $this->load->model('UsuarioModel', 'usuario_model');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->load->helper('Response');
        $this->user_id = $this->session->userdata('authorized');
        $this->user_tipo_id = $this->session->userdata('usua_tipo');
    }

    public function index()
    {
        $this->resumenmensual();
    }

    /*******************************************************************************************
                                    Flujo de Caja
     *******************************************************************************************/
    public function flujo_caja($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $columns = array(
            array('db' => 'id_flujocaja', 'dt' => 'DT_RowId'),
            array('db' => 'id_flujocaja', 'dt' => 'N° REGISTRO'),
            array('db' => 'descripcion_flujo', 'dt' => 'DESCRIPCION'),
            array('db' => 'descripcion_rubrogasto', 'dt' => 'RUBRO'),
            array('db' => 'DATE_FORMAT(fecha_flujo, "%d/%m/%Y")', 'dt' => 'FECHA'),
            array('db' => 'observacion_flujo', 'dt' => 'OBSERVACION'),
            array('db' => 'CONCAT("S/.", " ", ROUND(importe_flujo,2))', 'dt' => 'IMPORTE'),
            array('db' => 'importe_flujo', 'dt' => 'DT_IMPORTE'),

        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'flujocaja';
            $primaryKey = 'id_flujocaja';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM flujocaja 
            JOIN rubrogasto ON id_rubrogasto = rubrogasto_id_rubrogasto";
            $where = "";

            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fecha_flujo) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha_flujo) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            if ($this->input->post('categoria')) {
                $condiciones[] = 'flca_cate_id = ' . $this->input->post('categoria');
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Reporte Caja de Egresos";

        $this->cssjs->add_js($this->jsPath . "Contabilidad/flujo_caja.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/flujo_caja", $datos);
        $this->load->view('footer');
    }

    public function flujo_caja_crear($id = "")
    {
        if (empty($id)) {

            $flujocaja = new stdClass();
            $flujocaja->id_flujocaja = "";
            $flujocaja->descripcion_rubrogasto = "";
            $flujocaja->fecha_flujo = "";
            $flujocaja->descripcion_flujo = trim("");
            $flujocaja->observacion_flujo = "";
            $flujocaja->importe_flujo = "";
            $flujocaja->rubrogasto_id_rubrogasto = "";
            $flujocaja->flca_cate_id = "";
        } else {
            $flujocaja = $this->db->where("id_flujocaja", $id)->get("flujocaja")->row();
        }

        $data['categorias'] =  $this->general->getOptions('categorias', ['cate_id', 'cate_nombre'], '* Categoria'); //$this->general->s2GetCategorias();
        $data["flujocaja"] = $flujocaja;
        $data["tipo"] = $this->general->getOptions('rubrogasto', array("id_rubrogasto", "descripcion_rubrogasto"), '* Tipo gasto');

        $this->load->view($this->controller . "/form_flujo_caja", $data);
    }

    public function flujo_caja_guardar($id = "")
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser número mayor a 0');

        $this->form_validation->set_rules('tipo', 'Tipo', 'required');
        $this->form_validation->set_rules('fecha', 'Fecha', 'required');
        $this->form_validation->set_rules('importe', 'Importe', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $tipo = $this->input->post("tipo");
        $fecha = $this->input->post("fecha");
        $importe = $this->input->post("importe");
        $descripcion = $this->input->post("descripcion");
        $observacion = $this->input->post("observacion");
        $categoria = $this->input->post('categoria');
        $tipo_comprobante = $this->input->post("tipo_comp");

        $flujocaja = array(
            "descripcion_flujo"     =>  trim($descripcion),
            "importe_flujo"     => $importe,
            "fecha_flujo"       => $fecha,
            "observacion_flujo"     => trim($observacion),
            "rubrogasto_id_rubrogasto"        => $tipo,
            "flca_cate_id"        => $categoria,

        );

        if (!empty($id)) {
            $condicion = array("id_flujocaja" => $id);

            if ($this->general->update_data("flujocaja", $flujocaja, $condicion)) {
                $json["exito"] = true;
                $json["mensaje"] = "Usuario actualizado con exito";
                $json["comprobante"] = $tipo_comprobante;
                $json["id_res"] = $id;
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "No se guardaron cambios";
            }
        } else {
            $id_res = $this->general->save_data("flujocaja", $flujocaja);
            if ($id_res != false) {
                $json["exito"] = true;
                $json["mensaje"] = "Gastos registrados con exito";
                $json["comprobante"] = $tipo_comprobante;
                $json["id_res"] = $id_res;
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "Error ak al guardar Gastos";
            }
        }
        echo json_encode($json);
    }

    public function flujo_caja_eliminar($id)
    {

        $this->db->trans_start();
        $this->general->delete_data("flujocaja", array("id_flujocaja" => $id));
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


    /*******************************************************************************************
                        Inicio de Ingresos (Reporte de Pagos d elos alumnos)
     *******************************************************************************************/

    public function ingresos($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');

        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $columns = array(
            array('db' => 'idpagos', 'dt' => 'DT_RowId'),
            array('db' => 'idpagos', 'dt' => 'ID PAGO'),
            array('db' => 'pers_dni', 'dt' => 'N° DNI'),
            array('db' => 'pers_nombres', 'dt' => 'NOMBRE'),
            array('db' => 'pers_apellidos', 'dt' => 'APELLIDOS'),
            array('db' => 'nombre', 'dt' => 'CURSO'),
            array('db' => 'DATE_FORMAT(fechapago, "%d/%m/%Y")', 'dt' => 'FECHA'),
            array('db' => 'CONCAT("S/.", " ", ROUND(monto,2))', 'dt' => 'MONTO')
            //array('db' => 'alumnos_id_alumno', 'dt' => 'Cod')
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
            $joinQuery = 'FROM
            alumnos
            JOIN personas ON alum_pers_id = pers_id 
            JOIN grupos_alumnos ON gral_id_alumno = id_alumno
            JOIN grupos ON gral_grup_id = grup_id
            JOIN periodos ON grup_peri_id = peri_id 
            JOIN productos ON productos_id = id
            JOIN pagos ON alumnos.id_alumno = pagos.alumnos_id_alumno';
            $where = "";

            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fechapago) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fechapago) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            if ($this->input->post('categoria')) {
                $condiciones[] = 'productos.cate_id = ' . $this->input->post('categoria');
            }
            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Reporte de Ingresos";

        $this->cssjs->add_js($this->jsPath . "Contabilidad/ingresos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/ingresos", $datos);
        $this->load->view('footer');
    }

    /*                   FIN de Ingresos (Reporte de Pagos d elos alumnos)
     *******************************************************************************************/

    /*******************************************************************************************
                                 INICIO DE REPORTE DE PAGOS A PERSONAL
     *******************************************************************************************/

    public function reportepagos($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');

        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $pagototal = 'IFNULL(monto, 0) + IFNULL(bono, 0) + IFNULL(comisiondirecta, 0) + IFNULL(comisionasesores, 0) + IFNULL(horas, 0) * IFNULL(costohora, 0) - IFNULL(descuento, 0) - IFNULL(adelanto, 0)';


        $columns = array(
            array('db' => 'idpago', 'dt' => 'DT_RowId'),
            array('db' => 'idpago', 'dt' => 'ID PAGO'),
            array('db' => 'usua_dni', 'dt' => "N° DNI"),
            array('db' => 'CONCAT(usua_nombres, "  ", usua_apellidos )', 'dt' => "NOMBRES Y APELLIDOS"),
            array('db' => 'tipo_denominacion', 'dt' => 'CARGO'),
            array('db' => 'mes', 'dt' => 'MES'),
            array('db' => 'DATE_FORMAT(fecha, "%d/%m/%Y")', 'dt' => 'FECHA'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
            array('db' => 'curso', 'dt' => 'CURSO'),
            array('db' =>  'CONCAT("S/.", " ", ROUND(' . $pagototal . ',2))', 'dt' => 'MONTO')
        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'pagopersonal';
            $primaryKey = 'idpago';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = 'FROM
            pagopersonal JOIN usuario ON pagopersonal.usuario_usua_id = usuario.usua_id
            JOIN tipousuario ON usuario.usua_tipo = tipousuario.tipo_id';
            $where = "";

            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            if (!empty($_POST['categoira'])) {
                $condiciones[] = "usuario.cate_id = " . $_POST['categoira'];
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );

            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Reporte de Pagos a Personal";

        $this->cssjs->add_js($this->jsPath . "Contabilidad/reportepagos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/reportepagos", $datos);
        $this->load->view('footer');
    }

    /*                              FIN DE REPORTE DE PAGOS A PERSONAL
    ********************************************************************************************/

    /*******************************************************************************************
                                    INICIO DE PAGO A PERSONAL
     ********************************************************************************************/
    public function pagopersonal($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');

        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $tipo = "UPPER(tipo_denominacion)";
        $columns = array(
            array('db' => 'usua_id', 'dt' => 'DT_RowId'),
            array('db' => 'usua_dni', 'dt' => "N° DNI"),
            array('db' => 'CONCAT(usua_nombres, "  ", usua_apellidos )', 'dt' => "NOMBRE Y APELLIDOS"),
            array('db' => $tipo, 'dt' => 'CARGO'),
            array('db' => 'usua_movil', 'dt' => 'TELEFONO'),
            array('db' => 'usua_email', 'dt' => 'EMAIL')
        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'usuario';
            $primaryKey = 'usua_id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = 'FROM usuario JOIN tipousuario ON usua_tipo = tipo_id';
            $where = "";

            /*if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }*/
            if (!empty($_POST['tipo'])) {
                $condiciones[] = "usua_tipo = " . $_POST['tipo'];
            }

            if (!empty($_POST['categoira'])) {
                $condiciones[] = "cate_id = " . $_POST['categoira'];
            }
            $condiciones[] = "usua_habilitado = 1";
            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );

            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Pago Personal";
        $datos["tipos"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), 'Filtrar Cargo (Todos)');


        $this->cssjs->add_js($this->jsPath . "Contabilidad/pagopersonal.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/pagopersonal", $datos);
        $this->load->view('footer');
    }

    /** Contabilidad Crear Pago */

    public function getcomisiones($id)
    {
        $fecha = $_POST['mes'];
        echo json_encode($this->model->getComisiones($id, $fecha));
    }

    public function obtenerUventa($usuario)
    {
        return $usuario;
    }

    public function pagopersonal_crear($id, $opcion)
    {
        $datos = [];

        $query = "SELECT * FROM 
            pagopersonal 
            WHERE usuario_usua_id = $id
            ORDER BY idpago DESC, fecha DESC
            limit 1";

        $ultimo_pago = $this->db->query($query)->row();


        $datos["ultimo_pago"] = $ultimo_pago;


        $datos["tipo_usuarios"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"));
        $datos["usuario"] = $this->db->where("usua_id", $id)->get("usuario")->row();

        //print_r($datos["usuario"]);

        $sql = 'SELECT peri_prod_id AS id, CONCAT(nombre,"(",peri_anho,"-",grup_correlativo,")") AS nombre
                FROM grupos
                JOIN usuario ON grup_docente_id = usua_id
                JOIN periodos ON grup_peri_id = peri_id
                JOIN productos ON peri_prod_id = id
                WHERE grup_finalizado = 0
                AND usua_id = ' . $id;

        //$prueba = $this->db->query($sql)->result();

        $cursodictado = array();

        foreach ($this->db->query($sql)->result() as $row) {
            $cursodictado[$row->nombre] = $row->nombre;
        }

        $datos["cursodictado"] = $cursodictado;

        //print_r($datos["cursodictado"]);

        if ($opcion == 0) {
            $pagopersonal = new stdClass();
            $pagopersonal->idpago = "";
            $pagopersonal->mes = date('Y-m');
            $pagopersonal->fecha = date('Y-m-d');
            $pagopersonal->bono = 0;
            $pagopersonal->monto = 0;
            $pagopersonal->observacion = "";
            $pagopersonal->curso = "";
            $pagopersonal->comisiondirecta = 0;
            $pagopersonal->comisionasesores = 0;
            $pagopersonal->usuario_usua_id = "";
            $pagopersonal->tipousuario = "";
            $pagopersonal->horas = 0;
            $pagopersonal->costohora = 0;
            $pagopersonal->total = "";
            $pagopersonal->descuento = 0;
            $pagopersonal->adelanto = 0;
            $datos["pagopersonal"] = $pagopersonal;
        } elseif ($opcion == 1) {
            $pagopersonal = $this->db->where("usuario_usua_id", $id)->get("pagopersonal")->row();
            $datos["pagopersonal"] = $pagopersonal;
        }

        $this->cssjs->add_js($this->jsPath . "Contabilidad/pagopersonalform.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();
        $this->load->view('header', $script);
        $this->load->view('Contabilidad/pagopersonalform', $datos);
        $this->load->view('footer');
    }

    public function pagopersonal_editar($idpago)
    {
        $datos = [];
        $datos["rubros"] = $this->general->getOptions('rubros', array("id", "nombre"));
        $datos["productos"] = $this->general->getOptions('productos', array("id", "nombre"), '', '', '');
        $datos["tipo_alumnos"] = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $datos["tipo_pagos"] = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $datos["condiciones"] = $this->general->getOptions('condiciones', array("id", "nombre"));
        $datos["tipo_usuarios"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"));

        $pagopersonal = $this->db->where("idpago", $idpago)->get("pagopersonal")->row();

        $id = $pagopersonal->usuario_usua_id;

        $usuario = $this->db->where("usua_id", $id)->get("usuario")->row();
        $datos["usuario"] = $usuario;


        $query = "SELECT * 
            FROM pagopersonal 
            WHERE usuario_usua_id = $id  AND idpago != $idpago
            ORDER BY idpago DESC, fecha DESC 
            LIMIT 1";

        $fecha_ultima_comicion =  $this->db->query($query)->row();

        $datos["ultimo_pago"] = false;
        if (!empty($fecha_ultima_comicion)) {
            $datos["ultimo_pago"] = $fecha_ultima_comicion;
        }
        //print_r($datos["usuario"]);


        $sql = 'SELECT peri_prod_id AS id, CONCAT(nombre,"(",peri_anho,"-",grup_correlativo,")") AS nombre
        FROM grupos
        JOIN usuario ON grup_docente_id = usua_id
        JOIN periodos ON grup_peri_id = peri_id
        JOIN productos ON peri_prod_id = id
        WHERE grup_finalizado = 0
        AND usua_id = ' . $id;

        $datos["pagopersonal"] = $pagopersonal;

        $cursodictado = array();

        foreach ($this->db->query($sql)->result() as $row) {
            $cursodictado[$row->nombre] = $row->nombre;
        }

        $datos["cursodictado"] = $cursodictado;

        //print_r($datos);
        $this->cssjs->add_js($this->jsPath . "Contabilidad/pagopersonaleditar.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();

        $this->load->view('header', $script);
        $this->load->view('Contabilidad/pagopersonalformeditar', $datos);
        $this->load->view('footer');
    }

    public function pagopersonal_guardar()
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser número mayor a 0');

        $this->form_validation->set_rules('mes', 'Mes', 'required');
        $this->form_validation->set_rules('fechapago', 'Fecha de Pago', 'required');
        $this->form_validation->set_rules('monto', 'Sueldo Neto', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $anio_mes = $this->input->post("mes");
        $fecha = $this->input->post("fechapago");
        $bono = $this->input->post("bono");
        $monto = $this->input->post("monto");
        $observacion = $this->input->post("observacion");

        $comisiondirecta = $this->input->post("comisiondirecta");
        $comisionasesores = $this->input->post("comisionasesores");

        //$curso = $this->input->post("cursodictado");
        //$horas = $this->input->post("horas"); //para docentes
        //$costohora = $this->input->post("costohora"); //para docentes

        $descuento = $this->input->post("descuento");
        $adelanto = $this->input->post("adelanto");
        $usuario = $this->input->post("idpersona");
        $cursos = $this->input->post('cursodictado');
        $costoshoras = $this->input->post('costohora');
        $horascursos = $this->input->post('horas');

        $horas_costo = 0;
        if ($costoshoras) {
            foreach ($costoshoras as  $i => $elem) {
                $horas_costo += $elem * $horascursos[$i];
            }
        }


        $id_mes = explode('-', $anio_mes)[1];
        $mes = $this->db->where("mes_id", (int)$id_mes)->get("meses")->row();
        $pagopersonal = array(
            "mes"   => $mes->mes_nombre,
            "fecha" => $fecha,
            "bono"  => $bono,
            "monto" => $monto,
            "descuento" => $descuento,
            "adelanto" => $adelanto,
            "observacion"   => $observacion,
            "curso" => isset($_POST['cursodictado']) ? $cursos[0] :  NULL,
            "comisiondirecta"   => $comisiondirecta,
            "comisionasesores"  => $comisionasesores,
            "horas"   => 1,
            "costohora"  => $horas_costo,
            "usuario_usua_id"   => $usuario,
            "anio_mes" => $anio_mes,

            "doc_cursos"  => isset($_POST['cursodictado']) ? implode(',', $cursos) :  NULL,
            "doc_horas"   => isset($_POST['horas']) ? implode(',', $horascursos) :  NULL,
            "doc_costoshora" => isset($_POST['costohora']) ? implode(',', $costoshoras) :  NULL,
        );

        $pago = $this->general->save_data("pagopersonal", $pagopersonal);

        if ($pago != false) {
            $json["exito"] = true;
            $json["pago_id"] = $pago;
            $json["mensaje"] = "Gastos registrados con exito";
        } else {
            $json["exito"] = false;
            $json["mensaje"] = "Error al guardar Gastos";
        }
        echo json_encode($json);
    }

    public function pagopersonaleditar_guardar($id)
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser número mayor a 0');

        $this->form_validation->set_rules('mes', 'Mes', 'required');
        $this->form_validation->set_rules('fechapago', 'Fecha de Pago', 'required');
        $this->form_validation->set_rules('monto', 'Sueldo Neto', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $anio_mes = $this->input->post("mes");
        $fecha = $this->input->post("fechapago");
        $bono = $this->input->post("bono");
        $monto = $this->input->post("monto");
        $observacion = $this->input->post("observacion");

        $comisiondirecta = $this->input->post("comisiondirecta");
        $comisionasesores = $this->input->post("comisionasesores");

        //$curso = $this->input->post("cursodictado");
        //$horas = $this->input->post("horas"); //para docentes
        //$costohora = $this->input->post("costohora"); //para docentes

        $descuento = $this->input->post("descuento");
        $adelanto = $this->input->post("adelanto");
        $usuario = $this->input->post("idpersona");
        $cursos = $this->input->post('cursodictado');
        $costoshoras = $this->input->post('costohora');
        $horascursos = $this->input->post('horas');

        $horas_costo = 0;
        if ($costoshoras) {
            foreach ($costoshoras as  $i => $elem) {
                $horas_costo += $elem * $horascursos[$i];
            }
        }


        $id_mes = explode('-', $anio_mes)[1];
        $mes = $this->db->where("mes_id", (int)$id_mes)->get("meses")->row();
        $pagopersonal = array(
            "mes"   => $mes->mes_nombre,
            "fecha" => $fecha,
            "bono"  => $bono,
            "monto" => $monto,
            "descuento" => $descuento,
            "adelanto" => $adelanto,
            "observacion"   => $observacion,
            "curso" => isset($_POST['cursodictado']) ? $cursos[0] :  NULL,
            "comisiondirecta"   => $comisiondirecta,
            "comisionasesores"  => $comisionasesores,
            "horas"   => 1,
            "costohora"  => $horas_costo,
            "usuario_usua_id"   => $usuario,
            "anio_mes" => $anio_mes,

            "doc_cursos"  => isset($_POST['cursodictado']) ? implode(',', $cursos) :  NULL,
            "doc_horas"   => isset($_POST['horas']) ? implode(',', $horascursos) :  NULL,
            "doc_costoshora" => isset($_POST['costohora']) ? implode(',', $costoshoras) :  NULL,
        );


        $condicion = array("idpago" => $id);

        if ($this->general->update_data("pagopersonal", $pagopersonal, $condicion)) {
            $json["exito"] = true;
            $json["mensaje"] = "Pago Personal actualizado con exito";
        } else {
            $json["exito"] = false;
            $json["mensaje"] = "No se guardaron cambios";
        }

        echo json_encode($json);
    }

    /** Fin de Editar */

    public function pagopersonal_eliminar($id)
    {
        $this->db->trans_start();
        $this->general->delete_data("pagopersonal", array("idpago" => $id));
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $json["exito"] = false;
            $json["mensaje"] = "Error al tratar de eliminar";
        } else {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        }
        echo json_encode($json);
    }
    /**                     RESUMEN MENSUAL
     *************************************************** */
    public function resumenmensual($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');

        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $columns = array(
            array('db' => 'idpago', 'dt' => 'DT_RowId'),
            array('db' => 'descuento', 'dt' => 'Pago Total'),
            array('db' => 'descuento', 'dt' => 'Pago Total'),
        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'pagopersonal';
            $primaryKey = 'idpago';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = 'FROM pagopersonal';
            $where = "";

            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Resumen Mensual";


        $this->cssjs->add_js($this->jsPath . "Contabilidad/resumenmensual.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/resumenmensual", $datos);
        $this->load->view('footer');
    }

    /**                     RESUMEN MENSUAL
     *************************************************** */
    public function resumenanual($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');

        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $columns = array(
            array('db' => 'idpago', 'dt' => 'DT_RowId'),
            array('db' => 'monto+bono+comisiondirecta+comisionasesores+horas*costohora', 'dt' => 'Pago Total'),
            array('db' => 'monto+bono+comisiondirecta+comisionasesores+horas*costohora', 'dt' => 'Pago Total'),
        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'pagopersonal';
            $primaryKey = 'idpago';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = 'FROM pagopersonal';
            $where = "";

            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );

            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Resumen Anual";


        $this->cssjs->add_js($this->jsPath . "Contabilidad/resumenanual.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/resumenanual", $datos);
        $this->load->view('footer');
    }

    public function getresumenmensual()
    {
        $fechas = explode('-', $_POST['rango']);

        $categoria = '';

        if ($this->input->post('categoria')) {
            $categoria = $this->input->post('categoria');
        }
        #print_r($fechas);
        echo json_encode($this->model->getEgresosIngresos(cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1]), $categoria));
        #echo json_encode($this->model->getComisiones($this->user_id, cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1])));
    }

    /**                     PAGO PERSONAR COMPROBANTE
     *************************************************** */
    public function pagoPersonalComprobanteA4($idpago)
    {
        $pagopersonal = $this->db->where("idpago", $idpago)->get("pagopersonal")->row();

        $mes_id = explode('-', $pagopersonal->anio_mes)[1];
        $mes = $this->db->where("mes_id", $mes_id)->get("meses")->row();


        $id = $pagopersonal->usuario_usua_id;
        $usuario = $this->db->where("usua_id", $id)->get("usuario")->row();

        $pago_detalle = (object) [
            'Sueldo Neto' => $pagopersonal->monto,
            'Bonificacion' => $pagopersonal->bono,
        ];

        if ($usuario->usua_tipo == 1 || $usuario->usua_tipo == 7) {
            $pago_detalle->{'Comision Directa'} = $pagopersonal->comisiondirecta;
            $pago_detalle->{'Comision por Asesor de Venta'} = $pagopersonal->comisionasesores;
        }

        $titulo = 'NOTA DE PAGO PERSONAL';

        $pdf = new FPDF();

        $this->general->templatePdfA4($pdf);

        $pdf->SetTitle('COMPROBANTE DE PAGO', 1);
        $pdf->SetY(40); // Despues del header
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'U', 14);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(200, 8, utf8_decode($titulo), 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(110, 5, utf8_decode('Usuario: ' . $usuario->usua_nombres . ' ' . $usuario->usua_apellidos), 0, 'L');
        $pdf->Cell(90, 5, utf8_decode('Email: ' . $usuario->usua_email), 0, 'L');
        $pdf->Ln(6);
        $pdf->Cell(50, 5, utf8_decode('Celular: ' . $usuario->usua_movil), 0, 'R');
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(60, 5, utf8_decode('Mes pagado: ' . $mes->mes_nombre), 0, 'R');
        $pdf->Cell(50, 5, utf8_decode('Fecha del pago: ' . $pagopersonal->fecha), 0, 'R');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 10);
        //HEADER TABLE
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 7, utf8_decode('#'), 1, 0, 'C', 1, '');
        $pdf->Cell(100, 7, utf8_decode('DETALLE'), 1, 0, 'C', 1, '');
        $pdf->Cell(35, 7, utf8_decode('MONTO'), 1, 0, 'C', 1, '');
        $pdf->Cell(40, 7, utf8_decode('SUBTOTAL '), 1, 0, 'C', 1, '');

        $pdf->Ln(7);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetFillColor(200, 200, 200);
        $i = 1;
        $total = 0;


        foreach ($pago_detalle as $key => $val) {
            $pdf->Cell(15, 6, utf8_decode($i), 1, 0, 'C', 0, '');
            $pdf->Cell(100, 6, utf8_decode($key), 1, 0, 'L', 0, '');
            $pdf->Cell(35, 6, utf8_decode('S/. ' . $val), 1, 0, 'C', 0, '');
            $pdf->Cell(40, 6, utf8_decode('S/. ' . $val), 1, 0, 'C', 0, '');
            $pdf->Ln(6);
            $i++;
            $total += $val;
        }

        if ($usuario->usua_tipo == 5) {

            $total_docente = 0;

            $cursos = explode(',', $pagopersonal->doc_cursos);
            $horas = explode(',', $pagopersonal->doc_horas);
            $cosots_horas = explode(',', $pagopersonal->doc_costoshora);

            if ($pagopersonal->doc_cursos != NULL) {
                foreach ($cursos as  $j => $curos) {
                    $pdf->Cell(15, 6, utf8_decode($i), 1, 0, 'C', 0, '');
                    $pdf->Cell(100, 6, utf8_decode($curos), 1, 0, 'L', 0, '');
                    $pdf->Cell(35, 6, (int)$horas[$j] . 'h * ' . $cosots_horas[$j] . "", 1, 0, 'C', 0, '');
                    $total_docente += ($horas[$j] *  $cosots_horas[$j]);
                    $pdf->Cell(40, 6, 'S/. ' . number_format($horas[$j] * $cosots_horas[$j], 2), 1, 0, 'C', 0, '');
                    $pdf->Ln(6);
                    $i++;
                }
            }
            $total += $total_docente;
        }

        $igv = ($total * 18) / 100;
        $sub_total = $total - $igv;

        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('SUBTOTAL'), 0, 0, 'R', 0, '');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, 'S/. ' . number_format($sub_total, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('IGV(18%)'), 0, 0, 'R', 0, '');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, 'S/. ' . number_format($igv, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);

        $pdf->SetTextColor(250, 0, 0);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('DESCUENTO'), 0, 0, 'R', 0, '');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, 'S/. ' . number_format($pagopersonal->descuento, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('ADELANTOS'), 0, 0, 'R', 0, '');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, 'S/. ' . number_format($pagopersonal->adelanto, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 6, '', 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, '-------------------', 0, 0, 'C', 0, '');
        $pdf->Ln(4);

        $total -= ($pagopersonal->descuento + $pagopersonal->adelanto);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('TOTAL'), 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, 'S/. ' . number_format($total, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);



        $pdf->Output();
    }

    /**                     FLUJO DE CAJA COMPROBANTE
     *************************************************** */
    public function flujoCajaComprobante($id, $tipo)
    {

        $flujocaja = $this->db->join('rubrogasto', 'rubrogasto_id_rubrogasto = id_rubrogasto')->where("id_flujocaja", $id)->get("flujocaja")->row();
        if ($tipo ==  'A4') {
            $this->flujoCajaComprobanteA4($flujocaja);
        } else {
            $this->flujoCajaComprobanteTicket($flujocaja);
        }
    }
    public function flujoCajaComprobanteA4($datos)
    {
        $pdf = new FPDF();
        $this->general->templatePdfA4($pdf);

        $pdf->SetTitle('DETALLE DE GASTO', 1);
        $pdf->SetY(40); // Despues del header
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'U', 14);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(200, 8, utf8_decode('DETALLE DE GASTO'), 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 10);
        //HEADER TABLE
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 7, utf8_decode('#'), 1, 0, 'C', 1, '');
        $pdf->Cell(100, 7, utf8_decode('RUBRO'), 1, 0, 'C', 1, '');
        $pdf->Cell(35, 7, utf8_decode('FECHA'), 1, 0, 'C', 1, '');
        $pdf->Cell(40, 7, utf8_decode('SUBTOTAL '), 1, 0, 'C', 1, '');

        $pdf->Ln(7);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(15, 7, utf8_decode($datos->id_flujocaja), 1, 0, 'C', 0, '');

        $pdf->Cell(100, 7, utf8_decode($datos->descripcion_rubrogasto), 1, 0, 'L', 0, '');
        $pdf->Cell(35, 7, utf8_decode($datos->fecha_flujo), 1, 0, 'C', 0, '');
        $pdf->Cell(40, 7, utf8_decode('S/. ' . $datos->importe_flujo), 1, 0, 'C', 0, '');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(100, 7, utf8_decode('DESCRIPCIÓN:'), 0, 0, 'L', 0, '');
        $pdf->Ln(7);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->MultiCell(140, 7, utf8_decode($datos->descripcion_flujo), 0, 'L', 0);

        $total = $datos->importe_flujo;

        $pdf->Cell(150, 6, '', 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, '-------------------', 0, 0, 'C', 0, '');
        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->Cell(110, 6, utf8_decode('* ' . $datos->observacion_flujo), 0, 0, 'L', 0, '');

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(40, 6, utf8_decode('TOTAL:'), 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, 'S/. ' . number_format($total, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);

        $pdf->Output();
    }

    public function flujoCajaComprobanteTicket($datos)
    {
        $pdf = new FPDF('P', 'mm', array(80, 150));
        $this->general->templateTicket($pdf);
        $pdf->SetTitle('TICKET DE GASTO', 1);

        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->Cell(60, 5, 'TICKET - ' . $datos->id_flujocaja, 0, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'I', 9);
        $pdf->Cell(60, 5, $datos->fecha_flujo, 0, 0, 'C');

        $pdf->Ln(6);
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->Cell(60, 4, utf8_decode($datos->descripcion_rubrogasto), 0, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Courier', '', 8);
        $pdf->MultiCell(60, 4,  utf8_decode($datos->descripcion_flujo), 0, 'C', 0);

        $pdf->Cell(60, 5, '------------------------------', 0, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->MultiCell(60, 4, 'TOTAL:  S/.' .  number_format($datos->importe_flujo, 2), 0, 'C', 0);

        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'I', 8);
        $pdf->MultiCell(60, 4, '* ' .  utf8_decode($datos->observacion_flujo), 0, 'C', 0);

        $pdf->Output();
    }

    //DASHBOARD
    public function getVentasAnio()
    {
        $sql = "SELECT cate_id, cate_nombre FROM categorias";
        $categorias = $this->db->query($sql)->result();

        $data  = [];

        foreach ($categorias as $cate) {
            $data["$cate->cate_nombre"] = $this->getVentasAnioByCategoria($cate->cate_id);
        }
        $result['categorias'] = $categorias;
        $result['data'] = $data;
        echo json_encode($result);
    }
    public function getVentasAnioByCategoria($id)
    {
        $sql = "SELECT mes_nombre, IFNULL(AL.total_mes, 0) total_mes
                FROM meses
                LEFT JOIN (SELECT MONTH(fecha_inscripcion) Mes, COUNT(*) total_mes
                    FROM alumnos
                    JOIN productos PR ON productos_id = PR.id
                    WHERE PR.cate_id = $id
                    GROUP BY Mes) AL ON AL.mes = mes_id
                WHERE MONTH(CURDATE()) >= mes_id
                ORDER BY mes_id";

        $ventas = $this->db->query($sql)->result();

        return $ventas;
    }

    /***
     * FUNCIONES ADELANTO Y DSCUENTO
     */
    public function adelantoDescuentoForm($id_usua = '', $id_adde = '')
    {
        if (empty($id_adde)) {
            $pago = new stdClass();
            $pago->adde_id = "";
            $pago->adde_importe = "0";
            $pago->adde_usua_id = $id_usua;
            $pago->adde_anio_mes = date("Y-m");
            $pago->adde_fecha = date("Y-m-d");
            $pago->adde_descripcion = "";
            $pago->adde_tipo = "ADELANTO";
        } else {
            $pago = $this->db->where("adde_id", $id_adde)->get("adelantos_descuentos")->row();
        }

        $datos["tipo"] = ["ADELANTO" => "ADELANTO", "DESCUENTO" => "DESCUENTO"];
        $datos["pago"] = $pago;

        $this->load->view($this->controller . "/adelanto_descuento_form", $datos);
    }
    public function adelantoDescuentoGuardar($id_adde = '')
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser número mayor a 0');
        $this->form_validation->set_message('in_list', '%s solo puede ser ADELANTO O DESCUENTO');

        $this->form_validation->set_rules('tipo', 'Tipo', 'required|in_list[ADELANTO,DESCUENTO]');
        $this->form_validation->set_rules('fecha', 'Fecha', 'required');
        $this->form_validation->set_rules('importe', 'Importe', 'required');
        $this->form_validation->set_rules('usuario', 'Importe', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $importe = $this->input->post("importe");
        $usuario = $this->input->post("usuario");
        $fecha = $this->input->post("fecha");
        $descripcion = $this->input->post("descripcion");
        $tipo = $this->input->post("tipo");


        $pago = array(
            "adde_importe" =>  trim($importe),
            "adde_usua_id" => trim($usuario),
            "adde_anio_mes" => trim($fecha),
            "adde_fecha" => date("Y-m-d"),
            "adde_descripcion" => trim($descripcion),
            "adde_tipo" => trim($tipo),

        );

        if (!empty($id_adde)) {
            $condicion = array("adde_id" => $id_adde);

            if ($this->general->update_data("adelantos_descuentos", $pago, $condicion)) {
                $json["exito"] = true;
                $json["mensaje"] = "Usuario actualizado con exito";
                //$json["comprobante"] = $tipo_comprobante;
                $json["id_res"] = $id_adde;
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "No se guardaron cambios";
            }
        } else {
            $id_res = $this->general->save_data("adelantos_descuentos", $pago);
            if ($id_res != false) {
                $json["exito"] = true;
                $json["mensaje"] = "Gastos registrados con exito";
                //$json["comprobante"] = $tipo_comprobante;
                $json["id_res"] = $id_res;
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "Error ak al guardar Gastos";
            }
        }
        echo json_encode($json);
    }
    public function adelantoDescuentoEliminar($id_adde)
    {
        $this->db->trans_start();
        $this->general->delete_data("adelantos_descuentos", array("adde_id" => $id_adde));
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $json["exito"] = false;
            $json["mensaje"] = "Error al eliminar el registro";
        } else {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        }
        echo json_encode($json);
    }

    public function adelantoDescuento($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $adelanto = '<span class="label label-warning">ADELANTO</span>';
        $dscuento = '<span class="label label-danger">DESCUENTO</span>';
        $tipo = "IF(adde_tipo = 'ADELANTO','" . $adelanto . "','" . $dscuento . "')";

        $columns = array(
            array('db' => 'adde_id', 'dt' => 'DT_RowId'),
            array('db' => 'usua_id', 'dt' => 'DT_USUA_ID'),
            array('db' => 'adde_id', 'dt' => 'N°'),
            array('db' => "$tipo", 'dt' => 'TIPO'),
            array('db' => 'CONCAT(usua_nombres," ", usua_apellidos)', 'dt' => 'USUARIO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(adde_importe,2))', 'dt' => 'IMPORTE'),
            array('db' => 'mes_nombre', 'dt' => 'MES'),
            array('db' => 'adde_fecha', 'dt' => 'FECHA'),
            array('db' => 'adde_descripcion', 'dt' => 'DESCRIPCIÓN'),
        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'adelantos_descuentos';
            $primaryKey = 'adde_id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM adelantos_descuentos 
            JOIN usuario ON usua_id = adde_usua_id
            JOIN meses ON mes_id =  MONTH(CONCAT(adde_anio_mes,'-01'))
            ";
            $where = "";


            if ($this->input->post('fecha')) {
                $anio_mes = $this->input->post('fecha');
                $condiciones[] = "adde_anio_mes =  '$anio_mes'";
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Adelantos y Descuentos";

        $this->cssjs->add_js($this->jsPath . "Contabilidad/adelanto_descuento.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/adelanto_descuento", $datos);
        $this->load->view('footer');
    }

    public function comprobanteAdeDesc($id)
    {
        $query = "SELECT AD.*, usua_nombres, usua_apellidos, usua_email, usua_movil, usua_dni
                FROM adelantos_descuentos AD
                JOIN usuario ON adde_usua_id  =  usua_id
                WHERE adde_id= $id";

        $pago = $this->db->query($query)->row();

        $mes_id = explode('-', $pago->adde_anio_mes)[1];
        $mes = $this->db->where("mes_id", $mes_id)->get("meses")->row();

        $pdf = new FPDF();

        $this->general->templatePdfA4($pdf);

        $pdf->SetTitle('COMPROBANTE DE PAGO', 1);
        $pdf->SetY(40); // Despues del header
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'U', 14);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(200, 8, utf8_decode('DETALLE DE ' . $pago->adde_tipo), 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(110, 5, utf8_decode('Usuario: ' . $pago->usua_nombres . ' ' . $pago->usua_apellidos), 0, 'L');
        $pdf->Cell(90, 5, utf8_decode('Email: ' . $pago->usua_email), 0, 'L');
        $pdf->Ln(6);
        $pdf->Cell(50, 5, utf8_decode('Celular: ' . $pago->usua_movil), 0, 'R');
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(60, 5, utf8_decode('Mes ' . strtolower($pago->adde_tipo) . ': ' . $mes->mes_nombre), 0, 'R');
        $pdf->Cell(50, 5, utf8_decode('Fecha del pago: ' . $pago->adde_fecha), 0, 'R');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 10);
        //HEADER TABLE
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 7, utf8_decode('#'), 1, 0, 'C', 1, '');
        $pdf->Cell(100, 7, utf8_decode('DETALLE'), 1, 0, 'C', 1, '');
        $pdf->Cell(35, 7, utf8_decode('MONTO'), 1, 0, 'C', 1, '');
        $pdf->Cell(40, 7, utf8_decode('SUBTOTAL '), 1, 0, 'C', 1, '');

        $pdf->Ln(7);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetFillColor(200, 200, 200);
        $i = 1;
        $total = 0;


        $pdf->Cell(15, 6, utf8_decode($pago->adde_id), 1, 0, 'C', 0, '');
        $pdf->Cell(100, 6, utf8_decode($pago->adde_descripcion), 1, 0, 'L', 0, '');
        $pdf->Cell(35, 6, utf8_decode('S/. ' . $pago->adde_importe), 1, 0, 'C', 0, '');
        $pdf->Cell(40, 6, utf8_decode('S/. ' . $pago->adde_importe), 1, 0, 'C', 0, '');

        $pdf->Ln(6);
        $i++;

        $total += $pago->adde_importe;

        $pdf->Ln(2);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(150, 6, '', 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, '-------------------', 0, 0, 'C', 0, '');
        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('TOTAL:'), 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, 'S/. ' . number_format($total, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);

        $pdf->Output();
    }

    public function resumenGeneral()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;


        //echo '<pre>';
        //echo json_encode($res);
        //echo '</pre>';
        //die();

        $datos['titulo'] = "Resumen General";
        $this->cssjs->add_js($this->jsPath . "Contabilidad/reporte_general.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/reporte_general", $datos);
        $this->load->view('footer');
    }

    public function getDataResumen()
    {
        $select[] = "SELECT CONCAT('FC-' ,id_flujocaja) AS id, descripcion_rubrogasto AS descripcion,  CONCAT('-', ROUND(importe_flujo,2))  AS importe, fecha_flujo AS fecha, cate_id AS categoria
        FROM flujocaja
        JOIN rubrogasto ON rubrogasto_id_rubrogasto = id_rubrogasto
        JOIN categorias C ON cate_id = flca_cate_id ";

        $select[] =  "SELECT CONCAT('PP-' ,idpago) AS id, CONCAT(usua_nombres, ' ' ,usua_apellidos)  AS descripcion, CONCAT('-', ROUND(((horas*costohora) +comisiondirecta+comisionasesores+monto + bono - (descuento +adelanto)) ,2)) AS importe, fecha AS fecha, cate_id AS categoria  
        FROM pagopersonal 
        JOIN usuario ON usua_id = pagopersonal.usuario_usua_id ";


        $select[] = "SELECT CONCAT('AL-' ,idpagos) AS id,CONCAT(pers_nombres, ' ' ,pers_apellidos)  AS descripcion,  ROUND(monto,2)  AS importe, fechapago AS fecha, PR.cate_id AS categoria
        FROM pagos
        JOIN alumnos  ON alumnos_id_alumno = id_alumno
        JOIN productos PR ON productos_id = PR.id
        INNER JOIN personas P ON P.pers_id = alum_pers_id ";

        $select[] = "SELECT CONCAT('AD-' ,adde_id) AS id, CONCAT(U.usua_nombres, ' - ', adde_descripcion )   AS descripcion,  CONCAT('-', ROUND(adde_importe,2))  AS importe, adde_fecha AS fecha, U.cate_id AS categoria
        FROM adelantos_descuentos
        JOIN usuario U ON U.usua_id = adde_usua_id";


        $where = "";
        if (!empty($_POST['categoria'])) {

            if (!empty($where)) {
                $where = ' AND cate_id = ' . $_POST['categoria'];
            }
            $where = 'WHERE cate_id = ' . $_POST['categoria'];

            foreach ($select as $i  => $item) {
                $select[$i] = $item . " " . $where;
            }
        }

        if (!empty($_POST['rango'])) {
            $fechas = explode('-', $_POST['rango']);

            foreach ($select as $i  => $item) {

                if ($i == 0) {
                    if (!empty($where)) {
                        $where = "AND DATE(fecha_flujo) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha_flujo) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
                    }
                    $where = "AND DATE(fecha_flujo) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha_flujo) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
                } else if ($i == 1) {
                    if (!empty($where)) {
                        $where = "AND DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
                    }
                    $where = "AND DATE(fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
                } else if ($i == 3) {
                    if (!empty($where)) {
                        $where = "AND DATE(adde_fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(adde_fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "' AND adde_tipo = 'ADELANTO' ";
                    }
                    $where = "AND DATE(adde_fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(adde_fecha) <= '" . cambiaf_a_mysql($fechas[1]) . "' AND adde_tipo = 'ADELANTO' ";
                } else {
                    if (!empty($where)) {
                        $where = "AND DATE(fechapago) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fechapago) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
                    }
                    $where = "AND DATE(fechapago) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fechapago) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
                }

                $select[$i] = $item . " " . $where;
            }
        }

        $query = implode(' UNION ', $select);
        $query = $query .  " ORDER BY fecha DESC ";
        $limit = "";
        if ($_POST['length'] != -1) {
            $limit = isset($_POST['length']) ? "LIMIT " . $_POST['start'] . ", " . $_POST['length']  : "LIMIT 0, " . 10;
        }


        $datos = $this->db->query($query . "  $limit")->result();

        //echo "<pre>";
        //var_dump($datos);
        //echo "</pre>";
        //die();

        $res['data'] = $datos;
        $res['recordsTotal'] = $this->get_all_data();
        $res['recordsFiltered'] = count($this->db->query($query)->result());
        echo json_encode($res);
    }

    function get_all_data()
    {
        $query = "
        (SELECT CONCAT('FC-' ,id_flujocaja) AS id, descripcion_rubrogasto AS descripcion,  importe_flujo AS importe, fecha_flujo AS fecha  
        FROM flujocaja
        JOIN rubrogasto ON rubrogasto_id_rubrogasto = id_rubrogasto)
        UNION
        (SELECT CONCAT('PP-' ,idpago) AS id, CONCAT(usua_nombres, ' ' ,usua_apellidos)  AS descripcion, ((horas*costohora) +comisiondirecta+comisionasesores+monto + bono - (descuento +adelanto)) AS importe, fecha AS fecha  
        FROM pagopersonal 
        JOIN usuario ON usua_id = pagopersonal.usuario_usua_id)
        UNION
        (SELECT CONCAT('AL-' ,idpagos) AS id,CONCAT(pers_nombres, ' ' ,pers_apellidos)  AS descripcion,  monto AS importe, fechapago AS fecha  
        FROM pagos
        JOIN alumnos  ON alumnos_id_alumno = id_alumno
        INNER JOIN personas P ON P.pers_id = alum_pers_id)";
        $res = $this->db->query($query)->result();
        return count($res);
    }
}
