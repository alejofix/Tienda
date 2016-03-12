<?php
	
	class Eliminar extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Redirecciona al inicio de la aplicacion
		 */
		public function Index() {
			header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
			exit();
		}
		
		/**
		 * Metodo Publico
		 * Proveedor()
		 * 
		 * Muestra el formulario de eliminacion del proveedor
		 */
		public function Proveedor() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				$Validacion = new NeuralJQueryFormularioValidacion;
				$Validacion->Requerido('Data');
				$Validacion->ControlEnvio(
					NeuralJQueryAjaxConstructor::TipoDatos('html')
					->Datos('#Form')
					->TipoEnvio('POST')
					->URL(NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Eliminar', 'ProveedorEliminar'))
					->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
					->FinalizadoEnvio('location.href="'.NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Listado').'";')
				);				
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				$Plantilla->Parametro('Consulta', $this->Modelo->ProveedorConsulta(AppHexAsciiHex::HEX_ASCII($DatosPost['Parametro'])));
				$Plantilla->Filtro('HexASCII', function ($Parametro) { return AppHexAsciiHex::ASCII_HEX($Parametro); });
				$Plantilla->Parametro('Script', $Validacion->Constructor('Form'));
				echo $Plantilla->MostrarPlantilla('Proveedor/Eliminar.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ProveedorEliminar()
		 * 
		 * Genera el proceso de actualizacion a inactivo el proveedor
		 */
		public function ProveedorEliminar() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				$Data = AppHexAsciiHex::HEX_ASCII($DatosPost['Data']);
				if(is_numeric($Data) == true) {
					$this->Modelo->ProveedorEliminar($Data);
					Ayudas::print_r($_POST);
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}