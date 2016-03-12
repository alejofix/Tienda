<?php
	
	class Listado_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * Informacion($Parametro = false)
		 * 
		 * Consulta la informacion del proveedor correspondiente
		 * @param $Parametro: id del proveedor
		 */
		public function Informacion($Parametro = false) {
			if($Parametro == true AND is_bool($Parametro) == false AND is_numeric($Parametro) == true) {
				$Consulta = $this->Conexion->prepare('SELECT '.self::ColumnasTabla($this->Conexion, 'proveedores').' FROM proveedores WHERE Id = :ID');
				$Consulta->bindValue(':ID', $Parametro);
				$Consulta->execute();
				return $Consulta->fetch(PDO::FETCH_ASSOC);
			}
		}
		
		/**
		 * Metodo Publico
		 * Listado()
		 * 
		 * Genera la consulta de los proveedores en la base de datos
		 */
		public function Listado() {
			$Consulta = $this->Conexion->prepare('SELECT Id, Nombre, Estado, Telefono_1 FROM proveedores WHERE Estado != :Estado ORDER BY Estado ASC, Nombre ASC');
			$Consulta->bindValue(':Estado', 'INACTIVO');
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Metodo Privado
		 * ColumnasTabla($Conexion, $Tabla)
		 * 
		 * Genera el listado de las columnas de la tabla correspondiente
		 * en formato Tabla.Columna
		 * @param $Conexion: objeto de la conexion
		 * @param $Tabla: Tabla a ser descrita
		 */
		private function ColumnasTabla($Conexion, $Tabla) {
			$Consulta = $Conexion->prepare('DESCRIBE '.mb_strtolower(trim($Tabla)));
			$Consulta->execute();
			$Data = $Consulta->fetchAll(PDO::FETCH_ASSOC);
			foreach ($Data AS $Puntero => $Array) {
				$Listado[] = $Tabla.'.'.$Array['Field'];
			}
			unset($Conexion, $Tabla, $Consulta, $Data, $Puntero, $Array);
			return implode(', ', $Listado);
		}
	}