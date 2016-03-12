<?php
	
	class Alimentar_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * BuscarInventario($Referencia = false)
		 * 
		 * Genera la consulta de la referencia correspondiente
		 */
		public function BuscarInventario($Referencia = false) {
			$Consulta = $this->Conexion->prepare('SELECT '.self::ColumnasTabla($this->Conexion, ' inventario').' FROM inventario WHERE Referencia = :Referencia');
			$Consulta->bindValue(':Referencia', $Referencia);
			$Consulta->execute();
			return $Consulta->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Metodo Publico
		 * ProcesarActualizarInventario($Cantidad = false, $Id = false, $Usuario)
		 * 
		 * Genera el proceso de actualizacion
		 */
		public function ProcesarActualizarInventario($Cantidad = false, $Id = false, $Usuario) {
			if($Cantidad == true AND is_numeric($Cantidad) == true AND $Id == true AND is_numeric($Id) == true) {
				$CantidadBase = self::ConsultarCantidad($this->Conexion, $Id);
				$Total = $CantidadBase + $Cantidad;
				$SQL = new NeuralBDGab($this->Conexion, 'inventario');
				$SQL->Sentencia('Cantidad', $Total);
				$SQL->Condicion('Id', $Id);
				$SQL->Actualizar();
				self::HistorialInventario($this->Conexion, $Id, $Usuario, $Total);
			}
		}
		
		/**
		 * Metodo Privado
		 * ConsultarCantidad($Conexion, $Id)
		 * 
		 * Genera la consulta de la cantidad actual de inventario
		 */
		private function ConsultarCantidad($Conexion, $Id) {
			$Consulta = $Conexion->prepare('SELECT Id, Cantidad FROM inventario WHERE Id = :ID');
			$Consulta->bindValue(':ID', $Id);
			$Consulta->execute();
			$Data = $Consulta->fetch(PDO::FETCH_ASSOC);
			return $Data['Cantidad'];
		}
		
		/**
		 * Metodo Privado
		 * HistorialInventario($Conexion, $Id, $Usuario, $Cantidad)
		 * 
		 * Agrega el registro al historial del inventario
		 */
		private function HistorialInventario($Conexion, $Id, $Usuario, $Cantidad) {
			$SQLH = new NeuralBDGab($Conexion, 'inventario_historial');
			$SQLH->Sentencia('Fecha', date("Y-m-d"));
			$SQLH->Sentencia('Hora', date("H:i:s"));
			$SQLH->Sentencia('Usuario', $Usuario);
			$SQLH->Sentencia('Cantidad', $Cantidad);
			$SQLH->Sentencia('Inventario_Id', $Id);
			$SQLH->Sentencia('Tipo', 'ALIMENTAR');
			$SQLH->Insertar();
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