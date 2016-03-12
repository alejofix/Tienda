<?php
	
	class Index extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializacion();
			if(isset($_SESSION['Pedralbes']) == true) {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
				exit();
			}
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Mustra el Formulario del Login correspondiente
		 */
		public function Index() {
			$Validacion = new NeuralJQueryFormularioValidacion;
			$Validacion->Requerido('Usuario', 'Ingrese el Usuario Correspondiente');
			$Validacion->Requerido('Password', 'Ingrese El Password Correspondiente');
			
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			$Plantilla->Parametro('Script', $Validacion->Constructor('loginform'));
			echo $Plantilla->MostrarPlantilla('Login/Login.html');
		}
		
		/**
		 * Metodo Publico
		 * Autenticacion()
		 * 
		 * Genera el proceso de autenticacion del usuario
		 */
		public function Autenticacion() {
			if(isset($_POST) == true AND isset($_POST['Enviar']) == true) {
				if(AppValidar::Vacio()->MatrizDatos($_POST) == true) {
					$DatosPost = AppFormato::Espacio()->Mayusculas(array('Usuario'))->SuprimirSQL()->MatrizDatos($_POST);
					$Consulta = $this->Modelo->Autenticacion($DatosPost['Usuario'], $DatosPost['Password']);
					if($Consulta['Cantidad'] == 1) {
						if($Consulta[0]['Estado'] == 'ACTIVO') {
							AppSesion::Valor('Fecha', date("Y-m-d"))->Valor('Hora', date("H:i:s"))
							->Valor('Usuario', $Consulta[0]['Usuario'])
							->Valor('Time', strtotime(date("Y-m-d H:i:s")))
							->Valor('Nombre', $Consulta[0]['Nombre'].' '.$Consulta[0]['Apellidos'])
							->Valor('Permisos', $this->Modelo->Permisos($Consulta[0]['Permisos']))
							->Registrar();
							header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
							exit();
						}
						else {
							//Usuario bloqueado, validar con el administrador del sistema
							echo 'Usuario bloqueado, validar con el administrador del sistema';
						}
					}
					else {
						//Usuario y/o contraseña erroneos
						echo 'Usuario y/o contraseña erroneos';
					}
				}
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlApp('Index'));
				exit();
			}
		}
	}