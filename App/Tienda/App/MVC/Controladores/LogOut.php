<?php
	
	class LogOut extends Controlador {
		
		function __Construct() {
			parent::__Construct();
			NeuralSesiones::Inicializacion();
			NeuralSesiones::Finalizacion();
			header("Location: ".NeuralRutasApp::RutaUrlApp('Index'));
			exit();
		}
		
		public function Index() {
			
		}
	}