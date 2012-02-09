<?php
require_once 'config.php';

global $FM;
global $DB;
$DB = mysql_connect(DBHOST,DBUSERNAME,DBPASSWORD);

class Facemash {
	private $id;
	private $fields;
	
	public function __construct() {
		$this->id = null;
		$this->fields = array ('SF' => '', 'EX' => '0.5', 'RX' => '100', 'ST' => '0', 'TS' => '0', 'IA' => true );
	}
	
	public function __get($field) {
		if ($field == 'id') {
			return $this->id;
		} else {
			return $this->fields [$field];
		}
	}
	
	public function __set($field, $value) {
		if (array_key_exists ( $field, $this->fields )) {
			$this->fields [$field] = $value;
		}
	}
	
	public static function getById($id) {
		$y = new facemash ();
		$query = sprintf ( 'SELECT * FROM facemash WHERE ID="%s"', $id );
		$result = mysql_query ( $query );
		if (mysql_num_rows ( $result )) {
			$row = mysql_fetch_assoc ( $result );
			$y->id = $id;
			$y->SF = $row ['SF'];
			$y->EX = $row ['EX'];
			$y->RX = $row ['RX'];
			$y->ST = $row ['ST'];
			$y->TS = $row ['TS'];
			$y->IA = $row ['IA'];
			mysql_free_result ( $result );
			return $y;
		} else {
			return false;
		}
	}
	
	public function save() {
		$query = sprintf ( 'UPDATE facemash SET SF="%s",EX="%s",RX="%s",ST="%s",TS="%s",IA="%d" WHERE ID="%s"',
		$this->SF,$this->EX,$this->RX,$this->ST,$this->TS, $this->IA, $this->id );
		mysql_query ( $query );
	}
	public static function getStart() {
		$query = sprintf ( 'SELECT * FROM facemash WHERE IA = true ORDER BY ST,RX DESC ,RAND() LIMIT 1' ); 
		$result = mysql_query ( $query );
		$obj = mysql_fetch_object ( $result );
		$return [] = $obj;
		$query = sprintf ( 'SELECT * FROM facemash WHERE IA = true and ID<>"%s" ORDER BY ST,RX DESC,RAND() LIMIT 1', $obj->id); 
		$result = mysql_query ( $query );
		$obj = mysql_fetch_object ( $result );
				
		$return [] = $obj;
		mysql_free_result ( $result );
		return $return;
	}
	
	public static function getOtherNext($a, $b) {
		if(Facemash::check_orgin() && Facemash::check_cheating($a)){
			$aa = facemash::getById ( $a );
			$bb = facemash::getById ( $b );
			
			if(MODEL == 'debug'){
				print_r ($aa);
				echo '<br>';
				print_r ($bb);
				echo '<br>';
			}
			
			$aa->ST += 1;
			$bb->ST += 1;
			
			$aa->TS += 1; 
			$aa->EX = facemash::getE ( $aa->RX, $bb->RX );
			$bb->EX = facemash::getE ( $bb->RX, $aa->RX );
			
			$aa->RX = facemash::getR ( $aa->RX, 1, $aa->EX );
			$bb->RX = facemash::getR ( $bb->RX, 0, $bb->EX );
			
			if(MODEL == 'debug'){
				print_r ($aa);
				echo '<br>';
				print_r ($bb);
				echo '<br>';
			}
			
			$aa->save ();
			$bb->save ();
			Facemash::log($aa, 'winner');
			Facemash::log($bb, 'loser');
			Facemash::check_kickout($aa);
			Facemash::check_kickout($bb);
		}else {
			echo 'are you fall in loved with that girl?<br>';
		}
		
		$query = sprintf ( 'SELECT * FROM facemash WHERE ID<>"%s" AND ID<>"%s" AND IA=1 ORDER BY ST ASC,RAND() LIMIT 2', $a, $b );
		$result = mysql_query ( $query );
		while ( $obj = mysql_fetch_object ( $result ) ) {
			$return [] = $obj;
		}
		mysql_free_result ( $result );
		return $return;
	
	}
	

	public static function goBack($a, $b) {
		$a = intval ( $a );
		$b = intval ( $b );
		
		$a = facemash::getById ( $a );
		$b = facemash::getById ( $b );

		$a->RX = $a->RX - 16 * (1 - $a->EX);
		$b->RX = $b->RX - 16 * (0 - $b->EX);

		$a->save ();
		$b->save ();
		
		$return [] = $a;
		$return [] = $b;
		
		return $return;
	
	}

	public static function getE($ra, $rb) {
		$ra = floatval ( $ra );
		$rb = floatval ( $rb );
		return floatval ( 1 / (1 + pow ( 10, (($rb - $ra) / 400) )) );
	}
	

	public static function getR($ra, $sa, $ea) {
		$ra = floatval ( $ra );
		$sa = intval ( $sa );
		$ea = floatval ( $ea );
		if ($ra <= floatval ( 2099 )) {
			$k = 32;
		} else if ($ra <= floatval ( 2399 )) {
			$k = 24;
		} else {
			$k = 16;
		}
		return floatval ( $ra + $k * ($sa - $ea) );
	
	}
	public static function getTop($num) {
		$query = sprintf ( 'SELECT * FROM facemash WHERE IA = 1 ORDER BY RX DESC LIMIT %d', $num );
		$result = mysql_query ( $query );
		while ( $obj = mysql_fetch_object ( $result ) ) {
			$return [] = $obj;
		}
		return $return;
	}
	
public static function getKicked() {
		$query = sprintf ( 'SELECT * FROM facemash WHERE IA = 0 ORDER BY RX ASC' );
		$result = mysql_query ( $query );
		while ( $obj = mysql_fetch_object ( $result ) ) {
			$return [] = $obj;
		}
		return $return;
	}
	
	public static function get_file_path($file_id){
		if(strtoupper(substr($file_id,-3)) == 'JPG' || strtoupper(substr($file_id,-4)) == 'JPEG'){
			return HASHEDFILES.DS.substr($file_id,0,1).DS.$file_id;
		}
		return HASHEDFILES.DS.substr($file_id,0,1).DS.$file_id.'.jpg';
	}
	
	public static function init_system($dir)
	{
		$arr = scandir($dir);
		$des = HASHEDFILES;
		mkdir($des,0777);
		print_r($arr);
		$sql_insert = "INSERT INTO `facemash`.`facemash`(`id`,`SF`,`EX`,`RX`,`ST`,`TS`,`IA`)VALUES ('%s','%s','0.5','100','0','0','1')";
		foreach($arr as $item)
		{
			if(strtoupper(substr($item,-3))!='JPG' && strtoupper(substr($item,-4))!='JPEG'){
				continue;
			}
				
			$hash_num = hash_file('md5',$dir.DS.$item);
			echo "<br>";
			$to = Facemash::get_file_path($hash_num);
			if(copy($dir.DS.$item,$to) != 1)
			{
				mkdir($des.DS.substr($hash_num,0,1),0777);
				copy($dir.DS.$item,$to);
			}
			$query = sprintf($sql_insert,$hash_num,$item);
			mysql_query($query);
			echo $query;
		}
	}
	public static function log($a,$info = ''){
		if($info == ''){
			return ;
		}
		$sql_insert = "INSERT INTO `".DBNAME."`.`".LOGTABLE.
			"`(`id`,`result`,`RX`,`EX`,`IP`,`time`) VALUES ('%s','%s','%f','%f','%s',now());";
		$sql = sprintf($sql_insert,$a->id,$info,$a->RX,$a->EX,$_SERVER['REMOTE_ADDR']);
		mysql_query($sql);
		if(MODEL == 'debug'){
			echo $info.':::::::::::';
			print_r($a);
			echo '<br>'; 
		}
	}
	
	public static function check_kickout($a){
		if(($a->ST > 6 && $a->TS/$a->ST < 0.2)||$a->RX < 0)  {
			$a->IA = 0;
			$a->save();
			Facemash::log($a,'kickout');
		}
	}
	public static function check_cheating($target, $force = 3){
		$sql = "SELECT COUNT(*) FROM (SELECT id FROM %s WHERE IP = '%s' 
		AND result IN ('winner', 'loser') ORDER BY log_id DESC LIMIT %d) AS result 
		WHERE id='%s'";
		$query = sprintf($sql, LOGTABLE, $_SERVER['REMOTE_ADDR'], $force*2, $target);
		$result = mysql_query($query);
		$obj = mysql_fetch_array($result);
		if(MODEL == 'debug'){
			echo '<br>'.'::::::::::::::::::::::::::::::::::::CHECK_CHEATING'.'<br>';
			echo $query.'<br>';
			echo '<br>'.'::::::::::::::::::::::::::::::::::::::::::::::::::'.'<br>';
			
		}
		if($obj[0] > 0){
			echo 'check_cheating failure!<br>';
			return false;
		}
		return true;
	}
	public static function check_orgin(){
		if(!isset($_SERVER['HTTP_ORIGIN'])){
			return false;
		}
		if(MODEL == 'debug'){
			echo '<br>'.'::::::::::::::::::::::::::::::::::::::CHECK_ORIGIN'.'<br>';
			echo $_SERVER['HTTP_ORIGIN'];
			echo '<br>'.'::::::::::::::::::::::::::::::::::::::::::::::::::'.'<br>';
		}
		if(stripos($_SERVER['HTTP_ORIGIN'],FMORIGIN) == null){
			echo "check_origin failure!<br>";
			return false;
		}
		return true;
	}
}

$FM = new Facemash();
mysql_select_db(DBNAME,$DB);



