<?php session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>ip-rm</title>
</head>
<body>
<div style="text-align: center;"><br>
<span style="font-weight: bold;">&#1054;&#1082;&#1085;&#1086; &#1091;&#1087;&#1088;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1103; IP&nbsp; &#1072;&#1076;&#1088;&#1077;&#1089;&#1072;&#1084;&#1080;</span><br>
<br>
<span style="font-weight: bold;">&#1052;&#1086;&#1076;&#1091;&#1083;&#1100; &#1091;&#1076;&#1072;&#1083;&#1077;&#1085;&#1080;&#1103; IP &#1072;&#1076;&#1088;&#1077;&#1089;&#1086;&#1074;<br>
<br>
<?php
 	if(isset($_SESSION['error_ip'])) {                                                                                                  echo $_SESSION['error_ip'];                                                                                                  unset($_SESSION['error_ip']);
		}
?>
<br>
</span>
<div style="text-align: left;"><span style="font-weight: bold;"><br>
<form action="ip.rm-imq.php" method = "post">
&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; IP &#1076;&#1083;&#1103; &#1091;&#1076;&#1072;&#1083;&#1077;&#1085;&#1080;&#1103;:<br>
<br>
<td align="center" width="20%"> <input name="ip" style="width: 20%;" value="<?= $_SESSION['ip'] ?>" class="text" type="text"> </td>

<br>
<input style="width: 15%;" name="rmip" value="&#1059;&#1076;&#1072;&#1083;&#1080;&#1090;&#1100; IP" type="submit">
</span><span style="font-weight: bold;"></span></div>
</div>
</body>
</form>
</html>
