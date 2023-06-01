<?php

// v.1.0

class baseAPI {
	
	public $output = ["response" => [ "code" => 200 ]];
	
	public $success = true;

	
	public $post_params = null; 	
	public $method = null; 	
	public $request = null; 	
	
	
	public $scheme = [];
	
	
	public function fetch_params( $params, $strict = true ) {
		
		$res = [];
		
		if ( is_string($params) ) $params = [ $params ];
		foreach ( $params as $param_name ) {
			
			if ( in_array($this->method, ["get", "delete"]) ) {
				
				if ( !array_key_exists( $param_name , $_REQUEST) ) {
					if ( $strict ) $this->error( "Не передан параметр запроса - \"{$param_name}\"" );
				} else $res[ $param_name ] = $_REQUEST[ $param_name ];
				
			} else {
				
				if ( !array_key_exists( $param_name , $this->post_params) ) {
					if ( $strict ) $this->error( "Не передан параметр запроса - \"{$param_name}\"" );
				} else $res[ $param_name ] = $this->post_params[ $param_name ];
				
			}
		}
		
		return $this->success ? $res : false;
	}	
	
	
	public function pass_md5( $password ) {
		$password = trim( mb_strtolower( $password ));
		return md5( "Hsnd34fdds__sd3" . $password . "__dk3++:.d342dm,sm,csw34" );
	}	


	public function error( $error_name, $error_message = "" ) {
		$this->output["errors"][] = [ "name" => $error_name, "message" => $error_message ];
		$this->success = false;
	}


	public function error401( $error_name = "Error", $error_message = "Unauthorized" ) {
		$this->output["response"]["code"] = 401;
		$this->output["errors"][] = [ "name" => $error_name, "message" => $error_message ];
		$this->success = false;
	}


	public function error403( $error_name = "Error", $error_message = "Access denied" ) {
		$this->output["response"]["code"] = 403;
		$this->output["errors"][] = [ "name" => $error_name, "message" => $error_message ];
		$this->success = false;
	}
	
	
	public function success( $name, $message = "" ) {
		if ( !isset($this->output["messages"]) ) $this->output["messages"] = [];
		$this->output["messages"][] = [ "name" => $name, "message" => $message, "type" => "success" ];
	}
	

	public function output() {
		
		$this->output["success"] = isset($this->output["errors"]) ? false : $this->success;
		
		if ( !$this->output["success"] && !isset($this->output["errors"][0]) ) $this->output["errors"][] = [ "name" => "Unknown error" ];
		
		
		header('Content-Type: application/json; charset=utf-8');
		//http_response_code( $this->output["response"]["code"] );
		echo json_encode( $this->output );
		exit();
	}
	
	
	public function __get( $param_name ) {
		
		switch (	$param_name ) {
			case "session_role": 
				return isset($_SESSION["user"]["role"]) ? $_SESSION["user"]["role"] : false;
			break;
			case "session_id": 
				return isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : false;
			break;
			default: trigger_error( "Unknown baseApi property - {$param_name}", E_USER_ERROR );
		}		
		
	}

	

	public function __construct() {
		$this->post_params = json_decode(file_get_contents("php://input"), TRUE);
		$this->method = isset($_SERVER["REQUEST_METHOD"]) ? mb_strtolower ($_SERVER["REQUEST_METHOD"] ) : 'get';
		$this->request = isset( $_SERVER["PATH_INFO"] ) ? $this->method . mb_strtolower( $_SERVER["PATH_INFO"] ) : $this->method;		
	}
	
	
	
	protected function scheme_fields( $entity, $type ) {
		
		$scheme_fields = $def_scheme_fields = $remove_fields = [];
		
		if ( !isset( $this->scheme["entities"][$entity] ) ) {
			$this->error("Scheme entity \"{$entity}\" is not defined");	
			return false;
		};
		
		$scheme = $this->scheme["entities"][$entity];
		
		if ( isset($scheme[ $type ]["fields"]) ) $def_scheme_fields = $scheme[ $type ]["fields"];
		elseif ( $type == "post" && isset($scheme[ "patch" ]["fields"]) ) {
			$def_scheme_fields = $scheme[ "patch" ]["fields"];
			$remove_fields = [ "id", "time_created" ];
		} else {
			$this->error("Scheme fields for entity \"{$entity}\" are not defined");	
			return false;
		};
		
		if ( is_string( $def_scheme_fields ) ) {
			$fields = [];
			foreach ( explode(",", $def_scheme_fields) as $field ) {
				$field = trim($field);
				if ( !in_array( $field, $remove_fields ) ) $fields[] = $field;
			};
			$scheme_fields = $fields;
		} else {
			$scheme_fields = $def_scheme_fields;
		};
		
		return $scheme_fields;
	}
	
	protected function scheme_success( $entity, $type ) {
		$scheme = $this->scheme["entities"][$entity];
		
		$success_title = isset($scheme["success"]["title"]) ? $scheme["success"]["title"] : $this->scheme["success"]["title"];
		$success_text = isset($scheme["success"]["text"]) ? $scheme["success"]["text"] : $this->scheme["success"]["text"];
		$this->success( $success_title, $success_text );	 

	}
	
	
	public function getScheme( $entity ) {
		$scheme_fields = $this->scheme_fields( $entity, "patch" );
		
		if ( $params = $this->fetch_params( "id" , false ) ) {	
			
			if ( isset( $params["id"] ) ) {
				$data = iDB::row("SELECT * FROM `{$entity}` WHERE id=" . iS::n( $params["id"] ) );					
				if ( is_null($data) ) return false; 
				else {
					$this->output["data"][$entity] = $data;
					$this->output["data"]["modifications_author"] = iDB::row_assoc("SELECT U.name, M.time_created, M.row_id, M.`entity` FROM modifications M LEFT JOIN users U ON (U.id=M.user_id) WHERE M.row_id=". iS::n( $params["id"] ) ." AND M.`entity`= ". iS::sq($entity) ." ORDER BY M.id DESC LIMIT 1");
				}
			} else {
				$this->output["data"][$entity] = iDB::row("SELECT * FROM `{$entity}` ORDER BY id LIMIT 1000"  );					
			}
			//$this->scheme_success( $entity, "patch" );
		} else {
			$this->error("Error API");	
		}		
	}	

	public function getModifications() {
		if ( $params = $this->fetch_params( ["id", "entity"] ) ) {	
			
			$this->output["data"]["modifications"] = iDB::rows_assoc($query = "SELECT U.name as user_name, M.method, M.time_created, ML.field, ML.field_before, ML.field_after
FROM modifications M LEFT JOIN modification_logs ML ON (ML.modifications_id=M.id) LEFT JOIN users U ON (U.id=M.user_id)
				WHERE M.entity=". iS::sq( $params["entity"] ) ." AND M.row_id=" . iS::n( $params["id"]));
			
			// $this->output["data"]["query"] = $query;	
				
				
		} else {
			$this->error("Error API");	
		}		
	}	
	
	
	// modifications?item_id=orders-23
	
	
	function params_fields_to_string( array $fields ) {
		$output = [];
		foreach ( $fields as $field_name => $value ) $output[] = '`' . iS::s( trim($field_name) ) . '`';
		return implode(", ", $output); 
	}	
	
	public function logScheme( string $entity, array $fields, string $method, int $row_id = null ) {
		if ( !$this->session_id ) return false;

		$modifications_id = iDB::insertSQL( "modifications", [
			"entity" => $entity,
			"user_id" => $this->session_id,
			"method" => $method,
			"row_id" => $row_id
		]);
		
		if ( $method == "patch" ) {
			unset( $fields["id"] );
			
			$fields_str = $this->params_fields_to_string( $fields );
			$row_prev = iDB::row_assoc( "SELECT {$fields_str} FROM `". iS::s( $entity ) ."` WHERE id=" . iS::n($row_id) );
			if ( !is_null($row_prev) ) {
				
				$data_log = [];
				foreach ( $row_prev as $field_name => $value ) {
					if ( $row_prev[ $field_name ] != $fields[ $field_name ] ) {
						$data_log[] = [
							"field" => $field_name,
							"field_before" => $row_prev[ $field_name ],
							"field_after" => $fields[ $field_name ],
							"modifications_id" => $modifications_id
						];
					};
				};

				if ( !empty($data_log) ) iDB::insertDataSQL( "modification_logs", $data_log );	
			}
		};
		
		return true;
	}
	
	public function patchScheme( $entity ) {
		$scheme_fields = $this->scheme_fields( $entity, "patch" );
		
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $params = $this->fetch_params( $scheme_fields , false ) ) {	
			
			if ( is_array($params["id"]) ) {
				$ids = [];
				foreach ( $params["id"] as $id ) {
					$ids[] = $id;
					$this->logScheme( $entity, $params, "patch", $id );
				};
				
				$ids = implode(", ", $ids);
			} else {
				$ids = iS::n( $params["id"] );	
				$this->logScheme( $entity, $params, "patch", $ids );
			};
			
			unset( $params["id"] );
			
			iDB::updateSQL($entity, $params, "id IN ({$ids})");
			
			$this->scheme_success( $entity, "patch" );
		} else {
			$this->error("Error API");	
		}		
		
	}	
	
	
	public function postScheme( $entity ) {
		$scheme_fields = $this->scheme_fields( $entity, "post" );
		
		if ( $params = $this->fetch_params( $scheme_fields , false ) ) {	
			
			
			$item_id = iDB::insertSQL($entity, $params);
			$this->logScheme( $entity, $params, "post", $item_id );
			
			$this->output["data"] = iDB::row("SELECT * FROM `{$entity}` WHERE id={$item_id}");				

			$this->scheme_success( $entity, "patch" );
		} else {
			$this->error("Error API");	
		}		

	}
	
	public function deleteScheme( $entity ) {
		
		$scheme = $this->scheme["entities"][$entity];
		
		if ( !$this->session_role ) { $this->error401(); return false; }

		if ( $params = $this->fetch_params( ["id"] ) ) {
			
			if ( is_array($params["id"]) ) {
				$ids = [];
				foreach ( $params["id"] as $id ) {
					$ids[] = $id;
					$this->logScheme( $entity, [], "delete", $id );
				};
				
				$ids = implode(", ", $ids);
			} else {
				$ids = iS::n( $params["id"] );	
				$this->logScheme( $entity, [], "delete", $ids );
			};
			
			iDB::delete("DELETE FROM `{$entity}` WHERE id IN ({$ids})");

			$success_title = isset($scheme["success"]["title"]) ? $scheme["success"]["title"] : $this->scheme["success"]["title"];
			$success_text = isset($scheme["success"]["text"]) ? $scheme["success"]["text"] : $this->scheme["success"]["text"];
			$this->success( $success_title, $success_text );
			
		} else {
			$this->error("Error API");	
		}
		
	}


	public function copyRows() {
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $params = $this->fetch_params( ["ids", "entity"] ) ) {
			$entity = iS::s( $params["entity"] );
			$scheme_fields = $this->scheme_fields( $entity, "post" );
			$scheme_fields = implode(", ", $scheme_fields);
			
			foreach ( $params["ids"] as $id ) {
				$id = iS::n( $id );
				
				$insert_id = iDB::insert( $query = "INSERT INTO `{$entity}`( {$scheme_fields} ) SELECT {$scheme_fields} FROM `{$entity}` WHERE id={$id}");
				$this->output[ "data" ][] = iDB::row_assoc( "SELECT * FROM `{$entity}` WHERE id={$insert_id}" );
			}		
			

			$this->success( "Успішно", "Скопійовано " . count($params["ids"]) . " рядки" );
		} else {
			$this->error("Error API");	
		}
			
	}	
	
	
}