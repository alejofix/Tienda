<?php
	
	use \Neural\WorkSpace;
	
	class AppSesion {
		
		private static $Llave = '9b71d224bd62f3785d96d46ad3ea3d73319bfbc2890caadae2d';
		private static $Registro;
		private static $Puntero = 'Pedralbes';
		private static $TiempoSession = '10800';
		private static $Modulo;
		private static $Controlador = false;
		
		public static function Ini() {
			NeuralSesiones::Inicializacion();
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * Valor($Nombre = false, $Valor = false)
		 * Registro
		 * 
		 * asigna un valor predeterminado a la sesion
		 */
		public static function Valor($Nombre = false, $Valor = false) {
			if($Nombre == true AND $Valor == true) {
				self::$Registro[trim($Nombre)] = trim($Valor);
			}
			return new static;
		}
		
		/**
		 * Metodo Publico
		 * Registrar()
		 * Registro
		 * 
		 * Genera el proceso de registro y creacion de la sesion
		 */
		public static function Registrar() {
			if(is_array(self::$Registro) == true) {
				$Sesion = array(
					'Llave' => NeuralCriptografia::Codificar(implode('_', array(self::$Llave, self::$Registro['Fecha'], self::$Registro['Usuario'])), 'Tienda'),
					'Comparacion' => strtotime(self::$Registro['Fecha'].' '.self::$Registro['Hora']) + self::$TiempoSession,
					'Fecha' => self::$Registro['Fecha'],
					'Hora' => self::$Registro['Hora'], 
					'Permisos' => self::$Registro['Permisos']
				);
				$Info = array('Usuario' => self::$Registro['Usuario'], 'Fecha' => self::$Registro['Time'], 'Nombre' => self::$Registro['Nombre']);
				NeuralSesiones::AsignarSession('Pedralbes', array('Sesion' => NeuralCriptografia::Codificar(json_encode($Sesion), array(date("Y-m-d"), 'Tienda')), 'Informacion' => $Info));
				self::$Registro = '';
			}
		}
		
		public static function Controlador($Controladores = false) {
			if($Controladores == true AND is_bool($Controladores) == false) {
				self::$Controlador = $Controladores;
			}
			return new static;
		}
		
		private static function DatosSesionRegistrada() {
			$Sesion = NeuralSesiones::ObtenerSession('Pedralbes');
			$Sesion['Sesion'] = json_decode(NeuralCriptografia::DeCodificar($Sesion['Sesion'], array(date("Y-m-d"), 'Tienda')), true);
			$Sesion['Sesion']['Llave'] = NeuralCriptografia::DeCodificar($Sesion['Sesion']['Llave'], 'Tienda');
			$Sesion['Sesion']['Permisos'] = json_decode($Sesion['Sesion']['Permisos'], true);
			return $Sesion;
		}
		
		public static function ObtenerDatosUsuario() {
			$Base = NeuralSesiones::ObtenerSession('Pedralbes');
			return $Base['Informacion'];
		}
	}