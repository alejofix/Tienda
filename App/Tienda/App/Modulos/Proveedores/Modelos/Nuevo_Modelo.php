<?php
	
	class Nuevo_Modelo extends Modelo {
		
		/**
		 * Metodo Magico Constructor
		 * 
		 * Genera el proceso de constructor correspondiente
		 * $this->Conexion: Conexion de DoctrineDBAL
		 */
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * ConsultarProveedor($Proveedor = false)
		 * 
		 * Genera la consulta del proveedor correspondiente
		 * retornando valor como texto true o false
		 * @param $Proveedor: Nombre del proveedor
		 */
		public function ConsultarProveedor($Proveedor = false) {
			if($Proveedor == true AND is_bool($Proveedor) == false) {
				$Consulta = $this->Conexion->prepare('SELECT Id FROM proveedores WHERE Nombre = :Nombre');
				$Consulta->bindValue(':Nombre', $Proveedor);
				$Consulta->execute();
				return ($Consulta->rowCount() >= 1) ? 'false' : 'true';
			}
		}
		
		/**
		 * Metodo Publico
		 * ProcesarAgregarNuevoProveedor($Array = false, $Usuario = false)
		 * 
		 * Genera el proceso de guardado de los datos del nuevo proveedor
		 * @param $Array: matriz de datos a guardar
		 * @param $Usuario: usuario que ingresa la informacion
		 */
		public function ProcesarAgregarNuevoProveedor($Array = false, $Usuario = false) {
			if($Array == true AND is_array($Array) == true AND $Usuario == true) {
				$SQL = new NeuralBDGab($this->Conexion, 'proveedores');
				foreach ($Array AS $Columna => $Valor) {
					$SQL->Sentencia($Columna, $Valor);
				}
				$SQL->Sentencia('Estado', 'ACTIVO');
				$SQL->Sentencia('Usuario', $Usuario);
				$SQL->Sentencia('Fecha', date("Y-m-d"));
				$SQL->Sentencia('Hora', date("H:i:s"));
				$SQL->Insertar();
			}
		}
	}