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
$ux = $_POST['ux'];

$_SESSION['ip'] = $ip;
$_SESSION['ux'] = $ux;
$pattern = "(\.|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\`|\<|\>)";
$ipattern = "(\`|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\<|\>)";
$ipcheck = ip_check($ip);

 if (empty($ip)) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is empty!</font>";
    header('Location: ip-add-imq.php');
    } elseif (!empty($ip) && $ipcheck == 1) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is in use!</font>";
	    header('Location: ip-add-imq.php');
    } elseif (preg_match($ipattern, $_POST['ip'])) {
    $_SESSION['error_ip'] = "<font color=#FF0000>IP is a number!</font>";
    header('Location: ip-add-imq.php');
    } elseif (empty($ux)) {
    $_SESSION['error_ux'] = "<font color=#FF0000>UX is empty!</font>";
    header('Location: ip-add-imq.php');
    } elseif ($ux != 64 && $ux != 128) {
    $_SESSION['error_ux'] = "<font color=#FF0000>Illegal UX!</font>";
    header('Location: ip-add-imq.php');
    } elseif (preg_match($pattern, $_POST['ux'])) {
    $_SESSION['error_ux'] = "<font color=#FF0000>Illegal characters!</font>";
    header('Location: ip-add-imq.php');
    } else {


   system("/usr/bin/sudo -u root /root/web.ip.add.imq $ip $ux");
header('Location: ip-add-imq-ok.html');
	}
?>

