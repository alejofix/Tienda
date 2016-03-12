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
		 * Muestra el formulario correspondiente para agregar nuevo inventario
		 */
		public function Index() {
			
			$Validacion = new NeuralJQueryFormularioValidacion;
			$Validacion->Requerido('Referencia', 'Ingrese la Referencia Correspondiente');
			$Validacion->Numero('Referencia', 'La Referencia es un Código Númerico');
			$Validacion->Remoto('Referencia', NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Nuevo', 'ConsultarReferencia'), 'POST', false, 'Ya Existe La Referencia en la Base de Datos');
			$Validacion->Requerido('Proveedor', 'Seleccione Un Proveedor');
			$Validacion->Requerido('Categoria');
			$Validacion->Requerido('SubCategoria', 'Ingrese La SubCategoria Correspondiente');
			$Validacion->Requerido('Valor_Minimo', 'Ingrese el Valor Minimo');
			$Validacion->Numero('Valor_Minimo');
			$Validacion->Requerido('Valor_bodega', 'Ingrese el Valor de Bodega');
			$Validacion->Numero('Valor_bodega');
			$Validacion->Requerido('Valor_Almacen', 'Ingrese el Valor de Venta Total');
			$Validacion->Numero('Valor_Almacen');
			$Validacion->ControlEnvio(
				NeuralJQueryAjaxConstructor::TipoDatos('html')
				->Datos('#Form')
				->TipoEnvio('POST')
				->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Nuevo', 'ProcesarNuevoInventario'))
				->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
				->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form', 'respuesta'))
			);

			$Plantilla = new NeuralPlantillasTwig('Tienda');
			$Plantilla->Parametro('Script', $Validacion->Constructor('Form'));
			$Plantilla->Parametro('SeleccionarCategoria', NeuralJQueryConstructor::Inicializar(true)->DocumentoListo()->AccionClick('#SeleccionarCategoria')->AccionAparecer('#CargarCategoria', 400, true)->AccionCargar('#CargarCategoria', NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Nuevo', 'Campos'), false, array('Peticion' => 'SeleccionarCategoria'))->Construir());
			$Plantilla->Parametro('NuevaCategoria', NeuralJQueryConstructor::Inicializar(true)->DocumentoListo()->AccionClick('#NuevaCategoria')->AccionAparecer('#CargarCategoria', 400, true)->AccionCargar('#CargarCategoria', NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Nuevo', 'Campos'), false, array('Peticion' => 'NuevaCategoria'))->Construir());
			$Plantilla->Parametro('Proveedores', $this->Modelo->ListadoProveedores());
			echo $Plantilla->MostrarPlantilla('Inventario/Nuevo.html');
		}
		
		/**
		 * Metodo Publico
		 * ConsultarReferencia()
		 * 
		 * Genera la consulta si existe la referencia
		 */
		public function ConsultarReferencia() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				echo $this->Modelo->ConsultarReferencia($DatosPost['Referencia']);
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ProcesarNuevoInventario()
		 * 
		 * Guarda los datos del nuevo inventario
		 */
		public function ProcesarNuevoInventario() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true AND isset($_POST['EnviarNuevoInv']) == true AND $_POST['EnviarNuevoInv'] == 'Enviado') {
				$DatosPost = AppFormato::Espacio()->Mayusculas()->MatrizDatos($_POST);
				unset($_POST, $DatosPost['EnviarNuevoInv']);
				$DatosSession = AppSesion::ObtenerDatosUsuario();
				$this->Modelo->ProcesarNuevoInventario($DatosPost, $DatosSession['Usuario']);
				
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				$Plantilla->Parametro('SubCategoria', $DatosPost['SubCategoria']);
				echo $Plantilla->MostrarPlantilla('Inventario/NuevoAgregado.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * Campos()
		 * 
		 * Peticion Ajax
		 * Muestra los campos categoria
		 */
		public function Campos() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				if($DatosPost['Peticion'] == 'SeleccionarCategoria') {
					$Plantilla->Parametro('Peticion', 'SeleccionarCategoria');
					$Plantilla->Parametro('Consulta', $this->Modelo->SeleccionarCategoria());
				}
				elseif($DatosPost['Peticion'] == 'NuevaCategoria') {
					$Plantilla->Parametro('Peticion', 'NuevaCategoria');
				}
				echo $Plantilla->MostrarPlantilla('Campos.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}