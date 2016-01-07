<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>
<?php
$restrictGoTo = "user_error3.php";
if ($_SESSION['MM_rank'] < "2") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$colname_Recordset1 = "-1";
if (isset($_GET['UID'])) {
  $colname_Recordset1 = $_GET['UID'];
}
mysql_select_db($database_tankdb, $tankdb);
$query_Recordset1 = sprintf("SELECT * FROM tk_user WHERE uid = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $tankdb) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$restrictGoTo = "user_error3.php";
if ($row_Recordset1['uid'] <> $_SESSION['MM_uid'] && $_SESSION['MM_rank'] < "5") {   
  header("Location: ". $restrictGoTo); 
  exit;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ( empty( $_POST['tk_user_remark'] ) ){
$tk_user_remark = "tk_user_remark='',";
}else{
$tk_user_remark = sprintf("tk_user_remark=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_remark']), "text"));
}

if ( empty( $_POST['tk_user_contact'] ) ){
$tk_user_contact = "tk_user_contact='',";
}else{
$tk_user_contact = sprintf("tk_user_contact=%s,", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_contact']), "text"));
}

if ( empty( $_POST['tk_user_email'] ) ){
$tk_user_email = "tk_user_email=''";
}else{
$tk_user_email = sprintf("tk_user_email=%s", GetSQLValueString(str_replace("%","%%",$_POST['tk_user_email']), "text"));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tk_user SET tk_display_name=%s, tk_user_rank=%s, tk_user_birthday=%s, tk_user_gender=%s, tk_user_city=%s, tk_user_working_life=%s, tk_user_company=%s, tk_user_education=%s, tk_user_school=%s, tk_user_join_date=%s, tk_user_post=%s, tk_user_bef_comp=%s,$tk_user_remark $tk_user_contact $tk_user_email WHERE uid=%s",
                       
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
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_tankdb, $tankdb);
  $Result1 = mysql_query($updateSQL, $tankdb) or die(mysql_error());

  $updateGoTo = "user_view.php?recordID=$colname_Recordset1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }

  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php require('head.php'); ?>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/lhgcheck.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/lhgcore.js"></script>
<script type="text/javascript" src="srcipt/lhgcheck.js"></script>
<link rel="stylesheet" href="bootstrap/css/datepicker3.css" type="text/css"/>
<link href="skin/themes/base/lhgdialog.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="srcipt/jquery.js"></script>
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
	{ name: 'tk_display_name', mid: 'display_name', type: 'limit', requir: true, min: 2, max: 12, warn: '<?php echo $multilingual_user_namequired; ?>' }
	
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="25%" class="input_task_right_bg" valign="top"><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top"  class="gray2">
	 <h4 style="margin-top:40px" ><strong><?php echo $multilingual_user_tiptitle; ?></strong></h4>
	 <p >
	 <?php echo $multilingual_user_tiptext; ?></p>

              
              </td>
          </tr>
        </table></td>
      <td width="75%" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td><div class="col-xs-12">
                <h3><?php echo $multilingual_user_edit_title; ?></h3>
              </div>

              <div class="form-group col-xs-12">
                <label for="tk_user_login"><?php echo $multilingual_user_account; ?></label>
                <div>
				<?php echo $row_Recordset1['tk_user_login']; ?>
                </div>
              </div>
			  
			  <div class="form-group col-xs-12">
                <label ><?php echo $multilingual_user_password; ?></label>
                <div>
				<a data-toggle="modal" href="user_edit_password.php?UID=<?php echo $colname_Recordset1; ?>" data-target="#myModal"><?php echo $multilingual_user_edit_password; ?></a>
                </div>
              </div>
			  
			  




			  <div class="form-group col-xs-12">
                <label for="tk_display_name"><?php echo $multilingual_user_name; ?><span id="display_name" class="red">*</span></label>
                <div>
				<input type="text" name="tk_display_name" id="tk_display_name" value="<?php echo $row_Recordset1['tk_display_name']; ?>" placeholder="<?php echo $multilingual_user_name;?>"  class="form-control"/>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_name; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_contact"><?php echo $multilingual_user_contact; ?></label>
                <div>
				
				<input type="text" name="tk_user_contact" id="tk_user_contact" value="<?php echo $row_Recordset1['tk_user_contact']; ?>"  placeholder="<?php echo $multilingual_user_contact;?>"  class="form-control" />

                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_contact; ?></span>
              </div>




			  <div class="form-group col-xs-12">
                <label for="tk_user_email"><?php echo $multilingual_user_email; ?></label>
                <div>
				<input type="text" name="tk_user_email" id="tk_user_email" value="<?php echo $row_Recordset1['tk_user_email']; ?>"  placeholder="<?php echo $multilingual_user_email;?>"  class="form-control"/>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_mail; ?></span>
              </div>

<?php/*wangzi add start*/?>
				<div class="form-group col-xs-12">
                <label for="datepicker"><?php echo $multilingual_user_birthday; ?><span id="datepicker_msg"></span></label>
                <div>
                  <input type="text" name="tk_user_birthday" id="datepicker" value="<?php echo $row_Recordset1['tk_user_birthday']; ?>" class="form-control"  />
                </div>
                <span class="help-block"><?php echo $multilingual_user_birthday_tip; ?></span>
                </div>
                
               <div class="form-group col-xs-12">
                <label for="tk_user_gender"><?php echo $multilingual_user_gender; ?></label>
                <div>
                  <select name="tk_user_gender" id="tk_user_gender" class="form-control">
                    <option value="<?php echo $multilingual_user_gender_f; ?>" <?php if (!(strcmp($multilingual_user_gender_f, $row_Recordset1['tk_user_gender']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_gender_f; ?></option>
                    <option value="<?php echo $multilingual_user_gender_m; ?>" <?php if (!(strcmp($multilingual_user_gender_m, $row_Recordset1['tk_user_gender']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_gender_m; ?></option>
                  </select>
                </div>
                <span class="help-block"><?php echo $multilingual_user_gender_tip; ?></span>
              </div>
              
               <div class="form-group col-xs-12">
                <label for="tk_user_city"><?php echo $multilingual_user_city; ?></label>
                 <div>
                  <select name="tk_user_city" id="tk_user_city" class="form-control">
                    <option value="<?php echo $multilingual_user_city_tj; ?>" <?php if (!(strcmp($multilingual_user_city_tj, $row_Recordset1['tk_user_city']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_city_tj; ?></option>
                    <option value="<?php echo $multilingual_user_city_bj; ?>" <?php if (!(strcmp($multilingual_user_city_bj, $row_Recordset1['tk_user_city']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_city_bj; ?></option>
                  </select>
                </div>
				<span class="help-block"><?php echo $multilingual_user_city_tip; ?></span>
              </div>
              
               <div class="form-group col-xs-12"><div>
				<label for="tk_user_working_life" class="pull-left"><?php echo $multilingual_user_working_life; ?></label>
				<div class="pull-right">		
	  			<select  name="tk_user_working_life" id="tk_user_working_life" >
                  	<option value="1" <?php if (!(strcmp(1, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>1</option>
                  	<option value="2" <?php if (!(strcmp(2, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>2</option>
                  	<option value="3" <?php if (!(strcmp(3, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>3</option>
                  	<option value="4" <?php if (!(strcmp(4, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>4</option>
                  	<option value="5" <?php if (!(strcmp(5, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>5</option>
                  	<option value="6" <?php if (!(strcmp(6, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>6</option>
                  	<option value="7" <?php if (!(strcmp(7, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>7</option>
                  	<option value="8" <?php if (!(strcmp(8, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>8</option>
                  	<option value="9" <?php if (!(strcmp(9, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>9</option>
                  	<option value="10" <?php if (!(strcmp(10, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>10</option>
                  	<option value="11" <?php if (!(strcmp(11, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>11</option>
                  	<option value="12" <?php if (!(strcmp(12, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>12</option>
                  	<option value="13" <?php if (!(strcmp(13, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>13</option>
                  	<option value="14" <?php if (!(strcmp(14, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>14</option>
                  	<option value="15" <?php if (!(strcmp(15, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>15</option>
                  	<option value="16" <?php if (!(strcmp(16, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>16</option>
                  	<option value="17" <?php if (!(strcmp(17, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>17</option>
                  	<option value="18" <?php if (!(strcmp(18, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>18</option>
                  	<option value="19" <?php if (!(strcmp(19, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>19</option>
                  	<option value="20" <?php if (!(strcmp(20, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>20</option>
                  	<option value="21" <?php if (!(strcmp(21, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>21</option>
                  	<option value="22" <?php if (!(strcmp(22, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>22</option>
                  	<option value="23" <?php if (!(strcmp(23, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>23</option>
                  	<option value="24" <?php if (!(strcmp(24, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>24</option>
                  	<option value="25" <?php if (!(strcmp(25, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>25</option>
                  	<option value="26" <?php if (!(strcmp(26, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>26</option>
                  	<option value="27" <?php if (!(strcmp(27, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>27</option>
                  	<option value="28" <?php if (!(strcmp(28, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>28</option>
                  	<option value="29" <?php if (!(strcmp(29, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>29</option>
                  	<option value="30" <?php if (!(strcmp(30, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>30</option>
                  	<option value="31" <?php if (!(strcmp(31, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>31</option>
                  	<option value="32" <?php if (!(strcmp(32, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>32</option>
                  	<option value="33" <?php if (!(strcmp(33, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>33</option>
                  	<option value="34" <?php if (!(strcmp(34, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>34</option>
                  	<option value="35" <?php if (!(strcmp(35, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>35</option>
                  	<option value="36" <?php if (!(strcmp(36, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>36</option>
                  	<option value="37" <?php if (!(strcmp(37, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>37</option>
                  	<option value="38" <?php if (!(strcmp(38, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>38</option>
                  	<option value="39" <?php if (!(strcmp(39, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>39</option>
                  	<option value="40" <?php if (!(strcmp(40, intval($row_Recordset1['tk_user_working_life'])))) {echo "selected=\"selected\"";} ?>>40</option>
                </select>
				</div></div></div>
				
              	
              	<div class="form-group col-xs-12">
	                <label for="tk_user_company"><?php echo $multilingual_user_company; ?></label>
	                <div>
	                  <select name="tk_user_company" id="tk_user_company" class="form-control">
	                    <option value="" <?php if (!(strcmp("", $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_noSelect; ?></option>
	                    <option value="<?php echo $multilingual_user_company_bjjbf; ?>" <?php if (!(strcmp($multilingual_user_company_bjjbf, $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_bjjbf; ?></option>
	                    <option value="<?php echo $multilingual_user_company_jdjs; ?>" <?php if (!(strcmp($multilingual_user_company_jdjs, $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_jdjs; ?></option>
	                    <option value="<?php echo $multilingual_user_company_bjjd; ?>" <?php if (!(strcmp($multilingual_user_company_bjjd, $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_bjjd; ?></option>
	                    <option value="<?php echo $multilingual_user_company_bjzz; ?>" <?php if (!(strcmp($multilingual_user_company_bjzz, $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_bjzz; ?></option>
	                    <option value="<?php echo $multilingual_user_company_tjzz; ?>" <?php if (!(strcmp($multilingual_user_company_tjzz, $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_tjzz; ?></option>
	                    <option value="<?php echo $multilingual_user_company_bjkf; ?>" <?php if (!(strcmp($multilingual_user_company_bjkf, $row_Recordset1['tk_user_company']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_company_bjkf; ?></option>
	                  </select>
	                </div>
	                <span class="help-block"><?php echo $multilingual_user_company_tip; ?></span>
	              </div>
              	
	             <div class="form-group col-xs-12">
	                <label for="tk_user_education"><?php echo $multilingual_user_education; ?></label>
	                <div>
	                  <select name="tk_user_education" id="tk_user_education" class="form-control">
	                    <option value="<?php echo $multilingual_user_education_doctorate; ?>" <?php if (!(strcmp($multilingual_user_education_doctorate, $row_Recordset1['tk_user_education']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_education_doctorate; ?></option>
	                    <option value="<?php echo $multilingual_user_education_master; ?>" <?php if (!(strcmp($multilingual_user_education_master, $row_Recordset1['tk_user_education']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_education_master; ?></option>
	                    <option value="<?php echo $multilingual_user_education_university; ?>" <?php if (!(strcmp($multilingual_user_education_university, $row_Recordset1['tk_user_education']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_education_university; ?></option>
	                    <option value="<?php echo $multilingual_user_education_junior_college; ?>" <?php if (!(strcmp($multilingual_user_education_junior_college, $row_Recordset1['tk_user_education']))) {echo "selected=\"selected\"";} ?>><?php echo $multilingual_user_education_junior_college; ?></option>
	                  </select>
	                </div>
	                <span class="help-block"><?php echo $multilingual_user_education_tip; ?></span>
	              </div>
	              
	            <div class="form-group col-xs-12">
                <label for="tk_user_school"><?php echo $multilingual_user_school; ?></label>
                <div>
				<input type="text" name="tk_user_school" id="tk_user_school" value="<?php echo $row_Recordset1['tk_user_school']; ?>" placeholder="<?php echo $multilingual_user_school;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_school_tip; ?></span>
              	</div>
              	
              	<div class="form-group col-xs-12">
                <label for="datepicker2"><?php echo $multilingual_user_join_date; ?><span id="datepicker_msg"></span></label>
                <div>
                  <input type="text" name="tk_user_join_date" id="datepicker2" value="<?php echo $row_Recordset1['tk_user_join_date']; ?>" class="form-control"  />
                </div>
                <span class="help-block"><?php echo $multilingual_user_join_date_tip; ?></span>
                </div>
                
                <div class="form-group col-xs-12">
                <label for="tk_user_post"><?php echo $multilingual_user_post; ?></label>
                <div>
				<input type="text" name="tk_user_post" id="tk_user_post" value="<?php echo $row_Recordset1['tk_user_post']; ?>" placeholder="<?php echo $multilingual_user_post;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_post_tip; ?></span>
              	</div>
              	
              	<div class="form-group col-xs-12">
                <label for="tk_user_bef_comp"><?php echo $multilingual_user_bef_comp; ?></label>
                <div>
				<input type="text" name="tk_user_bef_comp" id="tk_user_bef_comp" value="<?php echo $row_Recordset1['tk_user_bef_comp']; ?>" placeholder="<?php echo $multilingual_user_bef_comp;?>"  class="form-control" />
                </div>
				<span class="help-block"><?php echo $multilingual_user_bef_comp_tip; ?></span>
              	</div>
<?php/*wangzi add end*/?>

			  <div class="form-group col-xs-12">
                <label for="tk_user_remark"><?php echo $multilingual_user_remark; ?></label>
                <div>
				<textarea name="tk_user_remark" id="tk_user_remark" class="form-control" rows="5" placeholder="<?php echo $multilingual_user_remark;?>"><?php echo $row_Recordset1['tk_user_remark']; ?></textarea>
                </div>
				<span class="help-block"><?php echo $multilingual_user_tip_remark; ?></span>
              </div>


<?php if ($_SESSION['MM_rank'] > "4") { ?>
			  <div class="form-group col-xs-12">
                <label for="tk_user_rank"><?php echo $multilingual_user_role; ?></label>
                <div>
				<select name="tk_user_rank"  id="tk_user_rank" class="form-control">
            <option value="0" <?php if (!(strcmp("0", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_disabled; ?></option>
            <option value="1" <?php if (!(strcmp("1", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_readonly; ?></option>
            <option value="2" <?php if (!(strcmp("2", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_guest; ?></option>
            <option value="3" <?php if (!(strcmp("3", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_general; ?></option>
            <option value="4" <?php if (!(strcmp("4", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_pm; ?></option>
            <option value="5" <?php if (!(strcmp("5", htmlentities($row_Recordset1['tk_user_rank'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $multilingual_dd_role_admin; ?></option>
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
<?php } else { ?>
          <input name="tk_user_rank" type="hidden" value="<?php echo $row_Recordset1['tk_user_rank'];?>"  />
            
		   <?php } ?>



  

           

				</td>
          </tr>
        </table></td>
    </tr>
    <tr class="input_task_bottom_bg" >
	<td></td>
      <td height="50px">
	  <button type="submit" class="btn btn-primary btn-sm submitbutton" name="cont" ><?php echo $multilingual_global_action_save; ?></button>
          <button type="button" class="btn btn-default btn-sm"  onclick="javascript:history.go(-1)" ><?php echo $multilingual_global_action_cancel; ?></button>
          
<input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="ID" value="<?php echo $row_Recordset1['uid']; ?>" /></td>
    </tr>
  </table>
  
</form>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php require('foot.php'); ?>
</body></html><?php
mysql_free_result($Recordset1);
?>
