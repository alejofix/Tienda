<?php
	
	class Actualizar extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Metodo Primario
		 * redireccion a central
		 */
		public function Index() {
			header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
			exit();
		}
		
		/**
		 * Metodo Publico
		 * Proveedor($Parametro = false)
		 * 
		 * Peticion Ajax
		 * Genera el Formulario correspondiente para la actualizacion de datos correspondientes
		 */
		public function Proveedor() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				
				$Validacion = new NeuralJQueryFormularioValidacion;
				$Validacion->Requerido('Nombre', 'Ingrese el Nombre del Proveedor');
				$Validacion->Requerido('Direccion', 'Ingrese la Dirección del Proveedor');
				$Validacion->Requerido('Telefono_1', 'Ingrese el Telefono del Proveedor');
				$Validacion->Numero('Telefono_1', 'El Telefono debe ser Compuesto por Solo Números');
				$Validacion->Requerido('Contacto_1', 'Ingrese el Nombre del Contacto Correspondiente');
				$Validacion->ControlEnvio(
					NeuralJQueryAjaxConstructor::TipoDatos('html')
					->Datos('#Form')
					->TipoEnvio('POST')
					->URL(NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Actualizar', 'ProveedorActualizar'))
					->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
					->FinalizadoEnvio('location.reload();')
				);
				
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				$Plantilla->Parametro('Consulta', $this->Modelo->ConsultaProveedor(AppHexAsciiHex::HEX_ASCII($DatosPost['Parametro'])));
				$Plantilla->Parametro('Script', $Validacion->Constructor('Form'));
				$Plantilla->Filtro('HexASCII', function ($Parametro) { return AppHexAsciiHex::ASCII_HEX($Parametro); });
				echo $Plantilla->MostrarPlantilla('Proveedor/Actualizar.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ProveedorActualizar()
		 * 
		 * Peticion Ajax
		 * Genera el proceso de actualizacion de los datos del proveedor
		 */
		public function ProveedorActualizar() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true AND isset($_POST['ActualizarProveedor']) == true) {
				if(AppValidar::Vacio(array('Nombre', 'Direccion', 'Telefono_1', 'Contacto_1')) == true) {
					$DatosPost = AppFormato::Espacio()->Mayusculas(array('Nombre', 'Direccion', 'Telefono_1', 'Contacto_1'))->MatrizDatos($_POST);
					$Id = AppHexAsciiHex::HEX_ASCII($DatosPost['Data']);
					unset($_POST, $DatosPost['ActualizarProveedor'], $DatosPost['Data']);
					$this->Modelo->ProveedorActualizar($DatosPost, $Id);
				}
				else {
					//Plantilla Formulario con Campos Vacios
					echo 'Plantilla Formulario con Campos Vacios';
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}