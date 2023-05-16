<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{

  public function obtener_datos()
  {
    $query = $this->db->get('grupos'); // Realiza una consulta a la tabla deseada

    return $query->result(); // Devuelve los resultados de la consulta
  }

  

  // Otros métodos del modelo...
}
