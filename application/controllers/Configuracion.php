<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Configuracion extends CI_Controller
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
        $this->load->model('Model_general', 'general');
        $this->controller = $this->router->fetch_class();
        $this->load->helper('Functions');
        $this->user_id = $this->session->userdata('authorized');
    }

    /*******************************************************************************************
                                    producto
     *******************************************************************************************/
    public function empresa()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $this->load->library('user_agent');

        $datos['titulo'] = "Mi Empresa";

        $empresa = $this->db->where("empr_id", 1)->get("empresa")->row();

        $datos["empresa"] = $empresa;

        $this->cssjs->add_js($this->jsPath . "configuracion/empresa.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/empresa", $datos);
        $this->load->view('footer');
    }

    public function producto($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $query_periodo = 'IFNULL((SELECT CONCAT(peri_anho, "-", peri_correlativo) FROM periodos WHERE peri_prod_id = id ORDER BY peri_id DESC LIMIT 1), "Sin asignar")';

        $columns = array(
            array('db' => 'id', 'dt' => 'DT_RowId'),
            //array('db' => 'id', 'dt' => 'ID'),
            array('db' => 'nombre', 'dt' => 'NOMBRE'),
            array('db' => 'notaaprobatoria', 'dt' => 'NOTA APROBATORIA'),
            array('db' => 'CONCAT("S/.", " ", ROUND(costo,2))', 'dt' => 'COSTO'),
            array('db' => 'CONCAT("S/.", " ", ROUND(comision,2))', 'dt' => 'COMISION'),
            array('db' => 'CONCAT("S/.", " ", ROUND(comision_asesor,2))', 'dt' => 'COMISION ASESOR'),
            //['db' => 'IF(peri_id IS NULL, "Sin asignar", CONCAT(peri_anho, "-", peri_correlativo))', 'dt' => 'Periodo']
            ['db' => $query_periodo, 'dt' => 'PERIODO']
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {
            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'productos';
            $primaryKey = 'id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = 'FROM productos';

            if (isset($_POST['cate_id'])) {
                $condiciones[] = 'productos.cate_id = ' . $_POST['cate_id'];
            }
            // $joinQuery = 'FROM productos LEFT JOIN periodos ON peri_prod_id = id';
            // $joinQuery = 'FROM productos LEFT JOIN (SELECT * FROM periodos GROUP BY peri_prod_id ORDER BY peri_id ASC) AS p ON p.peri_prod_id = id';
            // $joinQuery = 'FROM productos LEFT JOIN periodos ON id = 
            // (
            //     SELECT peri_id from periodos as p WHERE peri_id = p.peri_id order by peri_id desc limit 1
            // )';
            $where = "";

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Productos";

        $this->cssjs->add_js($this->jsPath . "configuracion/productos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/productos", $datos);
        $this->load->view('footer');
    }
    public function producto_crear($id = "")
    {
        if (empty($id)) {
            $producto = new stdClass();
            $producto->id = "";
            $producto->nombre = "";
            $producto->costo = "";
            $producto->cuotas = "";
            $producto->comision = "";
            $producto->comision_asesor = "";
            $producto->sesiones = "";
            $producto->matricula = "";
            $producto->notaaprobatoria = "";
            $producto->cate_id = "";
            $producto->libre = "";
        } else {
            $producto = $this->db->where("id", $id)->get("productos")->row();
        }


        $data["categorias"] = $this->general->getOptions('categorias', array("cate_id", "cate_nombre"), '* Tipo usuario');
        $data["tipo"] = array("1" => "LIBRE", "0" => "CERRADO");

        $data["producto"] = $producto;
        $this->load->view($this->controller . "/form_producto", $data);
    }
    public function producto_guardar($id = null)
    {
        $data = array(
            'nombre'        => $this->input->post("nombre"),
            'costo'       => $this->input->post("costo"),
            'cuotas'       => $this->input->post("cuotas"),
            'comision'       => $this->input->post("comision"),
            'comision_asesor'       => $this->input->post("comision_asesor"),
            'sesiones'       => $this->input->post("sesiones"),
            'matricula'       => $this->input->post("matricula"),
            'notaaprobatoria'       => $this->input->post("notaaprobatoria"),
            'cate_id'       => $this->input->post("cate_id"),
            'libre'       => $this->input->post("libre"),
        );
        if ($id != null) {
            $condicion = array("id" => $id);
            if ($this->general->update_data("productos", $data, $condicion)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "productos agregado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        } else {

            if ($this->general->save_data("productos", $data) != false) {
                $resp["exito"] = true;
                $resp["mensaje"] = "productos registrado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        }
        echo json_encode($resp);
    }
    public function producto_eliminar($id)
    {
        $resp = [];

        $periodos = $this->db->get_where('periodos', ['peri_prod_id' => $id])->result();
        if (sizeof($periodos) > 0) {
            $resp["exito"] = false;
            $resp["mensaje"] = "Este producto tiene periodos asociados";
        } else {
            $alumnos  = $this->db->get_where('alumnos', ['productos_id' => $id])->result();
            if (sizeof($alumnos) > 0) {
                $resp["exito"] = false;
                $resp["mensaje"] = "Este producto contine Venta(s) realizadas";
            } else {
                $this->db->trans_start();
                $this->general->delete_data("productos", array("id" => $id));
                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    $resp["exito"] = false;
                    $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
                } else {
                    $resp["exito"] = true;
                    $resp["mensaje"] = "Eliminado con exito";
                }
            }
        }
        echo json_encode($resp);
    }

    public function producto_agregar_periodo($id)
    {
        $periodos = $this->general->getData('periodos', ['peri_anho AS anho', 'peri_correlativo AS correlativo'], 'peri_prod_id = ' . $id);
        $correlativo = NULL;
        if (empty($periodos)) {
            $correlativo = 1;
        } else {
            $periodo = array_pop($periodos);
            $correlativo = $periodo->anho == date('Y') ? $periodo->correlativo + 1 : 1;
        }
        $anho = date('Y');

        $data = ['peri_anho' => $anho,  'peri_correlativo' => $correlativo, 'peri_prod_id' => $id];

        if ($this->general->save_data("periodos", $data) != false) {
            $resp["exito"] = true;
            $resp["mensaje"] = "El periodo fue registrado correctamente";
        } else {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        }

        echo json_encode($resp);
    }

    public function producto_eliminar_periodo($id)
    {
        $resp = [];

        $periodos = $this->db->get_where('grupos', ['grup_peri_id' => $id])->result();
        //print_r($periodos);
        if (sizeof($periodos) > 0) {
            $resp["exito"] = false;
            $resp["mensaje"] = "Este producto tiene periodos asociados";
        } else {

            $this->db->trans_start();
            $this->general->delete_data("periodos", array("peri_prod_id" => $id));
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            } else {
                $resp["exito"] = true;
                $resp["mensaje"] = "Eliminado con exito";
            }
        }
        echo json_encode($resp);
    }
    /*******************************************************************************************
                                    rubro
     *******************************************************************************************/
    public function rubro($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $columns = array(
            array('db' => 'id', 'dt' => 'DT_RowId'),
            array('db' => 'nombre', 'dt' => 'NOMBRE')
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'rubros';
            $primaryKey = 'id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM rubros";
            $where = "";

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Rubros";

        $this->cssjs->add_js($this->jsPath . "configuracion/rubros.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/rubros", $datos);
        $this->load->view('footer');
    }
    public function rubro_crear($id = "")
    {
        if (empty($id)) {
            $rubro = new stdClass();
            $rubro->id = "";
            $rubro->nombre = "";
            $rubro->costo = "";
        } else {
            $rubro = $this->db->where("id", $id)->get("rubros")->row();
        }
        $data["rubro"] = $rubro;
        $this->load->view($this->controller . "/form_rubro", $data);
    }
    public function rubro_guardar($id = null)
    {
        $data = array(
            'nombre'        => $this->input->post("nombre"),
        );
        if ($id != null) {
            $condicion = array("id" => $id);
            if ($this->general->update_data("rubros", $data, $condicion)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "rubros agregado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        } else {

            if ($this->general->save_data("rubros", $data) != false) {
                $resp["exito"] = true;
                $resp["mensaje"] = "rubros registrado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        }
        echo json_encode($resp);
    }
    public function rubro_eliminar($id)
    {
        $this->db->trans_start();
        $this->general->delete_data("rubros", array("id" => $id));
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        } else {
            $resp["exito"] = true;
            $resp["mensaje"] = "Eliminado con exito";
        }
        echo json_encode($resp);
    }
    /*******************************************************************************************
                                    rubro_gasto
     *******************************************************************************************/
    public function rubrogasto($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $columns = array(
            array('db' => 'id_rubrogasto', 'dt' => 'DT_RowId'),
            array('db' => 'descripcion_rubrogasto', 'dt' => 'DESCRIPCION')
        );

        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'rubrogasto';
            $primaryKey = 'id_rubrogasto';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM rubrogasto";
            $where = "";

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Rubros de Gasto";

        $this->cssjs->add_js($this->jsPath . "configuracion/rubrogasto.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/rubrogasto", $datos);
        $this->load->view('footer');
    }

    public function rubrogasto_crear($id = "")
    {
        if (empty($id)) {
            $rubrogasto = new stdClass();
            $rubrogasto->id_rubrogasto = "";
            $rubrogasto->descripcion_rubrogasto = "";
            $rubrogasto->costo = "";
        } else {
            $rubrogasto = $this->db->where("id_rubrogasto", $id)->get("rubrogasto")->row();
        }
        $data["rubrogasto"] = $rubrogasto;
        $this->load->view($this->controller . "/form_rubrogasto", $data);
    }

    public function rubrogasto_guardar($id = null)
    {
        $data = array(
            'descripcion_rubrogasto'        => $this->input->post("descripcion_rubrogasto"),
        );
        if ($id != null) {
            $condicion = array("id_rubrogasto" => $id);
            if ($this->general->update_data("rubrogasto", $data, $condicion)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "Rubro de Gasto actualizado correctamente";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Error al actualizar";
            }
        } else {

            if ($this->general->save_data("rubrogasto", $data) != false) {
                $resp["exito"] = true;
                $resp["mensaje"] = "Rubro de Gastos registrado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        }
        echo json_encode($resp);
    }

    public function rubrogasto_eliminar($id)
    {
        $resp = [];

        $periodos = $this->db->get_where('flujocaja', ['rubrogasto_id_rubrogasto' => $id])->result();
        if (sizeof($periodos) > 0) {
            $resp["exito"] = false;
            $resp["mensaje"] = "Este Rubro de Gasto tiene datos de egresos";
        } else {

            $this->db->trans_start();
            $this->general->delete_data("rubrogasto", array("id_rubrogasto" => $id));
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            } else {
                $resp["exito"] = true;
                $resp["mensaje"] = "Eliminado con exito";
            }
        }
        echo json_encode($resp);
    }

    /*******************************************************************************************
                                    tipo_alumno
     *******************************************************************************************/
    public function tipo_alumno($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $columns = array(
            array('db' => 'id', 'dt' => 'DT_RowId'),
            array('db' => 'nombre', 'dt' => 'NOMBRE')
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'tipo_alumnos';
            $primaryKey = 'id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = "FROM tipo_alumnos";
            $where = "";

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Tipo de Alumnos";

        $this->cssjs->add_js($this->jsPath . "configuracion/tipo_alumnos.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/tipo_alumnos", $datos);
        $this->load->view('footer');
    }
    public function tipo_alumno_crear($id = "")
    {
        if (empty($id)) {
            $tipo_alumno = new stdClass();
            $tipo_alumno->id = "";
            $tipo_alumno->nombre = "";
            $tipo_alumno->costo = "";
        } else {
            $tipo_alumno = $this->db->where("id", $id)->get("tipo_alumnos")->row();
        }
        $data["tipo_alumno"] = $tipo_alumno;
        $this->load->view($this->controller . "/form_tipo_alumno", $data);
    }
    public function tipo_alumno_guardar($id = null)
    {
        $data = array(
            'nombre'        => $this->input->post("nombre"),
        );
        if ($id != null) {
            $condicion = array("id" => $id);
            if ($this->general->update_data("tipo_alumnos", $data, $condicion)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "tipo_alumnos agregado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        } else {

            if ($this->general->save_data("tipo_alumnos", $data) != false) {
                $resp["exito"] = true;
                $resp["mensaje"] = "tipo_alumnos registrado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        }
        echo json_encode($resp);
    }
    public function tipo_alumno_eliminar($id)
    {
        $this->db->trans_start();
        $this->general->delete_data("tipo_alumnos", array("id" => $id));
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $resp["exito"] = false;
            $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
        } else {
            $resp["exito"] = true;
            $resp["mensaje"] = "Eliminado con exito";
        }
        echo json_encode($resp);
    }
    public function llamadas($json = false)
    {

        // error_reporting(0);
        // ini_set('display_errors', 0);
        $json = isset($_GET['json']) ? $_GET['json'] : false;
        $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : false;
        $columns = array(
            array('db' => 'id_llamada',  'dt' => 'DT_RowId'),
            array('db' => 'concat(usua_nombres," ",usua_apellidos)', 'dt' => 'usuario'),
            array('db' => 'pers_dni', 'dt' => 'dni'),
            array('db' => 'pers_nombres', 'dt' => 'nombre'),
            array('db' => 'pers_apellidos', 'dt' => 'apellidos'),
            array('db' => 'pers_celular', 'dt' => 'celular'),
            array('db' => 'nombre', 'dt' => 'estado'),
            array('db' => 'fecha', 'dt' => 'fecha'),
        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {

            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'llamadas';
            $primaryKey = 'id_llamada';
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );
            $condiciones = array();
            $joinQuery = "FROM llamadas JOIN personas ON llam_pers_id = pers_id left join respuestas on respuestas.id = respuestas_id left join usuario on usuario.usua_id = llamadas.usuario_usua_id";
            $where = "";
            if (!empty($_POST['mes'])) {
                $fecha = explode('-', $_POST['mes']);
                $condiciones[] = "year(fecha) ='" . $fecha[0] . "' AND month(fecha) = '" . $fecha[1] . "'";
            }
            if (!empty($_POST['usuario'])) {
                $condiciones[] = "llamadas.usuario_usua_id =" . $_POST['usuario'];
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
        $datos['titulo'] = "Llamadas";
        $datos["usuarios"] = $this->general->getOptions('usuario', array("usua_id", "usua_nombres"), 'Todos los usuarios');

        $this->cssjs->add_js($this->jsPath . $this->controller . "/llamadas.js?v=1.1", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/llamadas", $datos);
        $this->load->view('footer');
    }

    //CATEGORIA

    public function categorias($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $columns = array(
            array('db' => 'cate_id', 'dt' => 'DT_RowId'),
            array('db' => 'cate_id', 'dt' => 'ID'),
            array('db' => 'cate_nombre', 'dt' => 'NOMBRE'),

        );
        foreach ($columns as &$item) {
            $item['field'] = $item['db'];
        }

        if ($json) {
            $json = isset($_GET['json']) ? $_GET['json'] : false;

            $table = 'categorias';
            $primaryKey = 'cate_id';

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $condiciones = array();
            $joinQuery = '';

            $where = "";

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Categorias";

        $this->cssjs->add_js($this->jsPath . "configuracion/categorias.js", false, false);
        $this->load->view('header');
        $this->load->view($this->controller . "/categorias", $datos);
        $this->load->view('footer');
    }

    public function categoria_crear($id = "")
    {
        if (empty($id)) {
            $categoria = new stdClass();
            $categoria->cate_id = "";
            $categoria->cate_nombre = "";
        } else {
            $categoria = $this->db->where("cate_id", $id)->get("categorias")->row();
        }

        $data["categoria"] = $categoria;
        $this->load->view($this->controller . "/form_categoria", $data);
    }

    public function categoria_guardar($id = null)
    {
        $data = array(
            'cate_nombre'       => $this->input->post("cate_nombre"),
        );
        if ($id != null) {
            $condicion = array("cate_id" => $id);
            if ($this->general->update_data("categorias", $data, $condicion)) {
                $resp["exito"] = true;
                $resp["mensaje"] = "categorias agregado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        } else {

            if ($this->general->save_data("categorias", $data) != false) {
                $resp["exito"] = true;
                $resp["mensaje"] = "categorias registrado con exito";
            } else {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            }
        }
        echo json_encode($resp);
    }

    public function categoria_eliminar($id)
    {
        $resp = [];

        $periodos = $this->db->get_where('productos', ['cate_id' => $id])->result();
        //print_r($periodos);
        if (sizeof($periodos) > 0) {
            $resp["exito"] = false;
            $resp["mensaje"] = "Esta categoria tiene productos asociados";
        } else {

            $this->db->trans_start();
            $this->general->delete_data("categorias", array("cate_id" => $id));
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $resp["exito"] = false;
                $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
            } else {
                $resp["exito"] = true;
                $resp["mensaje"] = "Eliminado con exito";
            }
        }
        echo json_encode($resp);
    }

    
}

