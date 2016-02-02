<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="user_error.php";
  $loginUsername = $_POST['tk_user_login'];
  $LoginRS__query = sprintf("SELECT tk_user_login FROM tk_user WHERE tk_user_login=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_tankdb, $tankdb);
  $LoginRS=mysql_query($LoginRS__query, $tankdb) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$password = "-1";
if (isset($_POST['tk_user_pass'])) {
  $password = $_POST['tk_user_pass'];
}

$tk_password = md5(crypt($password,substr($password,0,2)));

if ( empty( $_POST['tk_user_contact'] ) ){
$tk_user_contact = "'',";
}else{
$tk_user_contact = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_contact']), "text"));
}

if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "'',";
}else{
$tk_user_email = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

if ( empty( $_POST['tk_user_remark'] ) ){
$tk_user_remark = "'',";
}else{
$tk_user_remark = sprintf("%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_remark']), "text"));
}

// wangzi modify
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tk_user (tk_user_login, tk_user_pass, tk_display_name, tk_user_rank, tk_user_remark, tk_user_contact, tk_user_email, tk_user_backup1, tk_user_birthday, tk_user_gender, tk_user_city, tk_user_working_life, tk_user_company, tk_user_education, tk_user_school, tk_user_join_date, tk_user_post, tk_user_bef_comp, tk_user_team) VALUES (%s, %s, %s, %s, $tk_user_remark $tk_user_contact $tk_user_email '', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tk_user_login'], "text"),
                       GetSQLValueString($tk_password, "text"),
                       GetSQLValueString($_POST['tk_display_name'], "text"),
                       GetSQLValueString($_POST['tk_user_rank'], "text"),
                       GetSQLValueString($_POST['tk_user_birthday'], "text"),
                       GetSQLValueString($_POST['tk_user_gender'], "text"),
                       GetSQLValueString($_POST['tk_user_city'], "text"),
                       GetSQLValueString($_POST['tk_user_working_life'], "text"),
                       GetSQLValueString($_POST['tk_user_company'], "text"),
                       GetSQLValueString($_POST['tk_user_education'], "text"),
                       GetSQLValueString($_POST['tk_user_school'], "text"),
                       GetSQLValueString($_POST['tk_user_join_date'], "text"),
                       GetSQLValueString($_POST['tk_user_post'], "text"),
                       GetSQLValueString($_POST['tk_user_bef_comp'], "text"),
                       GetSQLValueString($_POST['tk_user_team'], "text"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($insertSQL, $tankdb) or die(mysql_error());

  $insertGoTo = "default_user.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$Recordset_team = getAllTeam();
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<!-- wangzi add -->
<link rel="stylesheet" href="bootstrap/css/datepicker3.css" type="text/css"/>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link rel="StyleSheet" href="css/zTreeStyle/zTreeStyle.css" type="text/css" />
<link rel="StyleSheet" href="css/teamTree.css" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.exedit-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery-ui-1.10.4.min.js"></script>
<script charset="utf-8" src="editor/lang/zh_CN.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="bootstrap/js/locales/bootstrap-datepicker.zh-CN.js"></script>
<script type="text/javascript">
	$(function() {
		$('#datepicker').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
		$('#datepicker2').datepicker({
			format: "yyyy-mm-dd"
	<?php if ($language=="cn") {echo ", language: 'zh-CN'" ;}?>
		});
	});
</script>
<script type="text/javascript">
J.check.rules = [
    { name: 'tk_user_login', mid: 'user_login', type: 'limit|alpha', requir: true, min: 2, max: 24, warn: '<?php echo $multilingual_user_namequired; ?>|<?php echo $multilingual_user_alpha; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass', type: 'limit', requir: true, min: 2, max: 8, warn: '<?php echo $multilingual_user_namequired8; ?>' },
	{ name: 'tk_display_name', mid: 'display_name', type: 'limit', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>' },
	{ name: 'tk_user_pass', mid: 'user_pass2', requir: true, type: 'match', to: 'tk_user_pass2', warn: '<?php echo $multilingual_user_tip_match; ?>' }
];

window.onload = function()
{
    J.check.regform('form1');
}
</script>
<script>
  $(function() {
    var select = $( "#tk_user_working_life" );
    var slider = $( "<div id='slider' class='pull-left' style='width:842px; margin:5px;'></div>" ).insertAfter( select ).slider({
      min: 1,
      max: 40,
      range: "min",
      value: select[ 0 ].selectedIndex + 1,
      slide: function( event, ui ) {
        select[ 0 ].selectedIndex = ui.value - 1;
      }
    });
    $( "#tk_user_working_life" ).change(function() {
      slider.slider( "value", this.selectedIndex + 1 );
    });
  });
</script>
<script type="text/javascript">
	var setting = {
		view: {
			dblClickExpand: false,
			selectedMulti: false
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
		if ($pid == "0") {
	?>	
		{id:<?php echo $pid ?>, pId:<?php echo $parentID ?>, name:"<?php echo $title ?>", open:true, noR:false},	
	<?php 		
		} else {
	?>
		{id:<?php echo $pid ?>, pId:<?php echo $parentID ?>, name:"<?php echo $title ?>", open:false, noR:false},
	<?php 	
		}
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
		for (var i=0, l=nodes.length; i<l; i++) {
			name += nodes[i].name + ",";
			value += nodes[i].id + ",";
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="25%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top" class="gray2">
	 <h4 style="margin-top:40px" ><strong><?php echo $multilingual_user_about; ?></strong></h4>
	 <p >
	 <?php echo $multilingual_user_abouttext; ?>
	 </p>

              
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_user_new; ?></h3>
              </div>

              <div class="form-group col-xs-12">
                <label for="tk_user_login"><?php echo $multilingual_user_account; ?><span id="user_login" class="red">*</span></label>
                <div>
				<input type="text" name="tk_user_login" id="tk_user_login" value="" placeholder="<?php echo $multilingual_user_account;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_account; ?></span>
              </div>


			  <div class="form-group col-xs-12">
                <label for="tk_user_pass"><?php echo $multilingual_user_password; ?><span class="red" id="user_pass" >*</span></label>
                <div>
				<input type="password" name="tk_user_pass" id="tk_user_pass" value="" placeholder="<?php echo $multilingual_user_password;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_password; ?></span>
              </div>



			  <div class="form-group col-xs-12">
                <label for="tk_user_pass2"><?php echo $multilingual_user_password2; ?><span class="red" id="user_pass2" >*</span></label>
                <div>
				<input type="password" name="tk_user_pass2" id="tk_user_pass2" value="" placeholder="<?php echo $multilingual_user_password2;?>"  class="form-control"  />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_password2; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_display_name"><?php echo $multilingual_user_name; ?><span id="display_name" class="red">*</span></label>
                <div>
				<input type="text" name="tk_display_name" id="tk_display_name" value="" placeholder="<?php echo $multilingual_user_name;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_name; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_contact"><?php echo $multilingual_user_contact; ?></label>
                <div>
				<input type="text" name="tk_user_contact" id="tk_user_contact" value="" placeholder="<?php echo $multilingual_user_contact;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_contact; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_email"><?php echo $multilingual_user_email; ?></label>
                <div>
				<input type="text" name="tk_user_email" id="tk_user_email" value="" placeholder="<?php echo $multilingual_user_email;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_mail; ?></span>
              </div>

<?php/*wangzi add start*/?>
				<div class="form-group col-xs-12">
                <label for="tk_user_team"><?php echo $multilingual_user_team; ?></label>
               	<div class="zTreeDemoBackground left">
					<ul>
						<a id="menuBtn" href="#" onclick="showMenu(); return false;"><?php echo $multilingual_label_selectTeam ?></a>
						<input id="teamSel" type="text" readonly value="" class="form-control"/>
						<input id="teamSelVal" type="hidden" value="0" name="tk_user_team"/>
					</ul>
				</div>
                <span class="help-block"><?php echo $multilingual_user_team_tip_singleton; ?></span>
                </div>

				<div class="form-group col-xs-12">
                <label for="datepicker"><?php echo $multilingual_user_birthday; ?><span id="datepicker_msg"></span></label>
                <div>
                  <input type="text" name="tk_user_birthday" id="datepicker" value="<?php echo date('Y-m-d'); ?>" class="form-control"  />
                </div>
                <span class="help-block"><?php echo $multilingual_user_birthday_tip; ?></span>
                </div>
                
               <div class="form-group col-xs-12">
                <label for="tk_user_gender"><?php echo $multilingual_user_gender; ?></label>
                <div>
                  <select name="tk_user_gender" id="tk_user_gender" class="form-control">
                    <option value="<?php echo $multilingual_user_gender_f; ?>"><?php echo $multilingual_user_gender_f; ?></option>
                    <option value="<?php echo $multilingual_user_gender_m; ?>" SELECTED="SELECTED"><?php echo $multilingual_user_gender_m; ?></option>
                  </select>
                </div>
                <span class="help-block"><?php echo $multilingual_user_gender_tip; ?></span>
              </div>
              
               <div class="form-group col-xs-12">
                <label for="tk_user_city"><?php echo $multilingual_user_city; ?></label>
                 <div>
                  <select name="tk_user_city" id="tk_user_city" class="form-control">
                    <option value="<?php echo $multilingual_user_city_tj; ?>"><?php echo $multilingual_user_city_tj; ?></option>
                    <option value="<?php echo $multilingual_user_city_bj; ?>" SELECTED="SELECTED"><?php echo $multilingual_user_city_bj; ?></option>
                  </select>
                </div>
				<span class="help-block"><?php echo $multilingual_user_city_tip; ?></span>
              </div>
              
               <div class="form-group col-xs-12"><div>
				<label for="tk_user_working_life" class="pull-left"><?php echo $multilingual_user_working_life; ?></label>
				<div class="pull-right">		
	  			<select  name="tk_user_working_life" id="tk_user_working_life" >
                  	<option value="1" selected="selected">1</option>
                  	 <?php for($i=2; $i <= 40; $i++) { ?>
    				<option value="<?php echo $i ?>"><?php echo $i ?></option>
    				<?php }?>
                </select>
				</div></div></div>
				
              	
              	<div class="form-group col-xs-12">
	                <label for="tk_user_company"><?php echo $multilingual_user_company; ?></label>
	                <div>
	                  <select name="tk_user_company" id="tk_user_company" class="form-control">
	                    <option value="" SELECTED="SELECTED"><?php echo $multilingual_user_company_noSelect; ?></option>
	                    <option value="<?php echo $multilingual_user_company_bjjbf; ?>" ><?php echo $multilingual_user_company_bjjbf; ?></option>
                		<option value="<?php echo $multilingual_user_company_jdjs; ?>" ><?php echo $multilingual_user_company_jdjs; ?></option>
                		<option value="<?php echo $multilingual_user_company_bjjd; ?>" ><?php echo $multilingual_user_company_bjjd; ?></option>
               		 	<option value="<?php echo $multilingual_user_company_bjzz; ?>" ><?php echo $multilingual_user_company_bjzz; ?></option>
                		<option value="<?php echo $multilingual_user_company_tjzz; ?>" ><?php echo $multilingual_user_company_tjzz; ?></option>
                		<option value="<?php echo $multilingual_user_company_bjkf; ?>" ><?php echo $multilingual_user_company_bjkf; ?></option>
	                  </select>
	                </div>
	                <span class="help-block"><?php echo $multilingual_user_company_tip; ?></span>
	              </div>
              	
	             <div class="form-group col-xs-12">
	                <label for="tk_user_education"><?php echo $multilingual_user_education; ?></label>
	                <div>
	                  <select name="tk_user_education" id="tk_user_education" class="form-control">
	                    <option value="<?php echo $multilingual_user_education_doctorate; ?>"><?php echo $multilingual_user_education_doctorate; ?></option>
	                    <option value="<?php echo $multilingual_user_education_master; ?>"><?php echo $multilingual_user_education_master; ?></option>
	                    <option value="<?php echo $multilingual_user_education_university; ?>" SELECTED="SELECTED"><?php echo $multilingual_user_education_university; ?></option>
	                    <option value="<?php echo $multilingual_user_education_junior_college; ?>"><?php echo $multilingual_user_education_junior_college; ?></option>
	                  </select>
	                </div>
	                <span class="help-block"><?php echo $multilingual_user_education_tip; ?></span>
	              </div>
	              
	            <div class="form-group col-xs-12">
                <label for="tk_user_school"><?php echo $multilingual_user_school; ?></label>
                <div>
				<input type="text" name="tk_user_school" id="tk_user_school" value="" placeholder="<?php echo $multilingual_user_school;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_school_tip; ?></span>
              	</div>
              	
              	<div class="form-group col-xs-12">
                <label for="datepicker2"><?php echo $multilingual_user_join_date; ?><span id="datepicker_msg"></span></label>
                <div>
                  <input type="text" name="tk_user_join_date" id="datepicker2" value="<?php echo date('Y-m-d'); ?>" class="form-control"  />
                </div>
                <span class="help-block"><?php echo $multilingual_user_join_date_tip; ?></span>
                </div>
                
                <div class="form-group col-xs-12">
                <label for="tk_user_post"><?php echo $multilingual_user_post; ?></label>
                <div>
				<input type="text" name="tk_user_post" id="tk_user_post" value="" placeholder="<?php echo $multilingual_user_post;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_post_tip; ?></span>
              	</div>
              	
              	<div class="form-group col-xs-12">
                <label for="tk_user_bef_comp"><?php echo $multilingual_user_bef_comp; ?></label>
                <div>
				<input type="text" name="tk_user_bef_comp" id="tk_user_bef_comp" value="" placeholder="<?php echo $multilingual_user_bef_comp;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_bef_comp_tip; ?></span>
              	</div>
<?php/*wangzi add end*/?>

			  <div class="form-group col-xs-12">
                <label for="tk_user_remark"><?php echo $multilingual_user_remark; ?></label>
                <div>
				<textarea name="tk_user_remark" id="tk_user_remark" class="form-control" rows="5" placeholder="<?php echo $multilingual_user_remark;?>"></textarea>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_remark; ?></span>
              </div>

			  <div class="form-group col-xs-12">
                <label for="tk_user_rank"><?php echo $multilingual_user_role; ?></label>
                <div>
				<select name="tk_user_rank"  id="tk_user_rank" class="form-control">
	    <option value="0" ><?php echo $multilingual_dd_role_disabled; ?></option>
		<option value="1" ><?php echo $multilingual_dd_role_readonly; ?></option>
		<option value="2" ><?php echo $multilingual_dd_role_guest; ?></option>
        <option value="3" selected="selected"><?php echo $multilingual_dd_role_general; ?></option>
		<option value="4" ><?php echo $multilingual_dd_role_pm; ?></option>
        <option value="5" ><?php echo $multilingual_dd_role_admin; ?></option>		
      </select>
                </div>
				<span class="help-block">

<table width="100%" border="1" cellspacing="0" cellpadding="5" class="rank_talbe">
        <tr>
          <td><?php echo $multilingual_user_role; ?></td>
          <td align="center"><?php echo $multilingual_rank1; ?></td>
          <td align="center"><?php echo $multilingual_rank2; ?></td>
          <td align="center"><?php echo $multilingual_rank3; ?></td>
          <td align="center"><?php echo $multilingual_rank4; ?></td>
          <td align="center"><?php echo $multilingual_rank5; ?></td>
          <td align="center"><?php echo $multilingual_rank6; ?></td>
          <td align="center"><?php echo $multilingual_rank7; ?></td>
          <td align="center"><?php echo $multilingual_rank8; ?></td>
          <td align="center"><?php echo $multilingual_rank9; ?></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_disabled; ?></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_readonly; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_guest; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_general; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_pm; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
          <td align="center"><div class="iconer"></div></td>
        </tr>
        <tr>
          <td><?php echo $multilingual_dd_role_admin; ?></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
          <td align="center"><div class="iconok"></div></td>
        </tr>
      </table>
</span>
              </div>




  

           

				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" ><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm"  onclick="javascript:history.go(-1)" ><?php echo $multilingual_global_action_cancel; ?></button>
          




        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>



</form>
<!-- wangzi add -->
<div id="menuContent" class="menuContent" style="display:none; position: absolute; background-color:#ddd;">
	<ul id="treeDemo" class="ztree" style="margin-top:0; width:300px;"></ul>
</div>
<?php require('foot.php'); ?>
</body>
</html>

