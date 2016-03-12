<?php
	
	/**
	 * Controlador: Index
	 */
	class Index extends Controlador {
		
		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
		}
		
		/**
		 * Metodo: Index
		 */
		public function Index() {
			header("Location: ".NeuralRutasApp::RutaUrlApp('Central'));
			exit();
		}
	}