<?php
if(!defined('IN_ZBC'))
	exit('Access Denied');

class LibDbMysql extends LibDbBase{
	private $link;

	function LibDbMysql(){
		extension_loaded('mysql') or die('mysql:Extension does not exist!');
	}

	function connect($silent=FALSE){
		$func=($this->pconnect?'mysql_pconnect':'mysql_connect');
		if(!($this->link=@$func($this->host,$this->user,$this->pwd)) && !$silent)
			$this->halt('Can not connect to MySQL server');

		if($this->version() > '4.1'){
			if($this->charset)
				@mysql_query("SET character_set_connection=$this->charset,character_set_results=$this->charset,character_set_client=binary",$this->link);

			if($this->version() > '5.0.1')
				@mysql_query("SET sql_mode=''",$this->link);
		}

		if($this->name && !$silent && !$this->sdb($this->name))
			$this->halt('Database does not exist');

		return is_resource($this->link)?true:false;
	}

	function sdb($name){
		return @mysql_select_db($name,$this->link);
	}

	function query($sql,$silent=FALSE,$retry=FALSE){
		if(IS_DEBUG){
			$stime=smicrotime();
		}
		if(($query=@mysql_query($sql,$this->link))==FALSE && !$silent){
			if(in_array($this->errno(), array(2006, 2013)) && $retry===FALSE) {
				$this->connect();
				return $this->query($sql,$silent,TRUE);
			}
			$this->halt('MySQL Query Error',$sql);
		}
		if(IS_DEBUG){
			$etime=smicrotime();
			$time=bcmul(bcsub($etime,$stime,8),1000,3);

			$explain=array();
			if($query && strtolower(substr($sql,0,6))=='select')
				$explain=@mysql_fetch_assoc(@mysql_query('EXPLAIN '.$sql,$this->link));
			$this->querys[]=array('sql'=>$sql,'time'=>$time,'info'=>@mysql_info(),'explain'=>$explain);
		}
		return $query;
	}

	function row($query){
		if(is_string($query)){
			$query=$this->query($query,1);
		}
		return $this->tidy(@mysql_fetch_assoc($query));
	}

	function arows(){
		return @mysql_affected_rows($this->link);
	}

	function result($query,$row,$field=0){
		if(is_string($query)){
			$query=$this->query($query);
		}
		return $this->tidy(@mysql_result($query,$row,$field));
	}

	function clean($query){
		return @mysql_free_result($query);
	}

	function insert_id(){
		return ($id=@mysql_insert_id($this->link)) >= 0?$id:$this->result('SELECT last_insertid()',0);
	}

	function version(){
		return @mysql_get_server_info($this->link);
	}

	function close(){
		return @mysql_close($this->link);
	}

	function error(){
		return (($this->link)?@mysql_error($this->link):@mysql_error());
	}

	function errno(){
		return intval(($this->link)?@mysql_errno($this->link):@mysql_errno());
	}
}
