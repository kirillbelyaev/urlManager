<?php session_start();
#include_once("extern.php");
function ip_check($ip)
{
$str = system("/usr/bin/sudo -u root /usr/bin/grep -F $ip' ' /etc/rc.d/rc.firewall.accept");
if ($str!="") {
return 1;
}

return 0;
}

$ip = $_POST['ip'];
$tx = $_POST['tx'];
$rx = $_POST['rx'];

$_SESSION['ip'] = $ip;
$_SESSION['tx'] = $tx;
$_SESSION['rx'] = $rx;
$pattern = "(\.|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\`|\<|\>)";
$ipattern = "(\`|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\<|\>)";
$ipcheck = ip_check($ip);

 if (empty($ip)) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is empty!</font>";
    header('Location: ip-add.php');
    } elseif (!empty($ip) && $ipcheck == 1) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is in use!</font>";
	    header('Location: ip-add.php');
    } elseif (preg_match($ipattern, $_POST['ip'])) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is a number!</font>";
    header('Location: ip-add.php');
    } elseif (empty($tx)) {
    $_SESSION['error_tx'] = "<font color=#FF0000>TX is empty!</font>";
    header('Location: ip-add.php');
    } elseif ($tx != 19 && $tx != 33 && $tx != 45 && $tx != 64 && $tx != 96 && $tx != 128 && $tx != 256 && $tx != 512) {
    $_SESSION['error_tx'] = "<font color=#FF0000>Illegal TX!</font>";
    header('Location: ip-add.php');
    } elseif (preg_match($pattern, $_POST['tx'])) {
    $_SESSION['error_tx'] = "<font color=#FF0000>Illegal characters!</font>";
    header('Location: ip-add.php');
    } elseif (empty($rx)) {
    $_SESSION['error_rx'] = "<font color=#FF0000>RX is empty!</font>";
    header('Location: ip-add.php');
    } elseif ($rx != 19 && $rx != 33 && $rx != 45 && $rx != 64 && $rx != 96 && $rx != 128 && $rx != 256 && $rx != 512) {
    $_SESSION['error_rx'] = "<font color=#FF0000>Illegal RX!</font>";
    header('Location: ip-add.php');
    } elseif (preg_match($pattern, $_POST['rx'])) {
    $_SESSION['error_rx'] = "<font color=#FF0000>Illegal characters!</font>";
    header('Location: ip-add.php');
    } else {


   system("/usr/bin/sudo -u root /root/web.ip.add $ip $tx $rx");
header('Location: ip-add-ok.html');
	}
?>

