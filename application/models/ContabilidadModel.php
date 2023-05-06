<?php
class ContabilidadModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_general', 'general');
    }

    public function s2()
    {
        $id = 'id';
        $text = 'nombre';
        $query = 'FROM productos WHERE nombre LIKE "%' . $_GET['term'] . '%"';
        $response = $this->general->select2Query($id, $text, $query);
        echo json_encode($response);
    }

    public function s2ByDocente($docente_id)
    {
        $term = is_null($this->input->get('term')) ? '' : $this->input->get('term');
        $id = 'id';
        $text = 'nombre';
        $query = '  FROM    grupos 
                    JOIN    periodos 
                    ON      grup_peri_id = peri_id 
                    JOIN    productos 
                    ON      peri_prod_id = id' . ' 
                    WHERE   grup_docente_id = ' . $docente_id . '
                    AND     nombre LIKE "%' . $term . '%"';
        $response = $this->general->select2Query($id, $text, $query);
        return $response;
    }

    public function getByGrupo($grup_id)
    {
        $this->db->select('productos.*');
        $this->db->from('grupos');
        $this->db->join('periodos', 'grup_peri_id = peri_id');
        $this->db->join('productos', 'peri_prod_id = id');
        $this->db->where(['grup_id' => $grup_id]);
        return $this->db->get()->row();
    }

    public function getByPagoPersonal($id)
    {
        $this->db->select('*');
        $this->db->from('pagopersonal');
        $this->db->join('usuario', 'pagopersonal.usuario_usua_id = usuario.usua_id');
        $this->db->join('tipousuario', 'usuario.usua_tipo = tipousuario.tipo_id');
        $this->db->where(['idpago' => $id]);
        return $this->db->get()->row();
    }
    public function getEgresosAdelantoPersonal($fecha_inicial, $fecha_final, $categoria = '')
    {
        $where_categoria = empty($categoria) ? '' : "  AND cate_id = $categoria";

        $query  = " SELECT IFNULL(SUM(adde_importe),0) AS total_adelantos
                FROM adelantos_descuentos
                JOIN  usuario ON adde_usua_id = usua_id
                WHERE adde_tipo = 'ADELANTO'
                AND DATE(adde_fecha) >= '$fecha_inicial' AND DATE(adde_fecha) <= '$fecha_final'
                $where_categoria";

        return $this->db->query($query)->row();
    }

    private function getEgresosPagoPersonal($fecha_inicial, $fecha_final, $categoria = '')
    {
        $where_categoria = empty($categoria) ? '' : "  AND usuario.cate_id = $categoria";

        $sql = "SELECT SUM(bono+monto-descuento-adelanto+comisiondirecta+comisionasesores+horas*costohora) AS egresopagopersonal
                FROM pagopersonal
                JOIN  usuario ON pagopersonal.usuario_usua_id = usuario.usua_id  
                WHERE DATE(fecha) >= '$fecha_inicial' AND DATE(fecha) <= '$fecha_final'
                $where_categoria";

        return $this->db->query($sql)->row();
    }

    private function getEgresosFlujoDeCaja($fecha_inicial, $fecha_final, $categoria = '')
    {
        $where_categoria = empty($categoria) ? '' : "  AND flca_cate_id = $categoria";
        $sql = "SELECT SUM(importe_flujo) AS egresoflujocaja
                FROM flujocaja
                WHERE DATE(fecha_flujo) >= '$fecha_inicial'
                AND DATE(fecha_flujo) <= '$fecha_final' 
                $where_categoria";
        return $this->db->query($sql)->row();
    }

    private function getIngresosAlumnos($fecha_inicial, $fecha_final, $categoria = "")
    {

        $where_categoria = empty($categoria) ? '' : " AND cate_id  =  '$categoria' ";

        $sql = "SELECT SUM(monto) AS ingresopagos
                FROM pagos
                JOIN alumnos ON alumnos_id_alumno = id_alumno
                JOIN productos ON id = productos_id
                WHERE DATE(fechapago) >= '$fecha_inicial'
                    AND DATE(fechapago) <=  '$fecha_final'
                    $where_categoria";

        return $this->db->query($sql)->row();
    }
    private function getVentas($fecha_inicial, $fecha_final)
    {
        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }

    private function getVentasLast($fecha_inicial, $fecha_final)
    {
        $fecha_inicial = date("Y-m-d", strtotime($fecha_inicial . "- 1 month"));
        $fecha_inicial = strtotime($fecha_inicial);

        $mes = date("m", $fecha_inicial);
        $anio = date("Y", $fecha_inicial);

        $sql = "SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE  MONTH(fecha_inscripcion) = $mes AND YEAR(fecha_inscripcion) = $anio ";

        return $this->db->query($sql)->row();
    }
    private function getVentasEnero()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-01-01';
        $fecha_final = $año . '-01-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasFebrero()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-02-01';
        $fecha_final = $año . '-02-28';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasMarzo()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-03-01';
        $fecha_final = $año . '-03-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasAbril()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-04-01';
        $fecha_final = $año . '-04-30';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasMayo()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-05-01';
        $fecha_final = $año . '-05-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasJunio()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-06-01';
        $fecha_final = $año . '-06-30';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasJulio()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-07-01';
        $fecha_final = $año . '-07-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasAgosto()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-08-01';
        $fecha_final = $año . '-08-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasSetiembre()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-09-01';
        $fecha_final = $año . '-09-30';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasOctubre()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-10-01';
        $fecha_final = $año . '-10-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasNoviembre()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-11-01';
        $fecha_final = $año . '-11-30';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }
    private function getVentasDiciembre()
    {
        $año = date("Y");
        $fecha_inicial = $año . '-12-01';
        $fecha_final = $año . '-12-31';

        $sql = 'SELECT COUNT(*) AS numventas
                FROM alumnos
                WHERE DATE(fecha_inscripcion) >= "' . $fecha_inicial . '" ' .
            'AND DATE(fecha_inscripcion) <= "' . $fecha_final . '" ';

        return $this->db->query($sql)->row();
    }

    public function getEgresosIngresos($fecha_inicial, $fecha_final, $categoria = '')
    {
        $pagopersonal = $this->getEgresosPagoPersonal($fecha_inicial, $fecha_final, $categoria);
        $flujocaja = $this->getEgresosFlujoDeCaja($fecha_inicial, $fecha_final, $categoria);
        $adelantos = $this->getEgresosAdelantoPersonal($fecha_inicial, $fecha_final, $categoria);

        $pagos = $this->getIngresosAlumnos($fecha_inicial, $fecha_final, $categoria);

        $ventas = $this->getVentas($fecha_inicial, $fecha_final);
        $ventasLast = $this->getVentasLast($fecha_inicial, $fecha_final);

        $enero = $this->getVentasEnero();
        $febrero = $this->getVentasFebrero();
        $marzo = $this->getVentasMarzo();
        $abril = $this->getVentasAbril();
        $mayo = $this->getVentasMayo();
        $junio = $this->getVentasJunio();
        $julio = $this->getVentasJulio();
        $agosto = $this->getVentasAgosto();
        $setiembre = $this->getVentasSetiembre();
        $octubre = $this->getVentasOctubre();
        $noviembre = $this->getVentasNoviembre();
        $diciembre = $this->getVentasDiciembre();

        return compact('pagopersonal', 'adelantos', 'flujocaja', 'pagos', 'ventas', 'ventasLast', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre');
    }

    private function getComisionDirecta($usuario_id, $fecha)
    {
        $_fecha = explode('-', $fecha);
        $anio = (int)$_fecha[0];
        $mes = (int)$_fecha[1];

        $sql = "SELECT  IFNULL(SUM(comision), 0) AS total, 
                        IFNULL(GROUP_CONCAT(id_alumno), '') AS ids 
                FROM    alumnos 
                JOIN    productos ON      productos_id = id 
                JOIN pagos ON alumnos.id_alumno= pagos.alumnos_id_alumno
                WHERE   habilitado 
                AND MONTH(alum_fecha) = $mes AND YEAR(alum_fecha) = $anio 
                AND usuario_usua_id = $usuario_id ";

        return $this->db->query($sql)->row();
    }

    private function getComisionPorAsesorado($usuario_id, $fecha)
    {
        $_fecha = explode('-', $fecha);
        $anio = (int)$_fecha[0];
        $mes = (int)$_fecha[1];
        $sql = "SELECT  IFNULL(SUM(comision_asesor), 0) AS total, 
                        IFNULL(GROUP_CONCAT(id_alumno), '') AS ids 
                FROM    alumnos 
                JOIN    productos 
                ON      productos_id = id 
                WHERE   habilitado 
                AND     usuario_usua_id IN (SELECT usua_id FROM usuario WHERE usuario_usua_id =  $usuario_id)
                AND    MONTH(alum_fecha) = $mes AND YEAR(alum_fecha) = $anio";

        return $this->db->query($sql)->row();
    }

    public function getComisiones($usuario_id, $fecha)
    {
        $adelanto = $this->getAdelantoDescuento($usuario_id, $fecha, 'ADELANTO');
        $descuento = $this->getAdelantoDescuento($usuario_id, $fecha, 'DESCUENTO');

        $directo = $this->getComisionDirecta($usuario_id, $fecha);
        $asesorado = $this->getComisionPorAsesorado($usuario_id, $fecha);


        return compact('directo', 'asesorado', 'adelanto', 'descuento');
    }
    public function getAdelantoDescuento($usuario_id, $fecha, $tipo)
    {
        $query = " SELECT IFNULL(SUM(adde_importe), 0) AS total
                FROM adelantos_descuentos 
                WHERE adde_usua_id = $usuario_id AND adde_anio_mes = '$fecha' AND adde_tipo = '$tipo'";
        return $this->db->query($query)->row();
    }
}
