<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>Facemash HIT</title>
</head>
<body>
<?php

require_once 'facemash.php';
global $FM;

if(isset($_REQUEST['a']) && isset($_REQUEST['b'])){
	$fm_data = $FM->getOtherNext($_REQUEST['a'],$_REQUEST['b']);
	$a = $FM->get_file_path($fm_data[0]->id);
	$b = $FM->get_file_path($fm_data[1]->id);
	
}else{
	$fm_data = $FM->getStart();
	$a = $FM->get_file_path($fm_data[0]->id);
	$b = $FM->get_file_path($fm_data[1]->id);
}


$html1 = sprintf("<a href='javascript:document.forma.submit()'><img src='%s' height=400px width=300px/></a>",$a);
$html2 = sprintf("<a href='javascript:document.formb.submit()'><img src='%s' height=400px width=300px/></a>",$b);

?>


<div style='font-size:48px; text-align:center; color:red;'>choose which one is hotter!</div>

<div valign=middle align=center><table>
<tr>

<form name=forma action="index.php" method="post">
<input type="hidden" name="a" value="<?php echo $fm_data[0]->id?>">
<input type="hidden" name="b" value="<?php echo $fm_data[1]->id?>">   
<td><?php echo $html1;?></td></form>

<form name=formb action="index.php" method="post">
<input type="hidden" name="a" value="<?php echo $fm_data[1]->id?>">
<input type="hidden" name="b" value="<?php echo $fm_data[0]->id?>">   
<td><?php echo $html2;?></td>
</form>

</tr>
<a href="top.php" style="font-size:32px;">top 20 girls</a>
<tr>
<td><?php printf("EX:%f<br>RX:%d",$fm_data[0]->EX,$fm_data[0]->RX);?></td>
<td><?php printf("EX:%f<br>RX:%d",$fm_data[1]->EX,$fm_data[1]->RX);?></td>
</tr>
</table>
</div>
<script src="http://s13.cnzz.com/stat.php?id=3746062&web_id=3746062&show=pic1" language="JavaScript"></script>
</body>
</html>

