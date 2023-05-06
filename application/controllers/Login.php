<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->controller = $this->router->fetch_class();
		$this->load->library('cssjs');

		$this->load->model("Model_login");
		$this->load->model("Model_general");
    }
	function index() {
		if($this->session->userdata('authorized')){
			redirect(base_url());
		}

		$logo = $this->db->select('empr_logo')->get("empresa")->row();
		/*
		$this->cssjs->set_path_js(base_url()."assets/js/");
		$this->cssjs->add_js('login/form');
		$script['js'] = $this->cssjs->generate_js();
		*/
		$captcha= array();

		if(isset($_COOKIE["captcha_count"])){ 
			if($_COOKIE["captcha_count"]>3){
				$this->load->helper('Captcha');
				$vals = array(
					'img_path'      => './captcha/',
					'img_url'       => base_url().'captcha/',
					//'font_path'     => './assets/comic.ttf',
					'word_length'   => 4,
					'img_width'     => 200,
					'img_height'    => 75,
					'expiration'    => 7200,
					'font_size'     => 100,
					/*'img_id'        => 'Imageid',
					'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',*/
					'colors'        => array(
							'background' => array(255, 255, 255),
							'border' => array(200, 200, 200),
							'text' => array(40, 96, 170),
							'grid' => array(200, 200, 200)
						)
					);
				$cap = create_captcha($vals);
				$captcha["cap"] = $cap;
				$data = array(
				        'captcha_time'  => $cap['time'],
				        'ip_address'    => $this->input->ip_address(),
				        'word'          => $cap['word']
				);
				$query = $this->db->insert_string('captcha', $data);
				$this->db->query($query);
			}
		}

		$this->cssjs->add_css( base_url() . "assets/css/login_page.css?v=1", true);
        $script['css'] = $this->cssjs->generate_css();

		$this->load->view('header');
		$this->load->view($this->router->fetch_class().'/form', compact('captcha', 'logo'));
		$this->load->view('footer');
	}
	function verificar_login()
	{
		$this->load->helper('Cookie');
		$usuario  = $this->security->xss_clean(strip_tags($this->input->post("user")));
		$password = $this->security->xss_clean(strip_tags($this->input->post("password")));
		$captcha  = $this->security->xss_clean(strip_tags($this->input->post("captcha")));
		$response = new StdClass;
		$success_captcha = TRUE;
		$ip =$this->input->ip_address();
		if(isset($_COOKIE["captcha_count"])){ 
			if($_COOKIE["captcha_count"]>3)
			{	
				$expira = time() - 7200;
				$this->Model_general->delete_data("captcha",array("captcha_time <"=>$expira));
				$dts = array("word" => $captcha
								,"ip_address"=>$ip
								,"captcha_time >"=>$expira
							);
				$success_captcha = $this->Model_general->check_captcha($dts);
			}
		}

		if($success_captcha != FALSE ){
			if($usuario != FALSE){
			 	$user = array(
			 		"usua_user" => $usuario,
			 		"usua_habilitado" => '1'
			 		);

			 	$usua = $this->Model_login->login($user);
			 	if($usua != FALSE){
			 		$usu_pas = array(
			 		"usua_user" => $usuario,
			 		"usua_password" => md5($password),
			 		"usua_habilitado" => '1'
			 		);

			 		if($this->Model_login->login($usu_pas,TRUE)!=FALSE){
			 			$cambios = array("usua_intento"=>'0');
						$this->Model_login->guargar_edit_registro($cambios,$usua["id"]);
						unset($_COOKIE['captcha_count']);
						unset($_COOKIE['error']);
			 			$response->url = base_url();
			 			$response->error = "";

						if(isset($_SESSION['authorized']) && !isset($_SESSION['authorizedadmin'])){
							unset($_COOKIE['permision']);
							$cookie = array(
			                    'name'   => 'permision',
			                    'value'  => 1,
			                    'expire' => 60*60*24*365,
			                    'path'   => '/'
			                );
			                $this->input->set_cookie($cookie);
						}
			 		}else {
			 			$id = $usua["id"];
			 			$contador = $usua["intento"];
			 			$this->bloquear($contador,$id,$ip);
			 			$response->error ="Contraseña incorrecta";
			 			$response->url = base_url()."login";
			 		}
			 	}else{
			 		$response->error = "El usuario no exisite o fue bloqueado !!";
					$this->activar_cookie();
					$response->url = base_url()."login";
			 	}
			}else{
				$response->error = "Error al enviar datos";
				$this->activar_cookie();
				$response->url = base_url()."login";
			}
		}else{

			$response->error = "El captcha no coincide";
			$response->url = base_url()."login";

			$user = array(
				"usua_user" => $usuario,
				"usua_habilitado" => '1'
				);

			$usua = $this->Model_login->login($user);
			if($usua != FALSE){
				$id = $usua["id"];
					$contador = $usua["intento"];
					$this->bloquear($contador,$id,$ip);
			}else{
				$this->activar_cookie();
			}
		}	
		if($response->error != "")
			redirect($response->url."?t=".urlencode($response->error));
		else
			redirect($response->url);
	}
	function bloquear($contador,$id,$ip){
		if($contador<3){
 			if(isset($_COOKIE["captcha_count"])){ 
				if($_COOKIE["captcha_count"]>3)
				{
					$contador = 3;
				}
			}
			$this->activar_cookie();
		}
		else{
			$cookie = array(
			    'name'   => 'captcha_count',
		        'value'  => $contador+1,
		        // 'expire' => time()+86500,
		        'expire' => 60*10,
		        'path'   => '/'
			);
			 $this->input->set_cookie($cookie);	
		}
		$cambios = array("usua_intento" => $contador+1,"usua_ultimoip"=>$ip);
		if($cambios["usua_intento"]>=6){
			$cambios = array("usua_habilitado"=>'0',"usua_ultimoip"=>$ip);
		}
		$this->Model_login->guargar_edit_registro($cambios,$id);
	}
	function activar_cookie(){
		$cont=isset($_COOKIE["captcha_count"])?($this->input->cookie("captcha_count")+1): 1;
 		$cookie = array(
		    'name'   => 'captcha_count',
	        'value'  => $cont,
	        // 'expire' => time()+86500,
	        'expire' => 60*10,
	        'path'   => '/'
		);
		$this->input->set_cookie($cookie);
	}
	function salir() {
 		$cookie = array(
		    'name'   => 'captcha_count',
	        'value'  => 0,
	        // 'expire' => time()+86500,
	        'expire' => 60*10,
	        'path'   => '/'
		);
		$this->input->set_cookie($cookie);
		$this->session->sess_destroy();
 		unset($_COOKIE['captcha_count']);
 		redirect(base_url().'login');
	}
}
