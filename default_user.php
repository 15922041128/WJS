<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$pagetabs = "user";
if (isset($_GET['pagetab'])) {
  $pagetabs = $_GET['pagetab'];
}

$url_project = $_SERVER["QUERY_STRING"] ;
$current_url = current(explode("&sort",$url_project));

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = get_item( 'maxrows_user' );
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$sortlist = "tk_display_name";
if (isset($_GET['sort'])) {
  $sortlist = $_GET['sort'];
}

$sortlist = GetSQLValueString($sortlist, "defined", $sortlist, "NULL");
$order = "ORDER BY ".$sortlist;
if($sortlist == "tk_display_name"){
$order = "ORDER BY CONVERT(tk_display_name USING gbk )";
}

$orderlist = "DESC";
if (isset($_GET['order'])) {
  $orderlist= $_GET['order'];
}else if($sortlist == "tk_display_name"){
$orderlist = "ASC";
}

$colrole_Recordset1 = "";
if (isset($_GET['select3'])) {
  $colrole_Recordset1 = $_GET['select3'];
}

$education_Recordset1 = "";
if (isset($_GET['select4'])) {
  $education_Recordset1 = $_GET['select4'];
}

$working_life_Recordset1 = "0";
if (isset($_GET['select5'])) {
  $working_life_Recordset1 = $_GET['select5'];
}

$company_Recordset1 = "";
if (isset($_GET['select6'])) {
  $company_Recordset1 = $_GET['select6'];
}

$colrole_dis = "0";
if($pagetabs=="user"){
$where = "NOT LIKE";
}else{
$where = "=";
}

$colinputtitle_Recordset1 = "";
if (isset($_GET['inputtitle'])) {
  $colinputtitle_Recordset1 = $_GET['inputtitle'];
}

$post_Recordset1 = "";
if (isset($_GET['inputPost'])) {
  $post_Recordset1 = $_GET['inputPost'];
}

$team_Recordset1 = "";
if (isset($_GET['inputTeam'])) {
  $team_Recordset1 = $_GET['inputTeam'];
}

mysql_select_db($database_tankdb, $tankdb);
// wangzi modify
$querySQl = "SELECT team.tk_team_title, user.* FROM tk_user user left join tk_team team on user.tk_user_team = team.pid ";
$querySQl = $querySQl.sprintf(" WHERE tk_user_rank LIKE %s AND tk_user_rank $where %s AND tk_display_name LIKE %s ",
GetSQLValueString("%" . $colrole_Recordset1 . "%", "text"),
GetSQLValueString("%" . $colrole_dis . "%", "text"), 
GetSQLValueString("%" . $colinputtitle_Recordset1 . "%", "text"));
if(!empty($education_Recordset1)){
	$querySQl = $querySQl.sprintf(" AND tk_user_education LIKE %s ", GetSQLValueString("%" . $education_Recordset1 . "%", "text"));
}
if ("0" != $working_life_Recordset1) {
	$querySQl = $querySQl.sprintf(" AND tk_user_working_life <= %s ", GetSQLValueString($working_life_Recordset1, "text"));
}
if(!empty($company_Recordset1)){
	$querySQl = $querySQl.sprintf(" AND tk_user_company LIKE %s ", GetSQLValueString("%" . $company_Recordset1 . "%", "text"));
}
if(!empty($post_Recordset1)){
	$querySQl = $querySQl.sprintf(" AND tk_user_post LIKE %s ", GetSQLValueString("%" . $post_Recordset1 . "%", "text"));
}
if(!empty($team_Recordset1)){
	$querySQl = $querySQl.sprintf(" AND tk_user_team IN( $team_Recordset1 )  ");
}
$querySQl = $querySQl.sprintf(" $order %s", GetSQLValueString($orderlist, "defined", $orderlist, "NULL"));
$query_Recordset1 = $querySQl;
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
$Recordset_team = getAllTeam();
?>
<?php require('head.php'); ?>
<link rel="StyleSheet" href="css/zTreeStyle/zTreeStyle.css" type="text/css" />
<link rel="StyleSheet" href="css/teamTree.css" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.exedit-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery-ui-1.10.4.min.js"></script>
<script>
function getChildren(idArray, treeNode) {
	idArray.push(treeNode.id);
	if (treeNode.isParent) {
		for(var obj in treeNode.children){
			getChildren(idArray,treeNode.children[obj]);
		}
	}
	return idArray;
}
</script>
<script type="text/javascript">
	var setting = {
		view: {
			dblClickExpand: false,
			selectedMulti: true
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			beforeClick: beforeClick,
			onClick: onClick
		}
	};

	var zNodes =[
	<?php
	while ($row_team = mysql_fetch_assoc($Recordset_team)) {
		$pid = $row_team['pid'];
		$title = $row_team['tk_team_title'];
		$parentID = $row_team['tk_team_parentID'];
	?>	
		{id:<?php echo $pid ?>, pId:<?php echo $parentID ?>, name:"<?php echo $title ?>", open:false, noR:false},
	<?php
	}
	?>	
	];

	function beforeClick(treeId, treeNode) {
		
	}
	
	function onClick(e, treeId, treeNode) {
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		nodes = zTree.getSelectedNodes(),
		name = "";
		value = "";
		nodes.sort(function compare(a,b){return a.id-b.id;});
		
		var isSelectChild = $('input[name="selectChild"]:checked').val();
		for (var i=0, l=nodes.length; i<l; i++) {
			name += nodes[i].name + ",";
			
			if (isSelectChild == '1') {
				var idArray = new Array();
				idArray = getChildren(idArray, nodes[i]);
				for (var j = 0; j < idArray.length; j++) {
					value += idArray[j] + ",";
				}
			} else {
				value += nodes[i].id + ",";
			}
		}
		if (name.length > 0 ) name = name.substring(0, name.length-1);
		var teamObj = $("#teamSel");
		teamObj.attr("value", name);
		
		if (value.length > 0 ) value = value.substring(0, value.length-1);
		var teamValueObj = $("#teamSelVal");
		teamValueObj.attr("value", value);
	}

	function showMenu() {
		var teamObj = $("#teamSel");
		var teamOffset = $("#teamSel").offset();
		$("#menuContent").css({left:teamOffset.left + "px", top:teamOffset.top + teamObj.outerHeight() + "px"}).slideDown("fast");

		$("body").bind("mousedown", onBodyDown);
	}
	function hideMenu() {
		$("#menuContent").fadeOut("fast");
		$("body").unbind("mousedown", onBodyDown);
	}
	function onBodyDown(event) {
		if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
			hideMenu();
		}
	}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	});
</script>

<?php if ($_SESSION['MM_rank'] > "4") {  ?> 
<div class="subnav">

<div class="float_left" style="width:50%">
<div class="btn-group">
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "user") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=user"><?php echo $multilingual_user_list_title; ?></a>
<a type="button" class="btn btn-default btn-sm <?php if($pagetabs == "disuser") { echo "active";} ?>" href="<?php echo $pagename; ?>?pagetab=disuser"><?php echo $multilingual_user_list_showdis; ?></a>
<!-- wangzi add -->
<button type="button" class="btn btn-default btn-sm" name="button2" id="button2" onClick="javascript:self.location='user_add.php';">
<span class="glyphicon glyphicon-plus-sign"></span> <?php echo $multilingual_user_new; ?>
</button>
</div>
</div>


<!-- wangzi removed 
<div class="float_right">
<button type="button" class="btn btn-default btn-sm" name="button2" id="button2" onClick="javascript:self.location='user_add.php';">
<span class="glyphicon glyphicon-plus-sign"></span> <?php echo $multilingual_user_new; ?>
</button>
</div>
-->

</div>
<div class="clearboth"></div>
<?php }  ?>
<div class="pagemargin">
<?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
	<div class="search_div pagemarginfix">
	   
		  <form id="form1" name="form1" method="get" action="default_user.php"  class="saerch_form form-inline">
              <?php if ($pagetabs =="user") {  ?>
			  <select name="select3" id="select3" class="form-control input-sm">
                <option value="" selected="selected"><?php echo $multilingual_user_selectrole; ?></option>
                <option value="1" ><?php echo $multilingual_dd_role_readonly; ?></option>
                <option value="2" ><?php echo $multilingual_dd_role_guest; ?></option>
                <option value="3" ><?php echo $multilingual_dd_role_general; ?></option>
                <option value="4" ><?php echo $multilingual_dd_role_pm; ?></option>
                <option value="5" ><?php echo $multilingual_dd_role_admin; ?></option>	
              </select>        
			  <?php }  ?>
			  <?php //wangzi add start ?>
			   <select name="select4" id="select4" class="form-control input-sm">
                <option value="" selected="selected"><?php echo $multilingual_user_education_default; ?></option>
                <option value="<?php echo $multilingual_user_education_junior_college; ?>" ><?php echo $multilingual_user_education_junior_college; ?></option>
                <option value="<?php echo $multilingual_user_education_university; ?>" ><?php echo $multilingual_user_education_university; ?></option>
                <option value="<?php echo $multilingual_user_education_master; ?>" ><?php echo $multilingual_user_education_master; ?></option>
                <option value="<?php echo $multilingual_user_education_doctorate; ?>" ><?php echo $multilingual_user_education_doctorate; ?></option>
              </select>
              <select name="select5" id="select5" class="form-control input-sm">
                <option value="0" selected="selected"><?php echo $multilingual_user_working_life_default; ?></option>
                <?php for($i=1; $i <= 40; $i++) { ?>
    				<option value="<?php echo $i ?>"><?php echo $i ?></option>
    			<?php }?>
              </select>
              <select name="select6" id="select6" class="form-control input-sm" style="width:240px;">
                <option value="" selected="selected"><?php echo $multilingual_user_company_default; ?></option>
                <option value="<?php echo $multilingual_user_company_bjjbf; ?>" ><?php echo $multilingual_user_company_bjjbf; ?></option>
                <option value="<?php echo $multilingual_user_company_jdjs; ?>" ><?php echo $multilingual_user_company_jdjs; ?></option>
                <option value="<?php echo $multilingual_user_company_bjjd; ?>" ><?php echo $multilingual_user_company_bjjd; ?></option>
                <option value="<?php echo $multilingual_user_company_bjzz; ?>" ><?php echo $multilingual_user_company_bjzz; ?></option>
                <option value="<?php echo $multilingual_user_company_tjzz; ?>" ><?php echo $multilingual_user_company_tjzz; ?></option>
                <option value="<?php echo $multilingual_user_company_bjkf; ?>" ><?php echo $multilingual_user_company_bjkf; ?></option>
              </select>
              <input type="text" name="inputPost" id="inputPost" class="form-control input-sm" style="width:120px;" placeholder="<?php echo $multilingual_user_list_post_search; ?>">
              <?php //wangzi add end ?>
			  <input type="text" name="inputtitle" id="inputtitle" class="form-control input-sm" style="width:120px;" placeholder="<?php echo $multilingual_user_list_search; ?>">
			  <?php //wangzi add strat ?>
			  <?php echo $multilingual_label_selectTeam_selectChild ?>
			  <input type="radio" name="selectChild" value="1" checked="true"/><?php echo $multilingual_label_selectTeam_selectChild_Y ?>
			  <input type="radio" name="selectChild" value="2" /><?php echo $multilingual_label_selectTeam_selectChild_N ?>
			  <input type="text" id="teamSel" readonly class="form-control input-sm" style="width:240px;" placeholder="<?php echo $multilingual_user_list_team_search; ?>">
			  <input type="hidden" id="teamSelVal" name="inputTeam" style="width:80px;">
			  <button type="button" id="menuBtn" onclick="showMenu();" class="btn btn-default btn-sm" ><?php echo $multilingual_label_selectTeam ?></button>
			  <?php //wangzi add end ?>
			  <input type="hidden" name="pagetab" value="<?php echo $pagetabs; ?>">
			   
			  <button type="submit" name="button11" id="button11" class="btn btn-default btn-sm" /><span class="glyphicon glyphicon-search" style="display:inline;"></span> <?php echo $multilingual_global_searchbtn; ?></button>
			   
            </form>
	  </div>


  <table class="table table-striped table-hover glink" width="98%" style="white-space:nowrap;">
    <thead>
      <tr>
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_display_name&order=<?php 
	  if ( $sortlist <> "tk_display_name"){
	  echo "DESC";
	  }else if( $sortlist == "tk_display_name" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_display_name" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_display_name" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_title; ?></a></th>
		
		<?php // wangzi add start?>
		<th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_team&order=<?php 
	  if ( $sortlist <> "tk_user_team"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_team" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_team" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_team" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_team2; ?></a></th>
		<?php // wangzi add end?>
		
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_rank&order=<?php 
	  if ( $sortlist <> "tk_user_rank"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_rank" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_rank" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_rank" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_role; ?></a></th>
		
		<th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_contact&order=<?php 
	  if ( $sortlist <> "tk_user_contact"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_contact" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_contact" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_contact" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_contact; ?></a></th>
		
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_email&order=<?php 
	  if ( $sortlist <> "tk_user_email"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_email" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_email" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_email" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_user_email; ?></a></th>
		
<?php/*wangzi add start*/?>
	
	 <!-- wangzi removed
	 <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_birthday&order=<?php 
	  if ( $sortlist <> "tk_user_birthday"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_birthday" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_birthday" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_birthday" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_birthday; ?></a></th>
	  -->
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_gender&order=<?php 
	  if ( $sortlist <> "tk_user_gender"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_gender" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_gender" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_gender" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_gender; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_city&order=<?php 
	  if ( $sortlist <> "tk_user_city"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_city" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_city" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_city" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_city; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_working_life&order=<?php 
	  if ( $sortlist <> "tk_user_working_life"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_working_life" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_working_life" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_working_life" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_working_life; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_company&order=<?php 
	  if ( $sortlist <> "tk_user_company"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_company" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_company" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_company" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_company; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_education&order=<?php 
	  if ( $sortlist <> "tk_user_education"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_education" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_education" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_education" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_education; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_school&order=<?php 
	  if ( $sortlist <> "tk_user_school"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_school" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_school" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_school" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_school; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_join_date&order=<?php 
	  if ( $sortlist <> "tk_user_join_date"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_join_date" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_join_date" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_join_date" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_join_date; ?></a></th>
	  
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_post&order=<?php 
	  if ( $sortlist <> "tk_user_post"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_post" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_post" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_post" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_post; ?></a></th>
	  
	  <!-- 
	  <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_bef_comp&order=<?php 
	  if ( $sortlist <> "tk_user_bef_comp"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_bef_comp" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_bef_comp" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_bef_comp" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
	  <?php echo $multilingual_user_bef_comp; ?></a></th>
	  -->
<?php/*wangzi add end*/?>
		
        <th>
		<a href="default_user.php?<?php echo $current_url; ?>&sort=tk_user_registered&order=<?php 
	  if ( $sortlist <> "tk_user_registered"){
	  echo "DESC";
	  }else if( $sortlist == "tk_user_registered" && $orderlist == "DESC"){
	  echo "ASC";
	  } else {
	  echo "DESC";
	  }
	  ?>" 
	  <?php 
	  if($sortlist=="tk_user_registered" && $orderlist=="ASC"){
	  echo "class='sort_asc'";
	  } else if ($sortlist=="tk_user_registered" && $orderlist=="DESC"){
	  echo "class='sort_desc'";
	  }
	  ?>>
		<?php echo $multilingual_global_lastupdate; ?></a></th>
      </tr>
    </thead>
    <?php do { ?>
      <tr>
        <td><a href="user_view.php?recordID=<?php echo $row_Recordset1['uid']; ?>"><?php echo $row_Recordset1['tk_display_name']; ?></a></td>
        <td><?php echo $row_Recordset1['tk_team_title']; ?>&nbsp;</td>
        <td>
		<?php
switch ($row_Recordset1['tk_user_rank'])
{
case 0:
  echo $multilingual_dd_role_disabled;
  break;
case 1:
  echo $multilingual_dd_role_readonly;
  break;
case 2:
  echo $multilingual_dd_role_guest;
  break;
case 3:
  echo $multilingual_dd_role_general;
  break;
case 4:
  echo $multilingual_dd_role_pm;
  break;
case 5:
  echo $multilingual_dd_role_admin;
  break;
}
?>
		</td>
		<td><?php echo $row_Recordset1['tk_user_contact']; ?>&nbsp;</td>
        <td><a href="mailto:<?php echo $row_Recordset1['tk_user_email']; ?>"><?php echo $row_Recordset1['tk_user_email']; ?></a>&nbsp;</td>
        <?php/*wangzi add start*/?>
        <!-- wangzi removed
        <td><?php echo $row_Recordset1['tk_user_birthday']; ?>&nbsp;</td>
        -->
        <td><?php echo $row_Recordset1['tk_user_gender']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_city']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_working_life']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_company']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_education']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_school']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_join_date']; ?>&nbsp;</td>
        <td><?php echo $row_Recordset1['tk_user_post']; ?>&nbsp;</td>
        <!-- 
        <td><?php echo $row_Recordset1['tk_user_bef_comp']; ?>&nbsp;</td>
        -->
        <?php/*wangzi add end*/?>
        <td><?php echo $row_Recordset1['tk_user_registered']; ?></td>
      </tr>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  </table>

<table class="rowcon" border="0" align="center">
  <tr>
    <td><table border="0">
        <tr>
          <td>&nbsp;</td>
          <td><table border="0">
              <tr>
                <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><?php echo $multilingual_global_first; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_previous; ?></a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><?php echo $multilingual_global_next; ?></a>
                    <?php } // Show if not last page ?></td>
                <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><?php echo $multilingual_global_last; ?></a>
                    <?php } // Show if not last page ?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td align="right"><?php echo ($startRow_Recordset1 + 1) ?> <?php echo $multilingual_global_to; ?> <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> (<?php echo $multilingual_global_total; ?> <?php echo $totalRows_Recordset1 ?>)</td>
  </tr>
</table>
<?php } else { // Show if recordset empty ?>  
  <div class="alert alert-warning" style="margin:6px;">

	<?php echo $multilingual_user_sorrytip; ?>

  </div>
<?php } // Show if recordset empty ?>  
<p>&nbsp;</p>
</div><!--pagemargin结束 -->
<!-- wangzi add -->
<div id="menuContent" class="menuContent" style="display:none; position: absolute;background-color:#ddd;">
	<ul id="treeDemo" class="ztree" style="margin-top:0; width:300px;"></ul>
</div>
<?php require('foot.php'); ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
