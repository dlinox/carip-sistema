<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ventas extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized') && $this->session->userdata('usua_tipo') != 2) {
            redirect(base_url() . "login");
        }

        $this->load->library('Cssjs');
        $this->load->library('Fpdf/fpdf');
        $this->load->library('form_validation');
        $this->load->library('Ssp');
        $this->jsPath = base_url() . "assets/js/";
        $this->load->model('Model_general', 'general');
        $this->load->model('AlumnoModel', 'model');
        $this->load->model('UsuarioModel', 'usuario_model');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->load->helper('Response');
        $this->user_id = $this->session->userdata('authorized');
        $this->user_tipo_id = $this->session->userdata('usua_tipo');
    }
    public function index()
    {
        if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 2) {
            $this->ventanaadministrador();
        } else if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 5) {
            //redirect(base_url() . "AreaAcademica/alumnos");
            $this->ventanadocente();
            //$this->lista();
        } else if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 1) {
            $this->ventanaventas();

            //$this->lista();
        } else if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 3) {
            $this->ventanaatencion();
            //$this->lista();
        } else if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 4) {
            $this->ventanacobranza();
        } else if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 6) {
            $this->ventanacontabilidad();
        } else if ($this->session->userdata('authorized') && $this->session->userdata('usua_tipo') == 7) {
            $this->ventanaAteCli2();
        }
    }

    public function ventanaadministrador($json = false)
    {
        //DASHBOARD
        //---------------CURSOS MAS VENDIDOS
        $sql1 = "SELECT COUNT(PR.id) as cantidad, PR.nombre, C.cate_nombre
            FROM alumnos AL
            JOIN productos PR ON AL.productos_id = PR.id
            JOIN categorias C ON C.cate_id = PR.cate_id
            GROUP BY PR.id
            ORDER BY COUNT(PR.id) DESC
            LIMIT 3";
        $cursos_populares = $this->db->query($sql1)->result();
        //---------------ALUMONS POR CATEGORIA
        $sql2 = "SELECT COUNT(C.cate_id) as cantidad, C.cate_nombre
            FROM alumnos AL
            JOIN productos PR ON AL.productos_id = PR.id
            JOIN categorias C ON C.cate_id = PR.cate_id
            GROUP BY C.cate_id
            ORDER BY COUNT(PR.id) DESC";
        $total_alumnos = $this->db->query($sql2)->result();
        //---------------TOTAL DE USUARIOS POR CATEGORIA
        $sql3 = "SELECT COUNT(C.cate_id) as cantidad, C.cate_nombre
            FROM usuario U
            JOIN categorias C ON C.cate_id = U.cate_id
            GROUP BY C.cate_id";
        $total_usuarios = $this->db->query($sql3)->result();
        //---------------TOTAL DE CURSOS POR CATEGORIA
        $sql4 = "SELECT COUNT(C.cate_id) as cantidad, C.cate_nombre
                FROM productos P
                JOIN categorias C ON C.cate_id = P.cate_id
                GROUP BY C.cate_id";
        $total_cursos = $this->db->query($sql4)->result();
        //--------------CATEGORIAS
        $sql5 = "SELECT cate_id, cate_nombre FROM categorias";
        $categorias = $this->db->query($sql5)->result();



        $query = "SELECT SUM(adde_importe)  AS total
        FROM adelantos_descuentos
        WHERE adde_tipo = 'DESCUENTO'";
        $descuentos  = $this->db->query($query)->row();

        $query1 = "SELECT COUNT(grup_docente_id) AS cantidad, CONCAT(usua_nombres, ' ' ,usua_apellidos) nombres
                FROM grupos
                JOIN usuario ON usua_id = grup_docente_id
                GROUP BY grup_docente_id, nombres
                LIMIT 2";

        $docentes  = $this->db->query($query1)->result();        

        $data = [];
        $data['cursos_populares'] = $cursos_populares;
        $data['total_alumnos'] = $total_alumnos;
        $data['total_usuarios'] = $total_usuarios;
        $data['total_cursos'] = $total_cursos;
        $data['categorias'] = $categorias;
        $data['descuentos'] = $descuentos;
        $data['docentes'] = $docentes;


        $this->load->view('header');
        $this->cssjs->add_js($this->jsPath . "ventas/inicio.js", false, false);
        $this->load->view($this->controller . "/inicio", $data);
        $this->load->view('footer');
    }
    public function ventanadocente($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/iniciodocente");
        $this->load->view('footer');
    }
    public function ventanaventas($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/inicioventas");
        $this->load->view('footer');
    }
    public function ventanacobranza($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/iniciocobranza");
        $this->load->view('footer');
    }
    public function ventanaatencion($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/inicioatencion");
        $this->load->view('footer');
    }
    public function ventanacontabilidad($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/iniciocontabilidad");
        $this->load->view('footer');
    }

    public function ventanaAteCli2($json = false)
    {
        $this->load->view('header');
        $this->load->view($this->controller . "/inicioatencion2");
        $this->load->view('footer');
    }


    public function imprimir($cod)
    {
        $obs = '--';
        $sql = 'SELECT alumnos.id_alumno, DATE_FORMAT(alumnos.fecha_inscripcion, "%e/%m/%Y") AS fecha_inscripcion, personas.pers_nombres, personas.pers_apellidos, personas.pers_dni,
        personas.pers_celular, productos.nombre, productos.costo, productos.cuotas, productos.sesiones,
        rubros.nombre AS rubro, tipo_alumnos.nombre AS profesion, IFNULL(alumnos.observacion,"-") AS observacion    
        FROM alumnos
        JOIN personas ON personas.pers_id = alumnos.alum_pers_id
        JOIN productos ON productos.id = alumnos.productos_id
        JOIN rubros ON rubros.id = alumnos.rubros_id
        JOIN tipo_alumnos ON tipo_alumnos.id = alumnos.tipo_alumnos_id
        WHERE alumnos.id_alumno = ' . $cod;

        $resultado = $this->db->query($sql)->row();
        //echo $resultado;
        date_default_timezone_set('UTC');


        // Imprime algo como: Monday
        $fechaimpresion = date("d/m/Y");

        $cod = $resultado->id_alumno;
        $codigo = $resultado->id_alumno;
        $nombre = $resultado->pers_nombres;
        $apellido = $resultado->pers_apellidos;
        $fecha = $resultado->fecha_inscripcion;
        $dni = $resultado->pers_dni;
        $celular = $resultado->pers_celular;
        $rubro = $resultado->rubro;
        $profesion = $resultado->profesion;
        $curso = $resultado->nombre;
        $costo = $resultado->costo;
        $cuotas = $resultado->cuotas;
        $sesiones = $resultado->sesiones;
        $observacion = $resultado->observacion;

        $pdf = new FPDF();
        $this->general->templatePdfA4($pdf);
        $pdf->SetY(40);
        $pdf->SetTitle('FICHA DE INSCRIPCIÓN', 1);

        $pdf->Cell(60);
        $pdf->SetTextColor(45, 45, 45);
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(70, 10, utf8_decode('FICHA DE INSCRIPCIÓN'), 0, 0, 'C');
        $pdf->Ln(17);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 10, '1.- DATOS PERSONALES: ', 0, 1, 'L');

        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, utf8_decode('Código'), 1, 0, 'C');
        $pdf->Cell(140, 5, 'Nombres y Apellidos', 1, 0, 'C');


        $pdf->Ln(5);
        $pdf->SetFont('courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(40, 7, '00000' . $codigo, 0, 0, 'C');
        $pdf->Cell(140, 7, utf8_decode($nombre . " " . $apellido), 0, 0, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(60, 5, 'Rubro', 1, 0, 'C');
        $pdf->Cell(40, 5, utf8_decode('Profesión'), 1, 0, 'C');
        $pdf->Cell(40, 5, 'DNI', 1, 0, 'C');
        $pdf->Cell(40, 5, 'Celular', 1, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(60, 7, utf8_decode($rubro), 0, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode($profesion), 0, 0, 'C');
        $pdf->Cell(40, 7, $dni, 0, 0, 'C');
        $pdf->Cell(40, 7, $celular, 0, 0, 'C');
        $pdf->Ln(10);


        $pdf->SetLineWidth(0.1);
        $pdf->SetDrawColor(39, 55, 70);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(95, 10, '2.- DATOS DEL CURSO: ', 0, 1, 'L');
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(110, 5, 'Curso', 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode('Cuotas'), 1, 0, 'C');
        $pdf->Cell(30, 5, utf8_decode('Costo x Cuota'), 1, 0, 'C');
        $pdf->Cell(20, 5, utf8_decode('Sesiones'), 1, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(110, 7, utf8_decode($curso), 0, 0, 'C');
        $pdf->Cell(20, 7, utf8_decode($cuotas), 0, 0, 'C');
        $pdf->Cell(30, 7, "S/." . utf8_decode($costo), 0, 0, 'C');
        $pdf->Cell(20, 7, $sesiones, 0, 0, 'C');
        $pdf->Ln(10);





        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(95, 10, utf8_decode('3.- DATOS MATRÍCULA: '), 0, 1, 'L');
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(5);
        $pdf->Cell(50, 5, utf8_decode('Fecha de Inscripción:'), 1, 0, 'C');
        $pdf->Cell(130, 5, utf8_decode('Observación:'), 1, 0, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('courier', 'B', 13);
        $pdf->Cell(5);
        $pdf->Cell(50, 7, utf8_decode($fecha), 0, 0, 'C');
        $pdf->Cell(130, 7, utf8_decode($observacion), 0, 0, 'C');
        $pdf->Ln(8);

        $pdf->Output();
    }

    public function crear($id = "")
    {
        $datos = [];
        $datos['show_completar'] = false;
        $datos["rubros"] = $this->general->getOptions('rubros', array("id", "nombre"));

        $datos["productos"] = $this->general->getOptions('productos', array("id", "nombre"), 'Seleccione');

        $datos["tipo_alumnos"] = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $datos["tipo_pagos"] = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $datos["condiciones"] = $this->general->getOptions('condiciones', array("id", "nombre"));

        if (empty($id) || $id[0] == "L") {
            $venta = new stdClass();
            $venta->id_alumno = "";
            $venta->dni = "";
            $venta->nombre = "";
            $venta->apellidos = "";
            $venta->celular = "";
            $venta->fecha_inscripcion =  date('Y-m-d');
            $venta->alum_fecha =  date('Y-m-d');
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
            $venta->costo = "";
            $venta->condiciones_id = "";
            $venta->usuario_usua_id = "";
            $venta->alum_pers_id = "";
            $venta->alum_apoderado_id = "";
            $venta->habilitado = 1;
            $venta->alum_es_becado = 0;
            $venta->tieneDescuento = 0;
            $venta->alum_descuento = 0;
        } else {
            $venta = $this->db->where("id_alumno", $id)->get("alumnos")->row();
            //print_r($venta);
            $venta->alum_fecha = substr($venta->alum_fecha, 0, 10);
            $venta->costo = '';
            $venta->tieneDescuento = isset($venta->alum_descuento) ? 1 : 0;
        }

        // if(isset($id[0]) && $id[0] == "L") { $venta->alum_pers_id = substr($id, 1); }
        if (isset($_GET['persona'])) {
            $venta->alum_pers_id = $_GET['persona'];
        }
        if (isset($_GET['producto'])) {
            $venta->productos_id = $_GET['producto'];
        }

        $datos["venta"] = $venta;
        $datos["estado"] = array("1" => "HABILITADO", "0" => "BLOQUEADO");

        $datos["categorias"] = $this->general->getOptions('categorias', array("cate_id", "cate_nombre"), 'Seleccione');
        $this->cssjs->add_js($this->jsPath . "ventas/form.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();

        $this->load->view('header', $script);
        $this->load->view('Ventas/formulario', $datos);
        $this->load->view('footer');
    }

    public function editar($id = '')
    {
        $datos = [];
        $datos['show_completar'] = false;
        $datos["rubros"] = $this->general->getOptions('rubros', array("id", "nombre"));
        $datos["productos"] = $this->general->getOptions('productos', array("id", "nombre"), 'Seleccione');
        $datos["tipo_alumnos"] = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $datos["tipo_pagos"] = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $datos["condiciones"] = $this->general->getOptions('condiciones', array("id", "nombre"));

        $venta = $this->db->where("id_alumno", $id)->get("alumnos")->row();

        $sql1 = 'SELECT alumnos.id_alumno, alumnos.alum_pers_id, personas.pers_nombres, personas.pers_apellidos, personas.pers_dni
        , personas.pers_celular
        FROM alumnos
        JOIN personas ON personas.pers_id = alumnos.alum_pers_id
        WHERE id_alumno = ' . $id;

        $datosal = $this->db->query($sql1)->row();
        $datos["personas"] = $datosal;

        $venta->costo = '';
        $venta->tieneDescuento = isset($venta->alum_descuento) ? 1 : 0;

        if (isset($_GET['persona'])) {
            $venta->alum_pers_id = $_GET['persona'];
        }
        if (isset($_GET['producto'])) {
            $venta->productos_id = $_GET['producto'];
        }
        $datos["venta"] = $venta;
        $datos["estado"] = array("1" => "HABILITADO", "0" => "BLOQUEADO");
        $this->cssjs->add_js($this->jsPath . "ventas/form.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();

        //print_r($datos);

        $this->load->view('header', $script);
        $this->load->view('Ventas/editar', $datos);
        $this->load->view('footer');
    }

    public function guardaredicion($id)
    {
        //echo $id;
        $this->load->library('Form_validation');
        $this->form_validation->set_message('valid_email', '%s debe de ser un correo electrónico');
        $this->form_validation->set_message('required', '%s es un campo obligatorio');
        $this->form_validation->set_rules('correo_electronico', 'Correo personal', 'valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $data =
            [
                'pers_nombres' => $this->input->post('nombrepersona'),
                'pers_apellidos' => $this->input->post('apellidopersona'),
                'pers_dni' => $this->input->post('dnipersona'),
                'pers_celular' => $this->input->post('celularpersona')
            ];

        //print_r($data);

        $condicion = array("pers_id" => $id);
        $this->db->trans_start();
        if ($this->general->update_data('personas', $data, $condicion)) {
            $this->db->trans_complete();
            showSuccess('Alumno editado correctamente');
        }
        $this->db->trans_rollback();
        showError('Ocurrió un error editando al alumno');
    }

    public function completar($id = '')
    {
        $show_completar = true;

        $rubros = $this->general->getOptions('rubros', array("id", "nombre"));
        $tipo_alumnos = $this->general->getOptions('tipo_alumnos', array("id", "nombre"));
        $condiciones = $this->general->getOptions('condiciones', array("id", "nombre"));
        $productos = $this->general->getOptions('productos', array("id", "nombre"), 'Seleccione');
        $tipo_pagos = $this->general->getOptions('tipo_pagos', array("id", "nombre"));
        $estado = array("1" => "HABILITADO", "0" => "BLOQUEADO");

        // $paises = $this->general->getOptions('paises', array("idpaises", "nombre"));;
        $departamentos = $this->general->getOptions('departamentos', array("iddepartamentos", "nombre"));
        $entidades_bancarias = $this->general->getOptions('entidades_bancarias', array("enba_id", "enba_nombre"));
        $tipos_tarjeta = $this->general->enum_valores('alumnos', 'alum_tipo_tarjeta');
        $franquicias = $this->general->getOptions('franquicias', array("fran_id", "fran_nombre"));
        $opciones_bienvenida = ['OK' => 'Si', 'NC' => 'No contesto'];

        $venta = $this->db->where("id_alumno", $id)->get("alumnos")->row();
        $venta->costo = "";
        $venta->tieneDescuento = isset($venta->alum_descuento) ? 1 : 0;

        $persona = $this->db->where('pers_id', $venta->alum_pers_id)->get("personas")->row();

        $this->cssjs->add_js($this->jsPath . "ventas/form.js?v=1", false, false);
        $this->cssjs->add_js($this->jsPath . "ventas/completar.js?v=1", false, false);
        $script['js'] = $this->cssjs->generate_js();

        $this->load->view('header', $script);
        $this->load->view('Ventas/formulario', compact('rubros', 'tipo_alumnos', 'condiciones', 'productos', 'estado', 'venta', 'tipo_pagos', 'departamentos', 'persona', 'show_completar', 'entidades_bancarias', 'tipos_tarjeta', 'franquicias', 'opciones_bienvenida'));
        // $this->load->view('Ventas/completar', compact('venta'));
        $this->load->view('footer');
    }

    public function completar_guardar($id, $alum_id)
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('valid_email', '%s debe de ser un correo electrónico');
        // $this->form_validation->set_message('required', '%s es un campo obligatorio');
        $this->form_validation->set_rules('correo_electronico', 'Correo personal', 'valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $data =
            [
                'pers_correo_electronico' => $this->input->post('correo_electronico'),
                'pers_direccion' => $this->input->post('direccion'),
                'pers_centro_laboral' => $this->input->post('centro_laboral'),
                'pers_fecha_nacimiento' => $this->input->post('fecha_nacimiento'),
                'pers_iddepartamentos' => $this->input->post('departamento'),
            ];

        $condicion = array("pers_id" => $id);

        $data_alumno =
            [
                'alum_completado' => true,
                'alum_hubo_bienvenida' => $this->input->post('hubo_bienvenida'),
                'alum_enba_id' => $this->input->post('entidad_bancaria'),
                'alum_tipo_tarjeta' => $this->input->post('tipo_tarjeta'),
                'alum_fran_id' => $this->input->post('franquicia'),
                'alum_numero_tarjeta' => $this->input->post('numero_tarjeta'),
                'alum_mes_caducidad' => $this->input->post('mes_caducidad'),
                'alum_anho_caducidad' => $this->input->post('anho_caducidad')
            ];

        $this->db->trans_start();
        if ($this->general->update_data('alumnos', $data_alumno, ['id_alumno' => $alum_id])) {
            if ($this->general->update_data('personas', $data, $condicion)) {
                $this->db->trans_complete();
                showSuccess('Alumno editado correctamente');
            }
        }

        $this->db->trans_rollback();
        showError('Ocurrió un error editando al alumno');
    }

    public function eliminar($id = "")
    {
        $resp = [];
        $productos = $this->db->get_where('grupos_alumnos', ['gral_id_alumno' => $id])->result();
        if (sizeof($productos) > 0) {
            $resp["exito"] = false;
            $resp["mensaje"] = "El alumno esta registrado en un grupo, tiene que eliminarlo del grupo para eliminar la venta";
        } else {
            $this->db->trans_start();
            $this->general->delete_data("alumnos", array("id_alumno" => $id));
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

    public function guardar($id = null)
    {
        $tiene_descuento = $this->input->post('tieneDescuento');

        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_message('greater_than', '%s debe de ser número mayor a 0');

        if ($id == null) {
            $this->form_validation->set_message('check_user', 'El alumno ya esta inscrito en el curso.');
            $this->form_validation->set_rules('productos_id', 'Producto', 'required|callback_check_user');
        }

        if (isset($tiene_descuento) && $tiene_descuento) {
            $this->form_validation->set_rules('descuento', 'Descuento', 'required|greater_than[0]');
        }

        if (isset($_POST['agregar_persona'])) {
            $this->form_validation->set_rules('dni', 'Dni', 'required');
            $this->form_validation->set_rules('nombre', 'Nombre', 'required');
            $this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
            $this->form_validation->set_rules('celular', 'Celular', 'required');
        } else {
            $this->form_validation->set_rules('persona_id', 'Datos del alumno', 'required');
        }

        if ($_POST['condiciones_id'] == 2) {
            if (isset($_POST['agregar_apoderado'])) {
                $this->form_validation->set_rules('titular_dni', 'Dni del titular', 'required');
                $this->form_validation->set_rules('titular_nombre', 'Nombre del titular', 'required');
                $this->form_validation->set_rules('titular_apellidos', 'Apellidos del titular', 'required');
                $this->form_validation->set_rules('titular_celular', 'Celular del titular', 'required');
            } else {
                $this->form_validation->set_rules('apoderado_id', 'Apoderado', 'required');
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        if (isset($_POST['agregar_persona'])) {
            $dni = $this->input->post('dni');
            $nombre = $this->input->post('nombre');
            $apellidos = $this->input->post('apellidos');
            $celular = $this->input->post('celular');

            $get_by_dni = $this->general->getData("personas", array("1"), array("pers_dni" => $dni));
            if (!empty($get_by_dni)) {
                showError('La persona agregada ya se encuentra registrada');
            }
        }

        if ($_POST['condiciones_id'] == 2 && isset($_POST['agregar_apoderado'])) {
            $titular_dni = $this->input->post('titular_dni');
            $titular_nombre = $this->input->post('titular_nombre');
            $titular_apellidos = $this->input->post('titular_apellidos');
            $titular_celular = $this->input->post('titular_celular');
        }

        $fecha_inscripcion = $this->input->post('fecha_inscripcion');
        $alum_fecha = $this->input->post('alum_fecha');
        $observacion = $this->input->post('observacion');
        $rubros_id = $this->input->post('rubros_id');
        $productos_id = $this->input->post('productos_id');
        $tipo_alumnos_id = $this->input->post('tipo_alumnos_id');
        $tipo_pago_id = $this->input->post('tipo_pago_id');
        $cuotas = $this->input->post('cuotas');
        $condiciones_id = $this->input->post('condiciones_id');
        $habilitado = $this->input->post('habilitado');
        $llamada_id = $this->input->post('llamada_id');
        $es_becado = $this->input->post('esBecado');
        if (isset($tiene_descuento) && $tiene_descuento) {
            $descuento = $this->input->post('descuento');
        }

        $persona_data = !isset($_POST['agregar_persona']) ? $_POST['persona_id'] :
            [
                "pers_dni" => $dni,
                "pers_nombres" => $nombre,
                "pers_apellidos" => $apellidos,
                "pers_celular" => $celular,
            ];

        $titular_data = NULL;
        if ($_POST['condiciones_id'] == 2) {
            $titular_data = !isset($_POST['agregar_apoderado']) ? $_POST['apoderado_id'] :
                [
                    "pers_dni" => $titular_dni,
                    "pers_nombres" => $titular_nombre,
                    "pers_apellidos" => $titular_apellidos,
                    "pers_celular" => $titular_celular,
                ];
        }

        $data = array(

            "fecha_inscripcion" => $fecha_inscripcion,
            "alum_fecha" => $alum_fecha,
            "observacion" => $observacion,
            "rubros_id" => $rubros_id,
            "productos_id" => $productos_id,
            "tipo_alumnos_id" => $tipo_alumnos_id,
            "tipo_pago_id" => $tipo_pago_id,
            //"cuotas" => $cuotas,
            "condiciones_id" => $condiciones_id,
            "habilitado" => $habilitado,
            'alum_es_becado' => $es_becado ? 1 : 0,
            'alum_completado' => false
        );
        // if(isset($tiene_descuento) && $tiene_descuento) { $data['alum_descuento'] = $descuento; }
        $data['alum_descuento'] = (isset($tiene_descuento) && $tiene_descuento) ? $descuento : NULL;

        if ($id != null) {
            $condicion = array("id_alumno" => $id);
            if ($this->model->update($condicion, $data, $persona_data, $titular_data) == false) {
                showError('Ocurrió un error editando al alumno');
            } else {
                showSuccess('Alumno editado correctamente');
            }
        } else {
            $data['cuotas'] = 0;
            $data['alum_pagado'] = 0;
            $data['alum_comi_direc_pagada'] = 0;
            $data['alum_comi_deriv_pagada'] = 0;
            $data['usuario_usua_id'] = $this->user_id;

            if ($this->model->save($data, $persona_data, $titular_data, $llamada_id) == false) {
                showError('Ocurrió un error guardando al alumno');
            } else {
                showSuccess('Alumno registrado correctamente');
            }
        }

        //echo json_encode($resp);
        // redirect(base_url() . "Ventas");
    }



    public function lista($json = false)
    {
        // error_reporting(0);
        // ini_set('display_errors', 0);
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : false;

        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';


        $pago = '<span class="label label-success">PAGO</span>';
        $no_pago = '<span class="label label-warning">PENDIENTE</span>';
        $becado = '<span class="label label-primary">BECADO</span>';

        $estado = "IF(habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";

        $comision = "IF(alum_es_becado = '1','" . $becado . "', (IF(IFNULL(pagos.monto , 0) = 0 AND comision > 0 ,'" . $no_pago . "','" . $pago . "')) )";

        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'DT_RowId'),
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'CONCAT(pers_nombres, "  ", pers_apellidos)', 'dt' => 'ALUMNO'),
            array('db' => 'CONCAT(productos.nombre)', 'dt' => 'CURSO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(costo * productos.cuotas,2))', 'dt' => 'COSTO'),
            array(
                'db' =>
                'IF( IFNULL(pagos.monto , 0) = 0 AND comision > 0,
                     CONCAT("<div class=\'text-danger\'> <b> <del> S/.", ROUND(comision,2) , " </del> </b></div>"),
                     CONCAT("S/.", " ", ROUND(comision,2))
                )',
                'dt' => 'COMISION'
            ),
            array('db' => $comision, 'dt' => 'PAGOS'),
            array('db' => 'DATE_FORMAT(alum_fecha, "%d/%m/%Y")', 'dt' => 'FECHA VENTA'),
            array('db' => $estado, 'dt' => 'ESTADO ALUMNO'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
            array('db' => 'alum_completado', 'dt' => 'DT_COMPLETADO'),


        );

        if ($this->user_tipo_id == 2 || $this->user_tipo_id == 7) {
            $index = 7;
            array_splice($columns, $index, 0, [[]]);
            $columns[$index]['db'] = 'CONCAT(usuario.usua_nombres, " ", usuario.usua_apellidos)';
            $columns[$index]['dt'] = 'VENDEDOR';
        }

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        $columns_asesorado = array(
            array('db' => 'id_alumno', 'dt' => 'DT_RowId'),
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'CONCAT(pers_nombres, "  ", pers_apellidos)', 'dt' => 'ALUMNO'),
            array('db' => 'CONCAT(productos.nombre)', 'dt' => 'CURSO'),
            array('db' => 'ROUND(comision_asesor, 2)', 'dt' => 'COMISION'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
            array('db' => 'DATE_FORMAT(alum_fecha, "%d/%m/%Y")', 'dt' => 'FECHA DE VENTA'),
            array('db' => 'CONCAT(a.usua_nombres, " ", a.usua_apellidos)', 'dt' => 'USUARIO'),
            array('db' => $estado, 'dt' => 'ESTADO'),

        );
        foreach ($columns_asesorado as &$item) {
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
            $joinQuery = " 
                FROM alumnos 
                LEFT JOIN productos on productos.id = productos_id 
                JOIN personas ON alum_pers_id = pers_id
                JOIN usuario ON alumnos.usuario_usua_id = usuario.usua_id
                LEFT JOIN pagos ON alumnos.id_alumno= pagos.alumnos_id_alumno ";

            $where = "";
            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(alum_fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha_inscripcion) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            // Si no es un usuario administrador ...
            if ($this->user_tipo_id != 2 && $this->user_tipo_id != 3 && $this->user_tipo_id != 7) {
                $condiciones[] = " alumnos.usuario_usua_id =" . $this->user_id;
            }

            if ($this->user_tipo_id == 2 || $this->user_tipo_id == 3 || $this->user_tipo_id == 7) {

                if ($this->input->post('completado') == '2') {
                    $condiciones[] = '(alum_completado = 1 OR alum_completado = 0)';
                } else {
                    $condiciones[] = 'alum_completado = ' . $this->input->post('completado');
                }
                //$condiciones[] = 'productos.cate_id = ' . $this->input->post('cate_id');
            }

            if ($this->input->post('cate_id')) {
                $condiciones[] = 'productos.cate_id = ' . $this->input->post('cate_id');
            }

            if ($this->input->post('usua_id_vendor')) {
                $condiciones[] = 'usuario.usua_id = ' . $this->input->post('usua_id_vendor');
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            $groupby = "alumnos.id_alumno";
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
        $datos['columns_asesorado'] = $columns_asesorado;
        $datos['titulo'] = "Ventas";
        $datos['tipo_usuario'] = $this->user_tipo_id;
        $datos['tieneAsesorados'] = $this->usuario_model->tieneAsesorados($this->user_id);

        $datos["categorias"] = $this->general->getOptions('categorias', array("cate_id", "cate_nombre"), 'Seleccione');
        // $datos["proveedores"] = $this->general->getOptions("proveedor", array("prov_id", "prov_nombre"), "* Proveedor");

        //print_r($datos);

        $this->cssjs->add_js($this->jsPath . "ventas/lista.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/lista", $datos);
        $this->load->view('footer');
    }

    public function ventas_asesorados()
    {
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db' => $this->db->database,
            'host' => $this->db->hostname
        );
        $table = 'alumnos';
        $primaryKey = 'id_alumno';
        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';
        $estado = "IF(habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";
        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'DT_RowId'),
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'CONCAT(pers_nombres, "  ", pers_apellidos)', 'dt' => 'ALUMNO'),
            array('db' => 'CONCAT(productos.nombre)', 'dt' => 'CURSO'),
            array('db' => 'ROUND(costo,2)', 'dt' => 'COSTO'),
            array('db' => 'ROUND(comision_asesor, 2)', 'dt' => 'COMISION'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
            array('db' => 'DATE_FORMAT(alum_fecha, "%d/%m/%Y")', 'dt' => 'FECHA DE VENTA'),
            array('db' => 'CONCAT(a.usua_nombres, " ", a.usua_apellidos)', 'dt' => 'USUARIO'),
            array('db' => $estado, 'dt' => 'ESTADO')
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }
        $joinQuery = "  FROM        alumnos 
                        left join   productos 
                        on          productos.id = alumnos.productos_id 
                        JOIN        personas 
                        ON          alum_pers_id = pers_id 
                        JOIN        usuario AS a
                        ON          alumnos.usuario_usua_id = a.usua_id";
        $where = "";
        if (!empty($_POST['rango'])) {
            $fechas = explode('-', $_POST['rango']);
            $condiciones[] = "DATE(alum_fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha_inscripcion) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
        }

        // Si no es un usuario administrador ...
        $condiciones[] = "alumnos.usuario_usua_id IN (SELECT usua_id FROM usuario WHERE usuario_usua_id = " . $this->user_id . ')';

        $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
        $groupby = "";

        echo json_encode($this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupby));
    }

    public function getcomisiones()
    {
        $fechas = explode('-', $_POST['rango']);
        echo json_encode($this->model->getComisiones($this->user_id, cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1])));
    }

    public function miLista($json = false)
    {
        // error_reporting(0);
        // ini_set('display_errors', 0);
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : false;

        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';


        $pago = '<span class="label label-success">PAGO</span>';
        $no_pago = '<span class="label label-warning">PENDIENTE</span>';
        $becado = '<span class="label label-primary">BECADO</span>';

        $estado = "IF(habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";

        $comision = "IF(alum_es_becado = '1','" . $becado . "', (IF(IFNULL(pagos.monto , 0) = 0 AND comision > 0 ,'" . $no_pago . "','" . $pago . "')) )";


        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'DT_RowId'),
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'CONCAT(pers_nombres, "  ", pers_apellidos)', 'dt' => 'ALUMNO'),
            array('db' => 'CONCAT(productos.nombre)', 'dt' => 'CURSO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(costo * productos.cuotas,2))', 'dt' => 'COSTO'),
            array(
                'db' =>
                'IF( IFNULL(pagos.monto , 0) = 0 AND comision > 0,
                     CONCAT("<div class=\'text-danger\'> <b> <del> S/.", ROUND(comision,2) , " </del> </b></div>"),
                     CONCAT("S/.", " ", ROUND(comision,2))
                )',
                'dt' => 'COMISION'
            ),
            array('db' => $comision, 'dt' => 'PAGOS'),
            array('db' => 'DATE_FORMAT(alum_fecha, "%d/%m/%Y")', 'dt' => 'FECHA VENTA'),
            array('db' => $estado, 'dt' => 'ESTADO ALUMNO'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),


        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        $columns_asesorado = array(
            array('db' => 'id_alumno', 'dt' => 'DT_RowId'),
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'pers_dni', 'dt' => 'DNI'),
            array('db' => 'CONCAT(pers_nombres, "  ", pers_apellidos)', 'dt' => 'ALUMNO'),
            array('db' => 'CONCAT(productos.nombre)', 'dt' => 'CURSO'),
            array('db' => 'ROUND(comision_asesor, 2)', 'dt' => 'COMISION'),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
            array('db' => 'DATE_FORMAT(alum_fecha, "%d/%m/%Y")', 'dt' => 'FECHA DE VENTA'),
            array('db' => 'CONCAT(a.usua_nombres, " ", a.usua_apellidos)', 'dt' => 'USUARIO'),
            array('db' => $estado, 'dt' => 'ESTADO')
        );
        foreach ($columns_asesorado as &$item) {
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
            $joinQuery = " 
                FROM alumnos 
                left join productos on productos.id = productos_id 
                JOIN personas ON alum_pers_id = pers_id
                JOIN usuario ON alumnos.usuario_usua_id = usuario.usua_id
                LEFT JOIN pagos ON alumnos.id_alumno= pagos.alumnos_id_alumno ";

            $where = "";
            if (!empty($_POST['rango'])) {
                $fechas = explode('-', $_POST['rango']);
                $condiciones[] = "DATE(alum_fecha) >='" . cambiaf_a_mysql($fechas[0]) . "' AND DATE(fecha_inscripcion) <= '" . cambiaf_a_mysql($fechas[1]) . "'";
            }

            // Si no es un usuario administrador ...
            if ($this->user_tipo_id != 2 && $this->user_tipo_id != 3) {
                $condiciones[] = " alumnos.usuario_usua_id =" . $this->user_id;
            }

            if ($this->user_tipo_id == 2 || $this->user_tipo_id == 3) {
                $condiciones[] = 'alum_completado = ' . $this->input->post('completado');
            }

            if ($this->input->post('cate_id')) {
                $condiciones[] = 'productos.cate_id = ' . $this->input->post('cate_id');
            }

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
        $datos['columns_asesorado'] = $columns_asesorado;
        $datos['titulo'] = "Ventas";
        $datos['tipo_usuario'] = $this->user_tipo_id;
        $datos['tieneAsesorados'] = $this->usuario_model->tieneAsesorados($this->user_id);
        // $datos["proveedores"] = $this->general->getOptions("proveedor", array("prov_id", "prov_nombre"), "* Proveedor");

        //print_r($datos);

        $this->cssjs->add_js($this->jsPath . "ventas/lista.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/mis_ventas", $datos);
        $this->load->view('footer');
    }

    public function getVendedores()
    {
        $responese = new StdClass;
        $term = isset($_GET['term']) ? $_GET['term'] : '';
        $datos = array();
        $like = ['usua_nombres' => $term,];
        $where = "(usua_tipo = 1 OR usua_tipo = 7)";

        $results = $this->general->select2("usuario", $like, null, $where);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->usua_id,
                "text" => $value->usua_nombres
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }


    /*public function getcomisiones()
    {
        $fechas = explode('-', $_POST['rango']);
        echo json_encode($this->model->getComisiones($this->user_id, cambiaf_a_mysql($fechas[0]), cambiaf_a_mysql($fechas[1])));
    }*/
    public function VentasVendedor()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $porcentaje_pagado = 'CONCAT("<div class=\'barra\'>", ROUND(IFNULL(SUM(monto),0) * 100 / (productos.cuotas * costo - IFNULL(alum_descuento, 0)), 2), "</div>")';
        //$numcuotas = 'CONCAT("<div class=\'icono\'></div> ",(productos.cuotas*1))';
        $iconos = 'CONCAT("<div class=\'iconos\'>",(productos.cuotas * 1), "</div>")';
        $pago = '<span class="label label-success">PAGO</span>';
        $no_pago = '<span class="label label-warning">PENDIENTE</span>';
        $becado = '<span class="label label-primary">BECADO</span>';
        $comision = "IF(alum_es_becado = '1','" . $becado . "', (IF(IFNULL(pagos.monto , 0) = 0 AND comision > 0 ,'" . $no_pago . "','" . $pago . "')) )";

        $columns = array(
            array('db' => 'id_alumno', 'dt' => 'ID'),
            array('db' => 'CONCAT(pers_nombres, "  ", pers_apellidos)', 'dt' => 'ALUMNO'),
            array('db' => 'nombre', 'dt' => 'CURSO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(costo * productos.cuotas,2))', 'dt' => 'COSTO'),
            array('db' => 'DATE_FORMAT(alum_fecha, "%d/%m/%Y")', 'dt' => 'FECHA'),
            array('db' => 'CONCAT(usuario.usua_nombres, " ", usuario.usua_apellidos)', 'dt' => 'VENDEDOR'),
            array(
                'db' =>
                'IF( IFNULL(pagos.monto , 0) = 0 AND comision > 0,
                     CONCAT("<div class=\'text-danger\'> <b> <del> S/.", ROUND(comision,2) , " </del> </b></div>"),
                     CONCAT("S/.", " ", ROUND(comision,2))
                )',
                'dt' => 'COMISION'
            ),
            array('db' => $comision, 'dt' => 'PAGOS'),
            array('db' => 'CONCAT("S/.", " ", ROUND(SUM(IFNULL(monto,0)),2))', 'dt' => 'PAGADO'),
            array('db' => 'IF(alum_es_becado, "S/. 0.00" , CONCAT("S/.", " ", ROUND( (costo * productos.cuotas - IFNULL(alum_descuento, 0))  - IFNULL(SUM(monto),0) ,2)))', 'dt' => 'DEUDA'),
            array('db' => '(productos.cuotas*1)', 'dt' => 'DT_CUOTAS'),
            array('db' => '(costo * productos.cuotas - IFNULL(alum_descuento, 0))', 'dt' => 'DT_COSTO'),
            array('db' => $iconos, 'dt' => 'ICONOS'),
            array('db' => $porcentaje_pagado, 'dt' => 'PAGADO % '),
            array('db' => 'observacion', 'dt' => 'OBSERVACION'),
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
                    JOIN usuario ON alumnos.usuario_usua_id = usuario.usua_id
                    JOIN productos ON productos_id = id
                    LEFT JOIN pagos ON id_alumno = alumnos_id_alumno";

            $where = "";
            $groupBy = "id_alumno";

            //$groupBy = "personas.pers_id";

            if (isset($_POST['categoria'])) {
                $condiciones[] = 'productos.cate_id = ' . $_POST['categoria'];
            }
            if ($this->input->post('usua_id_vendor')) {
                $condiciones[] = 'usuario.usua_id = ' . $this->input->post('usua_id_vendor');
            }
            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";

            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where, $groupBy)
            );

            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['categorias'] =  $this->general->getOptions('categorias', array("cate_id", "cate_nombre"), 'Seleccione Categoria');
        $datos['titulo'] = "Ventas por Vendedor";
        $datos['_user_tipo_id'] = $this->user_tipo_id;
        // $datos["grupo_academico"] = $this->general->getOptions('grupo_academico', array("id", "nombre"), 'Todos los grupos');

        $this->cssjs->add_js(base_url() . "assets/js/ventas/ventas_vendedor.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/VentasVendedor", $datos);
        $this->load->view('footer');
    }


    //Callback RULES VALIDATION - GUARDAR
    function check_user()
    { //VALIDA SI EL ALUMNO YA ESTA INSCRITO EN EL CURSO 
        $productos_id = $this->input->post('productos_id');
        $persona_id = $this->input->post('persona_id');
        $this->db->from('alumnos');
        $this->db->where('productos_id', $productos_id);
        $this->db->where('alum_pers_id', $persona_id);
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
