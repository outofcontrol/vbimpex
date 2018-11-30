<?php

if (is_callable('mysql_connect')) {
	return;
}

define('MYSQL_BOTH',MYSQLI_BOTH);
define('MYSQL_NUM',MYSQLI_NUM);
define('MYSQL_ASSOC',MYSQLI_ASSOC);
define('MYSQL_CLIENT_COMPRESS',MYSQLI_CLIENT_COMPRESS);
define('MYSQL_CLIENT_IGNORE_SPACE',MYSQLI_CLIENT_IGNORE_SPACE);
define('MYSQL_CLIENT_INTERACTIVE',MYSQLI_CLIENT_INTERACTIVE);
define('MYSQL_CLIENT_SSL',MYSQLI_CLIENT_SSL);

/**
 * @param  resource  $connection
 * @return int
 **/
if (!function_exists('mysql_affected_rows')) {
	function mysql_affected_rows($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->affected_rows;
	}
}

/**
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_client_encoding')) {
	function mysql_client_encoding ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_character_set_name($connection);
	}
}

/**
 * @param  resource  $connection
 * @return bool
 **/
if (!function_exists('mysql_close')) {
	function mysql_close ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_close($connection);
	}
}

/**
 * @param  string  $server
 * @param  string  $nome_utente
 * @param  string  $password
 * @param  int  $flag_client
 * @return resource
 **/
if (!function_exists('mysql_pconnect')) {
	function mysql_pconnect($server='' ,$nome_utente='' ,$password='' ,$flag_client=0) {
		return mysql_connect($server,$nome_utente,$password,false,$flag_client,'p:');
	}
}

/**
 * @param  string  $server  es: 127.0.0.1 or 127.0.0.1:3306 or localhost:/var/run/mysqld/mysqld.sock or :/var/run/mysqld/mysqld.sock  
 * @param  string  $nome_utente
 * @param  string  $password
 * @param  bool  $nuova_connessione
 * @param  int  $client_flags
 * @return resource
 **/
if (!function_exists('mysql_connect')) {
	function mysql_connect ($server='' ,$nome_utente='' ,$password='' ,$nuova_connessione=false ,$client_flags=0,$persistente='') {
		if (func_num_args()===0 && $_SERVER['MYSQL_CONN']) foreach ($_SERVER['MYSQL_CONN'] as $hash=>&$conns) return $conns;
								
		$hash=sha1(serialize(func_get_args()));
		if (!$nuova_connessione && $_SERVER['MYSQL_CONN'][$hash]) return $_SERVER['MYSQL_CONN'][$hash];
		
		if (!$server)	  $server = ini_get("mysqli.default_host");
		$server=trim($server);
		if (!$nome_utente) $nome_utente = ini_get("mysqli.default_user");
		if (!$password)    $password= ini_get("mysqli.default_pw"); 
		
		$link = mysqli_init();
		
		$socket=null;
		if (strpos($server,':')!==false) list($server,$porta)=explode(':',$server,2);
								else $porta=ini_get("mysqli.default_port"); 
		if (!$server) $server='localhost';			  
		if (!is_numeric($porta)) {
								$socket=$porta;
								$porta=null;
								}
		if (!$porta && $porta!==null) $porta=3306;
		$ok=@mysqli_real_connect($link,$persistente.$server, $nome_utente, $password, '', $porta, $socket, $client_flags);
		if (!$ok) {
			return false;
		}
		$_SERVER['MYSQL_CONN'][$hash]=&$link;
		return $link;
	}
}

/**
 * @param  string  $nome_database
 * @param  resource  $connection
 * @return bool
 **/
if (!function_exists('mysql_create_db')) {
	function mysql_create_db ($nome_database ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return !@mysqli_query($connection,"create database `$nome_database`"); 
	}
}

/**
 * @param  resource  $identificativo_risultato
 * @param  int  $numero_riga
 * @return bool
 **/
if (!function_exists('mysql_data_seek')) {
	function mysql_data_seek ($identificativo_risultato ,$numero_riga) {
		mysqli_store_result($identificativo_risultato);
		return @mysqli_data_seek( $identificativo_risultato , $numero_riga );
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $riga
 * @param  mixed  $campo
 * @return string
 **/
if (!function_exists('mysql_db_name')) {
	function mysql_db_name ($risultato ,$riga ,$campo=null) {
		if (!@mysqli_data_seek( $risultato , $riga )) return false;
		$riga = mysqli_fetch_assoc($risultato);
		if (!$campo) {
			return $riga['Database'];
		} else {
			return $riga[$campo];
		}
	}
}

/**
 * @param  string  $database
 * @param  string  $query
 * @param  resource  $connection
 * @return resource
 **/
if (!function_exists('mysql_db_query')) {
	function mysql_db_query ($database ,$query ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
	    $rs=mysqli_query($connection,"select database()");
		$prec_db=@mysqli_fetch_row ($rs);
		@mysqli_query($connection,"use `$database`",MYSQLI_USE_RESULT);
		$rs=mysqli_query($connection,$query,MYSQLI_STORE_RESULT);
		if (strtolower($prec_db[0])!=strtolower($database)) {
			@mysqli_query($connection,"use `{$prec_db[0]}`",MYSQLI_USE_RESULT);
		}
		return $rs; 
	}
}

/**
 * @param  string  $nome_database
 * @param  resource  $connection
 * @return bool
 **/
if (!function_exists('mysql_drop_db')) {
	function mysql_drop_db ($nome_database ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return !@mysqli_query($connection,"drop database `$nome_database`");
	}
}

/**
 * @param  resource  $connection
 * @return int
 **/
if (!function_exists('mysql_errno')) {
	function mysql_errno ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->errno;
	}
}

/**
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_error')) {
	function mysql_error ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->error;
	}
}

/**
 * @param  string  $stringa_senza_escape
 * @return string
 **/
if (!function_exists('mysql_escape_string')) {
	function mysql_escape_string ($stringa_senza_escape) {
		return @mysql_real_escape_string($stringa_senza_escape);
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $tipo_risultato
 * @return array
 **/
if (!function_exists('mysql_fetch_array')) {
	function mysql_fetch_array ($risultato ,$tipo_risultato=null) {
		if ($tipo_risultato===null) {
			$tipo_risultato=MYSQL_BOTH;
		}
		return @mysqli_fetch_array($risultato ,$tipo_risultato);
	}
}

/**
 * @param  resource  $risultato
 * @return array
 **/
if (!function_exists('mysql_fetch_assoc')) {
	function mysql_fetch_assoc ($risultato) {
		return @mysqli_fetch_assoc($risultato);
	}
}

 

/**
 * @param  resource  $risultato
 * @return array
 **/
if (!function_exists('mysql_fetch_lengths')) {
	function mysql_fetch_lengths ($risultato) {
		return @mysqli_fetch_lengths($risultato);
	}
}

/**
 * @param  resource  $risultato
 * @return object
 **/
if (!function_exists('mysql_fetch_object')) {
	function mysql_fetch_object ($risultato) {
		return @mysqli_fetch_object($risultato);
	}
}

/**
 * @param  resource  $risultato
 * @return array
 **/
if (!function_exists('mysql_fetch_row')) {
	function mysql_fetch_row ($risultato) {
		return @mysqli_fetch_row ($risultato);
	}
}


/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return int
 **/
if (!function_exists('mysql_field_seek')) {
	function mysql_field_seek ($risultato ,$indice_campo) {
		return @mysqli_field_seek($risultato ,$indice_campo);
	}
}


/**
 * @param  resource  $risultato
 * @return bool
 **/
if (!function_exists('mysql_free_result')) {
	function mysql_free_result ($risultato) {
		@mysqli_free_result ($risultato);
	}
}

/**
 * @return string
 **/
if (!function_exists('mysql_get_client_info')) {
	function mysql_get_client_info () {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->client_info;
	}
}

/**
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_get_host_info')) {
	function mysql_get_host_info($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->host_info;
	}
}

/**
 * @param  resource  $connection
 * @return int
 **/
if (!function_exists('mysql_get_proto_info')) {
	function mysql_get_proto_info($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->protocol_version;
	}
}

/**
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_get_server_info')) {
	function mysql_get_server_info ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->server_info;
	}
}

/**
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_info')) {
	function mysql_info ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->info;
	}
}

/**
 * @param  resource  $connection
 * @return int
 **/
if (!function_exists('mysql_insert_id')) {
	function mysql_insert_id ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->insert_id;
	}
}

/**
 * @param  resource  $connection
 * @return resource
 **/
if (!function_exists('mysql_list_dbs')) {
	function mysql_list_dbs ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		$rs = @mysqli_query($connection,'SHOW DATABASES');
		@mysqli_store_result($rs);
		return $rs;
	}
}

 
/**
 * @param  resource  $risultato
 * @return int
 **/
if (!function_exists('mysql_list_fields')) {
	function mysql_num_fields ($risultato) {
		return @mysqli_num_fields ($risultato);
	}
}

/**
 * @param  resource  $risultato
 * @return int
 **/
if (!function_exists('mysql_num_rows')) {
	function mysql_num_rows ($risultato) {
		return @mysqli_num_rows($risultato);
	}
}



/**
 * @param  resource  $connection
 * @return bool
 **/
if (!function_exists('mysql_ping')) {
	function mysql_ping ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_ping($connection);
	}
}



/**
 * @param  string  $query
 * @param  resource  $connection
 * @return resource
 **/
if (!function_exists('mysql_query')) {
	function mysql_query ($query ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_query($connection,$query );
	}
}

/**
 * @param  string  $stringa_seza_escape
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_real_escape_string')) {
	function mysql_real_escape_string ($stringa_seza_escape ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_real_escape_string($connection,$stringa_seza_escape);
	}
}


/**
 * @param  string  $nome_database
 * @param  resource  $connection
 * @return bool
 **/
if (!function_exists('mysql_select_db')) {
	function mysql_select_db ($nome_database ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_select_db($connection,$nome_database);
	}
}

/**
 * @param  string  $charset
 * @param  resource  $connection
 * @return bool
 **/
if (!function_exists('mysql_set_charset')) {
	function mysql_set_charset ($charset ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_set_charset($connection,$charset);
	}
}

/**
 * @param  resource  $connection
 * @return string
 **/
if (!function_exists('mysql_stat')) {
	function mysql_stat ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		if (!is_object($connection)) {
			return false;
		}
		return $connection->stat;
	}
}

/**
 * @param  resource  $connection
 * @return int
 **/
if (!function_exists('mysql_thread_id')) {
	function mysql_thread_id ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return  (!is_object($connection)) ? false : $connection->thread_id;
	}
}

/**
 * @param  string  $query
 * @param  resource  $connection
 * @return resource
 **/
if (!function_exists('mysql_unbuffered_query')) {
	function mysql_unbuffered_query($query ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_query($connection,$query,0);
	}
}



/**
 * @param  string  $nome_database
 * @param  string  $nome_tabella
 * @param  resource  $connection
 * @return resource
 **/
if (!function_exists('mysql_list_fields')) {
	function mysql_list_fields ($nome_database ,$nome_tabella ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_query($connection,"select * FROM `$nome_database`.`$nome_tabella` limit 1");
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return string
 **/
if (!function_exists('mysql_field_name')) {
	function mysql_field_name ($risultato ,$indice_campo) {
		$info=@mysqli_fetch_field_direct($risultato,$indice_campo);
		return $info->name;
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return string
 **/
if (!function_exists('mysql_field_flags')) {
	function mysql_field_flags ($risultato ,$indice_campo) {
		$info=@mysqli_fetch_field_direct($risultato,$indice_campo);
		return $info->flags;
	}
}


/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return int
 **/
if (!function_exists('mysql_field_len')) {
	function mysql_field_len($risultato ,$indice_campo) {
		$info=@mysqli_fetch_field_direct($risultato,$indice_campo);
		return $info->length;
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return string
 **/
if (!function_exists('mysql_field_type')) {
	function mysql_field_type ($risultato ,$indice_campo) {
		$info=@mysql_fetch_field($risultato,$indice_campo);
		return $info->type;
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return string
 **/
if (!function_exists('mysql_field_table')) {
	function mysql_field_table ($risultato ,$indice_campo) {
		$info = @mysqli_fetch_field_direct($risultato,$indice_campo);
		return $info->table;
	}
}

/**
 * @param  resource  $connection
 * @return resource
 **/
if (!function_exists('mysql_list_processes')) {
	function mysql_list_processes ($connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_query($connection,"show processlist",MYSQLI_STORE_RESULT);
	}
}

/**     
 * @param  string  $database
 * @param  resource  $identificativoi_connessione
 * @return resource
 **/
if (!function_exists('mysql_list_tables')) {
	function mysql_list_tables ($database ,$connection=null) {
		if (!$connection) {
			$connection=mysql_connect();
		}
		return @mysqli_query($connection,"SHOW TABLES",MYSQLI_STORE_RESULT);
	}
}


/**
 * @param  resource  $risultato
 * @param  int  $i
 * @return string
 **/
if (!function_exists('mysql_tablename')) {
	function mysql_tablename ($risultato ,$i) {
		@mysqli_data_seek($risultato,$i);
		$row=@mysqli_fetch_row($risultato);
		return $row[0];
	}
}


/**
 * @param  resource  $risultato
 * @param  int  $campo
 * @param  mixed  $campo
 * @return mixed
 **/
if (!function_exists('mysql_result')) {
	function mysql_result ($risultato ,$riga ,$colonna=null) {
		$esito=@mysqli_data_seek($risultato,$riga);
		if (!$esito && $colonna!==null) return @mysqli_field_seek($risultato,$colonna);
		return $esito;
	}
}

/**
 * @param  resource  $risultato
 * @param  int  $indice_campo
 * @return object
 **/
if (!function_exists('mysql_fetch_field')) {
	function mysql_fetch_field ($risultato ,$indice_campo=0) {
		mysqli_field_seek($risultato, $indice_campo);
		$info=mysqli_fetch_field($risultato);
		
		$out=new stdclass();
		$out->name=$info->name;
		$out->table=$info->table;
		$out->def='';
		$out->max_length=$info->max_length;
		$infos=array();
		if ($info->orgtable && $info->db && $info->orgname) {
			$rs=mysqli_query(mysql_connect(),"select is_nullable,column_key,numeric_precision,column_type
													from `information_schema`.`COLUMNS` where
													table_schema='{$info->db}' and
													table_name='{$info->orgtable}' and
													column_name='{$info->orgname}' limit 1");
			$infos=mysqli_fetch_assoc($rs);
		}
				
		$out->not_null=($infos['is_nullable']=='YES'?0:1);
		$out->primary_key=($infos['column_key']=='PRI'?1:0);
		$out->multiple_key=($infos['column_key']=='MUL'?1:0);
		$out->unique_key=($infos['column_key']=='UNI'?1:0);
		$out->numeric=($infos['numeric_precision']>0?1:0);
		$out->blob=intval(preg_match('/blob$/',$infos['column_type']));
		$out->type=$info->type;
		$out->unsigned=intval(stripos(" {$infos['column_type']} ",' unsigned ')!==false);
		$out->zerofill=intval(stripos(" {$infos['column_type']} ",' zerofill ')!==false);

		switch ($info->type) {
			case 4:
			case 5:
			case 246:
				$out->type='real';break;
			case 7:
				$out->type='timestamp';
				$out->unsigned=1;
				$out->zerofill=1;
				break;
			case 10:
				$out->type='date';break;
			case 11:
				$out->type='time';break;
			case 12:
				$out->type='datetime';break;
			case 13:
				$out->type='year';
				$out->unsigned=1;
				$out->zerofill=1;
				break;
			case 16:
				$out->type = 'int';
				$out->numeric = 0;
				$out->unsigned = 1;
				$out->zerofill = 0;
				break;	
			case 255:
				$out->type='geometry';
				$out->blob=1;
				break;
			case 252:
				$out->type='blob';
				$out->blob=1;
				break;
			case 253:
			case 254:
				$out->type='string';
				break;
		}
		return $out;
	}
}
