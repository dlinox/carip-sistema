<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('api_model'); // Carga el modelo correspondiente a tu API
    }

    public function index() {
        echo 'API en CodeIgniter 3'; // Un ejemplo básico de respuesta
    }

    public function obtener_datos() {
        $datos = $this->api_model->obtener_datos(); // Llama a un método del modelo para obtener los datos

        // Devuelve los datos en formato JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($datos));
    }

    // Otros métodos de la API...
}
