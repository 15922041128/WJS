<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session_admin.php'); ?>
<?php 
$Recordset_team = getAllTeam();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WJS - <?php echo $multilingual_dept_title; ?></title>
<link href="skin/themes/base/custom.css" rel="stylesheet" type="text/css" />
<link href="skin/themes/base/tk_style.css" rel="stylesheet" type="text/css" />
<link rel="StyleSheet" href="css/zTreeStyle/zTreeStyle.css" type="text/css" />
<link rel="StyleSheet" href="css/teamTree.css" type="text/css" />
<script type="text/javascript" src="srcipt/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="srcipt/jquery.ztree.exedit-3.5.js"></script>
<script type="text/javascript" src="srcipt/js.js"></script>
<script type="text/javascript">
function decodeUTF8(str){  
	return str.replace(/(\\u)(\w{4}|\w{2})/gi, function($0,$1,$2){  
	    return String.fromCharCode(parseInt($2,16));  
	});   
} 

var setting = {
	view: {
		addHoverDom: addHoverDom,
		removeHoverDom: removeHoverDom,
		dblClickExpand: true
	},
	check: {
		enable: false
	},
	callback: {
		onRightClick: OnRightClick,
		onRename: onRename,
		onRemove: onRemove,
		beforeEditName: beforeEditName,
		beforeRemove: beforeRemove,
		beforeRename: beforeRename,
		beforeDrag: beforeDrag
	},
	edit: {
		enable: true,
		renameTitle: "<?php echo $multilingual_team_edit_title ?>",
		removeTitle: "<?php echo $multilingual_team_remove_title ?>",
		showRenameBtn: showRenameBtn,
		showRemoveBtn: showRemoveBtn
	},
	data: {  
	    simpleData: {  
	        enable: true,
	        idKey: "id",  
	        pIdKey: "pId",  
	        rootPId: 0                            
	    } 
	}
};

var zNodes =[
	<?php
	while ($row_team = mysql_fetch_assoc($Recordset_team)) {
		$pid = $row_team['pid'];
		$title = $row_team['tk_team_title'];
		$parentID = $row_team['tk_team_parentID'];
		if ($pid == 1){
	?>	
		{id:<?php echo $pid ?>, pId:<?php echo $parentID ?>, name:"<?php echo $title ?>", open:true, noR:true},
	<?php	
		} else {
	?>
		{id:<?php echo $pid ?>, pId:<?php echo $parentID ?>, name:"<?php echo $title ?>", open:true, noR:false},
	<?php		
		}
	?>	
	<?php
	}
	?>	
];

function getConfirmMessage(nodeName, flag) {
	if ("remove" == flag) {
		return "确认删除部门[ " + nodeName + "]吗？如果该部门为上级部门则子部门也将被删除。";
	} else if ("edit" == flag) {
		return "进入部门[" + nodeName + "]的编辑状态吗？";
	}
}


<!-- 右键菜单  start -->
function OnRightClick(event, treeId, treeNode) {
	if (!treeNode && event.target.tagName.toLowerCase() != "button" && $(event.target).parents("a").length == 0) {
		zTree.cancelSelectedNode();
		showRMenu("root", event.clientX, event.clientY);
	} else if (treeNode && !treeNode.noR) {
		zTree.selectNode(treeNode);
		showRMenu("node", event.clientX, event.clientY);
	}
}

function showRMenu(type, x, y) {
	$("#rMenu ul").show();
	if (type=="root") {
		$("#m_del").hide();
//		$("#m_check").hide();
//		$("#m_unCheck").hide();
	} else {
		$("#m_del").show();
//		$("#m_check").show();
//		$("#m_unCheck").show();
	}
	rMenu.css({"top":y+"px", "left":x+"px", "visibility":"visible"});

	$("body").bind("mousedown", onBodyMouseDown);
}
function hideRMenu() {
	if (rMenu) rMenu.css({"visibility": "hidden"});
	$("body").unbind("mousedown", onBodyMouseDown);
}
function onBodyMouseDown(event){
	if (!(event.target.id == "rMenu" || $(event.target).parents("#rMenu").length>0)) {
		rMenu.css({"visibility" : "hidden"});
	}
}
function addTreeNode() {
	hideRMenu();
	if (zTree.getSelectedNodes()[0]) {
		var returnData = addTeam(zTree.getSelectedNodes()[0].id, "<?php echo $multilingual_team_newNode ?>");
		returnData = eval('(' + returnData + ')');
		var newNode = {id:returnData.id, pId:zTree.getSelectedNodes()[0].id, name:"<?php echo $multilingual_team_newNode ?>"};
		newNode.checked = zTree.getSelectedNodes()[0].checked;
		zTree.addNodes(zTree.getSelectedNodes()[0], newNode);
	} 
}
function removeTreeNode() {
	hideRMenu();
	var nodes = zTree.getSelectedNodes();
	if (nodes[0].id == 1) {
		alert("<?php echo $multilingual_team_check_team_remove_root ?>");
		return false;
	}
	if (nodes && nodes.length>0) {
		var message = getConfirmMessage(nodes[0].name, "remove");
		if (confirm(message) == true) {
			var idArray = new Array();
			idArray = getChildren(idArray, nodes[0]);
			deleteTeam(idArray);
			zTree.removeNode(nodes[0]);
		}
	}
	
}
<!-- 右键菜单  end -->

<!-- setting配置操作 start -->
function beforeDrag(treeId, treeNodes) {
	return false;
}

function addHoverDom(treeId, treeNode) {
	var sObj = $("#" + treeNode.tId + "_span");
	if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
	var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
		+ "' title='<?php echo $multilingual_team_add_title ?>' onfocus='this.blur();'></span>";
	sObj.after(addStr);
	var btn = $("#addBtn_"+treeNode.tId);
	if (btn) btn.bind("click", function(){
		var zTree = $.fn.zTree.getZTreeObj("treeDemo");
		var returnData = addTeam(treeNode.id, "<?php echo $multilingual_team_newNode ?>");
		returnData = eval('(' + returnData + ')');
		zTree.addNodes(treeNode, {id:returnData.id, pId:treeNode.id, name:"<?php echo $multilingual_team_newNode ?>"});
		return false;
	});
};
function removeHoverDom(treeId, treeNode) {
	$("#addBtn_"+treeNode.tId).unbind().remove();
};

function showRenameBtn(treeId, treeNode) {
	return treeNode.level != 0;
}

function showRemoveBtn(treeId, treeNode) {
	return treeNode.level != 0;
}

function beforeEditName(treeId, treeNode) {
	return confirm(getConfirmMessage(treeNode.name, "edit"));
	
}

function beforeRename(treeId, treeNode, newName, isCancel) {
	if (newName.length == 0) {
		alert("<?php echo $multilingual_team_check_team_title ?>");
		return false;
	}
	return true;
}

function beforeRemove(treeId, treeNode) {
	var message = getConfirmMessage(treeNode.name, "remove");
	return confirm(message);
}

function onRename(e, treeId, treeNode, isCancel) {
	editTeam(treeNode.id, treeNode.name);
}

function onRemove(e, treeId, treeNode) {
	var idArray = new Array();
	idArray = getChildren(idArray, treeNode);
	deleteTeam(idArray);
}

function getChildren(idArray, treeNode) {
	idArray.push(treeNode.id);
	if (treeNode.isParent) {
		for(var obj in treeNode.children){
			getChildren(idArray,treeNode.children[obj]);
		}
	}
	return idArray;
}
<!-- setting配置操作 end -->

<!-- database操作 start -->
function addTeam(parentID, title){
    var returnData;
    $.ajax({  
         type : "post",  
         url :  "team_option.php",  
         data : {
		        method:"add",
		        parentID:parentID, 
		        title:title
	     }, 
         async : false,  
         success : function(data){  
            returnData = decodeUTF8(data);
         }  
     }); 
     return returnData;
}

function editTeam(pid, title){
 	$.post(
	      'team_option.php',
	      {
	        method:"edit",
	        id:pid, 
	        title:title
	      },
	      function (data) {
	        // alert(decodeUTF8(data));
	      }
    );
}

function deleteTeam(ids){
	$.post(
	      'team_option.php',
	      {
	        method:"delete",
	        pids:ids 
	      }
    );
}

<!-- database操作 end -->

var zTree, rMenu;
$(document).ready(function(){
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	zTree = $.fn.zTree.getZTreeObj("treeDemo");
	rMenu = $("#rMenu");
});
</script>
<style type="text/css">
div#rMenu {position:absolute; visibility:hidden; top:0; background-color: #555;text-align: left;padding: 2px;}
div#rMenu ul li{
	margin: 1px 0;
	padding: 0 5px;
	cursor: pointer;
	list-style: none outside none;
	background-color: #DFDFDF;
}
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
</style>

</head>

<body>
<?php require('admin_head.php'); ?>
<table border="0" cellspacing="5" cellpadding="12" width="100%">
  <tr>
    <td width="200px" class="set_menu_bg" valign="top"><?php require('setting_menu.php'); ?></td>
	<td >
<div class="ui-widget"  style="margin:auto;">

<div class="content_wrap">
	<div class="zTreeDemoBackground left">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
	<div class="right">
		<ul class="info">
		</ul>
	</div>
</div>
<div id="rMenu">
	<ul>
		<li id="m_add" onclick="addTreeNode();"><?php echo $multilingual_team_add_title ?></li>
		<li id="m_del" onclick="removeTreeNode();"><?php echo $multilingual_team_remove_title ?></li>
	</ul>
</div>


</div>
	</td>
  </tr>
</table>

<?php require('foot.php'); ?>
</body>
</html>
