<?php
if(strtoupper(substr(PHP_OS,0,2))=='WIN'){
	define('DS',DS);
}else{
	define('DS','/');
}

define('DBUSERNAME','root');
define('DBPASSWORD','root');
define('DBHOST','localhost');
define('DBNAME','facemash');
define('DBTABLENAME','facemash');
define('HASHEDFILES','hashed_files');
define('MODEL','run');
define('LOGTABLE','fmlog');
define('FMORIGIN','XX.net');

