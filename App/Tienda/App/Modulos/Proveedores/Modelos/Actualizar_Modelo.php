<?php
	
	class Actualizar_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * ConsultaProveedor($Id = false)
		 * 
		 * Genera la consulta de la informacion correspondiente
		 * @param $Id: Id del proveedor
		 */
		public function ConsultaProveedor($Id = false) {
			$Consulta = $this->Conexion->prepare('SELECT '.self::ColumnasTabla($this->Conexion, 'proveedores').' FROM proveedores WHERE Id = :ID');
			$Consulta->bindValue(':ID', $Id);
			$Consulta->execute();
			return $Consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Metodo Publico
		 * ProveedorActualizar($Array = false, $Id = false)
		 * 
		 * Genera el proceso de actualizacion de los datos del proveedor
		 * @param $Array: matriz de datos
		 * @param $Id: Id del proveedor
		 */
		public function ProveedorActualizar($Array = false, $Id = false) {
			if($Array == true AND is_array($Array) == true AND $Id == true) {
				$SQL = new NeuralBDGab($this->Conexion, 'proveedores');
				foreach ($Array AS $Columna => $Valor) {
					$SQL->Sentencia($Columna, $Valor);
				}
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