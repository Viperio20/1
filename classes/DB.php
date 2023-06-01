<?php

// version 0.1

define("DB_ONLY_CHECK_TABLE_EXISTS", 1);

define("DB_INSERT_IGNORE", 1);


class DB {

	static $rs = null;
	static $db_name = null;
	
	static function connect() {
		
		if ( is_null(self::$rs) ) {
			
			if ( !defined("DB_HOST") ) trigger_error( "Не задана константа DB_HOST",  E_USER_ERROR );
			if ( !defined("DB_USER") ) trigger_error( "Не задана константа DB_USER",  E_USER_ERROR );
			if ( !defined("DB_PASSWORD") ) trigger_error( "Не задана константа DB_PASSWORD",  E_USER_ERROR );
			if ( !defined("DB_NAME") ) trigger_error( "Не задана константа DB_NAME",  E_USER_ERROR );
			if ( !defined("DB_CHARSET") ) trigger_error( "Не задана константа DB_CHARSET",  E_USER_ERROR );
			
			self::$rs  = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
			
			if ( !self::$rs ) {
				self::error( "mysqli_connect( ". DB_HOST .", ". DB_USER .", ". DB_PASSWORD .", ". DB_NAME .")" );	
				
			} else {
				mysqli_set_charset(self::$rs, DB_CHARSET);		
			}
		};
			
		return self::$rs;	
	}	




		
		

	static function tableExists( $table_name ) {
		$query = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '". DB_NAME ."' AND TABLE_NAME = '". $table_name ."' LIMIT 1";

		return self::resQuery( $query ) ? true : false;
	}
	
	static function tables() {
		$query = "SELECT TABLE_NAME as name, TABLE_ROWS as rows_count, DATA_LENGTH as `data_length`, TABLE_COMMENT as `comment` FROM information_schema.TABLES WHERE TABLE_SCHEMA = '". DB_NAME ."'";	
		
		return self::rows( $query, ["key" => "name"] );
	}

	static function columnExists( $table_name, $column_name ) {
		$query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '". DB_NAME ."' AND TABLE_NAME = '". $table_name ."' AND COLUMN_NAME = '". $column_name . "' LIMIT 1";

		return self::resQuery( $query ) ? true : false;
	}
	
	static function columns( $table_name ) {
		
		$query = "SELECT
			COLUMN_NAME as name,
			COLUMN_DEFAULT as `default`,
			IS_NULLABLE as `is_null`,
			DATA_TYPE as `type`,
			IFNULL(CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION) as `length`,
			COLUMN_TYPE as `full_type`,
			COLUMN_KEY as `key`,
			COLUMN_COMMENT as `comment`

			FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '". DB_NAME ."' AND TABLE_NAME = '". $table_name ."' ORDER BY ORDINAL_POSITION";	
		
		return self::rows( $query, ["key" => "name"] );
	}

	static function fkeys() {
		
/*
SELECT *
FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'dovod'
*/		
		
		$query = "SELECT CONSTRAINT_NAME 'fk_name', TABLE_NAME 'table', COLUMN_NAME 'column',  REFERENCED_TABLE_NAME 'ref_table', REFERENCED_COLUMN_NAME 'ref_column'
			FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
			WHERE REFERENCED_TABLE_SCHEMA = '". DB_NAME ."'";
		
		return self::rows( $query );
	}

	static function resQuery( $query ) {
		self::connect();

		$res = mysqli_query( self::$rs,$query ) or self::error( $query );
		return ( $res && mysqli_num_rows($res)>0 ) ? $res : false;
	}


	static function error( $query ) {
		trigger_error( "Ошибка в запросе - «{$query}»‎", E_USER_WARNING);	
		trigger_error( "[". mysqli_errno( self::$rs ) . "] ". mysqli_error( self::$rs ),  E_USER_ERROR);	
	}

	
	static function completeTable( $table, $columns, $options = 0 ) {
		
		$uniques = [];
		
		if ( !DB::tableExists( $table ) ) {
			
			
			$query = "CREATE TABLE `{$table}` (`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,\n";
			foreach ( $columns as $field_name => $field_type ) {
				
				$sql_type = isset( $field_type["type"] ) ? $field_type["type"] : "TEXT";
				
				$query .= "`{$field_name}` {$sql_type}, ";
				
				if ( isset($field_type["unique"]) && $field_type["unique"] ) $uniques[] = $field_name;
			};
			$query .= "PRIMARY KEY(`id`) ) ENGINE = InnoDB";

			iDB::exec( $query );
			
			//foreach ( $uniques as $unique ) iDB::exec( "ALTER TABLE `{$table}` ADD UNIQUE `unique_{$unique}`(`{$unique}`);");
			

		
		// проверяем структуру таблицы и дополняем отсутствующими полями
		} elseif ( $options & DB_ONLY_CHECK_TABLE_EXISTS ) {
			return true;
		} else {
			
			$exists_columns = DB::columns( $table );
			$needs = [];
			
			foreach ( $columns as $field_name => $field_type ) {
				if ( !isset($exists_columns[ $field_name ] ) ) {
					
					$sql_type = isset( $field_type["type"] ) ? $field_type["type"] : "TEXT";
					
					$needs[] = "ADD COLUMN `{$field_name}` {$sql_type}";
					
					//if ( isset($field_type["unique"]) && $field_type["unique"] ) $uniques[] = $field_name;					
				}
			};
			
			if ( count($needs) > 0 ) {
				$query = "ALTER TABLE `{$table}`" . implode(", ", $needs);
				iDB::exec( $query );				
			};
		};		
	}
	
	
	
	static function insert( $table, $data, $options = DB_INSERT_IGNORE ) {

			
		
		if (!is_array($values_par)) trigger_error("Массив данных не является массивом",E_USER_WARNING);
		elseif (count($values_par)==0) trigger_error("Массив данных не содержит элементов",E_USER_WARNING);
		else {
			if (is_null(self::$rs)) self::connect();
			$fields = $values = array();
			$ignore = $options & DB_INSERT_IGNORE ? "IGNORE" : "";
			
			

			foreach ($values_par as $key=>$value) {
				$fields[] = '`' . $key . '`'; 	
				
				if ( is_null($value) ) $values[] = "NULL";
				else $values[] = mysqli_real_escape_string( self::$rs, $value );
			};
			
			$str_fields = implode( ',', $fields ); 
			$str_values = implode( ',', $values );
			
			
			
			mysqli_query(self::$rs,$query = "INSERT {$ignore} INTO `{$into}` ({$str_fields}) VALUES({$str_values})") or self::error($query);

			
			$insert_id = mysqli_insert_id(self::$rs);
			
			
			

			return $insert_id;
		};
		
		
		var_dump(debug_backtrace());
		return null;
	}
	
	
	static function dropTables() {
		
		foreach ( array_keys(self::tables()) as $table_name ) {
			self::exec("DROP TABLE `{$table_name}`");	
		}
		
	}
	
	
	static function rows( $query, $options = [] ) {
		$data = [];
		
		if ( $res = self::resQuery( $query ) ) while ($row = mysqli_fetch_assoc($res)) {
			
			if ( isset( $options["key"]) ) {
				$data[ $row[$options["key"]] ] = $row;	
			} else {
				$data[] = $row;
			}
		}
		
		return $data;
	}
	
	static function row( $query, $options = [] ) {
		$data = [];
		
		if ( $res = self::resQuery( $query ) ) while ($row = mysqli_fetch_assoc($res)) {
			
			if ( isset( $options["key"]) ) {
				$data[ $row[$options["key"]] ] = $row;	
			} else {
				$data = $row;
			}
			
			return $data;
		}
		
		return $data;
	}	
	
	
	static function exec($query) {
		self::connect();
		mysqli_query(self::$rs, $query) or self::error($query);
	}	
	
	
	static function isAssocArray( $array ) {
		if (array() === $array) return false;
		return array_keys($array) !== range(0, count($array) - 1);		
	}


	static function arrayDimension( $array ) {
		if (is_array(reset($array))) $return = self::arrayDimension(reset($array)) + 1;
		else $return = 1;

		return $return;
	}


	public static function arrayGet( $row_key, $ar ) {
		
		if ( !is_array($row_key) ) $row_key = [ $row_key ];
		
		$row = null;
		foreach ( $row_key as $key ) {
			
			$key = trim($key);
			
			if ( is_null($row) ) {
				if ( !isset( $ar[$key] ) ) return null;
				$row = &$ar[$key];
			} else {
				if ( !isset( $row[$key] ) ) return null;
				$row = &$row[$key];
			}
		}
		
		return $row;
	}



	public static function &arrayAdd( $row_key, $value, &$ar ) {
		
		if ( !is_array($row_key) ) $row_key = [ $row_key ];
		
		$row = null;
		foreach ( $row_key as $key ) {
			
			$key = trim($key);
			
			if ( is_null($row) ) {
				if ( !isset( $ar[$key] ) ) $ar[$key] = [];
				$row = &$ar[$key];
			} else {
				if ( !isset( $row[$key] ) ) $row[$key] = [];
				$row = &$row[$key];
			}
		}
		
		$row[] = $value;
		
		return $row[ count($row) - 1];
	}
	
	public static function &arraySet( $row_key, $value, &$ar ) {
		
		if ( !is_array($row_key) ) $row_key = [ $row_key ];
		
		$row = null;
		foreach ( $row_key as $key ) {
			
			$key = trim($key);
			
			if ( is_null($row) ) {
				if ( !isset( $ar[$key] ) ) $ar[$key] = [];
				$row = &$ar[$key];
			} else {
				if ( !isset( $row[$key] ) ) $row[$key] = [];
				$row = &$row[$key];
			}
		}
		
		$row = $value;
		return $row;
	}

	public static function arrayRemove( $row_key, &$ar ) {
		
		if ( !is_array($row_key) ) $row_key = [ $row_key ];
		
		$row = null;
		foreach ( $row_key as $key ) {
			
			$key = trim($key);
			
			if ( is_null($row) ) {
				if ( !isset( $ar[$key] ) ) return false;
				$row = &$ar[$key];
			} else {
				if ( !isset( $row[$key] ) ) return false;
				$row = &$row[$key];
			}
		}
		

		$row = null;
		return true;
	}


	

}