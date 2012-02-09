<?php
require_once 'facemash.php';

global $FM;
if(isset($_REQUEST['dir'])){
	$FM->init_system($_REQUEST['dir']);
}
