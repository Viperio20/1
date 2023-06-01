<?php
	class iS{
		static function s($s,$d='') {
			if (is_object($s)) {
				iSystem::error("Конвертация объекта в строку в классе iS");
				var_dump($s);
				//exit();
			};
			$s = (string) $s;
			if (isset($s)) {
				if (defined("DB_HOST")) {
					if (is_null(iDB::$rs)) iDB::connect(); 
					return mysqli_real_escape_string(iDB::$rs, $s);
				} else {
					return addslashes(trim($s)); 
				};
			} else {
				return $d;
			}
		}

		static function rs($v,$d=NULL) {
			if (isset($_REQUEST[$v])) return self::s($_REQUEST[$v],$d); else return $d; 
		}		

		
		
		static function rsq($v,$d=NULL) {
			return self::sq(self::rs($v,$d),$d);
		}		
		
		
		static function rb($v,$d=NULL) {
			if (isset($_REQUEST[$v])) return true; else return false; 
		}		

		
		static function sq($s,$d="''") {
			if (isset($s)) return "'".self::s($s)."'";  else return $d;
		}	
		
		static function n($v,$d=NULL) {
			return (int) $v;
		}		

		static function rn($v,$d=NULL) {
			if (isset($_REQUEST[$v])) return self::n($_REQUEST[$v],$d); else return $d; 
		}		

		static function f($v,$d=NULL) {
			return (float) $v;
		}		

		static function rf($v,$d=NULL) {
			if (isset($_REQUEST[$v])) return self::f($_REQUEST[$v],$d); else return $d; 
		}			
		
		
		static function cn($v,$d=NULL) {
			if (isset($_COOKIE[$v])) return self::n($_COOKIE[$v],$d); else return $d; 
		}		

		static function cs($v,$d=NULL) {
			if (isset($_COOKIE[$v])) return self::s($_COOKIE[$v],$d); else return $d; 
		}		
		
		static function sa($v, $max_length = 32, $preg_replace = '\W') {
			$v = preg_replace('#'.$preg_replace.'#sui', "", $v);
			if (mb_strlen($v)>$max_length) $v = mb_substr($v,0,$max_length);
			if (empty($v)) $v = null;
			
			return $v;
		}
		
		static function rsa($v, $max_length = 32, $preg_replace = '\W') {
			if (isset($_REQUEST[$v])) return self::sa($_REQUEST[$v], $max_length, $preg_replace); else return NULL;
		}
		
	}