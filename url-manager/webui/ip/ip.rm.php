<?php session_start();
	#include_once("extern.php");

$ip = $_POST['ip'];

$_SESSION['ip'] = $ip;
$ipattern = "(\`|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_)";
 if (empty($ip)) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is empty!</font>";
    header('Location: ip-rm.php');
    } elseif (preg_match($ipattern, $_POST['ip'])) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is a number!</font>";
    header('Location: ip-rm.php');
    } else {


   system("/usr/bin/sudo -u root /root/web.ip.rm $ip");
header('Location: ip-add-ok.html');
	}
?>
