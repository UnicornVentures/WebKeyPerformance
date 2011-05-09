<?
require_once "webkeyperformance.class.php";
if(isset($argv[2])) $web = new webkeyperformance($argv[1],$argv[2]);
else $web = new webkeyperformance($argv[1]);
$web->printInfo();
?>
