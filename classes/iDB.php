<?php
class iDB {
	static $rs;
	static function connect() {
		if (!defined("DB_HOST")) return false;
		
		self::$rs  = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
		mysqli_set_charset(self::$rs, DB_CHARSET);		

		return self::$rs;	
	}	
	
	
	static function error($query) {
		trigger_error( mysqli_error(self::$rs) . " in query = " . $query, E_USER_ERROR );
	}
	

	// одно значение запроса
	static function value($query,$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0) {$data = mysqli_fetch_row($res); $data = $data[0];};
		
		return $data;
	}	
	
	static function row($query,$default=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $default;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0) $data = mysqli_fetch_object($res);
		return $data;
	}

	static function row_assoc($query,$default=NULL, $hide_error=false) {
		if (is_null(self::$rs)) self::connect();
		$data = $default;
		
		if ($hide_error) @$res = mysqli_query(self::$rs,$query) or self::error($query);
		else $res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0) $data = mysqli_fetch_assoc($res);
		return $data;
	}

	// все значения первой колонки запроса в виде массива в виде индекс=>значение
	static function line($query,$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		while ($row = mysqli_fetch_row($res))
			$data[$row[0]] = $row[1];
			return $data;
	}	
	
	
	
	
	static function line_obj($query,$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		while ($row = mysqli_fetch_row($res))
			$data->$row[0] = $row[1];
			return $data;
	}	
	
	
	static function lines($query, $defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = array();
		
		$res = mysqli_query(self::$rs, $query) or self::error($query);
		
		if ($res && mysqli_num_rows($res)>0) {
			while ($row = mysqli_fetch_assoc($res)) {
			
				$current_key = null;
				foreach ($row as $key => $value) {
					if (is_null($current_key)) $current_key = $value;	
					else $data[ $current_key ] [ $key ] = $value;
				};
			};
			
			return $data;
			
		} else return $defaultValue;
		
	}	
	
	
	
	
	
	
	
	static function rows($query,$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		while ($row = mysqli_fetch_object($res))
			$data[] = $row;
		return $data;
	}	
	
	static function rows_ex($query, $func_update_data=null, $defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		$counter = 0;
		while ($row = mysqli_fetch_object($res))
			if (!is_null($func_update_data)) $data[] = $func_update_data($row); 	else $data[] = $row;
		return $data;
	}	

	static function rows_assoc($query,$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		while ($row = mysqli_fetch_assoc($res))
			$data[] = $row;
		return $data;
	}	

	static function rows_assoc_id($query,$column='id',$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		while ($row = mysqli_fetch_assoc($res)) {
			$data[$row[$column]] = $row;			
		};
		return $data;
	}	

	static function rows_assoc_column($query,$defaultValue=NULL) {
		if (is_null(self::$rs)) self::connect();
		$data = $defaultValue;
		$res = mysqli_query(self::$rs,$query) or self::error($query);
		if ($res && mysqli_num_rows($res)>0)
		while ($row = mysqli_fetch_row($res)) {
			$data[$row[0]] = $row[1];			
		};
		return $data;
	}	

	
	static function insert($query) {
		if (is_null(self::$rs)) self::connect();	
		mysqli_query(self::$rs,$query) or self::error($query);
		return mysqli_insert_id(self::$rs);
	}

	static function update($query) {
		if (is_null(self::$rs)) self::connect();
		mysqli_query(self::$rs,$query) or self::error($query);
		return mysqli_affected_rows(self::$rs);
	}
	
	static function delete($query) {
		if (is_null(self::$rs)) self::connect();
		mysqli_query(self::$rs,$query) or self::error($query);
		return mysqli_affected_rows(self::$rs);
	}	

	static function exec($query) {
		if (is_null(self::$rs)) self::connect();
		mysqli_query(self::$rs,$query) or self::error($query);
	}

	
	static function delete_request($table, $req_name, $field='id') {
		if (!isset($_REQUEST[$req_name])) return false;
		
		foreach ($_REQUEST[$req_name] as $key=>$value) $_REQUEST[$req_name][$key] = iS::n($value);
		$query = "DELETE FROM {$table} WHERE `{$field}` IN (".implode(',',$_REQUEST[$req_name]).")";	
		return iDB::exec($query);
	}
	
	static function safe_value_sql($key,$value) {
		// price
		if (in_array($key,array('____id'))) $value = iS::n($value);
		//elseif (in_array($key,array('time','time_add','time_create'))) $value = iS::s($value);
		elseif (in_array($key,array('price'))) $value = (float) $value;
		elseif (is_null($value)) $value = 'NULL';
		elseif ($value===FALSE) $value = "`{$key}`"	;	
		// elseif (strpos($key, 'time') === 0 || strpos($key, 'date') === 0) $value = 'FROM_UNIXTIME('.strtotime($value, time()).')';
		elseif (is_array($value)) $value = iS::sq(serialize($value));
		else $value = iS::sq($value);
		return $value;	
	}
	
	static function fields_to_str($data_fields) {
		$fields = '';
		
		foreach ($data_fields as $key=>$value) {
			$fields[] = '`'.$key.'`'; 	
		};
		
		return implode(',',$fields); 
	}
	
	
	static function insertSQL($into, $values_par, $ignore=TRUE) {
		//date_default_timezone_set($GLOBALS['_default_timezone']);
		
		
		if (!is_array($values_par)) trigger_error("Массив данных не является массивом",E_USER_WARNING);
		elseif (count($values_par)==0) trigger_error("Массив данных не содержит элементов",E_USER_WARNING);
		else {
			if (is_null(self::$rs)) self::connect();
			$fields = $values = [];
			$ignore ? $ignore = 'IGNORE' : $ignore = '';
			
			

			foreach ($values_par as $key=>$value) {
				$fields[] = '`'.$key.'`'; 	
				$values[] = self::safe_value_sql($key,$value);
			};
			$str_fields = implode(',',$fields); $str_values = implode(',',$values);
			
			
			
			mysqli_query(self::$rs,$query = "INSERT {$ignore} INTO `{$into}` ({$str_fields}) VALUES({$str_values})") or self::error($query);

			
			$insert_id = mysqli_insert_id(self::$rs);
			
			
			

			return $insert_id;
		};
		
		
		var_dump(debug_backtrace());
		return null;
	}		

	static function updateSQL($table, $values_par, $where, $entity_id = 0) {
		//date_default_timezone_set($GLOBALS['_default_timezone']);
		
		if (!is_array($values_par)) trigger_error("Массив данных не является массивом",E_USER_WARNING);
		elseif (count($values_par)==0) trigger_error("Массив данных не содержит элементов",E_USER_WARNING);
		else {
			
			
			if (is_null(self::$rs)) self::connect();
			$fields = $values = [];


			foreach ($values_par as $key=>$value) {
				if (!is_array($value) && strpos($value,'`')===0) "`{$key}`={$value}";
				else $values[] = "`{$key}`=".self::safe_value_sql($key,$value);
			};
			$str_values = implode(',',$values);
			
			
			if (isset($GLOBALS["_data_log"]["update"][$table])) self::log_update($table, $values_par, $where, $entity_id);
			mysqli_query(self::$rs,$query = "UPDATE `{$table}` SET {$str_values} WHERE {$where}") or self::error($query);


			//var_dump( $query );
		};
		return mysqli_affected_rows(self::$rs);
	}		

	




	static function insertDataSQL($into, $values_par, $ignore=TRUE) {
		//date_default_timezone_set($GLOBALS['_default_timezone']);
		
		
		if (!is_array($values_par)) trigger_error("Массив данных не является массивом",E_USER_ERROR);
		elseif (count($values_par)==0) trigger_error("Массив данных не содержит элементов",E_USER_ERROR);
		else {
			if (is_null(self::$rs)) self::connect();
			$fields = $values = [];
			$ignore ? $ignore = 'IGNORE' : $ignore = '';
			
			foreach ($values_par[0] as $key=>$value) {
				$fields[] = '`'.$key.'`'; 	
				//$values[] = self::safe_value_sql($key,$value);
			};
			
			$data_values = array();
			foreach ($values_par as $row) {
				$item_values = array();
				foreach ($row as $value) {
					$item_values[] = iS::sq($value);
				};
				$data_values[] = '(' . implode(',',$item_values) . ')';
			};
			$str_values = implode(",", $data_values);
			$str_fields = implode(',',$fields); 
			
			
			$query = "INSERT {$ignore} INTO `{$into}` ({$str_fields}) VALUES{$str_values}";
			$query = str_ireplace("'NULL'", 'NULL', $query);
			mysqli_query(self::$rs, $query) or self::error($query);
		};
		return null;
	}
	
	// проверяем существование таблицы	
	function check_table( $table_name ) {
		if (is_null(self::$rs)) self::connect();
		
		mysqli_query(self::$rs,$query = "SELECT id FROM `" . iS::s($table_name) . "` LIMIT 1");;			
		$res = mysqli_errno(self::$rs);
		
		if ($res != 0) {
			if ($res == 1146) {
				mysqli_query(self::$rs,$query = "CREATE TABLE `" . iS::s($table_name) . "` (
					`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'autocreated',
					PRIMARY KEY(`id`) ) ENGINE = InnoDB COMMENT = 'autocreated' CHARACTER SET utf8 COLLATE utf8_general_ci");
			} else trigger_error("Error API DBSET - unknown error for create table - {$res}", E_USER_ERROR);
		
		};	
	}
	
	// проверяем существование полей таблицы
	function check_fields( $table_name, $row_field ) {
		if (is_null(self::$rs)) self::connect();
		
		foreach ($row_field as $field) {
			mysqli_query(self::$rs,$query = "SELECT `{$field}` FROM `" . iS::s($table_name) . "` LIMIT 1");;			
			$res = mysqli_errno(self::$rs);
		
			if ($res != 0) {
				if ($res == 1054) {
					mysqli_query(self::$rs, $query = "ALTER TABLE `" . iS::s($table_name) . "` ADD COLUMN `{$field}` TEXT NOT NULL DEFAULT '' COMMENT 'autocreated'");
				} else trigger_error("Error API DBSET - unknown error for create column - {$res}", E_USER_ERROR);
			};
		};
	}	
	

}

?>
