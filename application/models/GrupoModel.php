<?php
class GrupoModel extends CI_Model
{
    static $table = 'grupos';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PeriodoModel', 'periodoModel');
        $this->load->model('ProductoModel', 'productoModel');
    }

    public function getById($id)
    {
        $this->db->from(self::$table);
        $this->db->join('periodos', 'grup_peri_id = peri_id');
        $this->db->join('productos', 'peri_prod_id = id');
        $this->db->where(['grup_id' => $id]);
        return $this->db->get()->row();
    }

    public function save($data_grupo, $data_periodo)
    {
        $this->db->trans_start();

        if (is_array($data_periodo)) {
            $periodo_id = $this->periodoModel->save($data_periodo);
            if ($periodo_id == false) {
                $this->db->trans_rollback();
                return false;
            }
        } else {
            $periodo_id = $data_periodo;
        }

        $data_grupo['grup_peri_id'] = $periodo_id;
        $data_grupo['grup_correlativo'] = $this->getSiguiente($periodo_id);
        $grupo_id = $this->general->save_data("grupos", $data_grupo);
        if ($grupo_id == false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_complete();
        return $grupo_id;
    }

    public function update($data_grupo, $data_periodo, $condition)
    {
        $data_grupo['grup_peri_id'] = $data_periodo;
        $grupo_id = $this->general->update_data("grupos", $data_grupo, $condition);
        if ($grupo_id == false) {
            return false;
        }
        return $grupo_id;
    }

    public function getSiguiente($peri_id)
    {
        $this->db->select('grup_correlativo AS actual');
        $this->db->from(self::$table);
        $this->db->where(['grup_peri_id' => $peri_id]);
        $this->db->order_by('grup_correlativo', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row();
        if (isset($row->actual)) {
            return ++$row->actual;
        } else {
            return 'A';
        }
    }

    public function getByPeriodoId($id, $docente_id = 0)
    {
        $this->db->select('grup_id AS id, grup_nombre AS label');
        $this->db->from(self::$table);
        $this->db->order_by('grup_correlativo', 'ASC');
        $this->db->where(['grup_peri_id' => $id]);
        if ($docente_id) {
            $this->db->where(['grup_docente_id' => $docente_id]);
        }
        $results = $this->db->get()->result();
        return $results;
    }

    public function getAlumnosInfo($grup_id)
    {
        $this->db->select('id_alumno AS alum_id, pers_nombres AS nombres, pers_apellidos AS apellidos, pers_dni AS dni, gral_asistencias AS asistencias, gral_notas AS notas, cuotas AS cuotas');
        $this->db->from('grupos_alumnos');
        $this->db->join('alumnos', 'gral_id_alumno = id_alumno');
        $this->db->join('personas', 'alum_pers_id = pers_id');
        $this->db->where(['gral_grup_id' => $grup_id]);
        $this->db->order_by('pers_apellidos', 'ASC');
        $this->db->order_by('pers_nombres', 'ASC');
        $alumnos = $this->db->get()->result();

        $producto = $this->productoModel->getByGrupo($grup_id);
        return compact('alumnos', 'producto');
    }


    public function getAlumnosInfoNotas($nivel_id, $grup_id)
    {
        $this->db->select('
        AL.id_alumno AS alum_id, P.pers_nombres AS nombres, P.pers_apellidos AS apellidos, P.pers_dni AS dni,
        GA.gral_asistencias AS asistencias, NT.nota_id as nota_id , NT.nota_notas AS notas, AL.cuotas AS cuotas, N.nive_nombre');
        $this->db->from('notas NT');
        $this->db->join('niveles N', 'N.nive_id = NT.nive_id');
        $this->db->join('grupos G', 'G.grup_id = N.grupo_id');
        $this->db->join('grupos_alumnos GA', 'GA.gral_id = NT.gral_id');
        $this->db->join('alumnos AL', 'GA.gral_id_alumno = AL.id_alumno');
        $this->db->join('personas P', 'AL.alum_pers_id = P.pers_id');
        $this->db->join('periodos PE', 'G.grup_peri_id = PE.peri_id');
        $this->db->join('productos PR', 'PE.peri_prod_id = PR.id ');
        $this->db->where(['N.nive_id' => $nivel_id]);
        $this->db->order_by('P.pers_apellidos', 'ASC');
        $this->db->order_by('P.pers_nombres', 'ASC');
        $alumnos = $this->db->get()->result();

        //echo '<pre>';
        //var_dump($alumnos);
        //echo '</pre>';

        $producto = $this->productoModel->getByGrupo($grup_id);
        return compact('alumnos', 'producto');
    }

    public function getAlumnosInfoPromedios($grup_id)
    {
        $sql = "
        SELECT AL.id_alumno AS alum_id, P.pers_nombres AS nombres, P.pers_apellidos AS apellidos , 
        group_concat(NT.nota_promedio ORDER BY N.nive_id ASC) AS notas,
        group_concat(N.nive_nombre ORDER BY N.nive_id ASC) AS niveles
        FROM notas NT
        JOIN niveles N ON N.nive_id = NT.nive_id
        JOIN grupos G ON G.grup_id = N.grupo_id
        JOIN grupos_alumnos GA ON GA.gral_id = NT.gral_id
        JOIN alumnos AL ON GA.gral_id_alumno = AL.id_alumno
        JOIN personas P ON AL.alum_pers_id = P.pers_id
        WHERE G.grup_id = $grup_id
        GROUP BY P.pers_nombres, P.pers_apellidos, AL.id_alumno
	    ORDER BY P.pers_apellidos ASC, P.pers_nombres ASC
        ";
        $alumnos = $this->db->query($sql)->result();

        $producto = $this->productoModel->getByGrupo($grup_id);
        return compact('alumnos', 'producto');
    }

    public function guardarAsistencias($data)
    {
        $this->db->trans_start();

        foreach ($data as $value) {
            $success = $this->general->update_data('grupos_alumnos', ['gral_asistencias' => $value['asistencias']], ['gral_grup_id' => $value['grup_id'], 'gral_id_alumno' => $value['id_alumno']]);
            if ($success == false) {
                $this->db->trans_rollback();
                return false;
            }
        }
        $this->db->trans_complete();
        return true;
    }

    public function guardarNotas($data)
    {
        $this->db->trans_start();

        foreach ($data as $value) {
            $success = $this->general->update_data(
                'notas',
                ['nota_notas' => $value['notas'], 'nota_promedio' => $value['promedio']],
                ['nota_id' => $value['nota_id']]
            );
            if ($success == false) {
                $this->db->trans_rollback();
                return false;
            }
        }
        $this->db->trans_complete();
        return true;
    }

    public function getAlumnosInfoCertificado($grup_id)
    {
        $this->db->select(
            'id_alumno AS alum_id, 
            pers_nombres AS nombres, 
            pers_apellidos AS apellidos,
            cert_num as codigo,
            cert_prefix as prefix,
            cert_cate_id as categoria,
            cert_menc_id as mencion,
            cert_fecha as fecha'
        );
        $this->db->from('grupos_alumnos');
        $this->db->join('alumnos', 'gral_id_alumno = id_alumno');
        $this->db->join('personas', 'alum_pers_id = pers_id');
        $this->db->join('certificados', 'gral_id_alumno = cert_alum_id', 'left');
        //$this->db->where(['cert_grup_id' => $grup_id]);
        $this->db->where(['gral_grup_id' => $grup_id]);

        $this->db->order_by('pers_apellidos', 'ASC');
        $this->db->order_by('pers_nombres', 'ASC');
        $alumnos = $this->db->get()->result();
        $producto = $this->productoModel->getByGrupo($grup_id);
        return compact('alumnos', 'producto');
    }
}
