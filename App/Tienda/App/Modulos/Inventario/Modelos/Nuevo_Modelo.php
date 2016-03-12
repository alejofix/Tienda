<?php
	
	class Nuevo_Modelo extends Modelo {
		
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * ListadoProveedores()
		 * 
		 * Genera el listado de proveedores ID, Nombre
		 */
		public function ListadoProveedores() {
			$Consulta = $this->Conexion->prepare('SELECT Id, Nombre FROM proveedores WHERE Estado = :Estado ORDER BY Nombre DESC');
			$Consulta->bindValue(':Estado', 'ACTIVO');
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Metodo Publico
		 * SeleccionarCategoria()
		 * 
		 * Lista las categorias existentes del inventario
		 */
		public function SeleccionarCategoria() {
			$Consulta = $this->Conexion->prepare('SELECT Categoria FROM inventario GROUP BY Categoria ORDER BY Categoria ASC');
			$Consulta->execute();
			return $Consulta->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Metodo Publico
		 * ProcesarNuevoInventario($Array = false, $Usuario = false)
		 * 
		 * Genera el proceso correspondiente de guardar el nuevo inventario
		 * y tambien de registrarlo en el historial
		 */
		public function ProcesarNuevoInventario($Array = false, $Usuario = false) {
			if($Array == true AND is_array($Array) == true AND $Usuario == true AND is_bool($Usuario) == false) {
				$SQLI = new NeuralBDGab($this->Conexion, 'inventario');
				foreach ($Array AS $Columna => $Valor) {
					$SQLI->Sentencia($Columna, $Valor);
				}
				$SQLI->Sentencia('Estado', 'ACTIVO');
				$SQLI->Insertar();
				
				$SQLH = new NeuralBDGab($this->Conexion, 'inventario_historial');
				$SQLH->Sentencia('Fecha', date("Y-m-d"));
				$SQLH->Sentencia('Hora', date("H:i:s"));
				$SQLH->Sentencia('Usuario', $Usuario);
				$SQLH->Sentencia('Cantidad', '0');
				$SQLH->Sentencia('Inventario_Id', self::BuscarIDInventario($this->Conexion, $Array));
				$SQLH->Sentencia('Tipo', 'NUEVO');
				$SQLH->Insertar();
			}
		}
		
		/**
		 * Metodo Privado
		 * BuscarIDInventario($Conexion, $Array)
		 * 
		 * Genera la busqueda del id correspondiente para agregar al historial
		 */
		private function BuscarIDInventario($Conexion, $Array) {
			foreach ($Array AS $Columna => $Valor) {
				$Condiciones[] = $Columna.' = :'.$Columna;
			}
			unset($Columna, $Valor);
			$Consulta = $Conexion->prepare('SELECT Id FROM inventario WHERE '.implode(' AND ', $Condiciones));
			foreach ($Array AS $Columna => $Valor) {
				$Consulta->bindValue(':'.$Columna, $Valor);
			}
			$Consulta->execute();
			$Data = $Consulta->fetch(PDO::FETCH_ASSOC);
			return $Data['Id'];
		}
		
		/**
		 * Metodo Publico
		 * ConsultarReferencia($Referencia = false)
		 * 
		 * Genera la consulta si existe o no la referencia correspondiente
		 */
		public function ConsultarReferencia($Referencia = false) {
			if($Referencia == true AND is_bool($Referencia) == false AND is_numeric($Referencia) == true) {
				$Consulta = $this->Conexion->prepare('SELECT Id FROM inventario WHERE Referencia = :Referencia');
				$Consulta->bindValue(':Referencia', $Referencia);
				$Consulta->execute();
				return ($Consulta->rowCount() >= 1) ? 'false' : 'true';
			}
		}
	}