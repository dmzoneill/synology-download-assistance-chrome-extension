<?php


global $stream, $declared_class_db;

if (!$stream && $declared_class_db!="yes")
{

class db 
{
	function graberrordesc() 
	{
		$this->error=mysql_error();
		return $this->error;
	}

	function graberrornum() 
	{
		$this->errornum=mysql_errno();
		return $this->errornum;
	}

	function connect()
	{
		global $mysql_host, $mysql_username, $mysql_password, $mysql_dbname;
		$this->db = @mysql_connect($mysql_host,$mysql_username,$mysql_password);
		@mysql_select_db($mysql_dbname, $this->db);
		@mysql_set_charset( 'utf8' , $this->db );
	}

	function do_query($query, $ret) 
	{
		$this->result = @mysql_query($query, $this->db);
		if (!$this->result || !$ret) 
		{
			echo "There was an error in the sql statement.<br /> mysql said: ".$this->graberrordesc();
			return "bad";
		} 
		else 
		{
			if ($ret=="array")
			{
				$this->return = array();
				while ($row = @mysql_fetch_row($this->result))
				{
					$this->return[] = $row;
				}
			} 
			elseif ($ret=="one")
			{
				$this->return = @mysql_result($this->result,0,0);
			} 
			elseif ($ret=="row")
			{
				$this->return = @mysql_fetch_row($this->result);
			} 
			else 
			{
				$this->return = "bad";
			}	
			return $this->return;
		}
	}

	function close()
	{
		@mysql_close($this->db);
	}
}

$declared_class_db = "yes";
}

$stream = new db;
$stream->connect();
