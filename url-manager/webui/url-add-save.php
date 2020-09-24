<?php session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>ip-add</title>
</head>
<body>
<div style="text-align: center;"><br>
<br>
<span style="font-weight: bold;">&#1054;&#1082;&#1085;&#1086; &#1091;&#1087;&#1088;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1103; URL&nbsp; листами </span><br>
<br>
<span style="font-weight: bold;">&#1052;&#1086;&#1076;&#1091;&#1083;&#1100; &#1076;&#1086;&#1073;&#1072;&#1074;&#1083;&#1077;&#1085;&#1080;&#1103; IP &#1072;&#1076;&#1088;&#1077;&#1089;&#1086;&#1074;</span><br>
<br>
<span style="color: rgb(255, 0, 0);">&#1042;&#1085;&#1080;&#1084;&#1072;&#1085;&#1080;&#1077;!</span><br
 style="color: rgb(255, 0, 0);">
<span style="font-weight: bold;">&#1055;&#1088;&#1080;&nbsp; &#1085;&#1072;&#1079;&#1085;&#1072;&#1095;&#1077;&#1085;&#1080;&#1080; &#1089;&#1082;&#1086;&#1088;&#1086;&#1089;&#1090;&#1080; &#1085;&#1072; IP
&#1072;&#1076;&#1088;&#1077;&#1089; &#1076;&#1077;&#1081;&#1089;&#1090;&#1074;&#1091;&#1077;&#1090; &#1089;&#1083;&#1077;&#1076;&#1091;&#1102;&#1097;&#1072;&#1103; &#1096;&#1082;&#1072;&#1083;&#1072; &#1095;&#1080;&#1089;&#1077;&#1083;-&#1087;&#1072;&#1088;&#1072;&#1084;&#1077;&#1090;&#1088;&#1086;&#1074;:</span><br
 style="font-weight: bold;">
<span style="font-weight: bold; color: rgb(255, 0, 0);">19&nbsp;&nbsp;
33&nbsp;&nbsp; 45&nbsp;&nbsp; 64&nbsp;&nbsp; 96&nbsp;&nbsp; 128</span><br>
<br>
&#1055;&#1088;&#1080; &#1087;&#1086;&#1087;&#1099;&#1090;&#1082;&#1077; &#1074;&#1074;&#1086;&#1076;&#1072; &#1076;&#1088;&#1091;&#1075;&#1080;&#1093; &#1095;&#1080;&#1089;&#1083;&#1086;&#1074;&#1099;&#1093; &#1079;&#1085;&#1072;&#1095;&#1077;&#1085;&#1080;&#1081;&nbsp; &#1074; &#1089;&#1077;&#1088;&#1074;&#1080;&#1089;&#1077; &#1073;&#1091;&#1076;&#1077;&#1090;
&#1086;&#1090;&#1082;&#1072;&#1079;&#1072;&#1085;&#1086;! <br>
<br style="font-weight: bold;">
<br style="font-weight: bold;">
<br style="font-weight: bold;">
<?php
 if(isset($_SESSION['error_ip'])) {
	 echo $_SESSION['error_ip'];
	  unset($_SESSION['error_ip']);
	  }
 if(isset($_SESSION['error_tx'])) {
	 echo $_SESSION['error_tx'];
	  unset($_SESSION['error_tx']);
	  }
 if(isset($_SESSION['error_rx'])) {
	 echo $_SESSION['error_rx'];
	  unset($_SESSION['error_rx']);
	  }
 
    
//echo $_SESSION['urls'];
    
 //$bb = array("hello", "world", "go");
 //$x=0;
 //print "this is: {$bb[$x]} ";
 //echo $b[0];
 //echo $_SESSION['b[hello]'];
 //print_r($_SESSION['b']);
 ?>

<form action="url.add.php" method = "post">
<div style="text-align: left;"><span style="font-weight: bold;">&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077;
IP &#1072;&#1076;&#1088;&#1077;&#1089;:</span><br style="font-weight: bold;">


<p>URL lists:<br>
<select name="listbox" size="15">

<?php
$b = array("hello", "world", "go");
$n=count($b);

    //loop through array
for($i=0;$i<$n;$i++)
    print "<option> {$b[$i]} </option>";

?>

</select>
</p>

<?php
//echo $_SESSION['urllist'] ;
echo "<p>listbox: " . $_SESSION['urllist'] . "</p>\n";
?>

<td align="center" width="20%"> <input name="ip" style="width: 20%;" value="<?= $_SESSION['ip'] ?>" class="text" type="text"> </td>
<br style="font-weight: bold;">
<br style="font-weight: bold;">
<span style="font-weight: bold;">&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1082;&#1086;&#1088;&#1086;&#1089;&#1090;&#1100; &#1085;&#1072; &#1087;&#1077;&#1088;&#1077;&#1076;&#1072;&#1095;&#1091; - TX:</span><br
 style="font-weight: bold;">
<td align="center" width="20%"> <input name="tx" style="width: 20%;" value="<?= $_SESSION['tx'] ?>" class="text" type="text"> </td>
<br style="font-weight: bold;">
<br style="font-weight: bold;">
<span style="font-weight: bold;">&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1082;&#1086;&#1088;&#1086;&#1089;&#1090;&#1100; &#1085;&#1072; &#1087;&#1088;&#1080;&#1105;&#1084; - RX:<br>
<td align="center" width="20%"> <input name="rx" style="width: 20%;" value="<?= $_SESSION['rx'] ?>" class="text" type="text"> </td>
<br>
<textarea name="urls" value="<?= $_SESSION['urls'] ?>" class="textarea" type="textarea" rows="25" cols="45">  </textarea>
<br>
<input style="width: 15%;" name="addip" value="&#1044;&#1086;&#1073;&#1072;&#1074;&#1080;&#1090;&#1100; IP" type="submit">
<br>
</form>
</span></div>
</div>
</body>
</html>
