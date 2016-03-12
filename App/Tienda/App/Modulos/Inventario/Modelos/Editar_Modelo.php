<?php
	
	class Editar_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * ControlEdicion($Usuario = false, $Password = false)
		 * 
		 * Genera el proceso de validacion del usuario
		 */
		public function ConfirmarPassword($Usuario = false, $Password = false) {
			if($Usuario == true AND $Password == true) {
				$Consulta = $this->Conexion->prepare('SELECT Id FROM usuarios WHERE Usuario = :Usuario AND Password = :Password');
				$Consulta->bindValue(':Usuario', $Usuario);
				$Consulta->bindValue(':Password', sha1($Password));
				$Consulta->execute();
				return ($Consulta->rowCount() >= 1) ? '1' : '0';
			}
		}
		
		/**
		 * Metodo Publico
		 * ConsultarInformacionInventario($Id = false)
		 * 
		 * Genera la consulta de la informacion de la referencia correspondiente
		 */
		public function ConsultarInformacionInventario($Id = false) {
			if($Id == true AND is_bool($Id) == false AND is_numeric($Id) == true) {
				$Consulta = $this->Conexion->prepare('SELECT '.self::ColumnasTabla($this->Conexion, 'inventario').' FROM inventario WHERE Id = :ID');
				$Consulta->bindValue(':ID', $Id);
				$Consulta->execute();
				return $Consulta->fetch(PDO::FETCH_ASSOC);
			}
		}
		
		/**
		 * Metodo Publico
		 * ListarProveedores()
		 * 
		 * Genera la Lista de Proveedores correspondiente
		 */
		public function ListarProveedores() {
			$Consulta = $this->Conexion->prepare('SELECT Id, Nombre FROM proveedores WHERE Estado = :Estado ORDER BY Nombre ASC');
			$Consulta->bindValue(':Estado', 'ACTIVO');
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Metodo Publico
		 * ProcesarEdicion()
		 * 
		 * Genera el proceso de actualizacion de datos
		 */
		public function ProcesarEdicion($Array = false, $Usuario = false) {
			if($Array == true AND is_array($Array) == true AND $Usuario == true) {
				self::ActualizarId($Array);
				if(isset($Array['Cantidad']) == true) {
					self::ActualizarIdHistorial($Array, $Usuario);
				}
			}
		}
		
		/**
		 * Metodo Privado
		 * ActualizarIdHistorial($Array = false, $Usuario = false)
		 * 
		 * Genera el ingreso de los datos al historial de cambios
		 */
		private function ActualizarIdHistorial($Array = false, $Usuario = false) {
			$SQL = new NeuralBDGab($this->Conexion, 'inventario_historial');
			$SQL->Sentencia('Fecha', date("Y-m-d"));
			$SQL->Sentencia('Hora', date("H:i:s"));
			$SQL->Sentencia('Usuario', $Usuario);
			$SQL->Sentencia('Cantidad', $Array['Cantidad']);
			$SQL->Sentencia('Inventario_Id', $Array['Id']);
			$SQL->Sentencia('Tipo', 'ACTUALIZACION');
			$SQL->Insertar();
		}
		
		/**
		 * Metodo Privado
		 * ActualizarId($Array = false)
		 * 
		 * Genera la actualizacion en la tabla de Inventario
		 */
		private function ActualizarId($Array = false) {
			$SQL = new NeuralBDGab($this->Conexion, 'inventario');
			foreach ($Array AS $Columna => $Valor) {
				if($Columna <> 'Id') {
					$SQL->Sentencia($Columna, $Valor);
				}
			}
			$SQL->Condicion('Id', $Array['Id']);
			$SQL->Actualizar();
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