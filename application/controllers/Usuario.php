<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('authorized')) {
            redirect(base_url() . "login");
        }
        if ($this->session->userdata('usua_tipo') != 2 && $this->session->userdata('usua_tipo') != 7) {
            redirect(base_url() . "login");
        }
        $this->load->library('Cssjs');
        $this->load->library('form_validation');
        $this->load->model('Model_general', 'general');
        $this->load->model('UsuarioModel', 'model');
        $this->user_id = $this->session->userdata('authorized');
        error_reporting(0);
        ini_set('display_errors', 0);
    }
    /*******************************************************************************************
									USUARIO
     *******************************************************************************************/
    public function Listado($json = false)
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $nombre = "CONCAT(usua_apellidos,', ',usua_nombres)";
        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';
        $estado = "IF(usua_habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";
        $tipo = "UPPER(tipo_denominacion)";
        $columns = array(
            array('db' => 'usua_id',            'dt' => 'ID',       "field" => "usua_id"),
            array('db' => $nombre,              'dt' => 'NOMBRES Y APELLIDOS',  "field" => $nombre),
            array('db' => 'usua_user',          'dt' => 'NOMBRE DE USUARIO',    "field" => "usua_user"),
            array('db' => 'usua_movil',         'dt' => 'TELEFONO', "field" => "usua_movil"),
            array('db' => 'usua_email',         'dt' => 'EMAIL',    "field" => "usua_email"),
            array('db' => $tipo,                'dt' => 'CARGO',     "field" => $tipo),
            array('db' => $estado,              'dt' => 'ESTADO',   "field" => $estado),
            array('db' => 'usua_id',            'dt' => 'DT_RowId', "field" => "usua_id")
        );

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
            $joinQuery = "FROM usuario JOIN tipousuario ON tipo_id = usua_tipo";
            $where = "";

            // if (!empty($_POST['lider'])) { $condiciones[] = "usuario_usua_id = ".$_POST['lider']; }
            // $condiciones[] = $_POST['lider'] == '' ?  'usuario_usua_id IS NULL' : "usuario_usua_id = " . $_POST['lider'];

            if (!empty($_POST['tipo'])) {
                $condiciones[] = "usua_tipo = " . $_POST['tipo'];
            }
            if (!empty($_POST['cate_id'])) {
                $condiciones[] = "cate_id = " . $_POST['cate_id'];
            }

            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Usuarios";
        $datos['usuarios'] = $this->model->getLideres();
        //$datos['lideres'] = $this->model->getLideres();

        $datos["tipos"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), 'Seleccione');

        $datos['no_asesorados'] = $this->model->getNoAsesorados();
        // $datos['usuarios'] = $this->general->getOptions('usuario', ['usua_id', 'usua_nombres'], 'Todos los usuarios');

        $this->cssjs->add_js(base_url() . "assets/js/usuario/listado.js", false, false);
        $this->cssjs->add_js(base_url() . "assets/js/usuario/formulario.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/listado", $datos);
        $this->load->view('footer');
    }

    public function vendedores()
    {
        $this->load->helper('Functions');
        $this->load->library('Ssp');
        $this->load->library('Cssjs');
        $json = isset($_GET['json']) ? $_GET['json'] : false;

        $nombre = "CONCAT(u.usua_nombres, ', ', u.usua_apellidos)";
        $habilitado = '<span class="label label-success">HABILITADO</span>';
        $bloqueado = '<span class="label label-danger">BLOQUEADO</span>';
        $estado = "IF(u.usua_habilitado = '0','" . $bloqueado . "','" . $habilitado . "')";
        $tipo = "UPPER(tipo_denominacion)";
        $columns = array(
            array('db' => 'CONCAT(u.usua_id)', 'dt' => 'ID'),
            array('db' => $nombre, 'dt' => 'NOMBRES Y APELLIDOS'),
            // array('db' => 'CONCAT(u.usua_user)', 'dt' => 'LOGIN'),
            array('db' => 'CONCAT(u.usua_movil)', 'dt' => 'TELEFONO'),
            array('db' => 'CONCAT(u.usua_email)', 'dt' => 'EMAIL'),
            array('db' => 'CONCAT(lider.usua_nombres, " ", lider.usua_apellidos)', 'dt' => 'LIDER'),
            array('db' => $estado, 'dt' => 'ESTADO'),
            array('db' => 'CONCAT(u.usua_id)', 'dt' => 'DT_RowId')
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
            $joinQuery = "FROM `usuario` AS `u` JOIN `tipousuario` ON (tipo_id = u.usua_tipo) LEFT JOIN `usuario` AS `lider` ON (`u`.`usuario_usua_id` = `lider`.`usua_id`)";
            $where = "";
            if (!empty($_POST['lider'])) {
                $condiciones[] = "u.usuario_usua_id = " . $_POST['lider'];
            }
            // $condiciones[] = $_POST['lider'] == '' ?  'usuario_usua_id IS NULL' : "usuario_usua_id = " . $_POST['lider'];

            $condiciones[] = "u.usua_tipo = 1";
            $where = count($condiciones) > 0 ? implode(' AND ', $condiciones) : "";
            echo json_encode(
                $this->ssp->simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where)
            );
            exit(0);
        }

        $datos['columns'] = $columns;
        $datos['titulo'] = "Ventas";
        $datos['usuarios'] = $this->model->getLideres();
        $datos['no_asesorados'] = $this->model->getNoAsesorados();
        $datos["tipos"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), 'Seleccione');

        $this->cssjs->add_js(base_url() . "assets/js/usuario/vendedores.js", false, false);
        $this->load->view('header');
        $this->load->view($this->router->fetch_class() . "/vendedores", $datos);
        $this->load->view('footer');
    }

    public function crear($usua_id = "")
    {
        if (empty($usua_id)) {
            $usua = new stdClass();
            $usua->usua_id = "";
            $usua->usua_user = "";
            $usua->usua_nombres = "";
            $usua->usua_apellidos = "";
            $usua->usua_email = "";
            $usua->usua_movil = "";
            $usua->usua_habilitado = "1";
            $usua->usua_tipo = "";
            $usua->usua_dni = "";
            $usua->usua_foto = "";
            $usua->cate_id = "";
        } else {
            $usua = $this->db->where("usua_id", $usua_id)->get("usuario")->row();
        }

        $data["categorias"] = $this->general->getOptions('categorias', array("cate_id", "cate_nombre"), '* Categoria');
        $data["usua"] = $usua;
        $data["estado"] = array("1" => "HABILITADO", "0" => "BLOQUEADO");
        $data["tipo"] = $this->general->getOptions('tipousuario', array("tipo_id", "tipo_denominacion"), '* Tipo usuario');
        $this->load->view($this->router->fetch_class() . "/usua_form", $data);
    }
    function validar()
    {
        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_rules('nombres', 'Nombres', 'required');
        $this->form_validation->set_rules('apellidos', 'Apellidos', 'required');
        $this->form_validation->set_rules('dni', 'DNI', 'required');
        // $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        // $this->form_validation->set_rules('movil', 'Teléfono', 'required');
        $this->form_validation->set_rules('user', 'Usuario', 'required');
        $this->form_validation->set_rules('tipo', 'Tipo', 'required');
        //$this->form_validation->set_rules('foto', 'Foto', 'required');
        if ($this->input->post("change_pass"))
            $this->form_validation->set_rules('pass', 'Contraseña', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }
    }
    function guardar($usua_id = "")
    {

        $pathFoto = 'uploads/FotoUsuario/';
        $config['upload_path']          = $pathFoto;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 2000;
        $config['max_width']            = 1024;
        $config['max_height']           = 1024;
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;

        $this->validar();

        $nombres = $this->input->post("nombres");
        $apellidos = $this->input->post("apellidos");
        $email = $this->input->post("email");
        $movil = $this->input->post("movil");
        $user = $this->input->post("user");
        $pass = md5($this->input->post("pass"));
        $tipo = $this->input->post("tipo");
        $habilitado = $this->input->post("habilitado");
        $usua_dni = $this->input->post("dni");
        $categoria = $this->input->post("cate_id");

        $usuario = array(
            "usua_nombres"      => $nombres,
            "usua_apellidos"    => $apellidos,
            "usua_user"         => $user,
            "usua_password"     => $pass,
            "usua_email"        => $email,
            "usua_movil"        => $movil,
            "usua_habilitado"   => $habilitado,
            "usua_tipo"         => $tipo,
            "usua_dni"          => $usua_dni,
            "cate_id"           => $categoria
        );

        $json["file"] = true;
        if (!empty($_FILES["foto"]["name"])) {
            $this->load->library('upload',  $config);

            if (!$this->upload->do_upload('foto')) {
                $error = array('error' => $this->upload->display_errors());

                $json["file_mensaje"] = $error;
                $json["file"] = false;
            } else {
                $data = array('upload_data' => $this->upload->data());
                $foto_perfil = $data['upload_data']['file_name'];
                $usuario['usua_foto'] = $foto_perfil;
            }
        }

        if (!empty($usua_id)) {

            $condicion = array("usua_id" => $usua_id);
            if (!$this->input->post("change_pass"))
                unset($usuario['usua_password']);
            if ($this->general->update_data("usuario", $usuario, $condicion)) {
                $json["exito"] = true;
                $json["mensaje"] = "Usuario actualizado con exito";
            } else {
                $json["exito"] = false;
                $json["mensaje"] = "No se guardaron cambios";
            }
        } else {
            $consulta = $this->db->query("SELECT * FROM usuario WHERE usua_user = '{$user}'")->result();
            if (COUNT($consulta) > 0) {
                $json["exito"] = false;
                $json["mensaje"] = "El nombre de usuario ya existe";
            } else {
                if ($this->general->save_data("usuario", $usuario) != false) {
                    $json["exito"] = true;
                    $json["mensaje"] = "Usuario agregado con exito";
                } else {
                    $json["exito"] = false;
                    $json["mensaje"] = "Error 2 al guardar usuario";
                }
            }
        }
        echo json_encode($json);
    }
    public function eliminar($usua_id = "")
    {
        $this->db->trans_start();
        $this->general->delete_data("usuario", array("usua_id" => $usua_id));
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
    public function buscar_asesor()
    {
        $responese = new StdClass;
        $lider = isset($_GET['lider']) ? $_GET["lider"] : '';
        $datos = array();
        // $where = $lider != "" ? "usuario_usua_id is null" : null;
        //$where = "usuario_usua_id is null or usua_id !=".$lider;
        $where = 'usuario_usua_id IS NULL AND usua_tipo = 1';
        $like =
            [
                'usua_nombres' => $_GET['term'],
                'usua_apellidos' => $_GET['term'],
                'usua_dni' => $_GET['term'],
            ];
        $asesores = $this->general->select2("usuario", $like, ['usua_id' => 'desc'], $where);
        // $asesores = $this->general->select2withJoin("usuario", $like, 'personas', 'alum_pers_id = pers_id');

        foreach ($asesores["items"] as $value) {
            $datos[] = array(
                "id" => $value->usua_id,
                "text" => $value->usua_nombres . ' ' . $value->usua_apellidos
            );
        }
        $responese->total_count = $asesores["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }
    public function asignar_usuario()
    {
        $lider = $this->input->post('lider');
        $asesorado = $this->input->post('asesorado');

        $this->load->library('Form_validation');
        $this->form_validation->set_message('required', '%s es un campo obligatorio ');
        $this->form_validation->set_rules('lider', 'Lider', 'required');
        $this->form_validation->set_rules('asesorado', 'Asesorado', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->general->dieMsg(array('exito' => false, 'mensaje' => validation_errors()));
        }

        $this->load->helper('Response');
        if ($this->general->update_data('usuario', array("usuario_usua_id" => $lider), "usua_id =" . $asesorado)) {
            showSuccess();
        } else {
            showError();
        }
    }
    public function eliminar_asesor($asesor = null)
    {
        if ($this->general->update_data('usuario', array("usuario_usua_id" => null), "usua_id =" . $asesor)) {
            $json["exito"] = false;
            $json["mensaje"] = "Error al tratar de eliminar el lector";
        } else {
            $json["exito"] = true;
            $json["mensaje"] = "Eliminado con exito";
        }
        // $json["mensaje"] = $this->db->last_query();
        $json["exito"] = true;
        $json["mensaje"] = "Asesor eliminado con exito";
        echo json_encode($json);
    }

    public function s2getByTipo($tipo)
    {
        $responese = new StdClass;
        $term = $_GET['term'];
        $datos = array();
        $like =
            [
                'usua_nombres' => $term,
                'usua_apellidos' => $term,
                'usua_dni' => $term,
            ];
        $where = ['usua_tipo' => $tipo];
        $results = $this->general->select2("usuario", $like, null, $where);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->usua_id,
                "text" => $value->usua_nombres . ' ' . $value->usua_apellidos . ' (' . $value->usua_dni . ')'
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }

    function getById($id)
    {
        $this->load->helper('Response');
        showJSON($this->db->where("usua_id", $id)->get("usuario")->row());
    }

    public function s2lideresElegibles()
    {
        $id = 'usua_id';
        $text = 'CONCAT(usua_nombres, " ", usua_apellidos)';
        $ors = [];
        $ors[] = 'usua_nombres LIKE "%' . $_GET['term'] . '%"';
        $ors[] = 'usua_apellidos LIKE "%' . $_GET['term'] . '%"';
        // $ors[] = 'usua_dni LIKE "%' . $_GET['term'] . '%"';
        $query = 'FROM usuario WHERE usuario_usua_id IS NULL AND usua_tipo = 1 AND (' . implode(' OR ', $ors) . ')';
        $response = $this->general->select2Query($id, $text, $query);
        echo json_encode($response);
    }

    public function s2noAsesorados()
    {
        $id = 'usua_id';
        $text = 'CONCAT(usua_nombres, " ", usua_apellidos)';
        $ors = [];
        $ors[] = 'usua_nombres LIKE "%' . $_GET['term'] . '%"';
        $ors[] = 'usua_apellidos LIKE "%' . $_GET['term'] . '%"';
        // $ors[] = 'usua_dni LIKE "%' . $_GET['term'] . '%"';
        $query = 'FROM usuario WHERE usuario_usua_id IS NULL AND usua_tipo = 1 AND usua_id NOT IN 
        (
            SELECT DISTINCT usuario_usua_id 
            FROM            usuario 
            WHERE           usua_tipo = 1 
            AND 			usuario_usua_id IS NOT NULL
        ) AND (' . implode(' OR ', $ors) . ')';
        $response = $this->general->select2Query($id, $text, $query);
        echo json_encode($response);
    }
}
