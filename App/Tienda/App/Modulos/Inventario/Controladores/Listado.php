<?php
	
	class Listado extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			$Plantilla->Parametro('Consulta', $this->Modelo->Listado());
			$Plantilla->Filtro('HEX', function ($Parametro) {
				return AppHexAsciiHex::ASCII_HEX($Parametro);
			});
			echo $Plantilla->MostrarPlantilla('Inventario/Listado.html');
		}
		
		public function Informacion($Id = false) {
			if($Id == true AND is_numeric(AppHexAsciiHex::HEX_ASCII($Id)) == true AND is_bool($Id) == false) {
				
				$Validacion = new NeuralJQueryFormularioValidacion;
				$Validacion->Requerido('Id');
				$Validacion->ControlEnvio(
					NeuralJQueryAjaxConstructor::TipoDatos('html')
					->Datos('#FormEditar')
					->TipoEnvio('POST')
					->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Editar', 'ControlEdicion'))
					->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#CargarContenido', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
					->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#CargarContenido', 'respuesta'))
				);
				
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				$Plantilla->Parametro('Consulta', $this->Modelo->Informacion(AppHexAsciiHex::HEX_ASCII($Id)));
				$Plantilla->Funcion('Titulo', function($Columna) {
					$Matriz = $this->Modelo->ListadoMatrizTabla('inventario');
					if(array_key_exists($Columna, $Matriz) == true) {
						return $Matriz[$Columna];
					}
				});
				$Plantilla->Parametro('Script', $Validacion->Constructor('FormEditar'));
				echo $Plantilla->MostrarPlantilla('Inventario/Ver_Info_Inventario.html');
				
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}