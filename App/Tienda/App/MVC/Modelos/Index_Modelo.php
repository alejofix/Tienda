<?php
	
	/**
	 * Clase: Index_Modelo
	 */
	class Index_Modelo extends Modelo {
		
		/**
		 * Metodo: Constructor
		 */
		function __Construct() {
			parent::__Construct();
			$this->Conexion = NeuralConexionDB::DoctrineDBAL('Pedralbes');
		}
		
		/**
		 * Metodo Publico
		 * Autenticacion($Usuario = false, $Password = false)
		 * 
		 * Genera la consulta del usuario y los datos de inicio de sesion
		 */
		public function Autenticacion($Usuario = false, $Password = false) {
			if($Usuario == true AND $Password == true AND is_bool($Usuario) == false AND is_bool($Password) == false) { 
				$Consulta = $this->Conexion->prepare('SELECT Usuario, Nombre, Apellidos, Permisos, Estado FROM usuarios WHERE Usuario = :Usuario AND Password = :Password');
				$Consulta->bindValue(':Usuario', $Usuario);
				$Consulta->bindValue(':Password', sha1($Password));
				$Consulta->execute();
				return ($Consulta->rowCount() >= 1) ? array_merge(array('Cantidad' => $Consulta->rowCount()), $Consulta->fetchAll(PDO::FETCH_ASSOC)) : array('Cantidad' => $Consulta->rowCount());	
			}
		}
		
		/**
		 * Metodo Publico
		 * Permisos($Permiso = false)
		 * 
		 * retorna los permisos asignados al usuario
		 */
		public function Permisos($Permiso = false) {
			if($Permiso == true AND is_bool($Permiso) == false AND is_numeric($Permiso) == true) {
				$Consulta = $this->Conexion->prepare('SELECT Detalle FROM permisos WHERE Id = :ID AND Estado = :Estado');
				$Consulta->bindValue(':ID', $Permiso);
				$Consulta->bindValue(':Estado', 'ACTIVO');
				$Consulta->execute();
				if($Consulta->rowCount() == 1) {
					$Data = $Consulta->fetchAll(PDO::FETCH_ASSOC);
					return $Data[0]['Detalle'];
				}
				else {
					return '{}';
				}
			}
		}
	}