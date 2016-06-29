<?php
use PDO as PDO;

class SQLConnector {
	
	/* Connection Details */
	private $server;
	private $database;
	private $user;
	private $password;
	private $type;
	
	/* Active Instance */
	private $activeConnection;
	
		/* Metrics */
	public $lastQuery;
	public $lastSuccessfullQuery;
	public $lastRowCount;
	public $lastInsertID;
	
	function __construct($server, $database, $user, $password, $type = "mysql") {

		$this->server = $server;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->type = $type;
		
		$this->Connect();
		
	}
	
	// Instantiates db connection object
	public function Connect()
	{
		try
		{
			$this->activeConnection = new DatabaseConnection($this->type, $this->server, $this->database, $this->user, $this->password);
		}
		catch(PDOException $ex)
		{
			throw($ex);  
		}
	}
	
	public function IsAlive()
	{
		return $this->activeConnection->IsAlive();
	}
	
	// Destroys db connection object
	public function Disconnect()
	{
		$this->activeConnection = null;
	}
	
	public function ErrorInfo()
	{
		return $this->activeConnection->connection->errorInfo();
	}

	// Connects to the db, processes query and then returns an associative array of results.
	/*
	$table    = string
	$select   = key value array
	$join     = string
	$where    = key value array (value should include comparison)
	$order    = string  
	*/
	public function Select($table, $select = array(), $join = "", $where = array(), $order = "")
	{
		try 
		{          
			// Build SQL string
			$SQL = "SELECT ";
		
			if (count($select) > 0)
			{
				for($i = 0; $i < count($select); $i++)
				{
					$SQL .= $select[$i];
		
					if ($i < count($select) - 1)
						$SQL .= ", ";
				} 
			}
			else
			{
				$SQL .= " * ";
			}
		
			$SQL .= " FROM " . $table;
		
			if ($join != "")
				$SQL .= " " . $join . " ";
		
			// Split $where claues into fields and values    
			$fields = array();
			$values = array();
			
			if ($where != null) {
				foreach ($where as $key => $value) {
					$fields[] = $key;
					$values[] = $value;
				}
			}
		
			if (count($fields) > 0)
			{
				$SQL .= " WHERE ";
			
				for($i = 0; $i < count($fields); $i++)
				{
					$SQL .= $fields[$i] . $values[$i];
			
					if ($i < count($fields) - 1)
						$SQL .= " AND ";
				}  
			}
		
			if ($order != "")
				$SQL .= " ORDER BY " . $order;
		
			$SQL .= ";";
		
			$this->lastQuery = $SQL;

			$result = $this->activeConnection->connection->prepare($SQL);
			if ($result->execute($values))
			{
				$this->lastSuccessfullQuery = $SQL;
				$this->lastRowCount = $result->rowCount();
		
				return $result->fetchAll(PDO::FETCH_ASSOC);
			}
			else
			{
				return null;
			}
		} 
		catch(PDOException $ex) 
		{
			throw($ex);
		}  
	}
	
	
	// Connects to the db, processes query and then returns last insert ID.
	/*
	$table    = string
	$insert   = key value array
	*/
	public function Insert($table, $insert)
	{
		try 
		{              
			// Build SQL string
			$SQL = "INSERT INTO " . $table;
			
			// Split insert into fields and values
			$fields = array();
			$values = array();
			
			foreach ($insert as $key => $value) {
				$fields[] = $key;
				$values[] = $value;
			}
			
			if (count($fields) > 0)
			{
				$SQL .= " ( ";
			
				for($i = 0; $i < count($fields); $i++)
				{
					$SQL .= $fields[$i];
			
					if ($i < count($fields) - 1)
						$SQL .= ", ";
				}
			
				$SQL .= " ) ";
			}
			
			$SQL .= " VALUES ";
			
			if (count($values) > 0)
			{
				$SQL .= " ( ";
			
				for($i = 0; $i < count($values); $i++)
				{
					$SQL .= "'" . $values[$i] . "'";
					
					if ($i < count($values) - 1)
						$SQL .= ", ";
				} 
				
				$SQL .= " ) ";
			}
			
			$SQL .= ";";
			
			$this->lastQuery = $SQL;

			$result = $this->activeConnection->connection->prepare($SQL);
			if ($result->execute($values))
			{
				$this->lastInsertID = $this->activeConnection->connection->lastInsertID();
				
				$this->lastSuccessfullQuery = $SQL;
				$this->lastRowCount = $result->rowCount();
				
				if ($this->lastInsertID > 0)
					return true;
				else
					return false;
			}
			else
			{
				return false;
			}
		} 
		catch(PDOException $ex) 
		{
			throw($ex);
		}  
	}
	
	
	
	// Connects to the db, processes query and then returns last insert ID.
	/*
	$table    = string
	$update   = key value array
	$where    = key value array (value should include comparison)
	*/
	public function Update($table, $update, $where)
	{
		try 
		{              
			// Build SQL string
			$SQL = "UPDATE " . $table . " SET ";
			
			// Split update into fields and values
			$update_fields = array();
			$update_values = array();
			
			foreach ($update as $key => $value) {
				$update_fields[] = $key;
				$update_values[] = $value;
			}
			
			if (count($update_fields) > 0)
			{               
				for($i = 0; $i < count($update_fields); $i++)
				{
					$SQL .= $update_fields[$i] . ' = "' . $update_values[$i] . '"';
					
					if ($i < count($update_fields) - 1)
						$SQL .= ", ";
				}
			}
			
			// Split where into fields and values
			$where_fields = array();
			$where_values = array();
			
			foreach ($where as $key => $value) {
				$where_fields[] = $key;
				$where_values[] = $value;
			}
			
			if (count($where_fields) > 0)
			{
				$SQL .= " WHERE ";
				
				for($i = 0; $i < count($where_fields); $i++)
				{
					$SQL .= $where_fields[$i] . $where_values[$i];
					
					if ($i < count($where_fields) - 1)
						$SQL .= " AND ";
				}  
			}

			$SQL .= ";";
			
			$this->lastQuery = $SQL;

			$result = $this->activeConnection->connection->prepare($SQL);
			if ($result->execute(array_merge($update_values, $where_values)))
			{
				$this->lastSuccessfullQuery = $SQL;
				$this->lastRowCount = $result->rowCount();
				
				if ($this->lastRowCount > 0)
					return true;
				else
					return false;
			}
			else
			{
				return false;
			}
		} 
		catch(PDOException $ex) 
		{
			throw($ex);
		}  
	}
	
	
	// Connects to the db, processes query and then returns an associative array of results.
	/*
	$table    = string
	$where    = key value array (value should include comparison)
	*/
	public function Delete($table, $where)
	{
		try 
		{              
			// Build SQL string
			$SQL = "DELETE FROM " . $table;
			
			// Split $where claues into fields and values    
			$fields = array();
			$values = array();
			
			foreach ($where as $key => $value) {
				$fields[] = $key;
				$values[] = $value;
			}
			
			if (count($fields) > 0)
			{
				$SQL .= " WHERE ";
				
				for($i = 0; $i < count($fields); $i++)
				{
					$SQL .= $fields[$i] . $values[$i];
					
					if ($i < count($fields) - 1)
						$SQL .= " AND ";
				}   
			}

			$SQL .= ";";
			
			$this->lastQuery = $SQL;

			$result = $this->activeConnection->connection->prepare($SQL); 

			if ($result->execute($values))
			{
				$this->lastSuccessfullQuery = $SQL;
				$this->lastRowCount = $result->rowCount();
				
				if ($this->lastRowCount > 0)
					return true;
				else
					return false;
			}
			else
			{
				return false;
			}
		} 
		catch(PDOException $ex) 
		{
			throw($ex);
		}  
	}
}

class DatabaseConnection
{
	var $connection;
	
	/* Database Type */
	var $type;
	
	/* Connection Details */
	var $server;
	var $database;
	var $user;
	var $password;
	
	function __construct($type, $server, $database, $user, $password) {	
		$this->type = $type;
		$this->server = $server;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
			
		$this->Connect();	
	}
	
	function __sleep() {
		return array('type', 'server', 'database', 'user', 'password');
	}
	
	function __wakeup() {
		$this->Connect();
	}	
	
	// Instantiates db connection object
	function Connect() {
		try
		{
			$this->connection = new PDO($this->type . ":host=" . $this->server . ";dbname=" . $this->database . ";charset=utf8", $this->user, $this->password);
		}
		catch(PDOException $ex)
		{
			throw($ex);  
		}
	}
	
	// Destroys db connection object
	function Disconnect() {
		$this->connection = null;
	}
	
		
	function IsAlive() {
		try
		{
			$this->connection = new PDO($this->type . ":host=" . $this->server . ";dbname=" . $this->database . ";charset=utf8", $this->user, $this->password);
			return true;
		}
		catch (Exception $ex)
		{
			return false; 
		}
	}
}

?>