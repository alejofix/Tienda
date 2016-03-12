<?php
	
	/**
	 * NeuralPHP Framework
	 * Marco de trabajo para aplicaciones web.
	 * 
	 * @author Zyos (Carlos Parra) <Neural.Framework@gmail.com>
	 * @copyright 2006-2014 NeuralPHP Framework
	 * @license GNU General Public License as published by the Free Software Foundation; either version 2 of the License. 
	 * @license Incluida licencia carpeta de Informacion 
	 * @see http://neuralphp.url.ph/
	 * @version 3.0
	 * 
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 */
	
	class NeuralSesiones {
		
		/**
		 * Metodo Publico
		 * AsignarSession($Llave = false, $Valor = false)
		 * 
		 * Asigna una Llave y un valor a la variable global se $_SESSION
		 * @param $Llave: Llave asociativa dentro de la matriz de session
		 * @param $Valor: valor que se le asigna a la llave
		 */
		public static function AsignarSession($Llave = false, $Valor = false) {
			if($Llave == true AND $Valor == true) {
				$_SESSION[trim($Llave)] = self::Codificar($Valor);
			}
		}
		
		/**
		 * Metodo Privado
		 * Codificar($Valor = false)
		 * 
		 * Genera la codificacion del valor para ser asignado
		 * @access private
		 */
		private static function Codificar($Valor = false) {
			return base64_encode(self::Compresion(json_encode(self::FormatearValor($Valor))));
		}
		
		/**
		 * Metodo Privado
		 * Compresion($Cadena = false)
		 * 
		 * Genera la compresion de la cadena indicada
		 * @access private
		 */
		private static function Compresion($Cadena = false) {
			return (function_exists('gzcompress') == true AND function_exists('gzuncompress') == true) ? gzcompress($Cadena, 9) : $Cadena;
		}
		
		/**
		 * Metodo Privado
		 * DesCodificar($Valor = false)
		 * 
		 * Genera la descodificacion del valor indicado
		 * @access private
		 */
		private static function DesCodificar($Valor = false) {
			return json_decode(self::DesCompresion(base64_decode($Valor, true)), true);
		}
		
		/**
		 * Metodo Privado
		 * DesCompresion($Cadena = false)
		 * 
		 * Genera la descompresion de la cadena indicada
		 * @access private
		 */
		private static function DesCompresion($Cadena = false) {
			return (function_exists('gzcompress') == true AND function_exists('gzuncompress') == true) ? gzuncompress($Cadena) : $Cadena;
		}
		
		/**
		 * Metodo Publico
		 * Finalizacion()
		 * 
		 * Genera la destruccion, Finalizacion y la eliminacion de todos los datos
		 * contenidos en la variable global $_SESSION
		 */
		public static function Finalizacion() {
			session_destroy();
			unset($_SESSION);
		}
		
		/**
		 * Metodo Privado
		 * FormatearValor($Valor = false)
		 * 
		 * Genera el formato de tipo al valor indicado
		 * @access private
		 */
		private static function FormatearValor($Valor = false) {
			if(is_bool($Valor) == true) {
				settype($Valor, "bool");
				return $Valor;
			}
			elseif(is_array($Valor) == true) {
				settype($Valor, "array");
				return $Valor;
			}
			elseif(is_object($Valor) == true) {
				settype($Valor, "object");
				return $Valor;
			}
			elseif(is_float($Valor) == true) {
				settype($Valor, "float");
				return trim($Valor);
			}
			elseif(is_null($Valor) == true) {
				settype($Valor, "null");
				return $Valor;
			}
			elseif(is_integer($Valor) == true) {
				settype($Valor, "integer");
				return trim($Valor);
			}
			else {
				return trim($Valor);
			}
		}
		
		/**
		 * Metodo Publico
		 * Inicializacion()
		 * 
		 * Inicializa la session correspondiente
		 */
		public static function Inicializacion() {
			session_start();
		}
		
		/**
		 * Metodo Publico
		 * ObtenerSession($Llave = false)
		 * 
		 * Retorna el Valor de la llave indicada
		 */
		public static function ObtenerSession($Llave = false) {
			if($Llave == true) {
				if(isset($_SESSION) == true AND array_key_exists($Llave, $_SESSION) == true) {
					return self::DesCodificar($_SESSION[trim($Llave)]);
				}
				else {
					throw new NeuralException('La Llave: '.$Llave.'. No Existe en la Session');
				}
			}
		}
		
		/**
		 * Metodo Publico
		 * ValidarExistenciaAsignacion($Llave = false)
		 * 
		 * Valida si la llave buscada se encuentra en las llaves base de la session
		 * @param $Llave: nombre de la llave a validar
		 * @return true: existe
		 * @return false: no existe
		 */
		public static function ValidarExistenciaAsignacion($Llave = false) {
			if(isset($_SESSION) == true) {
				if(array_key_exists($Llave, $_SESSION) == true) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
	}