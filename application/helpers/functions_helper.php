<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


function getNombreMes($i)
{
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $meses[$i];
}
function THS($arr)
{
    $str = "";
    foreach ($arr as $cod => $val) {
        if (!preg_match('/DT_/', $val['dt']))
            $str .= '<th class="ths">' . $val['dt'] . '</th>';
    }
    return $str;
}

function es($valor)
{
    return $valor != '0.00' && !empty($valor);
}

function genDataTable($id, $columns, $withcheck = false, $responsive = false, $nowrap = 'nowrap')
{
    if ($responsive) $class = "table table-striped table-bordered responsive " . $nowrap;
    else $class = "table table-striped table-bordered";
    return '<table id="' . $id . '" wch="' . $withcheck . '" cellpadding="0" cellspacing="0" border="0" width="100%" class="' . $class . '">
            <thead>
                <tr>
                    ' . ($withcheck ? '<th></th>' : '') . THS($columns) . '
                </tr>
            </thead>
        </table>';
}

function gen_table($id, $columns, $responsive = false)
{
    if ($responsive) $class = "table table-striped table-bordered responsive";
    else $class = "table table-striped table-bordered";
    return '<table id="' . $id . '" class="' . $class . '">
            <thead>
                <tr>
                    ' . split_colums($columns) . '
                </tr>
            </thead>
            <tbody></tbody>
        </table>';
}
function split_colums($columns)
{
    $cols = "";
    foreach ($columns as $cell) {
        $cols .= "<th>" . $cell . "</th>";
    }
    return $cols;
}

function cambiaf_a_mysql($fecha)
{
    if (empty($fecha))
        return '0000-00-00';
    preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})/", $fecha, $mifecha);

    if (empty($mifecha[3]) || empty($mifecha[2]) || empty($mifecha[1]))
        return '0000-00-00';

    $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];

    return $lafecha;
}
