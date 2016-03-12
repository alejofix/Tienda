<?php
	
	class Alimentar extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Genera el formulario de la busqueda de la referencia
		 */
		public function Index() {
			$Registro = new NeuralJQueryFormularioValidacion;
			$Registro->Requerido('Registro', 'Ingrese el Número de Referencia');
			$Registro->Numero('Registro');
			$Registro->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
				->Datos('#BuscarRegistro')
				->TipoEnvio('POST')
				->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Alimentar', 'BuscarInventario'))
				->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#CargarContenido', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
				->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#CargarContenido', 'respuesta'))
			);
			
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			$Plantilla->Parametro('Script', $Registro->Constructor('BuscarRegistro'));
			echo $Plantilla->MostrarPlantilla('Inventario/Alimentar_Formulario.html');
		}
		
		/**
		 * Metodo Publico
		 * BuscarInventario
		 * 
		 * Genera el formulario para ingresar la cantidad correspondiente de inventario
		 */
		public function BuscarInventario() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				if(AppValidar::Vacio()->Numero(array('Registro'))->MatrizDatos($DatosPost) == true) {
					
					$Validacion = new NeuralJQueryFormularioValidacion;
					$Validacion->Requerido('Cantidad', 'Ingrese la Cantidad Correspondiente');
					$Validacion->Numero('Cantidad');
					$Validacion->ControlEnvio(
						NeuralJQueryAjaxConstructor::TipoDatos('html')
						->Datos('#FormActualizacion')
						->TipoEnvio('POST')
						->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Alimentar', 'ProcesarActualizarInventario'))
						->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#DataCarga', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
						->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#DataCarga', 'respuesta'))
					);
					
					$Plantilla = new NeuralPlantillasTwig('Tienda');
					$Plantilla->Parametro('Consulta', $this->Modelo->BuscarInventario($DatosPost['Registro']));
					$Plantilla->Parametro('Script', $Validacion->Constructor('FormActualizacion'));
					echo $Plantilla->MostrarPlantilla('Inventario/Alimentar_Inventario_Form.html');
				}
				else {
					//Plantilla Formulario Vacio
					echo 'Plantilla Formulario Vacio';
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ProcesarActualizarInventario()
		 * 
		 * Genera el proceso de alimentacion de inventario
		 */
		public function ProcesarActualizarInventario() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				if(AppValidar::Vacio()->Numero()->MatrizDatos($_POST) == true) {
					$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
					$Info = AppSesion::ObtenerDatosUsuario();
					$this->Modelo->ProcesarActualizarInventario($DatosPost['Cantidad'], $DatosPost['Id'], $Info['Usuario']);
					
					$Plantilla = new NeuralPlantillasTwig('Tienda');
					echo $Plantilla->MostrarPlantilla('Inventario/MensajeActualizado.html');
				}
				else {
					//Plantilla Formulario vacio y/o Campo no numerico
					echo 'Plantilla Formulario vacio y/o Campo no numerico';
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}