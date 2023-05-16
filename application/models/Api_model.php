<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_model extends CI_Model
{

  public function obtener_datos()
  {
    $query = $this->db->get('grupos'); // Realiza una consulta a la tabla deseada

    return $query->result(); // Devuelve los resultados de la consulta
  }

  public function validar_certificado($searchBy, $search)
  {

    if ($searchBy == 'N° de registro') {
      return  $this->getByCodigo($search);
    }

    if ($searchBy == 'Nombre') {
      return  $this->getByNombre($search);
    }

    // Devuelve los resultados de la consulta
  }


  function getByCodigo($search)
  {
    $this->db->select('C.cert_alum_nombre, C.cert_fecha, CC.cert_cate_nombre, CM.cert_menc_nombre');
    $this->db->select("CONCAT(IFNULL(C.cert_prefix, ''), C.cert_num) AS codigo", FALSE);
    $this->db->from('certificados AS C');
    $this->db->join('cert_categorias AS CC', 'C.cert_cate_id = CC.cert_cate_id', 'inner');
    $this->db->join('cert_menciones AS CM', 'C.cert_menc_id = CM.cert_menc_id', 'inner');
    $this->db->where("CONCAT(IFNULL(C.cert_prefix, ''), C.cert_num) = ",  $search);
    $query = $this->db->get();
    return $query->result();
  }

  function getByNombre($search)
  {
    $this->db->select('C.cert_alum_nombre, C.cert_fecha, CC.cert_cate_nombre, CM.cert_menc_nombre');
    $this->db->select("CONCAT(IFNULL(C.cert_prefix, ''), C.cert_num) AS codigo", FALSE);
    $this->db->from('certificados AS C');
    $this->db->join('cert_categorias AS CC', 'C.cert_cate_id = CC.cert_cate_id', 'inner');
    $this->db->join('cert_menciones AS CM', 'C.cert_menc_id = CM.cert_menc_id', 'inner');
    $this->db->where("C.cert_alum_nombre = ",  $search);
    $query = $this->db->get();
    return $query->result();
  }

  function getByDNI($search)
  {
  }
  // Otros métodos del modelo...
}
