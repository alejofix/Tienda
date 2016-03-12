<?php
	
	class Editar extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		public function Index() {
			header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
			exit();
		}
		
		/**
		 * Metodo Publico
		 * ControlEdicion()
		 * 
		 * Genera el Formulario de validacion ante sde generar la actualizacion
		 * validacion de credenciales del usuario
		 */
		public function ControlEdicion() {
			if(AppValidar::PeticionAjax() == true) {
				if(AppValidar::Vacio()->MatrizDatos($_POST) == true) {
					$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
					
					$Validacion = new NeuralJQueryFormularioValidacion;
					$Validacion->Requerido('Comparacion');
					$Validacion->ControlEnvio(
						NeuralJQueryAjaxConstructor::TipoDatos('html')
						->Datos('#ComparacionID')
						->TipoEnvio('POST')
						->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Editar', 'Actualizar'))
						->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#CargarEdicion', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
						->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#CargarEdicion', 'respuesta'))
					);
					
					$Plantilla = new NeuralPlantillasTwig('Tienda');
					$Plantilla->Parametro('Consulta');
					$Plantilla->Parametro('Script', $Validacion->Constructor('ComparacionID'));
					$Plantilla->Parametro('ID', $DatosPost['Id']);
					echo $Plantilla->MostrarPlantilla('Inventario/Editar/ControlEdicion.html');
				}
				else {
					//Plantilla formulario vacio
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
		 * Actualizar()
		 * 
		 * Peticion Ajax donde se muestra el formulario para actualizar
		 */
		public function Actualizar() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				if(AppValidar::Vacio()->MatrizDatos($_POST) == true) {
					$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
					$InfoSesion = AppSesion::ObtenerDatosUsuario();
					$Consulta = $this->Modelo->ConsultarInformacionInventario($DatosPost['Id']);
					
					$Validacion = new NeuralJQueryFormularioValidacion(true, true, true);
					$Validacion->Requerido('Referencia', 'Ingrese la Referencia Correspondiente');
					$Validacion->Numero('Referencia', 'La Referencia es un Código Númerico');
					$Validacion->Requerido('Cantidad', 'Ingrese la Cantidad Correspondiente');
					$Validacion->Numero('Cantidad', 'La Cantidad es un Dato Númerico');
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
						->Datos('#Form_act')
						->TipoEnvio('POST')
						->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Editar', 'ProcesarEdicion'))
						->AntesEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form_act', '"<img src=\''.NeuralRutasApp::WebPublico('css/dark/images/loading.gif').'\'>"'))
						->FinalizadoEnvio(NeuralJQueryConstructor::Inicializar(false, false)->InsertarHTML('#Form_act', 'respuesta'))
					);
					
					$Plantilla = new NeuralPlantillasTwig('Tienda');
					$Plantilla->Parametro('Password', $this->Modelo->ConfirmarPassword($InfoSesion['Usuario'], $DatosPost['Comparacion']));
					$Plantilla->Parametro('Consulta', $Consulta);
					$Plantilla->Parametro('Proveedores', $this->Modelo->ListarProveedores());
					$Plantilla->Parametro('Script', $Validacion->Constructor('Form_act'));
					$Plantilla->Parametro('CargarInput', NeuralJQueryScript::IdPrincipal('CambiarCantidad')
						->IdSecundario('CargarContenidoInput')
						->URL(NeuralRutasApp::RutaUrlAppModulo('Inventario', 'Editar', 'InputCantidad'))
						->Parametros(array('Cantidad' => $Consulta['Cantidad']))
						->CargarLinkPeticionPost()
					
					);
					echo $Plantilla->MostrarPlantilla('Inventario/Editar/Actualizar.html');
				}
				else {
					//Plantilla Formulario vacio
					echo 'Plantilla Formulario vacio';
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * ProcesarEdicion()
		 * 
		 * Genera el proceso de actualizacion del inventario seleccionado
		 */
		public function ProcesarEdicion() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->Mayusculas()->SuprimirSQL()->MatrizDatos($_POST);
				$InfoSesion = AppSesion::ObtenerDatosUsuario();
				$this->Modelo->ProcesarEdicion($DatosPost, $InfoSesion['Usuario']);
				
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				echo $Plantilla->MostrarPlantilla('Inventario/MensajeActualizado.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * InputCantidad()
		 * 
		 * Peticion ajax para mostrar el campo de Cantidad
		 */
		public function InputCantidad() {
			if(AppValidar::PeticionAjax() == true AND isset($_POST) == true) {
				$DatosPost = AppFormato::Espacio()->MatrizDatos($_POST);
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				$Plantilla->Parametro('Cantidad', $DatosPost['Cantidad']);
				echo $Plantilla->MostrarPlantilla('Inventario/Editar/CampoInputCantidad.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
	}