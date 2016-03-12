<?php
	class Listado_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		public function Listado() {
			$Consulta = $this->Conexion->prepare('SELECT inventario.Id, inventario.Referencia, proveedores.Nombre AS Proveedor, inventario.Categoria, inventario.SubCategoria, inventario.Cantidad, inventario.Estado FROM inventario, proveedores WHERE inventario.Proveedor = proveedores.Id ORDER BY Cantidad DESC, SubCategoria ASC');
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function Informacion($Id = false) {
			if($Id == true) {
				$Consulta = $this->Conexion->prepare('SELECT '.self::ColumnasTabla($this->Conexion, 'inventario').' FROM inventario WHERE Id = :ID');
				$Consulta->bindValue(':ID', $Id);
				$Consulta->execute();
				return ($Consulta->rowCount() >= 1) ? array_merge(array('Cantidad' => $Consulta->rowCount()), $Consulta->fetchAll(PDO::FETCH_ASSOC)) : array('Cantidad' => '0');
			}
		}
		
		public function ListadoMatrizTabla($Tabla = false) {
			if($Tabla == true AND is_bool($Tabla) == false) {
				$Consulta = $this->Conexion->prepare('SELECT COLUMN_NAME, COLUMN_COMMENT FROM information_schema.columns WHERE table_name= :Tabla');
				$Consulta->bindValue(':Tabla', $Tabla);
				$Consulta->execute();
				$Matriz = $Consulta->fetchAll(PDO::FETCH_ASSOC);
				foreach ($Matriz AS $Puntero => $Comentario) {
					$Lista[$Comentario['COLUMN_NAME']] = trim($Comentario['COLUMN_COMMENT']);
				}
				unset($Tabla, $this, $Consulta, $Matriz, $Puntero, $Comentario);
				return $Lista;
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