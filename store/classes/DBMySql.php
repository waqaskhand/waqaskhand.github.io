<?php
class DBMySql {
	
	function __construct()
	{
		$this->Database		= DATABASE_NAME;
		$this->Host			= DATABASE_HOST;
		$this->User			= DATABASE_USER;
		$this->Password 	= DATABASE_PASSWORD;
	}
  
  /* public: connection parameters */
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";
  var $RowsCount=""; 
  var $GetTotalRowsInDB='';

  /* public: configuration parameters */
  var $Auto_Free     = 0;     ## Set to 1 for automatic mysql_free_result()
  var $Debug         = 0;     ## Set to 1 for debugging messages.
  var $Halt_On_Error = "yes"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
  var $Seq_Table     = "db_sequence";

  /* public: result array and current row number */
  var $Record   = array();
  var $Row;

  /* public: current error number and error text */
  var $Errno    = 0;
  var $Error    = "";

  /* public: this is an api revision, not a CVS revision. */
  var $type     = "mysql";
  var $revision = "1.2";

  /* private: link and query handles */
  var $Link_ID  = 0;
  var $Query_ID = 0;

  /* public: constructor */
  function DB_Sql($query = "") {
      $this->query($this->Link_ID,$query);
  }

  /* public: some trivial reporting */
  function link_id() {
    return $this->Link_ID;
  }

  function query_id() {
    return $this->Query_ID;
  }

  /* public: connection management */
  function connect($Database = "", $Host = "", $User = "", $Password = "") {
    /* Handle defaults */
    if ("" == $Database)
      $Database = $this->Database;
    if ("" == $Host)
      $Host     = $this->Host;
    if ("" == $User)
      $User     = $this->User;
    if ("" == $Password)
      $Password = $this->Password;
      
    /* establish connection, select database */
    if ( 0 == $this->Link_ID ) {
      $this->Link_ID=mysqli_connect($Host, $User, $Password);
      if (!$this->Link_ID) {
	  //echo $Database;
        $this->halt("connect($Host, $User, \$Password) failed.");
        return 0;
      }

      if (!@mysqli_select_db($this->Link_ID,$Database)) {
        $this->halt("cannot use database ".$this->Database);
        return 0;
      }
    }
    
    return $this->Link_ID;
  }

  /* public: discard the query result */
  function free() {
      @mysqli_free_result($this->Query_ID);
      $this->Query_ID = 0;
  }

  /* public: perform a query */
  function query($Query_String) {
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;

    if (!$this->connect()) {
      return 0; /* we already complained in connect() about that. */
    };

    # New query, discard previous result.
    if ($this->Query_ID) {
      $this->free();
    }

    if ($this->Debug)
      printf("Debug: query = %s<br>\n", $Query_String);

    $this->Query_ID = @mysqli_query($this->Link_ID,$Query_String);
    $this->Row   = 0;
    $this->Errno = mysqli_errno();
    $this->Error = mysqli_error();
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }

    # Will return nada if it fails. That's fine.
    return $this->Query_ID;
  }
  /* public: walk result set */
  function next_record() {
    if (!$this->Query_ID) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }
    $this->Record = @mysqli_fetch_array($this->Query_ID);
    $this->Row   += 1;
    $this->Errno  = mysqli_errno();
    $this->Error  = mysqli_error();

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_Free) {
      $this->free();
    }
    return $stat;
  }
  /* public: position in result set */
  function seek($pos = 0) {
    $status = @mysqli_data_seek($this->Query_ID, $pos);
    if ($status)
      $this->Row = $pos;
    else {
      $this->halt("seek($pos) failed: result has ".$this->num_rows()." rows");

      /* half assed attempt to save the day, 
       * but do not consider this documented or even
       * desireable behaviour.
       */
      @mysqli_data_seek($this->Query_ID, $this->num_rows());
      $this->Row = $this->num_rows;
      return 0;
    }

    return 1;
  }

  /* public: table locking */
  function lock($table, $mode="write") {
    $this->connect();
    
    $query="lock tables ";
    if (is_array($table)) {
      while (list($key,$value)=each($table)) {
        if ($key=="read" && $key!=0) {
          $query.="$value read, ";
        } else {
          $query.="$value $mode, ";
        }
      }
      $query=substr($query,0,-2);
    } else {
      $query.="$table $mode";
    }
    $res = @mysqli_query($this->Link_ID,$query);
    if (!$res) {
      $this->halt("lock($table, $mode) failed.");
      return 0;
    }
    return $res;
  }

  function unlock() {
    $this->connect();

    $res = @mysqli_query($this->Link_ID,"unlock tables");
    if (!$res) {
      $this->halt("unlock() failed.");
      return 0;
    }
    return $res;
  }


  /* public: evaluate the result (size, width) */
  function affected_rows() {
    return @mysqli_affected_rows($this->Link_ID);
  }

  function num_rows() {
    return @mysqli_num_rows($this->Query_ID);
  }
  function list_fields(){
//      return @mysql_list_fields($this->Database, $table);`
echo $table;
  }

  function num_fields() {
    return @mysqli_num_fields($this->Query_ID);
  }

  /* public: shorthand notation */
  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
    if(isset($this->Record[$Name]))
      return $this->Record[$Name];
    else 
      return "";
  }

  function p($Name) {
    print $this->Record[$Name];
  }

  /* public: sequence numbers */
  function nextid($seq_name) {
    $this->connect();
    
    if ($this->lock($this->Seq_Table)) {
      /* get sequence number (locked) and increment */
      $q  = sprintf("select nextid from %s where seq_name = '%s'",
                $this->Seq_Table,
                $seq_name);
      $id  = @mysqli_query($this->Link_ID,$q);
      $res = @mysqli_fetch_array($id);
      
      /* No current value, make one */
      if (!is_array($res)) {
        $currentid = 0;
        $q = sprintf("insert into %s values('%s', %s)",
                 $this->Seq_Table,
                 $seq_name,
                 $currentid);
        $id = @mysqli_query($this->Link_ID,$q);
      } else {
        $currentid = $res["nextid"];
      }
      $nextid = $currentid + 1;
      $q = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
               $this->Seq_Table,
               $nextid,
               $seq_name);
      $id = @mysqli_query($this->Link_ID,$q);
      $this->unlock();
    } else {
      $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");
      return 0;
    }
    return $nextid;
  }

  /* public: return table metadata */
  function metadata($table='',$full=false) {
    $count = 0;
    $id    = 0;
    $res   = array();

    /*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *   ["meta"][field name]  index of field named "field name"
     *   The last one is used, if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) { ...
     */

    // if no $table specified, assume that we are working with a query
    // result
    if ($table) {
      $this->connect();
      $id = @mysql_list_fields($this->Database, $table);
      if (!$id)
        $this->halt("Metadata query failed.");
    } else {
      $id = $this->Query_ID; 
      if (!$id)
        $this->halt("No query specified.");
    }
 
    $count = @mysqli_num_fields($id);

    // made this IF due to performance (one if is faster than $count if's)
    if (!$full) {
      for ($i=0; $i<$count; $i++) {
        $res[$i]["table"] = @mysql_field_table ($id, $i);
        $res[$i]["name"]  = @mysql_field_name  ($id, $i);
        $res[$i]["type"]  = @mysql_field_type  ($id, $i);
        $res[$i]["len"]   = @mysql_field_len   ($id, $i);
        $res[$i]["flags"] = @mysql_field_flags ($id, $i);
      }
    } else { // full
      $res["num_fields"]= $count;
    
      for ($i=0; $i<$count; $i++) {
        $res[$i]["table"] = @mysql_field_table ($id, $i);
        $res[$i]["name"]  = @mysql_field_name  ($id, $i);
        $res[$i]["type"]  = @mysql_field_type  ($id, $i);
        $res[$i]["len"]   = @mysql_field_len   ($id, $i);
        $res[$i]["flags"] = @mysql_field_flags ($id, $i);
        $res["meta"][$res[$i]["name"]] = $i;
      }
    }
    
    // free the result only if we were called on a table
    if ($table) @mysqli_free_result($id);
    return $res;
  }
  
  function CountTableField()
  {
	  $count=mysqli_num_fields($result);
	  return $count;
  }
  function TableFieldName($i, $table)
  {
	  //echo $i.'<br>'.$table;
	  //$id = @mysql_list_fields($this->Database, $table);
	  //return mysql_field_name($id, $i);
	  
	   //$id = @mysqli_fetch_fields($this->Query_ID);
	   //echo  mysqli_fetch_field_direct($this->Query_ID,$i);exit;
	   
	   return $this->mysqli_field_name($this->Query_ID,$i);
	   
  }
  
	function mysqli_field_name($result, $field_offset)
	{
	$properties = mysqli_fetch_field_direct($result, $field_offset);
	return is_object($properties) ? $properties->name : null;
	}
  
  function MysqlInsertID()
  {
	  return mysqli_insert_id($this->Link_ID);
  }

  /* private: error handling */
  function halt($msg) {
    $this->Error = @mysqli_error($this->Link_ID);
    $this->Errno = @mysqli_errno($this->Link_ID);
    if ($this->Halt_On_Error == "no")
      return;

    $this->haltmsg($msg);

    if ($this->Halt_On_Error != "report")
      die("Session halted, please retry!<br><a href=javascript:history.go(-1)>Go Back</a>");
  }

  function haltmsg($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);
	  return Errno;
  }

  function table_names() {
    $this->query("SHOW TABLES");
    $i=0;
    while ($info=mysqli_fetch_row($this->Query_ID))
     {
      $return[$i]["table_name"]= $info[0];
      $return[$i]["tablespace_name"]=$this->Database;
      $return[$i]["database"]=$this->Database;
      $i++;
     }
   return $return;
  }
  
	function GetRecords($FieldsToGet='*', $Where='', $OrderBy='', $Limit='', $table='')
	{
		if($table=='')
			$table = $this->_table;
		
		if(!isset($_REQUEST['start']))
			$_REQUEST['start']=0;
			
		$Paging		= $this->Paging($_REQUEST['start']);
		$Where		= $this->SetWhere($_REQUEST);
		$OrderBy	= $this->SetOrder();
		
		if($FieldsToGet!='*')
			$FieldsToGet = implode(", ", $FieldsToGet);
		
		$Query = "select ".$FieldsToGet." from ".$table.$this->AddWhereInQuery().$this->AddOrderByInQuery();

		$this->query($Query);
		$this->GetTotalRowsInDB = $this->num_rows();

		$Query .= $this->AddLimitInQuery($Limit);
		//echo $Query;
		$this->query($Query);
		$this->RowsCount=$this->num_rows();
	}
	
	function GetRowsCount()
	{
		return $this->RowsCount;
	}
	
	function GetTotalRowsInDB()
	{
		return $this->GetTotalRowsInDB;
	}
	
	function AddWhereInQuery()
	{
		$Where = $this->SetWhere($_REQUEST);
		$Query = " where 1=1";
		if(is_array($Where))
		{
			foreach($Where as $Field => $Value)
			{
				if($Value!='' || $Value=='0')
				{
					$Condition = explode("|", $Field);
					if($Condition[1]=='Like')
					{
						$Query .= " and ".$Condition[0]." like '%".$Value."%'";
					}
					else if($Condition[1]=='GreaterEqual')
					{
						$Query .= " and ".$Condition[0]." >= '".$Value."'";
					}
					else if($Condition[1]=='SmallerEqual')
					{
						$Query .= " and ".$Condition[0]." <= '".$Value."'";
					}
					else if($Condition[1]=='Greater')
					{
						$Query .= " and ".$Condition[0]." > '".$Value."'";
					}
					else if($Condition[1]=='Smaller')
					{
						$Query .= " and ".$Condition[0]." < '".$Value."'";
					}
					else
					{
						$Query .= " and ".$Condition[0]." = '".$Value."'";
					}
				}
			}
		}
		return $Query;
	}
	
	function AddOrderByInQuery()
	{
		$OrderBy= $this->SetOrder();
		$Query	= '';
		if(is_array($OrderBy))
		{
			$Query .= " order by ";
			$o=0;
			foreach($OrderBy as $Field)
			{
				if($o==0)
				{ $Query .= $Field; }
				else
				{ $Query .= ", ".$Field; }
				$o++;
			}
		}
		return $Query;
	}
	
	function AddLimitInQuery($Limit=0)
	{
		$Paging	= $this->Paging($_REQUEST['start']);
		$Query	= '';
		if($Limit!='0')
		{
			$Query .= " limit " . $this->PageLimitEu .", ".$_SESSION[ADMIN_LOGGED_IN]["RecordsPerPage"];
		}
		return $Query;
	}
	
	function InsertQuery($Insert, $Table)
	{
		$Query = "insert into ".$Table." set ";
		$Count=0;
		foreach($Insert as $Key => $Value)
		{
			if($Count==0)
				$Query .= $Key."='".$Value."'";
			else
				$Query .= ", ".$Key."='".$Value."'";
				
			$Count++;
		}
		$Query .= ", CreatedBy='".$this->LoggedInUser()."', CreationDateTime='".$this->GetCurrentDateTime()."'";
		$this->query($Query);
		$Return["RecordID"]=$this->MysqlInsertID();
		$Return["Error"]=0;
		return $Return;
	}
	
	function UpdateQuery($Update, $Where, $Table)
	{
		
		$Query = "update ".$Table." set ";
		$Count=0;
		foreach($Update as $Key => $Value)
		{
			if($Count==0)
				$Query .= $Key."='".$Value."'";
			else
				$Query .= ", ".$Key."='".$Value."'";
				
			$Count++;
		}
		$Query .= ", ModifiedBy='".$this->LoggedInUser()."', ModificationDateTime='".$this->GetCurrentDateTime()."' where ";
		$Count=0;
		foreach($Where as $Key => $Value)
		{
			if($Count==0)
				$Query .= $Key."='".$Value."'";
			else
				$Query .= " and ".$Key."='".$Value."'";
			
			$Count++;
		}
		$this->query($Query);
		$Return["Error"]=0;
		return $Return;
	
	}
	
	function DeleteQuery($Ids, $Table)
	{
		if(is_array($Ids))
		{
			$Id = implode(", ", $Ids);
			$this->query("delete from ".$Table." where TableID in ($Id)");
		}
	}
}
?>