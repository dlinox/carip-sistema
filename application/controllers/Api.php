<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

  protected $response;

  public function __construct()
  {
    parent::__construct();

    $this->response['data'] = null;
    $this->response['ok'] = false;
    $this->load->model('api_model'); // Carga el modelo correspondiente a tu API
  }

  public function index()
  {
    echo 'API en CodeIgniter 3'; // Un ejemplo básico de respuesta
  }

  public function obtener_datos()
  {
    $datos = $this->api_model->obtener_datos(); // Llama a un método del modelo para obtener los datos

    // Devuelve los datos en formato JSON
    $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($datos));
  }


  public function validar_certificado()
  {
    $searchBy = $this->input->post('searchBy');
    $search = $this->input->post('search');
    try {
      $datos = $this->api_model->validar_certificado($searchBy, $search);
      $this->response['data'] = $datos;
      $this->response['ok'] = true;
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->response));
    } catch (\Throwable $th) {
      $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->response));
    }
  }
  // Otros métodos de la API...
}
