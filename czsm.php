<?php require_once('config/tank_config.php'); ?>
<?php require_once('session_unset.php'); ?>
<?php require_once('session.php'); ?>


<?php require('head.php'); ?>

<body>
<div class="main_body">
<div class="globalnav" id="headerlink">
<ul>
<li class="gn_x"></li>
<li class="gn_right"></li>
</ul>
</div>

<script type="text/javascript" language="javascript">
if(location.href.toLowerCase().indexOf("default.php")>-1)
	document.getElementById("headerlink").getElementsByTagName("A")[0].className="nav_select";
else if(location.href.toLowerCase().indexOf("introduction.php")>-1 || location.href.toLowerCase().indexOf("feature.php")>-1)
	document.getElementById("headerlink").getElementsByTagName("A")[1].className="gn_intro_select";
else if(location.href.toLowerCase().indexOf("demo.php")>-1)
	document.getElementById("headerlink").getElementsByTagName("A")[2].className="gn_intro_select";
else if(location.href.toLowerCase().indexOf("download.php")>-1)
	document.getElementById("headerlink").getElementsByTagName("A")[3].className="gn_intro_select";
else if(location.href.toLowerCase().indexOf("file.php")>-1)
	document.getElementById("headerlink").getElementsByTagName("A")[4].className="gn_intro_select";
else if(location.href.toLowerCase().indexOf("feedback.php")>-1)
	document.getElementById("headerlink").getElementsByTagName("A")[5].className="gn_intro_select";
</script><div class="content_bg"> 
<br>
<br>
<table align="center" class="fontsize-s input_task_table glink">
<tbody>

<tr>
    <td colspan="2"><span class="input_task_title margin-y" style="margin-top:0px;">填写工作日志</span></td>
</tr>
    <tr>
    <td colspan="2"><p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<b><span style="font-family:宋体;">功能说明：</span></b>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<span style="font-family:宋体;">任务执行人员可以基于已经分配的任务计划填写任务日志。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<span style="font-family:宋体;">为方便填写，任务日志采用直观的日历形式，任务执行人员可以直接在日历中选择任务每天的执行状态、消耗的工时以及详细的工作日志。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<span style="font-family:宋体;">当任务日志保存时，所填写的工时将立即纳入项目进行汇总统计。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<span>&nbsp;</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<b><span>&nbsp;</span></b>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;text-indent:-18pt;">
	<span style="font-family:宋体;">1）</span><span style="font-family:宋体;">入口：最快捷的日志填写方式是在任务列表页面直接填写工作日志。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;">拖动任务列表页面的滚动条至下图位置，点击一处空白日志或已经填写的日志可进行日志操作（填写日志应基于已分配的任务，如没有任务指派给你，则应先“新建任务”后，再填写任务日志）。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;"><img border="0" width="624" height="281" src="./WSS - 文档_files/image014.jpg"></span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;">或点击任务名称进入“任务详情”页面</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;"><img border="0" width="437" height="436" src="./WSS - 文档_files/image015.jpg"></span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;">于“任务详情”页面的日历处点击一处空白日志或已经填写的日志可进行日志操作</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;"><font style="color:red">只能操作当天或前一天的日志</font></span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;"><img border="0" width="508" height="392" src="./WSS - 文档_files/image016.jpg"></span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;">&nbsp;</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;text-indent:-18pt;">
	<span>2）<span style="font-size:7pt;font-family:&#39;Times New Roman&#39;;">&nbsp;</span></span>&nbsp;<span style="font-family:宋体;">界面说明：</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span><img border="0" width="420" height="330" src="./WSS - 文档_files/image017.jpg"></span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:60pt;text-indent:-21pt;">
	<span>a)<span style="font-size:7pt;font-family:&#39;Times New Roman&#39;;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>&nbsp;<span style="font-family:宋体;">日期：日期</span><span>,</span><span style="font-family:宋体;">无需修改。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:60pt;text-indent:-21pt;">
	<span>b)<span style="font-size:7pt;font-family:&#39;Times New Roman&#39;;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>&nbsp;<span style="font-family:宋体;">状态：当前任务在该日期的状态；管理员可以通过后台管理任务状态。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:60pt;text-indent:-21pt;">
	<span>c)<span style="font-size:7pt;font-family:&#39;Times New Roman&#39;;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>&nbsp;<span style="font-family:宋体;">工时：当前任务在该日期所产生的工作时数。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:60pt;text-indent:-21pt;">
	<span>d)<span style="font-size:7pt;font-family:&#39;Times New Roman&#39;;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span>&nbsp;<span style="font-family:宋体;">日志：工作日志详情，可根据实际情况选择性填写。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;">
	<span>&nbsp;</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;text-indent:-18pt;">
	<span>3）<span style="font-size:7pt;font-family:&#39;Times New Roman&#39;;">&nbsp;</span></span>&nbsp;<span style="font-family:宋体;">填写完成后点击“保存”，任务日志填写流程结束。任务日志中所产生的工时，将立即纳入项目总工时，可供查询。任务日志只能由任务执行人来填写。</span>
</p>
<p style="font-family:Tahoma, Arial;font-size:13px;text-align:justify;background-color:#FFFFFF;margin-left:36pt;">
	<span style="font-family:宋体;">可以通过单击该界面中的“删除”按钮来删除一个已填写的日志。</span>
</p><br>
	
		</td>
  </tr>

      
</tbody></table>
<p>&nbsp;</p>
</div>
</div>

</body>
</html>

<?php require('foot.php'); ?>