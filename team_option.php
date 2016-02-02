<?php require_once('config/tank_config.php'); ?>
<?php

$myjson = my_json_encode($arr);
echo $myjson;
function my_json_encode($arr){
	global $tankdb;
	global $database_tankdb;
	
	$arr = $_REQUEST;
	$method=$arr['method'];
	
	if ($method == "add") {
		$insertSQL = sprintf("INSERT INTO tk_team (tk_team_title, tk_team_parentID) VALUES (%s, %s)",
	                       GetSQLValueString($arr['title'], "text"),
	                       GetSQLValueString($arr['parentID'], "int"));
	  	mysql_select_db($database_tankdb, $tankdb);
	  	$result = mysql_query($insertSQL, $tankdb) or die(mysql_error());
	  	$newID = mysql_insert_id();
	  	
	  	$dataArray = array('id'=>$newID, 'title'=>$_POST['title'], 'parentID'=>$_POST['parentID']);
	  	return utf8_encode(json_encode($dataArray));
	} else if ($method == "edit") {
		$updateSQL = sprintf("Update tk_team set tk_team_title = %s where pid = %s",
	                       GetSQLValueString($arr['title'], "text"),
	                       GetSQLValueString($arr['id'], "int"));
	  	mysql_select_db($database_tankdb, $tankdb);
	  	$result = mysql_query($updateSQL, $tankdb) or die(mysql_error());
	  	
	  	$dataArray = array('id'=>$arr['id'], 'title'=>$arr['title']);
    	return utf8_encode(json_encode($dataArray));
		
	} else if ($method == "delete") {
		$deleteID = implode(",", $arr['pids']);
		$deleteSQL = "delete from tk_team where pid in ($deleteID)";
	  	mysql_select_db($database_tankdb, $tankdb);
	  	$result = mysql_query($deleteSQL, $tankdb) or die(mysql_error());
	  	
    	return null;
	} else if ($method == "moveTeam") {
		$ids = implode(",", $arr['_ids']);
		$moveTeamSQL = sprintf("update tk_team set tk_team_parentID = %s where pid in ($ids)",
					GetSQLValueString($arr['parentID'], "int"));
	  	mysql_select_db($database_tankdb, $tankdb);
	  	$result = mysql_query($moveTeamSQL, $tankdb) or die(mysql_error());
	  	
    	return null;
	}

}
?>
