<?php
	
	class CajaMenor extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			AppSesion::Ini();
		}
		
		public function Index() {
			$Plantilla = new NeuralPlantillasTwig('Tienda');
			echo $Plantilla->MostrarPlantilla('Cajas/CajaMenor.html');

		}
	}