<?php 
 
class Mysql  
{ 
	private $link; 
	private $host; 
	private $user; 
	private $database; 
 
	private $last_query; 
	private $query_result; 
 
	function Mysql($user, $password, $database=NULL, $host='localhost') { 
		$this->host = $host; 
		$this->user = $user; 
 
		$this->link = mysql_pconnect($host, $user, $password, true); 
		if (!$this->link) { 
			throw new Exception( 'Could not connect: ' . mysql_error() ); 
		} 
 
		if ( $database != NULL ) { 
			$this->selectDatabase($database);
		        /*if(!$this->query("CREATE TABLE IF NOT EXISTS logs (".
		         "`id` INT( 7 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,".
			 "`url` VARCHAR( 255 ) NOT NULL,".
		 	 "`status` TEXT)")) {
				 die("could not create table: " .mysql_error());
			 }*/
			if(!$this->query("CREATE TABLE IF NOT EXISTS `logs` (
  			`id` int(7) NOT NULL auto_increment,
  			`url` varchar(255) NOT NULL,
		      	`status` text,
			`modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
			PRIMARY KEY  (`id`))")) {
				die("could not create table: " .mysql_error());
			}
			if(!$this->query("
				 CREATE TABLE IF NOT EXISTS `subjects` (
					 `id` INT NOT NULL AUTO_INCREMENT ,
					 `url` VARCHAR( 255 ) NOT NULL ,
					 `content` TEXT NOT NULL ,
					 `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					 PRIMARY KEY ( `id` ) ,
					 INDEX ( `id` )
				 ) ENGINE = MYISAM")) {
					 die("could not create table: " .mysql_error());
			}			
			if(!$this->query("
				CREATE TABLE IF NOT EXISTS `coverletters` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`url` VARCHAR( 255 ) NOT NULL ,
					`content` TEXT NOT NULL ,
					`modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					PRIMARY KEY ( `id` ) ,
					INDEX ( `id` )
				) ENGINE = MYISAM")) {
					die("could not create table: " .mysql_error());
			}
			if(!$this->query("
				CREATE TABLE IF NOT EXISTS `recipients` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`url` VARCHAR( 255 ) NOT NULL ,
					`content` TEXT NOT NULL ,
					`modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
					PRIMARY KEY ( `id` ) ,
					INDEX ( `id` )
				) ENGINE = MYISAM")) {
					die("could not create table: " .mysql_error());
			}

		} else {
			die("no database specified in config");//$this->database = NULL;
		}
	} 
	function __destruct() { 
		mysql_close($this->link); 
	} 
 
	public function selectDatabase($database) { 
		if ( !mysql_select_db($database, $this->link) ) { 
			throw new Exception ("Can\'t use $database : " . mysql_error()); 
		} 
 
		$this->database = $database; 
	} 
 
	public function query($query) { 
		$this->query_result = mysql_query ($query, $this->link);
		$this->last_query = $query; 
 		return ( $this->query_result ); 
	} 
 
	public function getNumRows($result=NULL) { 
		if ($result !== NULL) { 
			return mysql_num_rows($result); 
		} else { 
			return mysql_num_rows ($this->query_result); 
		} 
	} 
 
	public function num_rows($result=NULL) { 
		return $this->getNumRows ($result); 
	} 
 
	public function fetchAssoc ($result=NULL) { 
		if ($result !== NULL) { 
			return ( mysql_fetch_assoc ($result) ); 
		} else { 
			return ( mysql_fetch_assoc ($this->query_result) ); 
		} 
	} 
 
	public function getNextId($table) { 
		$sql = "SHOW TABLE STATUS LIKE '$table'"; 
		$result = mysql_query ($sql, $this->link); 
 
		$row = mysql_fetch_assoc($result); 
		return ($row['Auto_increment']); 
	} 
 
	public function makeSafe($string) { 
		return mysql_real_escape_string ($string, $this->link); 
	}
	
	public function getActiveDatabase()
	{
		return ($this->database);
	}
	
	public function tableExists($tableName) 
	{
		$sql = "SHOW TABLES LIKE '$tableName'";
		$this->query($sql);
		
		if ( $this->getNumRows() == 1 ) {
			return (true);
		} else {
			return (false);
		}
	}

	public function getError()
	{
		return mysql_error($this->link);
	}
} 
 
?>
