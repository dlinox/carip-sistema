<?php
class Model_general extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function save_data($table, $data)
    {
        $this->db->set($data);
        $this->db->insert($table);

        if ($this->db->affected_rows() > 0)
            return $this->db->insert_id();
        else
            return false;
    }
    public function update_data($table, $data, $condition)
    {
        $this->db->where($condition);
        $this->db->update($table, $data);
        if ($this->db->trans_status() === FALSE)
            return false;
        else
            return true;
    }
    public function delete_data($table, $condition)
    {
        $this->db->where($condition);
        $this->db->delete($table);
        if ($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }
    public function getData($table, $datos, $where = null, $order = null)
    {
        $this->db->select(implode(",", $datos));
        if ($where != null)
            $this->db->where($where);
        if ($order != null)
            $this->db->order_by($order, "asc");
        $this->db->from($table);
        $consulta = $this->db->get();
        return $consulta->result();
    }
    public function check_captcha($where)
    {

        $this->db->where($where);
        $this->db->limit(1);
        $consulta = $this->db->get('captcha');

        if ($consulta->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function select_options($datos, $opts, $vacio = FALSE)
    {
        $options = ($vacio != FALSE) ? array("" => $vacio) : array();
        $id = $opts[0];
        $nombre = $opts[1];
        foreach ($datos as $value) {
            $options[$value->$id] = $value->$nombre;
        }

        return $options;
    }
    public function getOptions($table, $datos, $vacio = FALSE, $order = null, $where = null)
    {
        return $this->select_options($this->getData($table, $datos, $where, $order), $datos, $vacio);
    }

    function seleccionarDatos($datos, $opts, $vacio = FALSE)
    {
        $options = ($vacio != FALSE) ? array("" => $vacio) : array();
        $id = $opts[0];
        $nombre = $opts[1];
        $apellido = $opts[2];
        foreach ($datos as $value) {
            $options[$value->$id] = $value->$nombre . " " . $value->$apellido;
        }

        return $options;
    }
    public function obtenerDatos($table, $datos, $vacio = FALSE, $order = null, $where = null)
    {
        return $this->seleccionarDatos($this->getData($table, $datos, $where, $order), $datos, $vacio);
    }

    function enum_valores($tabla, $campo)
    {
        $consulta = $this->db->query("SHOW COLUMNS FROM $tabla LIKE '$campo'");
        if ($consulta->num_rows() > 0) {
            $consulta = $consulta->row();
            $array = explode(",", str_replace(array("enum", "'", "(", ")"), "", $consulta->Type));
            foreach ($array as $key) {
                $array2[$key] = $key;
            }
            return $array2;
        } else {
            return FALSE;
        }
    }
    function select2($tabla, $search, $order = null, $where = null)
    {
        $this->db->select("sql_calc_found_rows *", FALSE);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->or_like($search);
            $this->db->group_end();
        }
        $this->db->from($tabla);
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);
        $consulta = $this->db->get();
        $query = $this->db->query('SELECT FOUND_ROWS() AS total_count');
        $total_count = $query->row()->total_count;
        $response = array("total_count" => $total_count, "items" => $consulta->result());
        return $response;
    }

    function select2withJoin($tabla, $search, $joinTable, $joinCondition, $order = null, $where = null)
    {
        $this->db->select("sql_calc_found_rows *", FALSE);
        if (!empty($search))
            $this->db->like($search);
        $this->db->from($tabla);
        $this->db->join($joinTable, $joinCondition);
        if ($where != null) $this->db->where($where);
        if ($order != null) $this->db->order_by($order);
        $consulta = $this->db->get();
        $query = $this->db->query('SELECT FOUND_ROWS() AS total_count');
        $total_count = $query->row()->total_count;
        $response = array("total_count" => $total_count, "items" => $consulta->result());
        return $response;
    }

    function select2query($id, $text, $query)
    {
        $response = new StdClass();
        $sql = 'SELECT DISTINCT SQL_CALC_FOUND_ROWS ' . $id . ' AS id, ' . $text . ' AS text ' . $query;
        $response->items = $this->db->query($sql)->result();
        $query = $this->db->query('SELECT FOUND_ROWS() AS total_count');
        $response->total_count = $query->row()->total_count;
        $response->incomplete_results = false;
        return $response;
    }

    function fecha_to_mysql($fecha)
    {
        if (empty($fecha))
            return NULL;
        if (preg_match('#([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})?#', $fecha, $mifecha)) {
            return $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        }
        return NULL;
    }
    function time_to_mysql($fechahora)
    {
        if (preg_match('#([0-9]{1,2}):([0-9]{1,2})#', $fechahora, $buf)) {
            $hora = $buf[1];
            return $hora . ':' . $buf[2] . ':00';
        } else {
            return NULL;
        }
    }
    /*
    function time_to_mysql($fechahora) {
        if (preg_match('#([0-9]{1,2}):([0-9]{1,2}) ([A|P][M])#', $fechahora, $buf)){
            $hora = ($buf[3]=='PM' && $buf[1]<12) ? $buf[1]+12 : ($buf[1]==12 && $buf[3]=='AM'? 0:$buf[1]);
            return $hora . ':' . $buf[2] . ':00';
        }else{
            return NULL;
        }
    }
    */
    function dieMsg($json)
    {
        echo json_encode($json);
        exit;
    }
    function get_detaVenta($vent_id)
    {
        $this->db->from("venta_detalle");
        $this->db->join("producto", "prod_id = deta_prod_id");
        $this->db->where("deta_vent_id", $vent_id);
        return $this->db->get()->result();
    }
    public function encaja($_vTipo, $_vTipoId, $_vMoneda, $_vMonto, $_vCuenta, $_vDescripcion, $_vUsuaId, $obs)
    {

        $vIngreso = 0.00;
        $vEgreso = 0.00;

        if ($_vTipo == 'INGRESO') {
            $vIngreso = $_vMonto;
            $this->db->query("UPDATE cuenta SET cuen_saldo=cuen_saldo+{$_vMonto} WHERE cuen_id={$_vCuenta}");
        } else {
            $vEgreso = $_vMonto;
            $this->db->query("UPDATE cuenta SET cuen_saldo=cuen_saldo-{$_vMonto} WHERE cuen_id={$_vCuenta}");
        }

        $consulta = $this->db->query("SELECT cuen_saldo,cuen_moneda FROM cuenta WHERE cuen_id=$_vCuenta")->row();
        $vSaldo = $consulta->cuen_saldo;
        if ($_vMoneda == "")
            $vMoneda = $consulta->cuen_moneda;
        else
            $vMoneda = $_vMoneda;

        $data = array(
            "movi_cuen_id" => $_vCuenta,
            "movi_tipo_id" => $_vTipoId,
            "movi_tipo" => $_vTipo,
            "movi_fechareg" => date('Y-m-d H:i:s'),
            "movi_descripcion" => $_vDescripcion,
            "movi_monto" => $_vMonto,
            "movi_ingreso" => $vIngreso,
            "movi_egreso" => $vEgreso,
            "movi_saldo" => $vSaldo,
            "movi_moneda" => $vMoneda,
            "movi_usua_id" => $_vUsuaId,
            "movi_obs" => $obs
        );
        $this->db->set($data);
        $this->db->insert("cuenta_movimiento");
    }
    public function afectar_almacen($prod_id, $costo, $precio, $cantidad, $accion, $sucursal, $tipocomp, $serie, $numero, $tipooper, $descripcion)
    {

        $signo = ($accion == "INGRESO") ? "+" : "-";
        $fechass = ($accion == "INGRESO") ? "stoc_reg_fingreso" : "stoc_reg_fsalida";
        $sql = "INSERT INTO stock(stoc_sucu_id,stoc_prod_id,stoc_cantidad,{$fechass}) 
        VALUES ('{$sucursal}','{$prod_id}','{$signo}{$cantidad}',NOW()) 
        ON DUPLICATE KEY UPDATE stoc_cantidad=stoc_cantidad{$signo}({$cantidad}),{$fechass}=NOW();";
        if (!$this->db->query($sql)) return false;

        $old_cantidad = 0;
        $old_costo = 0;
        $old_total = 0;

        $ing_cantidad = 0;
        $ing_costo = 0;
        $ing_total = 0;

        $egr_cantidad = 0;
        $egr_costo = 0;
        $egr_total = 0;

        $sal_cantidad = 0;
        $sal_costo = 0;
        $sal_total = 0;

        $row = $this->db->query("SELECT * FROM kardex_producto WHERE kard_prod_id='{$prod_id}' AND kard_sucu_id='{$sucursal}' ORDER BY kard_id DESC LIMIT 1")->row();
        if (!empty($row->kard_id)) {
            $old_cantidad = $row->kard_sal_cantidad;
            $old_costo = $row->kard_sal_costo;
            $old_total = $row->kard_sal_total;
        }
        /*
        if(in_array($tipooper, array(5,6))){
            if($accion == 'EGRESO'){
                $ing_cantidad = $cantidad*-1;
                $ing_costo = $costo;
                $ing_total = $ing_cantidad*$ing_costo;
                $sal_cantidad = $old_cantidad+$ing_cantidad;
                $sal_costo = ($old_total+$ing_total)/$sal_cantidad;
                $sal_total = $sal_cantidad*$sal_costo;
                $accion = "INGRESO";
            }else{
               $egr_cantidad = $cantidad*-1;
               $egr_costo = $old_costo;
               $egr_total = $egr_cantidad*$egr_costo;
               $sal_cantidad = $old_cantidad-$egr_cantidad;
               $sal_costo = $old_costo;
               $sal_total = $sal_cantidad*$sal_costo;
               $accion = "EGRESO";
            }
        }else{
            if($accion == 'INGRESO'){
                $ing_cantidad = $cantidad;
                $ing_costo = $costo;
                $ing_total = $ing_cantidad*$ing_costo;
                $sal_cantidad = $old_cantidad+$ing_cantidad;
                $sal_costo = ($old_total+$ing_total)/$sal_cantidad;
                $sal_total = $sal_cantidad*$sal_costo;
            }else{
               $egr_cantidad = $cantidad;
               $egr_costo = $old_costo;
               $egr_total = $egr_cantidad*$egr_costo;
               $sal_cantidad = $old_cantidad-$egr_cantidad;
               $sal_costo = $old_costo;
               $sal_total = $sal_cantidad*$sal_costo;
            }
        }
        */
        if ($accion == 'INGRESO') {
            $ing_cantidad = $cantidad;
            $ing_costo = $costo;
            $ing_total = $ing_cantidad * $ing_costo;
            $sal_cantidad = $old_cantidad + $ing_cantidad;
            $sal_costo = ($old_total + $ing_total) / $sal_cantidad;
            $sal_total = $sal_cantidad * $sal_costo;
        } else {
            $egr_cantidad = $cantidad;
            $egr_costo = $old_costo;
            $egr_total = $egr_cantidad * $egr_costo;
            $sal_cantidad = $old_cantidad - $egr_cantidad;
            $sal_costo = $old_costo;
            $sal_total = $sal_cantidad * $sal_costo;
        }
        $kard = array(
            "kard_sucu_id" => $sucursal,
            "kard_tipo" => $accion,
            "kard_comp_id" => $tipocomp,
            "kard_serie" => $serie,
            "kard_numero" => $numero,
            "kard_tipo_id" => $tipooper,
            "kard_prod_id" => $prod_id,

            "kard_ing_cantidad" => $ing_cantidad,
            "kard_ing_costo" => $ing_costo,
            "kard_ing_total" => $ing_total,
            "kard_egr_cantidad" => $egr_cantidad,
            "kard_egr_costo" => $egr_costo,
            "kard_egr_total" => $egr_total,
            "kard_sal_cantidad" => $sal_cantidad,
            "kard_sal_costo" => $sal_costo,
            "kard_sal_total" => $sal_total,

            "kard_old_cantidad" => $old_cantidad,
            "kard_old_costo" => $old_costo,
            "kard_old_total" => $old_total,

            "kard_cantidad" => $cantidad,
            "kard_precio" => $precio,
            "kard_total" => $precio * $cantidad,
            "kard_descripcion" => $descripcion,
            "kard_fechareg" => date('Y-m-d H:i:s'),
            "kard_usuario" => $this->session->userdata('authorized')
        );
        if ($this->save_data("kardex_producto", $kard) != false)
            return true;
        else
            return false;
    }
    public function get_venta($vent_id)
    {


        $this->db->where("vent_id", $vent_id);
        $this->db->from("venta");
        $this->db->join("moneda", "mone_id = vent_moneda");
        $venta = $this->db->get()->row();

        $cobrado = $this->db->query("SELECT SUM(cobr_monto) monto FROM cobros where cobr_vent_id = {$vent_id}")->row();

        $venta->cobrado = ($cobrado->monto != "") ? $cobrado->monto : '0.00';
        $venta->saldo = number_format($venta->vent_total - $venta->cobrado, 2, '.', '');

        return $venta;
    }
    public function getServicioPaquete($paquete)
    {
        $data = $this->db->query("SELECT deta_id id, deta_paqu_id paqu_id, deta_tipo tipo, 
                                            deta_pax pax, deta_paxinf paxinf, deta_paxchd paxchd, deta_tc tc, IF(deta_esturistico = '1', serv_nombre, deta_descripcion) servicio, deta_tiposervicio tiposervicio,
                                            DATE_FORMAT(deta_fecha, '%d/%m/%Y') fecha, 
                                            DATE_FORMAT(deta_hora, '%H:%i') hora, 
                                            deta_precio precio, deta_tipocobro tipocobro, deta_obs obs
                                        FROM paquete_detalle
                                        LEFT JOIN servicio ON serv_id = deta_servicio
                                        WHERE deta_paqu_id = {$paquete} ORDER BY deta_fecha ASC, deta_hora ASC")->result();
        return $data;
    }
    public function getServicesBiblia($date, $tipo, $paqu_id)
    {
        $query = $this->db->query(
            "SELECT 
                deta_paqu_id paqu_id,
                deta_fecha,
                paqu_clie_rsocial agencia,
                paqu_nombre nombre,
                deta_pax pax,
                deta_paxchd paxchd,
                deta_paxinf paxinf,
                deta_tc tc,
                CONCAT(COALESCE(serv_nombre, ''), ' ', deta_obs) detalle,
                DATE_FORMAT(deta_hora, '%H:%i') hora,
                paqu_hotel_nombre hotel,
                deta_id id,
                color,
                texto,
                deta_tiposervicio stipo,
                CONCAT(paqu_datenum, '-', paqu_numero) file
            FROM
                paquete_detalle
                    LEFT JOIN
                paquete ON paqu_id = deta_paqu_id
                    LEFT JOIN
                servicio ON serv_id = deta_servicio
                    LEFT JOIN
                paquete_detalle_estado ON id = deta_estado
            WHERE
            (deta_fecha = '{$date}' or deta_paqu_id = '{$paqu_id}') AND deta_tipo = '{$tipo}'
            ORDER BY deta_hora ASC"
        );
        return $query->result();
    }
    public function getServicesFile($id)
    {
        $this->db->select("paqu_clie_rsocial agencia, paqu_nombre nombre, deta_pax pax, 
                            deta_paxchd paxchd, deta_paxinf paxinf, deta_tc tc, CONCAT(coalesce(serv_nombre,''),' ',deta_obs) detalle, DATE_FORMAT(deta_hora, '%H:%i') hora, 
                            DATE_FORMAT(deta_fecha, '%d/%m/%Y') fecha, paqu_hotel_nombre hotel, deta_id id, color, texto, deta_tiposervicio stipo, serv_nombre servicio");
        $this->db->from("paquete_detalle");
        $this->db->join("paquete", "paqu_id = deta_paqu_id");
        $this->db->join("servicio", "serv_id = deta_servicio", 'left');
        $this->db->join("paquete_detalle_estado", "id = deta_estado");
        $this->db->where("paqu_estado", 'VIGENTE');
        $this->db->where("paqu_id", $id);
        $this->db->order_by("deta_fecha", "asc");
        $this->db->order_by("deta_hora", "asc");
        return $this->db->get()->result();
    }
    public function getDetasOrdenServicio($orden = '')
    {
        $this->db->select("*, DATE_FORMAT(osdet_fecha,'%d/%m/%Y') osdet_fecha");
        $this->db->where("osdet_oserv_id", $orden);
        $this->db->from("ordserv_detalle");
        return $this->db->get()->result();
    }
    public function getRecordatorios()
    {
        $this->db->select("COUNT(*) cantidad");
        $this->db->from("recordatorios");
        $this->db->where("fecha >= ", date('Y-m-d'));
        $cantidad = $this->db->get()->row();

        if ($cantidad->cantidad > 0)
            return $cantidad->cantidad;
        else
            return 0;
    }
    public function getProveedoresServicio($deta_id, $tipo)
    {
        $proveedores = $this->db->query("SELECT GROUP_CONCAT(CONCAT(oser_datenum,'-',oser_numero,' | ',oser_prov_rsocial) SEPARATOR '<br>') proveedores
                                            FROM orden_servicio
                                            WHERE oser_id IN (SELECT osdet_oserv_id 
                                                                FROM ordserv_detalle
                                                                WHERE osdet_pdeta_id = {$deta_id})
                                            AND oser_prov_tipo IN ({$tipo})
                                            LIMIT 1");
        return $proveedores->num_rows() > 0 ? $proveedores->row()->proveedores : "";
    }
    public function getProveedoresServicio2($deta_id, $tipo)
    {
        $proveedores = $this->db->query("SELECT GROUP_CONCAT(CONCAT(oser_prov_rsocial) SEPARATOR '<br>') proveedores
                                            FROM orden_servicio
                                            WHERE oser_id IN (SELECT osdet_oserv_id 
                                                                FROM ordserv_detalle
                                                                WHERE osdet_pdeta_id = {$deta_id})
                                            AND oser_prov_tipo IN ({$tipo})
                                            LIMIT 1");
        return $proveedores->num_rows() > 0 ? $proveedores->row()->proveedores : "";
    }


    public function getPagoAlumno($id)
    {
        $sql = "SELECT pagos.*, nombre, pers_nombres, pers_apellidos, pers_celular, pers_dni
                FROM pagos
                JOIN alumnos ON alumnos_id_alumno = id_alumno
                JOIN productos ON id = productos_id
                JOIN personas ON pers_id = alum_pers_id
                WHERE idpagos = $id";

        $res = $this->db->query($sql)->row();

        if ($res) {

            $resultado['alumno'] = (object)  [
                'nombre' => $res->pers_nombres,
                'apellidos' => $res->pers_apellidos,
                'dni' => $res->pers_dni,
                'celular' => $res->pers_celular
            ];
            $resultado['pago'] = (object) [
                'producto' => $res->nombre,
                'fecha' => $res->fechapago,
                'monto' => $res->monto,
                'id_pago' => $res->idpagos
            ];

            return $resultado;
        }

        return false;
    }

    public function getPagosAlumnos($ids_where) //de varios alumnos
    {

        $sql = "SELECT pers_nombres, pers_apellidos, pers_celular, pers_dni,
                group_concat(nombre ORDER BY idpagos ASC) AS productos,
                group_concat(monto ORDER BY idpagos ASC) AS importes,
                group_concat(fechapago ORDER BY idpagos ASC) AS fechas,
                group_concat(idpagos ORDER BY idpagos ASC) AS idpagos
                FROM pagos
                JOIN alumnos ON alumnos_id_alumno = id_alumno
                JOIN productos ON id = productos_id
                JOIN personas ON pers_id = alum_pers_id
                WHERE idpagos = $ids_where
                GROUP BY pers_dni, pers_nombres, pers_apellidos, pers_celular
                ORDER BY pers_dni ASC";

        $res = $this->db->query($sql)->result();

        if ($res) {
            return $res;
        }

        return false;
    }

    

    public function comprobantePdfA4($datos, $titulo = '')
    {
        $titulo = 'NOTA DE PAGO';
        $alumno = $datos['alumno'];
        $pago = $datos['pago'];


        $pdf = new FPDF();

        $this->templatePdfA4($pdf);
        $pdf->SetTitle('COMPROBANTE DE PAGO', 1);
        $pdf->SetY(40); // Despues del header
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'U', 14);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(200, 8, utf8_decode($titulo), 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(110, 5, utf8_decode('Cliente: ' . $alumno->nombre . ' ' . $alumno->apellidos), 0, 'L');
        $pdf->Cell(45, 5, utf8_decode('DNI: ' . $alumno->dni), 0, 'L');
        $pdf->Cell(45, 5, utf8_decode('Celular: ' . $alumno->celular), 0, 'R');

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', '', 10);
        //HEADER TABLE
        $pdf->SetTextColor(240, 240, 240);
        $pdf->SetFillColor(45, 45, 100);
        $pdf->Cell(15, 7, utf8_decode('ID'), 1, 0, 'C', 1, '');
        $pdf->Cell(100, 7, utf8_decode('PRODUCTO'), 1, 0, 'C', 1, '');
        $pdf->Cell(35, 7, utf8_decode('FECHA DE PAGO'), 1, 0, 'C', 1, '');
        $pdf->Cell(40, 7, utf8_decode('MONTO'), 1, 0, 'C', 1, '');

        //HEADER BODY
        $pdf->Ln(7);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(15, 6, utf8_decode($pago->id_pago), 1, 0, 'C', 0, '');
        $pdf->Cell(100, 6, utf8_decode($pago->producto), 1, 0, 'C', 0, '');
        $pdf->Cell(35, 6, utf8_decode($pago->fecha), 1, 0, 'C', 0, '');
        $pdf->Cell(40, 6, utf8_decode('S/. ' . $pago->monto), 1, 0, 'C', 0, '');
        $pdf->Ln(4);

        $pdf->Cell(150, 6, '', 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, '-------------------', 0, 0, 'C', 0, '');
        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(150, 6, utf8_decode('TOTAL:'), 0, 0, 'R', 0, '');
        $pdf->Cell(40, 6, 'S/. ' . number_format($pago->monto, 2), 0, 0, 'C', 0, '');


        $pdf->Output();
    }

    public function templatePdfA4($pdf)
    {

        $empresa = $this->db->where("empr_id", 1)->get("empresa")->row();

        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 17);
        $pdf->Cell(100, 5, utf8_decode($empresa->empr_nombre), 0, 'L');
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Ln(5);
        $pdf->Cell(80, 5, 'RUC: ' . utf8_decode($empresa->empr_ruc), 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(80, 5, utf8_decode($empresa->empr_ubicacion), 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(80, 5, utf8_decode('Teléfono: ') . utf8_decode($empresa->empr_ruc), 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(80, 5, utf8_decode('Email: ') . utf8_decode($empresa->empr_correo), 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(80, 5, utf8_decode('Web: ') . utf8_decode($empresa->empr_social), 0, 0, 'L');

        $pdf->Image("assets/img/$empresa->empr_logo", 160, 5, 30, 30);
        $pdf->Ln(8);

        $pdf->Cell(65);
        $pdf->Line(10, 40, 200, 40);

        /** Footer */
        $pdf->SetY(268);
        // Select Arial italic 8
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Line(10, 268, 200, 268);
        $pdf->Ln(2);
        // Print centered page number
        $pdf->Cell(60, 5, utf8_decode($empresa->empr_nombre), 0, 'L');
        $pdf->Cell(130, 5, 'Pagina ' . $pdf->PageNo(), 0, 0, 'R');
    }

    public function comprobanteTicket($datos, $titulo = '')
    {
        $titulo = 'NOTA DE PAGO';
        $alumno = $datos['alumno'];
        $pago = $datos['pago'];

        $pdf = new FPDF('P', 'mm', array(80, 150));

        $pdf->SetTitle('TICKET DE PAGO', 1);
        $this->templateTicket($pdf);

        $pdf->SetFont('Courier', 'I', 8);

        $pdf->Cell(60, 5, utf8_decode('Fecha: ' . $pago->fecha), 0, 0, 'C');

        $pdf->SetFont('Courier', 'B', 10);

        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode('Ticket - ' . $pago->id_pago), 0, 0, 'C');

        $pdf->Ln(6);
        $pdf->SetFont('Courier', '', 8);

        $pdf->Cell(60, 5, utf8_decode('Cliente: ' . $alumno->nombre . ' ' . $alumno->apellidos), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode('DNI: ' . $alumno->dni), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode('Celular: ' . $alumno->celular), 0, 0, 'C');


        $pdf->Ln(5);
        $pdf->Cell(60, 5, '------------------------------', 0, 0, 'C');

        $pdf->SetFont('Courier', 'B', 8);
        $pdf->Cell(60, 5, utf8_decode('Curso: '), 0, 0, 'C'); //producto
        $pdf->Ln(5);
        $pdf->SetFont('Courier', '', 8);
        $pdf->Cell(60, 5, utf8_decode($pago->producto), 0, 0, 'C'); //producto

        $pdf->Ln(5);
        $pdf->SetFont('Courier', 'B', 8);
        $pdf->Cell(60, 5, utf8_decode('IMPORTE'), 0, 0, 'C'); //producto
        $pdf->Ln(5);
        $pdf->SetFont('Courier', '', 8);
        $pdf->Cell(60, 5, utf8_decode('S/. ' . $pago->monto), 0, 0, 'C'); //producto
        $pdf->Output();
    }

    public function templateTicket($pdf)
    {        
        $empresa = $this->db->where("empr_id", 1)->get("empresa")->row();
        $pdf->AddPage();
        $pdf->Image("assets/img/$empresa->empr_logo", 28, 5, 27, 27);
        $pdf->Ln(28);
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->MultiCell(60, 2, utf8_decode($empresa->empr_nombre), 0, 'C', 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Courier', '', 8);
        $pdf->Ln(5);
        $pdf->Cell(60, 5, 'RUC: ' . utf8_decode($empresa->empr_ruc), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode($empresa->empr_ubicacion), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode('Teléfono: ') . utf8_decode($empresa->empr_ruc), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode('Email: ') . utf8_decode($empresa->empr_correo), 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, utf8_decode($empresa->empr_social), 0, 0, 'C');

        $pdf->Ln(5);
        $pdf->Cell(60, 5, '------------------------------', 0, 0, 'C');

    }

    public function errorPDF()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 20);
        $pdf->MultiCell(190, 10, utf8_decode('ERROR AL GENERAR EL PDF'), 0, 'C', 0);

        $pdf->Output();
    }

    public function s2GetCategorias()
    {
        $responese = new StdClass;
        $term = isset($_GET['term']) ? $_GET['term'] : '';
        $datos = array();
        $like =
            [
                'cate_nombre' => $term,
            ];

        $results = $this->general->select2("categorias", $like, null, null);

        foreach ($results["items"] as $value) {
            $datos[] = array(
                "id" => $value->cate_id,
                "text" => $value->cate_nombre
            );
        }
        $responese->total_count = $results["total_count"];
        $responese->incomplete_results = false;
        $responese->items = $datos;
        echo json_encode($responese);
    }
}
