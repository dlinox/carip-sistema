<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contabilidad extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized')) {
            redirect(base_url() . "login");
        }
        if ($this->session->userdata('usua_tipo') != 2) {
            redirect(base_url() . "login");
        }

        $this->load->library('Cssjs');
        $this->load->library('form_validation');
        $this->load->library('Ssp');
        $this->jsPath = base_url() . "assets/js/";
        $this->cssPath = base_url() . "assets/css/";
        $this->load->model('Model_general', 'general');
        $this->load->model('ContabilidadModel', 'model');
        $this->load->model('AlumnoModel', 'model_alumno');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->load->helper('Response');
        $this->user_id = $this->session->userdata('authorized');
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
            array('db' => 'id_flujocaja', 'dt' => 'N° Registro'),
            array('db' => 'descripcion_flujo', 'dt' => 'Descripcion'),
            array('db' => 'descripcion_rubrogasto', 'dt' => 'Rubro'),
            array('db' => 'fecha_flujo', 'dt' => 'Fecha'),
            array('db' => 'observacion_flujo', 'dt' => 'Observacion'),
            array('db' => 'importe_flujo', 'dt' => 'Importe'),

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
            $flujocaja->descripcion_flujo = "";
            $flujocaja->observacion_flujo = "";
            $flujocaja->importe_flujo = "";
            $flujocaja->rubrogasto_id_rubrogasto = "";
        } else {
            $flujocaja = $this->db->where("id_flujocaja", $id)->get("flujocaja")->row();
        }
        $data["flujocaja"] = $flujocaja;
        $data["tipo"] = $this->general->getOptions('rubrogasto', array("id_rubrogasto", "descripcion_rubrogasto"), '* Tipo gasto');


        $this->load->view($this->controller . "/form_flujo_caja", $data);
    }

    public function flujo_caja_guardar($id = "")
    {
        $tipo = $this->input->post("tipo");
        $fecha = $this->input->post("fecha");
        $importe = $this->input->post("importe");
        $descripcion = $this->input->post("descripcion");
        $observacion = $this->input->post("observacion");

        $flujocaja = array(
            "descripcion_flujo"     => $descripcion,
            "importe_flujo"     => $importe,
            "fecha_flujo"       => $fecha,
            "observacion_flujo"     => $observacion,
            "rubrogasto_id_rubrogasto"        => $tipo
        );


        if (!empty($id)) {
            $condicion = array("id_flujocaja" => $id);

            if ($this->general->update_data("flujocaja", $flujocaja, $condicion)) {
                $json["exito"] = true;
                $json["mensaje"] = "Usuario actualizado con exito";
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "No se guardaron cambios";
            }
        } else {

            if ($this->general->save_data("flujocaja", $flujocaja) != false) {
                $json["exito"] = true;
                $json["mensaje"] = "Gastos registrados con exito";
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
            array('db' => 'idpagos', 'dt' => 'Id Pagos'),
            array('db' => 'pers_dni', 'dt' => 'N° DNI'),
            array('db' => 'pers_nombres', 'dt' => 'Nombre'),
            array('db' => 'pers_apellidos', 'dt' => 'Apellidos'),
            array('db' => 'nombre', 'dt' => 'Curso'),
            array('db' => 'fechapago', 'dt' => 'Fecha de Pago'),
            array('db' => 'monto', 'dt' => 'Monto')
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
            JOIN productos ON peri_prod_id = id 
            JOIN pagos ON alumnos.id_alumno = pagos.alumnos_id_alumno';
            $where = "";

            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(fechapago) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fechapago) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
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

        $user = $this->db->where("id")->get("productos")->row();

        print_r($user);


        $columns = array(
            array('db' => 'idpago', 'dt' => 'DT_RowId'),
            array('db' => 'idpago', 'dt' => 'N° de Pago'),
            array('db' => 'usua_dni', 'dt' => "N° DNI"),
            array('db' => 'CONCAT(usua_nombres, "  ", usua_apellidos )', 'dt' => "Nombre y Apellidos"),
            array('db' => 'tipo_denominacion', 'dt' => 'Cargo'),
            array('db' => 'mes', 'dt' => 'Mes'),
            array('db' => 'fecha', 'dt' => 'Fecha de Pago'),
            array('db' => 'observacion', 'dt' => 'Observacion'),
            array('db' => 'curso', 'dt' => 'Curso'),
            array('db' => 'monto+bono+comisiondirecta+comisionasesores+horas*costohora', 'dt' => 'Pago Total')
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

    public function pagopersonal_crear($id = "")
    {
        $pagopersonal = new stdClass();
        $pagopersonal->idpago = "";
        $pagopersonal->mes = "";
        $pagopersonal->fecha = "";
        $pagopersonal->bono = "";
        $pagopersonal->monto = "";
        $pagopersonal->observacion = "";
        $pagopersonal->curso = "";
        $pagopersonal->comisiondirecta = "";
        $pagopersonal->comisionasesores = "";
        $pagopersonal->horas = "";
        $pagopersonal->costohora = "";
        $pagopersonal->usuario_usua_id = $id;
        $pagopersonal->total = 0;

        $data["pagopersonal"] = $pagopersonal;

        /** Comision Directa */
        $sql1 = 'SELECT IFNULL(SUM(comision), 0) AS comisiondirecta, 
        IFNULL(GROUP_CONCAT(id_alumno), "") AS ids
        FROM alumnos JOIN productos ON productos_id = id
        WHERE habilitado AND usuario_usua_id = ' . $id;
        $comisiondirecta = $this->db->query($sql1)->row();
        print_r($comisiondirecta);

        //echo "____________________";
        $data['comisiondirecta'] = $comisiondirecta;

        //echo "______________________";

        /** Comision Asesores*/
        $sql = 'SELECT IFNULL(SUM(comision_asesor), 0) AS comisionasesores, IFNULL(GROUP_CONCAT(id_alumno), "") AS ids 
        FROM alumnos JOIN productos ON productos_id = id WHERE 
        habilitado AND usuario_usua_id IN (SELECT usua_id FROM usuario WHERE usuario_usua_id = ' . $id . ')';
        $comisionasesores = $this->db->query($sql)->row();
        print_r($comisionasesores);
        //echo "______________________";
        $data['comisionasesores'] = $comisionasesores;

        //echo "_______________________________________________________________________________";

        /** Busca Los Cursos que enseña */
        #$nombregrupo = 'CONCAT(nombre," ", peri_anho,"-" ,peri_correlativo, "-", grup_nombre)';
        $sql2 = 'SELECT peri_prod_id, nombre FROM grupos
        JOIN usuario ON grup_docente_id = usua_id
        JOIN periodos ON grup_peri_id = peri_id JOIN productos ON peri_prod_id = id WHERE usua_id = ' . $id . ' AND grup_finalizado = 0';
        #$cursos = $this->db->query($sql2)->row();

        $query = $this->db->query($sql2);
        $cursos = [];
        foreach ($query->result() as $row) {
            $i = 0;
            //echo $row->peri_prod_id;
            //echo $row->nombre;
            //echo "____________________";
            $cursos[] = array($row->peri_prod_id => $row->nombre);
        }

        //print_r($cursos);

        $data['cursos'] = $cursos;

        //echo "//************************************************************************************";
        #print_r($cursos);
        //echo "************************************************************************************";

        $usuario = $data['pagopersonal']->usuario_usua_id;

        //echo $usuario;

        $user = $this->db->where("usua_id", $usuario)->get("usuario")->row();
        //print_r($user);
        $datos["usuario"] = $user;

        $data["nombrepersona"] = $user;

        #print_r($data);


        if ($datos["usuario"]->usua_tipo == '1') {
            $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario');
            $this->load->view($this->controller . "/form_pagopersonalventa", $data);
        } elseif ($datos["usuario"]->usua_tipo == '2') {

            //echo "Tiene Codigo 2 es Administracion";
            $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario', '', '');

            $this->load->view($this->controller . "/form_pagopersonaladmatecob", $data);
        } elseif ($datos["usuario"]->usua_tipo == '3') {
            //echo "Tiene Codigo 1 es Atencion";
            $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario');


            $this->load->view($this->controller . "/form_pagopersonaladmatecob", $data);
        } elseif ($datos["usuario"]->usua_tipo == '4') {
            //echo "Tiene Codigo 1 es Cobranza";
            $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario');


            $this->load->view($this->controller . "/form_pagopersonaladmatecob", $data);
        } elseif ($datos["usuario"]->usua_tipo == '5') {
            //echo "Tiene Codigo 5 es Docente";
            $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario');
            $a = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario');
            //echo "22222222222222222222222222222222222222222222222222222222222222222222222";
            //print_r($a);
            $this->load->view($this->controller . "/form_pagopersonaldocente", $data);
        }
    }

    /** Fin de Editar */

    public function pagopersonalatencion_crear($id = "")
    {

        if (empty($id)) {

            $pagopersonal = new stdClass();
            $pagopersonal->idpago = "";
            $pagopersonal->mes = "";
            $pagopersonal->fecha = "";
            $pagopersonal->bono = "";
            $pagopersonal->monto = "";
            $pagopersonal->observacion = "";
            $pagopersonal->curso = "";
            $pagopersonal->comisiondirecta = "";
            $pagopersonal->comisionasesores = "";
            $pagopersonal->usuario_usua_id = "";
            $pagopersonal->tipousuario = "";
            $pagopersonal->total = 0;
        } else {
            #$pagopersonal = $this->db->where("idpago", $id)->get("pagopersonal")->row();

            $pagopersonal = $this->model->getByPagoPersonal($id);

            print_r($pagopersonal);
        }
        $pagopersonal->total = intval($pagopersonal->bono) + intval($pagopersonal->monto);

        $data["pagopersonal"] = $pagopersonal;
        $data["tipo"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), '', '', 'tipo_id=3');
        $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario', '', 'usua_tipo=3');

        $this->load->view($this->controller . "/form_pagopersonal", $data);
    }

    public function pagopersonalcobranza_crear($id = "")
    {
        if (empty($id)) {

            $pagopersonal = new stdClass();
            $pagopersonal->idpago = "";
            $pagopersonal->mes = "";
            $pagopersonal->fecha = "";
            $pagopersonal->bono = "";
            $pagopersonal->monto = "";
            $pagopersonal->observacion = "";
            $pagopersonal->curso = "";
            $pagopersonal->comisiondirecta = "";
            $pagopersonal->comisionasesores = "";
            $pagopersonal->usuario_usua_id = "";
            $pagopersonal->tipousuario = "";
            $pagopersonal->total = 0;
        } else {
            $pagopersonal = $this->db->where("idpago", $id)->get("pagopersonal")->row();
        }
        $pagopersonal->total = intval($pagopersonal->bono) + intval($pagopersonal->monto);

        $data["pagopersonal"] = $pagopersonal;
        $data["tipo"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), '', '', 'tipo_id=4');
        $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario', '', 'usua_tipo=4');

        $this->load->view($this->controller . "/form_pagopersonal", $data);
    }

    public function pagopersonaldocente_crear($id = "")
    {

        if (empty($id)) {

            $pagopersonal = new stdClass();
            $pagopersonal->idpago = "";
            $pagopersonal->mes = "";
            $pagopersonal->fecha = "";
            $pagopersonal->bono = "";
            $pagopersonal->monto = "";
            $pagopersonal->observacion = "";
            $pagopersonal->curso = "";
            $pagopersonal->comisiondirecta = "";
            $pagopersonal->comisionasesores = "";
            $pagopersonal->usuario_usua_id = "";
            $pagopersonal->tipousuario = "";
            $pagopersonal->total = 0;
            $pagopersonal->horas = 0;
            $pagopersonal->costohora = 0;
        } else {
            $pagopersonal = $this->db->where("idpago", $id)->get("pagopersonal")->row();
        }
        $pagopersonal->monto = intval($pagopersonal->bono) + floatval($pagopersonal->horas) * floatval($pagopersonal->costohora);

        $data["pagopersonal"] = $pagopersonal;
        $data["tipo"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), '', '', 'tipo_id=5');
        $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario', '', 'usua_tipo=5');
        $data["producto"] = $this->general->getOptions('productos', array("id", "nombre"), '* Cursos');
        $this->load->view($this->controller . "/form_pagopersonaldocente", $data);
    }
    public function pagopersonalventa_crear($id = "")
    {

        if (empty($id)) {

            $pagopersonal = new stdClass();
            $pagopersonal->idpago = "";
            $pagopersonal->mes = "";
            $pagopersonal->fecha = "";
            $pagopersonal->bono = "";
            $pagopersonal->monto = "";
            $pagopersonal->observacion = "";
            $pagopersonal->curso = "";
            $pagopersonal->comisiondirecta = "";
            $pagopersonal->comisionasesores = "";
            $pagopersonal->usuario_usua_id = "";
            $pagopersonal->tipousuario = "";
            $pagopersonal->total = 0;
        } else {
            $pagopersonal = $this->db->where("idpago", $id)->get("pagopersonal")->row();
        }
        $pagopersonal->total = intval($pagopersonal->bono) + intval($pagopersonal->monto) + intval($pagopersonal->comisiondirecta) + intval($pagopersonal->comisionasesores);

        $data["pagopersonal"] = $pagopersonal;
        $data["tipo"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), '', '', 'tipo_id=1');
        $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario', '', 'usua_tipo=1');
        $this->load->view($this->controller . "/form_pagopersonalventa", $data);
    }
    public function pagopersonaladmin_crear($id = "")
    {
        if (empty($id)) {

            $pagopersonal = new stdClass();
            $pagopersonal->idpago = "";
            $pagopersonal->mes = "";
            $pagopersonal->fecha = "";
            $pagopersonal->bono = "";
            $pagopersonal->monto = "";
            $pagopersonal->observacion = "";
            $pagopersonal->curso = "";
            $pagopersonal->comisiondirecta = "";
            $pagopersonal->comisionasesores = "";
            $pagopersonal->usuario_usua_id = "";
            $pagopersonal->tipousuario = "";
            $pagopersonal->total = "";
        } else {
            $pagopersonal = $this->db->where("idpago", $id)->get("pagopersonal")->row();
        }
        $pagopersonal->total = intval($pagopersonal->bono) + intval($pagopersonal->monto);

        $data["pagopersonal"] = $pagopersonal;
        $data["tipo"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), '', '', 'tipo_id=2');
        $data["usuario"] = $this->general->obtenerDatos('usuario', array("usua_id", "usua_nombres", "usua_apellidos"), '* Usuario', '', 'usua_tipo=2');

        $this->load->view($this->controller . "/form_pagopersonaladmatecob", $data);
    }

    /** Inicio de Guardar Pago a Atencion, Administracion y Cobranza*/
    public function pagopersonaladmatecob_guardar($id = "")
    {
        $mes = $this->input->post("mes");
        $fecha = $this->input->post("fecha");
        $bono = $this->input->post("bono");
        $monto = $this->input->post("monto");
        $observacion = $this->input->post("observacion");
        $curso = $this->input->post("curso");
        $comisiondirecta = $this->input->post("comisiondirecta");
        $comisionasesores = $this->input->post("comisionasesores");
        $comisionasesores = $this->input->post("horas");
        $comisionasesores = $this->input->post("costohora");
        $usuario = $this->input->post("usuario");

        $pagopersonal = array(
            "mes"   => $mes,
            "fecha" => $fecha,
            "bono"  => $bono,
            "monto" => $monto,
            "observacion"   => $observacion,
            "curso" => $curso,
            "comisiondirecta"   => $comisiondirecta,
            "comisionasesores"  => $comisionasesores,
            "usuario_usua_id"   => $usuario
        );

        if (!empty($id)) {
            $condicion = array("idpago" => $id);

            if ($this->general->update_data("pagopersonal", $pagopersonal, $condicion)) {
                $json["exito"] = true;
                $json["mensaje"] = "Usuario actualizado con exito";
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "No se guardaron cambios";
            }
        } else {

            if ($this->general->save_data("pagopersonal", $pagopersonal) != false) {
                $json["exito"] = true;
                $json["mensaje"] = "Gastos registrados con exito";
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "Error ak al guardar Gastos";
            }
        }
        echo json_encode($json);
    }
    /** Fin de Guardar Pago a Atencion, Administracion y Cobranza*/


    public function pagopersonal_guardar($id = "")
    {
        $mes = $this->input->post("mes");
        $fecha = $this->input->post("fecha");
        $bono = $this->input->post("bono");
        $monto = $this->input->post("monto");
        $observacion = $this->input->post("observacion");
        $curso = $this->input->post("curso");
        $comisiondirecta = $this->input->post("comisiondirecta");
        $comisionasesores = $this->input->post("comisionasesores");
        $horas = $this->input->post("horas");
        $costohora = $this->input->post("costohora");
        $usuario = $this->input->post("idpersona");

        $pagopersonal = array(
            "mes"   => $mes,
            "fecha" => $fecha,
            "bono"  => $bono,
            "monto" => $monto,
            "observacion"   => $observacion,
            "curso" => $curso,
            "comisiondirecta"   => $comisiondirecta,
            "comisionasesores"  => $comisionasesores,
            "horas" => $horas,
            "costohora" => $costohora,
            "usuario_usua_id"   => $usuario
        );


        if (!empty($id)) {
            $condicion = array("idpago" => $id);

            if ($this->general->update_data("pagopersonal", $pagopersonal, $condicion)) {
                $json["exito"] = true;
                $json["mensaje"] = "Usuario actualizado con exito";
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "No se guardaron cambios";
            }
        } else {

            if ($this->general->save_data("pagopersonal", $pagopersonal) != false) {
                $json["exito"] = true;
                $json["mensaje"] = "Gastos registrados con exito";
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "Error ak al guardar Gastos";
            }
        }
        echo json_encode($json);
    }



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
        $datos['titulo'] = "Resumen Mensual";


        $this->cssjs->add_js($this->jsPath . "Contabilidad/resumenmensual.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/resumenmensual", $datos);
        $this->load->view('footer');
    }

    public function resumenanual($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');

        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $columns = array(
            array('db' => 'idpago', 'dt' => 'DT_RowId'),
            array('db' => 'idpago', 'dt' => 'N° de Pago'),
            array('db' => 'usua_dni', 'dt' => "N° DNI"),
            array('db' => 'CONCAT(usua_nombres, "  ", usua_apellidos )', 'dt' => "Nombre y Apellidos"),
            array('db' => 'tipo_denominacion', 'dt' => 'Cargo'),
            array('db' => 'mes', 'dt' => 'Mes'),
            array('db' => 'fecha', 'dt' => 'Fecha de Pago'),
            array('db' => 'observacion', 'dt' => 'Observacion'),
            array('db' => 'curso', 'dt' => 'Curso'),
            array('db' => 'monto+bono+comisiondirecta+comisionasesores+horas*costohora', 'dt' => 'Pago Total')
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
        #print_r($fechas);
        echo json_encode($this->model->getEgresosIngresos(cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1])));
        #echo json_encode($this->model->getComisiones($this->user_id, cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1])));
    }

    public function getcomisiones()
    {
        $fechas = explode('-', $_POST['rango']);
        echo json_encode($this->model_alumno->getComisiones($this->user_id, cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1])));
    }
}
