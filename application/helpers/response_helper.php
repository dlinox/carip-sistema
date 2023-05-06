<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


function showError($message = '')
{
    $response = 
    [
        "exito" => false, 
        "mensaje" => $message
    ];

    echo json_encode($response);
    die();
}

function showSuccess($message = '')
{
    $response = 
    [
        "exito" => true, 
        "mensaje" => $message
    ];

    echo json_encode($response);
    die();
}

function showData($data = [])
{
    $response = 
    [
        "exito" => true, 
        "data" => $data
    ];

    echo json_encode($response);
    die();
}

function showJSON($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}