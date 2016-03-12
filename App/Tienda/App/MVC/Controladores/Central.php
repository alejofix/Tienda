<?php
	
	class Central extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			//NeuralSesiones::Inicializacion();
			AppSesion::Ini();
		}
		
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			echo $Plantilla->MostrarPlantilla('Central/Central.html');
		}
	}