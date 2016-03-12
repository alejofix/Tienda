<?php
	
	class Eliminar_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * ProveedorConsulta($Id = false)
		 * 
		 * Consulta la informacion del proveedor correspondiente
		 */
		public function ProveedorConsulta($Id = false) {
			if($Id == true AND is_bool($Id) == false AND is_numeric($Id) == true) {
				$Consulta = $this->Conexion->prepare('SELECT '.self::ColumnasTabla($this->Conexion, 'proveedores').' FROM proveedores WHERE Id = :ID');
				$Consulta->bindValue(':ID', $Id);
				$Consulta->execute();
				return $Consulta->fetch(PDO::FETCH_ASSOC);
			}
		}
		
		/**
		 * Metodo Publico
		 * ProveedorEliminar($Id = false)
		 * 
		 * Actualiza a inactivo el proveedor correspondiente
		 */
		public function ProveedorEliminar($Id = false) {
			if($Id == true AND is_bool($Id) == false) {
				$SQL = new NeuralBDGab($this->Conexion, 'proveedores');
				$SQL->Sentencia('Estado', 'INACTIVO');
				$SQL->Condicion('Id', $Id);
				$SQL->Actualizar();
			}
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