<?php

class API extends baseAPI {
	
	
	public function getAuth() {
		if ( $this->session_id ) {	
			$user_table = "users";
			$oUser = iDB::row_assoc("SELECT * FROM `{$user_table}` WHERE `id`=" . iS::n($_SESSION["user"]["id"]));
			

			$oUser["auth"] = true;	
			//$oUser["avatar"] = iDB::value("SELECT rel_url FROM uploads WHERE id=" . iS::n($oUser["avatar_id"]));
			
			unset( $oUser["password"] );
			$this->output = $oUser;
		}
	}
	

	public function postAuth() {
		
		if ( $params = $this->fetch_params( ["login", "password"] ) ) {
			$login = trim( $params["login"] );
			$password = trim( $params["password"] );
			
			
			$oUser = iDB::row_assoc("SELECT * FROM users WHERE `login`=" . iS::sq($login) . " AND `password`=" . iS::sq($password) );
			
			if ( !is_null($oUser) ) {
				unset( $oUser["password"] );	
				$_SESSION["user"] = $oUser;	
			
				$this->output = $_SESSION["user"];					
			} else {
				$this->error("You entered the wrong login or password");
				sleep(1);
			};
			
		};
	}

	
	public function deleteAuth() {
		unset ($_SESSION["user"] );
	}


	public function getData() {
		if ( !$this->session_role ) { $this->error401(); return false; }	

		if ( $this->session_role == "user" ) {
			$posada = trim(mb_strtolower( $_SESSION["user"]["posada"]));

			$this->output["data"]["templates"] = iDB::rows_assoc( $query = "SELECT * FROM templates WHERE posada=" . iS::sq( $posada ), [] );
			//$this->output["data"]["query"] = $query;
		} else {
			$this->output["data"]["templates"] = iDB::rows_assoc( "SELECT * FROM templates", [] );
			
		};

		// $this->output["data"]["role"] = $this->session_role;

	}	


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~			Managers

	public function getTemplatesDetail() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
			
		if ( $params = $this->fetch_params( ["id"] ) ) {	
			$item_id = iS::n( $params["id"] );
			$template = iDB::row_assoc( "SELECT * FROM templates WHERE id={$item_id}" );
			
			$fields = $field_names = [];
			
			if ( preg_match_all("#<<(.+?)>>#suix", $template["text"], $sub, PREG_SET_ORDER) ) {
				foreach ( $sub as $key => $item ) {
					if ( isset( $field_names[ $item[1] ] ) ) continue;
					$field_names[ $item[1] ] = true;
					$fields[] = [ "title" => $item[1], "name" => $item[1] ];
				};
			};		
			
			$this->output["data"]["fields"] = $fields;
		} else {
			$this->error("Error API");	
		}
	}
	
	
	public function getManager() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $params = $this->fetch_params( ["id"] ) ) {	
			$manager_id = iS::n( $params["id"] );
			$this->output["data"] = iDB::row("SELECT * FROM managers WHERE id={$manager_id}", []);
		} else {
			$this->error("Error API");	
		}
	}	
	
	
	public function patchManager() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $params = $this->fetch_params( ["id", "firstname", "lastname"], false) ) {	
			
			$manager_id = iS::n( $params["id"] );
			unset( $params["id"] );
			
			iDB::updateSQL("managers", $params, "id={$manager_id}");
			
			$this->success("Success", "Данные менеджера обновлены");	 
		} else {
			$this->error("Error API");	
		}
	}
	

	public function postManager() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		
		if ( $params = $this->fetch_params( ["firstname", "lastname"], false ) ) {
			 $manager_id = iDB::insertSQL("managers", $params);
			
			$this->output["data"] = iDB::row("SELECT * FROM managers WHERE id={$manager_id}");	 
			$this->success("Success", "Добавлен новый менеджер");	 
				
			 
		} else {
			$this->error("Error API");	
		};
			
	}


	public function deleteManager() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $params = $this->fetch_params( ["id"] ) ) {
			iDB::delete("DELETE FROM managers WHERE id=" . iS::n($params["id"]));
			$this->success("Success", "Менеджер был удален");	
		} else {
			$this->error("Error API");	
		}	
			
	}	


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~			Accounts

	public function getAccounts() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
			
		$query = "SELECT * FROM accounts";				
		$this->output["data"] = iDB::rows_assoc( $query, []);
	}


	public function patchAccount() {
		
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $in = $this->fetch_params( ["id", "managers_id", "invited_acc_name"] ) ) {	
			
			$account_id = iS::n( $in["id"] );
			unset( $in["id"] );
			
			$upd = [];
			
			if ( $in["managers_id"] == 'null' ) $in["managers_id"] = null;
			$upd["managers_id"] = $in["managers_id"];

			
			$invited_acc_name = trim( $in["invited_acc_name"] );
			if ( empty($invited_acc_name) ) $upd["invited_acc"] = null;
			else {
				
				$invited_acc = iDB::value("SELECT id FROM accounts WHERE `name`=" . iS::sq($invited_acc_name));
				
				//$this->output["invited_acc"] = $invited_acc;
				if ( !is_null($invited_acc) ) {
					$upd["invited_acc"] = $invited_acc;
					$upd["invited_acc_name"] = $invited_acc_name;
				} else $this->error("Error", "В базе нет такого приглашенного пользователя. Перепроверьте!"); 	

			}

			
			if ( !empty($upd) ) {
				iDB::updateSQL("accounts", $upd, "id={$account_id}");
				$this->success("Success", "Данные аккаунта обновлены");	 
			}
			
			$this->output["upd"] = $upd;
			$this->output["data"] = iDB::row("SELECT * FROM accounts WHERE id={$account_id}");	 
			
		} else {
			$this->error("Error API");	
		}
	}


	public function getSearchAccount() {
		if ( $params = $this->fetch_params( ["quote"] ) ) {
			
			$count_quotes = 7;
			$sql_word = iS::sq( $params["quote"] . "%");
			
			$this->output["data"] = iDB::rows_assoc("SELECT name FROM accounts WHERE name LIKE {$sql_word} LIMIT {$count_quotes}", []);
		}
	}
	

	public function getChart() {
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		if ( $params = $this->fetch_params( ["account_id"]) ) {	
		
			$account_id = iS::n( $params["account_id"] );
		
			$query = "SELECT time_to, avg_hashrate_24h FROM `data` WHERE referal={$account_id}";					
			$this->output["data"]["referal"] = iDB::rows_assoc( $query, []);
			
			$query = "SELECT time_to, SUM(avg_hashrate_24h) as avg_hashrate_24h FROM `data` D
				LEFT JOIN accounts A ON ( D.referal = A.id)
				WHERE D.referal={$account_id} OR A.invited_acc={$account_id}
				GROUP BY time_to";

			$this->output["data"]["invited_acc"] = iDB::rows_assoc( $query, []);
			
		} else {
			$this->error("Error API");	
		}
	}
	
	

	public function getNotifications() {
		if ( !$this->session_role ) { $this->error401(); return false; }
		
		$this->output["data"] = iDB::rows_assoc("SELECT * FROM notifications WHERE `red`=0", []);
		
		iDB::update( "UPDATE notifications SET `red`=1 WHERE `red`=0");
	}





	
	public function getServerTime() {
		$this->output["time"] = iDB::value("SELECT UNIX_TIMESTAMP()");
	}	
	


	
	function __construct() {
		parent::__construct();
	}		
	

	
	
}

