<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AtencionCliente extends CI_Controller
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
        $this->load->model('Model_general', 'general');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->user_id = $this->session->userdata('authorized');
        // error_reporting(0);
        // ini_set('display_errors', 0);
    }
    public function index()
    {
        $this->lista();
    }
    public function crear($id = "")
    {
        $datos = [];
        $datos["rubros"] = $this->general->getOptions('rubros', array("id", "nombre"));
        $datos["productos"] = $this->general->getOptions('productos', array("id", "nombre"));
        $datos["tipo_alumnos"] = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $datos["tipo_pagos"] = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $datos["condiciones"] = $this->general->getOptions('condiciones', array("id", "nombre"));
        $datos["paises"] = $this->general->getOptions('paises', array("idpaises", "nombre"));
        $datos["departamentos"] = $this->general->getOptions('departamentos', array("iddepartamentos", "nombre"));

        if (empty($id)) {
            $venta = new stdClass();
            $venta->id_alumno = "";
            $venta->dni = "";
            $venta->nombre = "";
            $venta->apellidos = "";
            $venta->celular = "";
            $venta->fecha_inscripcion =  date('Y-m-d');
            $venta->titular_dni = "";
            $venta->titular_nombre = "";
            $venta->titular_apellidos = "";
            $venta->titular_celular = "";
            $venta->rubros_id = "";
            $venta->productos_id = "";
            $venta->tipo_alumnos_id = "";
            $venta->tipo_pago_id = "";
            $venta->observacion = "";
            $venta->cuotas = "";
            $venta->condiciones_id = "";
            $venta->usuario_usua_id = "";
            $venta->habilitado = "";
            $venta->correo_electronico = "ce";
            $venta->direccion = "d";
            $venta->centro_laboral = "cl";
            $venta->fecha_nacimiento = "fn";
            $venta->paises_id = "pi";
            $venta->departamentos_id = "di";
        } else if ($id[0] == "L") {
            $llamada = $this->db->where("id_llamada", substr($id, 1))->get("llamadas")->row();
            $venta = new stdClass();
            $venta->id_alumno = "";
            $venta->dni = $llamada->dni;
            $venta->nombre = $llamada->nombre;
            $venta->apellidos = $llamada->apellidos;
            $venta->celular = $llamada->celular;
            $venta->fecha_inscripcion =  date('Y-m-d');
            $venta->titular_dni = "";
            $venta->titular_nombre = "";
            $venta->titular_apellidos = "";
            $venta->titular_celular = "";
            $venta->rubros_id = "";
            $venta->productos_id = "";
            $venta->tipo_alumnos_id = "";
            $venta->tipo_pago_id = "";
            $venta->observacion = "";
            $venta->cuotas = "";
            $venta->condiciones_id = "";
            $venta->usuario_usua_id = "";
            $venta->habilitado = "";
        } else {
            $venta = $this->db->where("id_alumno", $id)->get("alumnos")->row();
        }
        $datos["venta"] = $venta;
        $datos["estado"] = array("1" => "HABILITADO", "0" => "BLOQUEADO");
        $this->cssjs->add_js($this->jsPath . "ventas/form.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();
        $this->load->view('header', $script);
        $this->load->view($this->controller . '/formulario', $datos);
        $this->load->view('footer');
    }
    public function vista($id = "")
    {
        $datos = [];
        $datos["rubros"] = $this->general->getOptions('rubros', array("id", "nombre"));
        $datos["productos"] = $this->general->getOptions('productos', array("id", "nombre"));
        $datos["tipo_alumnos"] = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $datos["tipo_pagos"] = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $datos["condiciones"] = $this->general->getOptions('condiciones', array("id", "nombre"));
        $datos["paises"] = $this->general->getOptions('paises', array("idpaises", "nombre"));
        $datos["departamentos"] = $this->general->getOptions('departamentos', array("iddepartamentos", "nombre"));

        if (empty($id)) {
            $venta = new stdClass();
            $venta->id_alumno = "";
            $venta->dni = "";
            $venta->nombre = "";
            $venta->apellidos = "";
            $venta->celular = "";
            $venta->fecha_inscripcion =  date('Y-m-d');
            $venta->titular_dni = "";
            $venta->titular_nombre = "";
            $venta->titular_apellidos = "";
            $venta->titular_celular = "";
            $venta->rubros_id = "";
            $venta->productos_id = "";
            $venta->tipo_alumnos_id = "";
            $venta->tipo_pago_id = "";
            $venta->observacion = "";
            $venta->cuotas = "";
            $venta->condiciones_id = "";
            $venta->usuario_usua_id = "";
            $venta->habilitado = "";
            $venta->correo_electronico = "ce";
            $venta->direccion = "d";
            $venta->centro_laboral = "cl";
            $venta->fecha_nacimiento = "fn";
            $venta->paises_id = "pi";
            $venta->departamentos_id = "di";
        } else if ($id[0] == "L") {
            $llamada = $this->db->where("id_llamada", substr($id, 1))->get("llamadas")->row();
            $venta = new stdClass();
            $venta->id_alumno = "";
            $venta->dni = $llamada->dni;
            $venta->nombre = $llamada->nombre;
            $venta->apellidos = $llamada->apellidos;
            $venta->celular = $llamada->celular;
            $venta->fecha_inscripcion =  date('Y-m-d');
            $venta->titular_dni = "";
            $venta->titular_nombre = "";
            $venta->titular_apellidos = "";
            $venta->titular_celular = "";
            $venta->rubros_id = "";
            $venta->productos_id = "";
            $venta->tipo_alumnos_id = "";
            $venta->tipo_pago_id = "";
            $venta->observacion = "";
            $venta->cuotas = "";
            $venta->condiciones_id = "";
            $venta->usuario_usua_id = "";
            $venta->habilitado = "";
        } else {
            $venta = $this->db->where("id_alumno", $id)->get("alumnos")->row();
        }
        $datos["venta"] = $venta;
        $datos["estado"] = array("1" => "HABILITADO", "0" => "BLOQUEADO");

        $this->cssjs->add_js($this->jsPath . "ventas/form.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();
        $this->load->view('header', $script);
        $this->load->view($this->controller . '/vista', $datos);
        $this->load->view('footer');
    }
    public function guardar($id = null)
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_rules('dni', 'Dni', 'required');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
        $this->form_validation->set_rules('celular', 'Celular', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }
        // $this->load->helper('Funciones');
        $dni = $this->input->post('dni');
        $nombre = $this->input->post('nombre');
        $apellidos = $this->input->post('apellidos');
        $celular = $this->input->post('celular');
        $fecha_inscripcion = $this->input->post('fecha_inscripcion');
        $titular_dni = $this->input->post('titular_dni');
        $titular_nombre = $this->input->post('titular_nombre');
        $titular_apellidos = $this->input->post('titular_apellidos');
        $titular_celular = $this->input->post('titular_celular');
        $rubros_id = $this->input->post('rubros_id');
        $productos_id = $this->input->post('productos_id');
        $tipo_alumnos_id = $this->input->post('tipo_alumnos_id');
        $tipo_pago_id = $this->input->post('tipo_pago_id');
        $cuotas = $this->input->post('cuotas');
        $condiciones_id = $this->input->post('condiciones_id');
        $habilitado = $this->input->post('habilitado');

        $correo_electronico = $this->input->post('correo_electronico');
        $direccion = $this->input->post('direccion');
        $centro_laboral = $this->input->post('centro_laboral');
        $fecha_nacimiento = $this->input->post('fecha_nacimiento');
        $paises_id = $this->input->post('paises_id');
        $departamentos_id = $this->input->post('departamentos_id');

        $data = array(
            "dni" => $dni,
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "celular" => $celular,
            "fecha_inscripcion" => $fecha_inscripcion,
            "titular_dni" => $titular_dni,
            "titular_nombre" => $titular_nombre,
            "titular_apellidos" => $titular_apellidos,
            "titular_celular" => $titular_celular,
            "rubros_id" => $rubros_id,
            "productos_id" => $productos_id,
            "tipo_alumnos_id" => $tipo_alumnos_id,
            "tipo_pago_id" => $tipo_pago_id,
            "cuotas" => $cuotas,
            "condiciones_id" => $condiciones_id,
            "usuario_usua_id" => $this->user_id,
            "correo_electronico" => $correo_electronico,
            "direccion" => $direccion,
            "centro_laboral" => $centro_laboral,
            "fecha_nacimiento" => $fecha_nacimiento,
            "paises_id" => $paises_id,
            "departamentos_id" => $departamentos_id,
        );

        if ($id != null) {
            $condicion = array("id_alumno" => $id);
            if ($this->general->update_data("alumnos", $data, $condicion)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "alumno agregado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        } else {
            $data2 = $this->general->getData("alumnos", array("dni"), array("dni" => $dni));
            if (!empty($data2)) {
                $resp["exito"] = false;
                $resp["mensaje"] = "alumno ya existe";
                echo json_encode($resp);
                die;
            }

            if ($this->general->save_data("alumnos", $data) != false) {
                $resp["exito"] = true;
                $resp["mensaje"] = "alumno registrado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        }
        echo json_encode($resp);
        // redirect(base_url() . "Ventas");
    }
    public function lista($json = false)
    {

        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : false;
        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';
        $estado = "IF(habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";
        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'DT_RowId'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'concat(pers_nombres)', 'dt' => 'NOMBRES'),
            array('db' => 'pers_apellidos', 'dt' => 'APELLIDOS'),
            array('db' => 'concat(productos.nombre)', 'dt' => 'CURSO'),
            array('db' => 'round(costo,2)', 'dt' => 'COSTO CURSO'),
            array('db' => 'round(costo*0.3, 2)', 'dt' => 'COMISION DIRECTA'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
            array('db' => $estado, 'dt' => 'ESTADO'),
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
            $joinQuery = "FROM alumnos JOIN personas ON alum_pers_id = pers_id JOIN productos on productos.id = alumnos.productos_id";
            $where = "";
            if (!empty($_POST['mes'])) {
                $fecha = explode('-', $_POST['mes']);
                $condiciones[] = "year(fecha_inscripcion) ='" . $fecha[0] . "' AND month(fecha_inscripcion) = '" . $fecha[1] . "'";
            }
            if (!empty($_POST['buscar'])) {
                $condicion = "dni LIKE CONCAT('%','" . $_POST['buscar'] . "' ,'%') OR ";
                $condicion .= "pers_nombres LIKE CONCAT('%','" . $_POST['buscar'] . "' ,'%') OR ";
                $condicion .= "pers_apellidos LIKE CONCAT('%','" . $_POST['buscar'] . "' ,'%')";
                $condiciones[] =  $condicion;
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
        $datos['titulo'] = "Ventas";
        // $datos["proveedores"] = $this->general->getOptions("proveedor", array("prov_id", "prov_nombre"), "* Proveedor");

        $this->cssjs->add_js($this->jsPath . "AtencionCliente/lista.js?v=1.1", false, false);
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
        $datos['comision_directa'] = $datos['comision_directa'] != null ? $datos['comision_directa'] : 0;
        $datos['comision_total'] = $datos['comision_asesor'] + $datos['comision_directa'];
        echo json_encode($datos);
    }
    /** Agregar Formulario Anterior */
    public function agregarPagoFormulario($id)
    {
        $sql = 'SELECT  * 
                FROM    alumnos 
                JOIN    grupos_alumnos 
                ON      gral_id_alumno = id_alumno 
                JOIN    grupos 
                ON      gral_grup_id = grup_id 
                JOIN    periodos 
                ON      grup_peri_id = peri_id 
                JOIN    productos 
                ON      peri_prod_id = id 
                WHERE   id_alumno = ' . $id;
        $alumno = $this->db->query($sql)->row();
        print_r($alumno);
        $costo_total = number_format($alumno->cuotas * $alumno->costo, 2, '.', '');
        #$descuento_total = number_format($alumno->cuotas * $alumno->alum_descuento, 2);
        $descuento_total = number_format(1 * $alumno->alum_descuento, 2, '.', '');
        $costo_con_descuento = number_format($costo_total - $descuento_total, 2, '.', '');
        $pendiente = $costo_con_descuento - $alumno->alum_pagado;
        $pagado = $$this->load->view($this->controller . "/agregar_pago", compact('costo_total', 'descuento_total', 'costo_con_descuento', 'alumno', 'pendiente'));
    }
    /*** Agregar Pago anterior misma tablac (actualizar) */
    public function agregarPagoSavee($id)
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser mayor a 0');

        $this->form_validation->set_rules('monto', 'Monto', 'required|greater_than[0]');
        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $this->db->set('alum_pagado', 'alum_pagado +' . $this->input->post('monto'), false);
        $this->db->where('id_alumno', $id);
        $result = $this->db->update('alumnos');

        if ($result) {
            $resp["exito"] = true;
            $resp["mensaje"] = "Pago registrado con exito";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        }

        echo json_encode($resp);
    }
    /*** Guardar Pago */

    public function agregarPagoForm($id)
    {
        $sql = 'SELECT  * 
                FROM    alumnos 
                JOIN    grupos_alumnos  ON      gral_id_alumno = id_alumno 
                JOIN    grupos          ON      gral_grup_id = grup_id 
                JOIN    periodos        ON      grup_peri_id = peri_id 
                JOIN    productos       ON      productos_id = id
                LEFT JOIN		pagos   ON		id_alumno = alumnos_id_alumno	 
                WHERE   id_alumno = ' . $id;

        $alumno = $this->db->query($sql)->row();
        //print_r($alumno);

        //echo "------------------------------------------------------------------------------------";

        $sqlpagos = 'SELECT SUM(IFNULL(monto,0)) AS pagado
                    FROM pagos
                    WHERE alumnos_id_alumno = ' . $id;

        $pagos = $this->db->query($sqlpagos)->row();
        //print_r($pagos);

        $costo_total = number_format($alumno->cuotas * $alumno->costo, 2, '.', '');

        #$descuento_total = number_format($alumno->cuotas * $alumno->alum_descuento, 2);
        $descuento_total = number_format(1 * $alumno->alum_descuento, 2, '.', '');
        $costo_con_descuento = number_format($costo_total - $descuento_total, 2, '.', '');
        $pendiente = number_format($costo_con_descuento - $pagos->pagado, 2, '.', '');
        $pagado = number_format($pagos->pagado, 2, '.', '');
        $this->load->view($this->controller . "/agregar_pago", compact('costo_total', 'descuento_total', 'costo_con_descuento', 'alumno', 'pendiente', 'pagado'));
    }

    public function agregarPagoSave($id)
    {
        $pendiente = $this->input->post('pendiente');
        $monto = $this->input->post('monto');

        if($monto > $pendiente){
            $resp["exito"] = false;
            $resp["mensaje"] = "El monto es mayor a la deuda";
            echo json_encode($resp);
            die();
        }
       
        
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser mayor a 0');
        $this->form_validation->set_message('less_than_equal_to', '%s debe de ser meno a %s');

        $this->form_validation->set_rules('monto', "Monto', 'required|greater_than[0]|less_than_equal_to[160]");
        $this->form_validation->set_rules('fechapago', 'Fecha de Pago', 'required');

        $this->form_validation->set_rules('fechapago', 'Fecha de Pago', 'required');


        
        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }
        $this->db->set('fechapago', $this->input->post('fechapago'));
        $this->db->set('monto', $this->input->post('monto'));
        $this->db->set('alumnos_id_alumno', $id);

        $data = [
            'fechapago' => $this->input->post('fechapago'),
            'monto' => $this->input->post('monto'),
            'alumnos_id_alumno' => $id,
        ];

        //$result = $this->db->insert('pagos');
        //$venta = $this->db->where("id_alumno", $id)->get("alumnos")->row();
        $result = $this->general->save_data("pagos", $data);

        if ($result != false) {
            $resp["exito"] = true;
            $resp["mensaje"] = "Pago registrado con exito";
            $resp["pago_id"] = $result;
            $resp["tipo_comp"] = $this->input->post('tipo_comp');
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        }

        echo json_encode($resp);
    }

    public function editarpago($id)
    {
        $sql = 'SELECT  * 
        FROM    pagos  
        JOIN    alumnos
        ON		id_alumno = alumnos_id_alumno
        JOIN productos
        ON productos_id = id 
        WHERE   idpagos = ' . $id;
        $alumno = $this->db->query($sql)->row();
        //print_r($alumno);



        $costo_total = number_format($alumno->cuotas * $alumno->costo, 2, '.', '');
        #$descuento_total = number_format($alumno->cuotas * $alumno->alum_descuento, 2);
        $descuento_total = number_format(1 * $alumno->alum_descuento, 2, '.', '');
        $costo_con_descuento = number_format($costo_total - $descuento_total, 2, '.', '');
        $pagado = $alumno->monto;
        $fecha = $alumno->fechapago;


        $this->load->view($this->controller . "/editar_pago", compact('costo_total', 'descuento_total', 'costo_con_descuento', 'pagado', 'fecha', 'alumno'));
    }

    public function editarSave($id)
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser mayor a 0');

        $this->form_validation->set_rules('monto', 'Monto', 'required|greater_than[0]');
        $this->form_validation->set_rules('fechapago', 'Fecha de Pago', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }
        $this->db->set('fechapago', $this->input->post('fechapago'));
        $this->db->set('monto', $this->input->post('monto'));
        $this->db->where('idpagos', $id);
        $result = $this->db->update('pagos');
        //$result = $this->db->insert('pagos');
        // Produces: INSERT INTO mytable (`name`) VALUES ('{$name}')
        //$result = $this->db->insert('', $this->input->post('fechapago'), '$id');
        //$this->db->set('alum_pagado', 'alum_pagado +' . $this->input->post('monto'), false);
        //$this->db->where('id_alumno', $id);
        //$result = $this->db->update('alumnos');

        if ($result) {
            $resp["exito"] = true;
            $resp["mensaje"] = "Pago Actualizado con exito";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        }

        echo json_encode($resp);
    }


    public function eliminarpago($id)
    {
        $this->db->trans_start();
        $this->general->delete_data("pagos", array("idpagos" => $id));
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


    public function verNotas($alum_id, $grup_id)
    {
        $query = " SELECT AL.id_alumno AS alum_id,
                    group_concat(NT.nota_promedio ORDER BY N.nive_id ASC) AS notas,
                    group_concat(N.nive_nombre ORDER BY N.nive_id ASC) AS niveles
                    FROM notas NT
                    JOIN niveles N ON N.nive_id = NT.nive_id
                    JOIN grupos G ON G.grup_id = N.grupo_id
                    JOIN grupos_alumnos GA ON GA.gral_id = NT.gral_id
                    JOIN alumnos AL ON GA.gral_id_alumno = AL.id_alumno
                    WHERE G.grup_id = $grup_id AND AL.id_alumno = $alum_id
                    GROUP BY AL.id_alumno";


        $notas = $this->db->query($query)->row();


        $this->load->view($this->controller . "/ver_notas", compact('notas'));
    }

    public function verAsistencias($alum_id, $grup_id)
    {
        $opciones_bienvenida = ['0' => 'No contesto', '1' => 'Si'];
        $data = $this->general->getData('grupos_alumnos', ['gral_asistencias AS asistencias, gral_llamadas AS llamadas'], ['gral_id_alumno' => $alum_id, 'gral_grup_id' => $grup_id]);
        $asistencias = $data[0]->asistencias;
        $llamadas = $data[0]->llamadas;
        if ($llamadas) {
            $llamadas = explode(',', $llamadas);
        } else {
            $llamadas = [];
        }
        $this->load->view($this->controller . "/ver_asistencias", compact('asistencias', 'llamadas', 'opciones_bienvenida', 'alum_id', 'grup_id'));
    }

    public function guardarAsistencias($alum_id, $grup_id)
    {
        $resp = [];
        $hubo_bienvenida = implode(',', $this->input->post('hubo_bienvenida'));
        $condicion = array("gral_grup_id" => $grup_id, 'gral_id_alumno' => $alum_id);
        $data = ['gral_llamadas' => $hubo_bienvenida];
        if ($this->general->update_data("grupos_alumnos", $data, $condicion)) {
            $resp["exito"] = true;
            $resp["mensaje"] = "Datos guardados correctamente";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrió un error guardando las llamadas";
        }

        echo json_encode($resp);
    }

    public function alumno($id = '')
    {
        $show_completar = false;
        $es_alumno =  false;
        $rubros = $this->general->getOptions('rubros', array("id", "nombre"));
        $tipo_alumnos = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $condiciones = $this->general->getOptions('condiciones', array("id", "nombre"));

        //$productos = $this->general->getOptions('productos', array("id", "nombre"), 'Seleccione');
        //$tipo_pagos = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $estado = array("1" => "HABILITADO", "0" => "BLOQUEADO");

        // $paises = $this->general->getOptions('paises', array("idpaises", "nombre"));;
        $departamentos = $this->general->getOptions('departamentos', array("iddepartamentos", "nombre"));
        $entidades_bancarias = $this->general->getOptions('entidades_bancarias', array("enba_id", "enba_nombre"));
        $tipos_tarjeta = $this->general->enum_valores('alumnos', 'alum_tipo_tarjeta');
        $franquicias = $this->general->getOptions('franquicias', array("fran_id", "fran_nombre"));
        $opciones_bienvenida = ['OK' => 'Si', 'NC' => 'No contesto'];


        $sql = "
        SELECT *
            FROM personas P
	        LEFT JOIN alumnos A ON P.pers_id = A.alum_pers_id
	        LEFT JOIN departamentos D ON D.iddepartamentos = P.pers_iddepartamentos
	        WHERE P.pers_id = {$id}";

        $persona = $this->db->query($sql)->row();

        $venta = "";
        if ($persona->id_alumno != null) {
            $show_completar = true;
            $es_alumno =  true;
            $venta = new stdClass();
            $venta->id_alumno = $persona->id_alumno;
            $venta->productos_id = $persona->productos_id;
            $venta->alum_pers_id = $persona->alum_pers_id;
            $venta->alum_apoderado_id = $persona->alum_apoderado_id;
            $venta->rubros_id = $persona->rubros_id;
            $venta->tipo_alumnos_id = $persona->tipo_alumnos_id;
            $venta->condiciones_id = "";
            $venta->fecha_inscripcion = $persona->fecha_inscripcion;
            $venta->habilitado = $persona->habilitado;
            $venta->observacion = $persona->observacion;
            //$venta->costo = $persona->costo;
            $venta->tieneDescuento = isset($persona->alum_descuento) ? 1 : 0;

            $venta->alum_hubo_bienvenida = $persona->alum_hubo_bienvenida;
            $venta->tipo_pago_id = $persona->tipo_pago_id;
            //$venta->costo = $persona->costo;

            $venta->alum_enba_id = $persona->alum_enba_id;
            $venta->alum_tipo_tarjeta = $persona->alum_tipo_tarjeta;
            $venta->alum_fran_id = $persona->alum_fran_id;
            $venta->alum_numero_tarjeta = $persona->alum_numero_tarjeta;
            $venta->alum_mes_caducidad = $persona->alum_mes_caducidad;
            $venta->alum_anho_caducidad = $persona->alum_anho_caducidad;
            $venta->observacion = $persona->observacion;
        }


        $this->cssjs->add_js($this->jsPath . "ventas/form.js?v=1", false, false);
        $this->cssjs->add_js($this->jsPath . "AtencionCliente/editarAlumno.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();

        //echo "<pre>";
        //var_dump($venta);
        //echo "</pre>";
        $this->load->view('header', $script);
        $this->load->view('AtencionCliente/editar_alumno', compact('rubros', 'tipo_alumnos', 'condiciones', /*'productos',*/ 'estado', 'venta', /*'tipo_pagos',*/ 'departamentos', 'persona', 'show_completar', 'entidades_bancarias', 'tipos_tarjeta', 'franquicias', 'opciones_bienvenida', 'es_alumno'));
        // $this->load->view('Ventas/completar', compact('venta'));
        $this->load->view('footer');
    }

    public function alumnoEditar($id = '')
    {

        $resp = [];

        $pers_id =  $this->input->post('_persona_id');

        $data_pers = [
            'pers_nombres' => $this->input->post('per_nombre'),
            'pers_apellidos' => $this->input->post('per_apellido'),
            'pers_dni' => $this->input->post('per_dni'),
        ];

        $data_alumn = [
            'rubros_id' => $this->input->post('rubros_id'),
            'tipo_alumnos_id' => $this->input->post('tipo_alumnos_id'),
            'condiciones_id' => $this->input->post('condiciones_id'),
        ];


        $condicion = array("pers_id" => $pers_id,);
        $condicion_alumn = array("id_alumno" => $id,);

        //rubros_id
        //tipo_alumnos_id
        //condiciones_id

        if (
            $this->general->update_data("personas", $data_pers, $condicion) &&
            $this->general->update_data("alumnos", $data_alumn, $condicion_alumn)
        ) {
            $resp["exito"] = true;
            $resp["mensaje"] = "Alumno editado correctamente";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrió un error editando al alumno";
        }

        echo json_encode($resp);

        //$this->load->library('Form_validation');
        //$this->form_validation->set_message('required', '%s es un campo obligatorio ');
        //echo json_encode($id);
    }
    public function personaEditar($id = '')
    {
        $data_pers = [
            'pers_nombres' => $this->input->post('per_nombre'),
            'pers_apellidos' => $this->input->post('per_apellido'),
            'pers_dni' => $this->input->post('per_dni'),
            'pers_celular' => $this->input->post('pers_celular'),
        ];
        $condicion = array("pers_id" => $id,);

        if (
            $this->general->update_data("personas", $data_pers, $condicion)
        ) {
            $resp["exito"] = true;
            $resp["mensaje"] = "Persona Editada Correctamente";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrió un error al editar la persona";
        }

        echo json_encode($resp);
    }
    public function comprobante($id = '', $tipo = 'A4')
    {
        $data = $this->general->getPagoAlumno($id);

        if (!$data) $this->general->errorPDF();

        if ($tipo  == 'A4') {
            $this->general->comprobantePdfA4($data);
        } else {
            $this->general->comprobanteTicket($data);
        }
    }

    public function comprobanteVarios($ids = '', $tipo = 'A4')
    {
        $get_ids = explode('-', $ids);
        $ids_where = implode(' OR idpagos = ', $get_ids);

        $datos = $this->general->getPagosAlumnos($ids_where);

        //echo '<pre>';
        //var_dump($datos);
        //echo '</pre>';

        //die();
        if (count($datos) == 1) {
            $this->reportePagoUnClienteA4($datos[0]);
        } else if (count($datos) > 1) {
            $this->reportePagoVariosClientesA4($datos);
        }

        die();
    }

    public function reportePagoUnClienteA4($datos)
    {
        $pdf = new FPDF();
        $this->general->templatePdfA4($pdf);

        $pdf->SetTitle('DETALLE DE PAGOS', 1);
        $pdf->SetY(40); // Despues del header
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'U', 14);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(200, 8, utf8_decode('DETALLE DE PAGOS'), 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(110, 5, utf8_decode('Cliente: ' . $datos->pers_nombres . ' ' . $datos->pers_apellidos), 0, 'L');
        $pdf->Cell(45, 5, utf8_decode('DNI: ' . $datos->pers_dni), 0, 'L');
        $pdf->Cell(45, 5, utf8_decode('Celular: ' . $datos->pers_celular), 0, 'R');


        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 10);
        //HEADER TABLE
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 7, utf8_decode('ID'), 1, 0, 'C', 1, '');
        $pdf->Cell(100, 7, utf8_decode('PRODUCTO'), 1, 0, 'C', 1, '');
        $pdf->Cell(35, 7, utf8_decode('FECHA DE PAGO'), 1, 0, 'C', 1, '');
        $pdf->Cell(40, 7, utf8_decode('IMPORTE'), 1, 0, 'C', 1, '');
        $pdf->Ln(7);

        $productos = explode(',', $datos->productos);
        $importes = explode(',', $datos->importes);
        $fechas = explode(',', $datos->fechas);

        //HEADER BODY
        foreach ($productos as $i => $pago) {

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(15, 6, $i + 1, 1, 0, 'C', 0, '');
            $pdf->Cell(100, 6, utf8_decode($pago), 1, 0, 'C', 0, '');
            $pdf->Cell(35, 6, utf8_decode($fechas[$i]), 1, 0, 'C', 0, '');
            $pdf->Cell(40, 6, utf8_decode('S/. ' . $importes[$i]), 1, 0, 'C', 0, '');
            $pdf->Ln(6);
        }

        $total = array_sum($importes);

        $igv = ($total * 18) / 100;
        $sub_total = $total - $igv;

        $pdf->Ln(2);

        //HEADER FOOTER

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


        $pdf->Cell(150, 6, '', 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, '-------------------', 0, 0, 'C', 0, '');
        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('TOTAL'), 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, 'S/. ' . number_format($total, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);



        $pdf->Output();
    }

    public function reportePagoVariosClientesA4($datos)
    {
        //echo '<pre>';
        //var_dump($datos);
        //echo '</pre>';

        $pdf = new FPDF();
        $this->general->templatePdfA4($pdf);

        $pdf->SetTitle('DETALLE DE PAGOS', 1);
        $pdf->SetY(40); // Despues del header
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'U', 14);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(200, 8, utf8_decode('DETALLE DE PAGOS'), 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(110, 5, utf8_decode('Cliente: VARIOS'), 0, 'L');
        //$pdf->Cell(45, 5, utf8_decode('DNI: ' . $datos->pers_dni), 0, 'L');
        //$pdf->Cell(45, 5, utf8_decode('Celular: ' . $datos->pers_celular), 0, 'R');


        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 10);
        //HEADER TABLE
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 7, utf8_decode('ID'), 1, 0, 'C', 1, '');
        $pdf->Cell(100, 7, utf8_decode('PRODUCTO'), 1, 0, 'C', 1, '');
        $pdf->Cell(35, 7, utf8_decode('FECHA DE PAGO'), 1, 0, 'C', 1, '');
        $pdf->Cell(40, 7, utf8_decode('IMPORTE'), 1, 0, 'C', 1, '');
        $pdf->Ln(7);

        //HEADER BODY
        $index = 0;
        $total = 0;
        foreach ($datos as $alumno) {

            $productos = explode(',', $alumno->productos);
            $importes = explode(',', $alumno->importes);
            $fechas = explode(',', $alumno->fechas);
            $pdf->SetTextColor(240, 240, 240);
            $pdf->SetFillColor(80, 80, 245);
            $pdf->Cell(190, 7, utf8_decode($alumno->pers_nombres . ' ' . $alumno->pers_apellidos . '  DNI: ' . $alumno->pers_dni
                . '  Celular: ' . $alumno->pers_celular), 1, 0, 'C', 1, '');
            $pdf->Ln(7);

            foreach ($productos as $i => $pago) {
                $index++;
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->SetTextColor(40, 40, 40);
                $pdf->SetFillColor(254, 254, 254);
                $pdf->Cell(15, 6, $index, 1, 0, 'C', 1, '');
                $pdf->Cell(100, 6, utf8_decode($pago), 1, 0, 'C', 1, '');
                $pdf->Cell(35, 6, utf8_decode($fechas[$i]), 1, 0, 'C', 1, '');
                $pdf->Cell(40, 6, utf8_decode('S/. ' . $importes[$i]), 1, 0, 'C', 1, '');
                $pdf->Ln(6);
            }
            $total +=  array_sum($importes);
        }


        $pdf->Cell(150, 6, '', 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, '-------------------', 0, 0, 'C', 0, '');
        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('TOTAL'), 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, 'S/. ' . number_format($total, 2), 0, 0, 'C', 0, '');
        $pdf->Ln(6);
        $pdf->Output();
    }
}
