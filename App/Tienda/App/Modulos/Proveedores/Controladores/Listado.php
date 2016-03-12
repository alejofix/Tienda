<?php
	
	class Listado extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		/**
		 * Metodo Publico
		 * Index()
		 * 
		 * Genera la tabla donde se listan los proveedores
		 * que se encuentran en la base de datos
		 */
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			$Plantilla->Parametro('Consulta', $this->Modelo->Listado());
			$Plantilla->Filtro('HexASCII', function ($Parametro) { return AppHexAsciiHex::ASCII_HEX($Parametro); });
			echo $Plantilla->MostrarPlantilla('Proveedor/Listado.html');
		}
		
		/**
		 * Metodo Publico
		 * Informacion($Parametro = false)
		 * 
		 * Genera la consulta de la informacion del proveedor seleciconado
		 */
		public function Informacion($Parametro = false) {
			if($Parametro == true AND is_bool($Parametro) == false AND is_numeric(AppHexAsciiHex::HEX_ASCII($Parametro)) == true) {
				
				$Plantilla = new NeuralPlantillasTwig('Tienda');
				$Plantilla->Parametro('Consulta', $this->Modelo->Informacion(AppHexAsciiHex::HEX_ASCII($Parametro)));
				$Plantilla->Filtro('HexASCII', function ($Parametro) { return AppHexAsciiHex::ASCII_HEX($Parametro); });
				$Plantilla->Parametro('Script',
					NeuralJQueryConstructor::Inicializar(true)
					->DocumentoListo()
					->AccionClick('#Actualizar')
					->AccionCargar('#Contenido', NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Actualizar', 'Proveedor'), false, array('Parametro' => $Parametro))
					."\n".
					NeuralJQueryConstructor::Inicializar(true)
					->DocumentoListo()
					->AccionClick('#Eliminar')
					->AccionCargar('#Contenido', NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Eliminar', 'Proveedor'), false, array('Parametro' => $Parametro))
				);
				echo $Plantilla->MostrarPlantilla('Proveedor/Informacion.html');
			}
			else {
				header("Location: ".NeuralRutasApp::RutaUrlAppModulo('Proveedores', 'Listado'));
				exit();
			}
		}
	}