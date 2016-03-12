<?php
	
	class Nuevo extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Genera el formulario para agregar un nuevo proveedor
		 */
		public function Index() {
			$Validacion = new NeuralJQueryFormularioValidacion;
			$Validacion->Requerido('Nombre', 'Ingrese el Nombre del Proveedor');
			$Validacion->Remoto('Nombre', NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Nuevo', 'ConsultarProveedor'), 'POST', false, 'El Proveedor Ya Existe en la Base de Datos');
			$Validacion->Requerido('Direccion', 'Ingrese la Dirección del Proveedor');
			$Validacion->Requerido('Telefono_1', 'Ingrese el Telefono del Proveedor');
			$Validacion->Numero('Telefono_1', 'El Telefono debe ser Compuesto por Solo Números');
			$Validacion->Requerido('Contacto_1', 'Ingrese el Nombre del Contacto Correspondiente');
			$Validacion->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
				->Datos('#Form')
				->TipoEnvio('POST')
				->URL(NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Nuevo', 'ProcesarAgregarNuevoProveedor'))
				->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
				->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form', 'respuesta'))
			);
			
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			$Plantilla->Parametro('Script', $Validacion->Constructor('Form'));
			echo $Plantilla->MostrarPlantilla('ProveedorNuevo/FormularioNuevo.html');
		}
		
		/**
		 * Metodo Publico Ajax
		 * ProcesarAgregarNuevoProveedor()
		 * 
		 * Genera la validacion de la peticion ajax
		 * guarda los datos correspondiente
		 */
		public function ProcesarAgregarNuevoProveedor() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true AND isset($_POST['GuardarProveedor']) == true) {
				if(AppValidar::Vacio(array('Nombre', 'Direccion', 'Telefono_1', 'Contacto_1')) == true) {
					$DatosPost = AppFormato::Espacio()->Mayusculas(array('Nombre', 'Direccion', 'Telefono_1', 'Contacto_1'))->MatrizDatos($_POST);
					unset($_POST, $DatosPost['GuardarProveedor']);
					$Usuario = AppSesion::ObtenerDatosUsuario();
					$this->Modelo->ProcesarAgregarNuevoProveedor($DatosPost, $Usuario['Usuario']);
					
					$Plantilla = new NeuralPlantillasTwig('Tienda');
					echo $Plantilla->MostrarPlantilla('ProveedorMensajes/ProveedorAgregado.html');
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
		
		/**
		 * Metodo Publico
		 * ConsultarProveedor()
		 * 
		 * Validacion Peticion Ajax
		 * Consulta el nombre del proveedor correspondiente
		 */
		public function ConsultarProveedor() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->Mayusculas()->MatrizDatos($_POST);
				if(strlen($DatosPost['Nombre']) >= 3) {
					echo $this->Modelo->ConsultarProveedor($DatosPost['Nombre']);
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}