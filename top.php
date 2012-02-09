<html>
<head>
<title>top 20 girls</title>
</head>
<body>
<?php
	require_once 'facemash.php';
	global $FM;
	if(isset($_REQUEST['mode'])){
		if($_REQUEST['mode'] == 'kicked'){
			$return = $FM->getKicked();
		}
	}else{
		$return = $FM->getTop(20);
		echo '<div style="font-size:48px;color:red;text-align:center;">top 20 girls</div>';
	}
	
	$i = 0;
	echo '<table align=center>';
    foreach ($return as $person){
    	if($i%5 == 0){
    		if($i != 0)
    			echo '</tr>';
    		echo '<tr>';
    	}
    	echo '<td>';
    	$a = substr($person->id,0,1).'/'.$person->id.'.jpg';
    	$html = sprintf("<img src='%s' height=160px width=120px/>",'hashed_files/'.$a);
    	echo $html."<br>\n";
    	echo '</td>';
    	$i = $i + 1;
    }
    if($i%5 != 0){
    	echo '</tr>';
    }
    echo '</table>';
?>

<script src="http://s13.cnzz.com/stat.php?id=3746062&web_id=3746062&show=pic1" language="JavaScript"></script>
</body>
</html>