<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Certificados extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    if (
      !$this->session->userdata('authorized')
      && ($this->session->userdata('usua_tipo') != 2 ||  $this->session->userdata('usua_tipo') != 7)
    ) {
      redirect(base_url() . "login");
    }
    $this->load->library('Cssjs');
    $this->load->library('form_validation');
    $this->load->library('Ssp');
    $this->load->model('Model_general', 'general');
    $this->jsPath = base_url() . "assets/js/";
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

    $datos = [];
    $this->cssjs->add_js($this->jsPath . "Certificados/certificados.js", false, false);


    $datos["categorias"] = $this->general->getOptions('cert_categorias', array("cert_cate_id", "cert_cate_nombre"), '* Categoria');
    $datos["menciones"] = $this->general->getOptions('cert_menciones', array("cert_menc_id", "cert_menc_nombre"), '* Mención');

    $datos['titulo'] = "Administrar Certificados";

    $this->load->view('header',);
    $this->load->view($this->controller . "/index", $datos);
    $this->load->view('footer');
  }

  public function certificados_guardar()
  {
    $usuarios =  $this->input->post("certificado[]");
    //$alumnos_ids =  $this->input->post("alumnos_ids");
    $data['cert_cate_id'] = $this->input->post("cate_id");
    $data['cert_fecha'] = $this->input->post("fecha_emision");
    $data['cert_menc_id'] = $this->input->post("menc_id");
    $data['cert_grup_id'] = $this->input->post("grup_id");
    $data['cert_prefix'] = $this->getIniciales($this->input->post("nombre_curso"));



    if (empty($this->input->post("cate_id"))   || empty($this->input->post("fecha_emision"))   || empty($this->input->post("menc_id"))) {
      $resp["exito"] = false;
      $resp["mensaje"] = "Todos los campos son obligatorios";
      echo json_encode($resp);
      return;
    }

    $this->db->trans_start();

    $usuarios_ids =  [];
    foreach ($usuarios  as $key => $value) {
      array_push($usuarios_ids, $key);
    }

    $this->db->where('cert_grup_id', $this->input->post("grup_id"));
    $this->db->delete('certificados');

    foreach ($usuarios_ids as  $value) {
      $numero = str_pad($data['cert_grup_id'], 3, '0', STR_PAD_LEFT) . '-'
        . str_pad($data['cert_cate_id'], 2, '0', STR_PAD_LEFT)
        . str_pad($data['cert_menc_id'], 2, '0', STR_PAD_LEFT)
        . str_pad($value, 4, '0', STR_PAD_LEFT);

      $data['cert_alum_id'] = $value;
      $data['cert_num'] = $numero;

      $this->db->insert("certificados", $data);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === false) {
      $resp["exito"] = false;
      $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
    } else {
      $resp["exito"] = true;
      $resp["mensaje"] = "Certificados Generados";
    }

    echo json_encode($resp);
  }

  public function categorias()
  {

    $datos = [];

    $this->load->helper('Functions');
    $this->load->library('Ssp');
    $this->load->library('Cssjs');
    $json = isset($_GET['json']) ? $_GET['json'] : false;

    $columns = array(
      array('db' => 'cert_cate_id', 'dt' => 'DT_RowId'),
      array('db' => 'cert_cate_id', 'dt' => 'ID'),
      array('db' => 'cert_cate_nombre', 'dt' => 'NOMBRE'),
      array('db' => 'cert_cate_descripcion', 'dt' => 'DESCRIPCIÓN'),

    );
    foreach ($columns as &$item) {
      $item['field'] = $item['db'];
    }

    if ($json) {
      $json = isset($_GET['json']) ? $_GET['json'] : false;

      $table = 'cert_categorias';
      $primaryKey = 'cert_cate_id';

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

    $this->cssjs->add_js($this->jsPath . "certificados/categorias.js", false, false);
    $datos['titulo'] = "Categorias";
    $this->load->view('header',);
    $this->load->view($this->controller . "/categorias", $datos);
    $this->load->view('footer');
  }

  public function categoria_crear($id = "")
  {
    if (empty($id)) {
      $categoria = new stdClass();
      $categoria->cert_cate_id = "";
      $categoria->cert_cate_nombre = "";
      $categoria->cert_cate_descripcion = "";
    } else {
      $categoria = $this->db->where("cert_cate_id", $id)->get("cert_categorias")->row();
    }
    $data["categoria"] = $categoria;
    $this->load->view($this->controller . "/form_categoria", $data);
  }

  public function categoria_guardar($id = null)
  {
    $data = array(
      'cert_cate_nombre' => $this->input->post("cert_cate_nombre"),
      'cert_cate_descripcion' => $this->input->post("cert_cate_descripcion"),
    );
    if ($id != null) {
      $condicion = array("cert_cate_id" => $id);
      if ($this->general->update_data("cert_categorias", $data, $condicion)) {
        $resp["exito"] = true;
        $resp["mensaje"] = "categorias actualizada con exito";
      } else {
        $resp["exito"] = false;
        $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
      }
    } else {

      if ($this->general->save_data("cert_categorias", $data) != false) {
        $resp["exito"] = true;
        $resp["mensaje"] = "Categorias agregada con exito";
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

    $periodos = []; // $this->db->get_where('productos', ['cate_id' => $id])->result();
    //print_r($periodos);
    if (sizeof($periodos) > 0) {
      $resp["exito"] = false;
      $resp["mensaje"] = "Esta categoria tiene productos asociados";
    } else {

      $this->db->trans_start();
      $this->general->delete_data("cert_categorias", array("cert_cate_id" => $id));
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

  public function menciones()
  {

    $datos = [];

    $this->load->helper('Functions');
    $this->load->library('Ssp');
    $this->load->library('Cssjs');
    $json = isset($_GET['json']) ? $_GET['json'] : false;

    $columns = array(
      array('db' => 'cert_menc_id', 'dt' => 'DT_RowId'),
      array('db' => 'cert_menc_id', 'dt' => 'ID'),
      array('db' => 'cert_menc_nombre', 'dt' => 'NOMBRE'),
      array('db' => 'cert_menc_descripcion', 'dt' => 'DESCRIPCIÓN'),

    );
    foreach ($columns as &$item) {
      $item['field'] = $item['db'];
    }

    if ($json) {
      $json = isset($_GET['json']) ? $_GET['json'] : false;

      $table = 'cert_menciones';
      $primaryKey = 'cert_menc_id';

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
    $datos['titulo'] = "Menciones";


    $this->cssjs->add_js($this->jsPath . "certificados/menciones.js", false, false);
    $this->load->view('header',);
    $this->load->view($this->controller . "/menciones", $datos);
    $this->load->view('footer');
  }

  public function mencion_crear($id = "")
  {
    if (empty($id)) {
      $mencion = new stdClass();
      $mencion->cert_menc_id = "";
      $mencion->cert_menc_nombre = "";
      $mencion->cert_menc_descripcion = "";
    } else {
      $mencion = $this->db->where("cert_menc_id", $id)->get("cert_menciones")->row();
    }
    $data["mencion"] = $mencion;
    $this->load->view($this->controller . "/form_mencion", $data);
  }

  public function mencion_guardar($id = null)
  {
    $data = array(
      'cert_menc_nombre' => $this->input->post("cert_menc_nombre"),
      'cert_menc_descripcion' => $this->input->post("cert_menc_descripcion"),
    );
    if ($id != null) {
      $condicion = array("cert_menc_id" => $id);
      if ($this->general->update_data("cert_menciones", $data, $condicion)) {
        $resp["exito"] = true;
        $resp["mensaje"] = "Mención actualizada con exito";
      } else {
        $resp["exito"] = false;
        $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
      }
    } else {

      if ($this->general->save_data("cert_menciones", $data) != false) {
        $resp["exito"] = true;
        $resp["mensaje"] = "Mención agregada con exito";
      } else {
        $resp["exito"] = false;
        $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
      }
    }
    echo json_encode($resp);
  }

  public function mencion_eliminar($id)
  {
    $resp = [];

    $periodos = []; // $this->db->get_where('productos', ['cate_id' => $id])->result();
    //print_r($periodos);
    if (sizeof($periodos) > 0) {
      $resp["exito"] = false;
      $resp["mensaje"] = "Esta mencion tiene productos asociados";
    } else {

      $this->db->trans_start();
      $this->general->delete_data("cert_menciones", array("cert_menc_id" => $id));
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

  public function personal()
  {
    $datos = [];

    $this->load->helper('Functions');
    $this->load->library('Ssp');
    $this->load->library('Cssjs');
    $json = isset($_GET['json']) ? $_GET['json'] : false;


    $columns = array(
      array('db' => 'cert_id', 'dt' => 'DT_RowId'),
      array('db' => 'cert_id', 'dt' => 'ID'),
      array('db' => 'cert_alum_nombre', 'dt' => 'ALUMNO'),
      array('db' => 'cert_prefix', 'dt' => 'PREFIJO'),
      array('db' => 'cert_num', 'dt' => 'NÚMERO'),
      array('db' => 'cert_fecha', 'dt' => 'FECHA'),

    );

    foreach ($columns as &$item) {
      $item['field'] = $item['db'];
    }

    if ($json) {
      $json = isset($_GET['json']) ? $_GET['json'] : false;

      $table = 'certificados';
      $primaryKey = 'cert_id';

      $sql_details = array(
        'user' => $this->db->username,
        'pass' => $this->db->password,
        'db' => $this->db->database,
        'host' => $this->db->hostname
      );

      $condiciones = array('cert_grup_id IS NULL');
      $joinQuery = '';

      $where = "";

      $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";

      //var_dump($where);
      echo json_encode(
        $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
      );
      exit(0);
    }

    $datos['columns'] = $columns;
    $datos['titulo'] = "Certificados Personales";
    $this->cssjs->add_js($this->jsPath . "certificados/personal.js", false, false);
    $this->load->view('header',);
    $this->load->view($this->controller . "/personales", $datos);
    $this->load->view('footer');
  }

  public function personal_crear($id = "")
  {
    if (empty($id)) {
      $certificado = new stdClass();
      $certificado->cert_id = "";
      $certificado->cert_cate_id = "";
      $certificado->cert_menc_id = "";
      $certificado->cert_alum_id = "";
      $certificado->cert_alum_nombre = "";
      $certificado->cert_prefix = "";
      $certificado->cert_num = "";
      $certificado->cert_fecha = "";
    } else {
      $certificado = $this->db->where("cert_id", $id)->get("certificados")->row();
    }
    $data["certificado"] = $certificado;

    $data["categorias"] = $this->general->getOptions('cert_categorias', array("cert_cate_id", "cert_cate_nombre"), '* Categoria');
    $data["menciones"] = $this->general->getOptions('cert_menciones', array("cert_menc_id", "cert_menc_nombre"), '* Mención');

    //$this->cssjs->add_js($this->jsPath . "certificados/personal.js", false, false);
    $this->cssjs->add_js(base_url() . "assets/js/AreaAcademica/alunos.js", false, false);
    //$script['js'] = $this->cssjs->generate_js();

    $this->load->view($this->controller . "/form_personal", $data);
  }

  public function certificado_guardar($id = null)
  {
    $data = [
      'cert_menc_id' => $this->input->post("cert_menc_id"),
      'cert_cate_id' => $this->input->post("cert_cate_id"),
      'cert_fecha' => $this->input->post("cert_fecha"),
      'cert_alum_nombre' => $this->input->post("cert_alum_nombre"),
      'cert_prefix' => $this->input->post("cert_prefix"),
      'cert_num' => $this->input->post("cert_num"),
    ];

    if ($id != null) {
      $condicion = array("cert_id" => $id);

      if ($this->general->update_data("certificados", $data, $condicion)) {
        $resp["exito"] = true;
        $resp["mensaje"] = "Certificado actualizado con exito";
      } else {
        $resp["exito"] = false;
        $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
      }
    } else {

      if ($this->general->save_data("certificados", $data) != false) {
        $resp["exito"] = true;
        $resp["mensaje"] = "Certificado creado con exito";
      } else {
        $resp["exito"] = false;
        $resp["mensaje"] = "Ocurrio un error, intentelo más tarde";
      }
    }
    echo json_encode($resp);
  }

  function getIniciales($texto)
  {
    $res = '';
    $explode = explode(' ', $texto);
    foreach ($explode as $x) {
      $res .=  $x[0];
    }
    return $res;
  }
}
