<?php
	
	/**
	 * Clase: Index_Modelo
	 */
	class CajaMenor_Modelo extends Modelo {
		
		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
	
		}
		
		/**
		 * Metodo: Ejemplo
		 */
		public function ConsultaSQL() {
			
		}
	}