<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu
{
	var $menu;
	function __construct()
	{
	}
	public function init()
	{
		if ($_SESSION['usua_tipo'] == 1) {
			$this->menu[] = array(
				"name" => "Ventas",
				"icon" => "dollar",
				"url" => "Ventas/lista",
				"add" => array(
					array(
						"name" => "Ventas",
						"icon" => "dot-circle",
						"url" => "Ventas/lista",
					),
					array(
						"name" => "Llamadas",
						"icon" => "dot-circle",
						"url" => "Llamadas/lista",
					),
				)
			);
		}


		if ($_SESSION['usua_tipo'] == 2) {
			$this->menu[] = array(
				"name" => "Ventas",
				"icon" => "dollar",
				"url" => "Ventas/lista",
				"add" => array(
					array(
						"name" => "Ventas",
						"icon" => "dot-circle",
						"url" => "Ventas/lista",
					),
					array(
						"name" => "Llamadas",
						"icon" => "dot-circle",
						"url" => "Llamadas/lista",
					),

					array(
						"name" => "Ventas por Vendedor",
						"icon" => "dot-circle",
						"url" => "Ventas/VentasVendedor",
					),
				)
			);
		}

		if ($_SESSION['usua_tipo'] == 7) {
			$this->menu[] = array(
				"name" => "Ventas",
				"icon" => "dollar",
				"url" => "Ventas/lista",
				"add" => array(
					array(
						"name" => "Ventas",
						"icon" => "dot-circle",
						"url" => "Ventas/lista",
					),
					array(
						"name" => "Mis Ventas",
						"icon" => "dot-circle",
						"url" => "Ventas/miLista",
					),
					array(
						"name" => "Llamadas",
						"icon" => "dot-circle",
						"url" => "Llamadas/lista",
					),
				)
			);
		}

		if ($_SESSION['usua_tipo'] == 2 || $_SESSION['usua_tipo'] == 7) {
			$this->menu[] = array(
				"name" => "Atención al cliente",
				"icon" => "book",
				"url" => "",
				"add" => array(
					array(
						"name" => "Ventas",
						"icon" => "dot-circle",
						"url" => "Ventas/lista",
					),
					array(
						"name" => "Asignar grupos",
						"icon" => "dot-circle",
						"url" => "grupos/grupos_alumnos",
					),
					array(
						"name" => "Pagos Alumnos",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/alumnos",
					),
					array(
						"name" => "Lista de alumnos",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/listarAlumnos",
					)
				)
			);
		}

		if ($_SESSION['usua_tipo'] == 2 || $_SESSION['usua_tipo'] == 4 || $_SESSION['usua_tipo'] == 7) {
			$this->menu[] = array(
				"name" => "Cobranza",
				"icon" => "fas fa-cash-register",
				"url" => "",
				"add" => array(
					array(
						"name" => "Pagos Alumnos",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/alumnos",
					),
					array(
						"name" => "Editar Pago",
						"icon" => "dot-circle",
						"url" => "Contabilidad/ingresos",
					)
				)
			);
		}

		if ($_SESSION['usua_tipo'] == 3) {
			$this->menu[] = array(
				"name" => "Atención al cliente",
				"icon" => "book",
				"url" => "",
				"add" => array(
					array(
						"name" => "Ventas",
						"icon" => "dot-circle",
						"url" => "Ventas/lista",
					),
					array(
						"name" => "Asignar grupos",
						"icon" => "dot-circle",
						"url" => "grupos/grupos_alumnos",
					),
					array(
						"name" => "Pagos Alumnos",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/alumnos",
					),

					array(
						"name" => "Lista de alumnos",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/listarAlumnos",
					)
				)
			);
		}

		if ($_SESSION['usua_tipo'] == 2 || $_SESSION['usua_tipo'] == 7) {
			$this->menu[] = array(
				"name" => "Grupos",
				"icon" => "group",
				"url" => "grupos",
				"add" => array(
					array(
						"name" => "Listar grupos",
						"icon" => "dot-circle",
						"url" => "grupos/lista",
					),
					array(
						"name" => "Asignar grupos",
						"icon" => "dot-circle",
						"url" => "grupos/grupos_alumnos",
					),
				)
			);
		}


		if ($_SESSION['usua_tipo'] == 2 || $_SESSION['usua_tipo'] == 5 || $_SESSION['usua_tipo'] == 7) {
			$this->menu[] = array(
				"name" => "Area academica",
				"icon" => "folder",
				"url" => "AreaAcademica/alumnos",
				"add" => array(
					array(
						"name" => "Asistencia",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/asistencia",
					),
					array(
						"name" => "Notas",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/notas",
					),
					array(
						"name" => "Pagos de alumnos",
						"icon" => "dot-circle",
						"url" => "AreaAcademica/alumnos",
					)
				)
			);
		}
		if ($_SESSION['usua_tipo'] == 2 || $_SESSION['usua_tipo'] == 6) {
			$this->menu[] = array(
				"name" => "Contabilidad",
				"icon" => "money",
				"url" => "Contabilidad/ingresos",
				"add" => array(
					array(
						"name" => "Flujo de Caja",
						"icon" => "dot-circle",
						"url" => "Contabilidad/flujo_caja",
					),
					array(
						"name" => "Pago a Personal",
						"icon" => "dot-circle",
						"url" => "Contabilidad/pagopersonal",
					),

					array(
						"name" => "Reporte de Ade-Desc",
						"icon" => "dot-circle",
						"url" => "Contabilidad/adelantoDescuento",
					),

					array(
						"name" => "Reporte de Pagos Alumnos",
						"icon" => "dot-circle",
						"url" => "Contabilidad/ingresos",
					),
					array(
						"name" => "Reporte de Pago Personal",
						"icon" => "dot-circle",
						"url" => "Contabilidad/reportepagos",
					),
					array(
						"name" => "Resumen Mensual",
						"icon" => "dot-circle",
						"url" => "Contabilidad/resumenmensual",
					),
					array(
						"name" => "Resumen Anual",
						"icon" => "dot-circle",
						"url" => "Contabilidad/resumenanual",
					),
					array(
						"name" => "Resumen General",
						"icon" => "dot-circle",
						"url" => "Contabilidad/resumenGeneral",
					)

					
				)
			);
		};


		if ($_SESSION['usua_tipo'] == 2) {
			$this->menu[] = array(
				"name" => "Usuarios",
				"icon" => "user",
				"url" => "usuario/listado",
				"add" =>
				[
					[
						"name" => "Listado",
						"icon" => "dot-circle",
						"url" => "usuario/listado",
					],
					[
						"name" => "Ventas",
						"icon" => "dot-circle",
						"url" => "usuario/vendedores",
					]
				]
			);
		};

		if ($_SESSION['usua_tipo'] == 2) {
			$this->menu[] = array(
				"name" => "Configuracion",
				"icon" => "cog",
				"url" => "configuracion/",
				"add" => array(
					array(
						"name" => "Empresa",
						"icon" => "dot-circle",
						"url" => "configuracion/empresa/",
					),

					array(
						"name" => "Categorias",
						"icon" => "dot-circle",
						"url" => "configuracion/categorias",
					),
					array(
						"name" => "Productos",
						"icon" => "dot-circle",
						"url" => "configuracion/producto",
					),
					array(
						"name" => "Rubros",
						"icon" => "dot-circle",
						"url" => "configuracion/rubro",
					),
					array(
						"name" => "Rubro de Gasto",
						"icon" => "dot-circle",
						"url" => "configuracion/rubrogasto",
					),
					array(
						"name" => "Tipo de alumnos",
						"icon" => "dot-circle",
						"url" => "configuracion/tipo_alumno",
					),
					


					// array(
					// 	"name" => "llamadas",
					// 	"icon" => "circle-o",
					// 	"url" => "configuracion/llamadas",
					// ),

				)
			);
		}
	}
	public function getMenu()
	{
		$this->init();
		return $this->menu;
	}
}
