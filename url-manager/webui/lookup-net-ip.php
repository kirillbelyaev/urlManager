<?php session_start();
    #url-block management tool
    #Copyright (C) 2014 Kirill Belyaev (kirill@cs.colostate.edu)
    #
    #This program is free software; you can redistribute it and/or
    #modify it under the terms of the GNU General Public License
    #as published by the Free Software Foundation; either version 2
    #of the License, or (at your option) any later version.
    #
    #This program is distributed in the hope that it will be useful,
    #but WITHOUT ANY WARRANTY; without even the implied warranty of
    #MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    #GNU General Public License for more details.
    #
    #You should have received a copy of the GNU General Public License
    #along with this program; if not, write to the Free Software
    #Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
    
require_once("functions.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>lookup-net-IP-module</title>
</head>
<body>
<div style="text-align: center;"><br>
<span style="font-weight: bold;">lookup-net-IP module</span><br>
<br>

<?php
include("include/topa.php");
    
if(isset($_SESSION['error_netl']))
{
    echo $_SESSION['error_netl'];
    unset($_SESSION['error_netl']);
}
 
if(isset($_SESSION['URL_MANAGER_LOOKUP_NET_IP_STATUS']))
{
    echo $_SESSION['URL_MANAGER_LOOKUP_NET_IP_STATUS'];
    unset($_SESSION['URL_MANAGER_LOOKUP_NET_IP_STATUS']);
}
 
if(isset($_SESSION['SUCCESS']))
{
    echo $_SESSION['SUCCESS'];
    unset($_SESSION['SUCCESS']);
}
    
 //echo $_SESSION['urls'];
 //echo "<p>count of urls: " . $_SESSION['count_urls'] . "</p>\n";
 //$bb = array("hello", "world", "go");
 //$x=0;
 //print "this is: {$bb[$x]} ";
 //echo $b[0];
 //echo $_SESSION['b[hello]'];
 //print_r($_SESSION['b']);
    
 ?>

<form action="lookup.net.ip.php" method = "post">
<div style="text-align: left;"><span style="font-weight: bold;"> Select Net list from the list box below: </span><br style="font-weight: bold;">

<p>Net lists:<br>
<select name="net_listbox" size="15">

<?php
$net_tags = read_net_tags($NET_LISTS_TAGS_FILE);
$n=count($net_tags);
//loop through array and deliver the values
for($i=0;$i<$n;$i++)
    print "<option> {$net_tags[$i]} </option>";

?>
</select>
</p>


<?php
//echo $_SESSION['urllist'] ;
//echo "<p>listbox: " . $_SESSION['urllist'] . "</p>\n";
?>



<br>
<br>
<input style="width: 25%;" name="lookup_net_ip" value="Lookup Net IP range" type="submit">
<br>
</form>
</span></div>
</div>
<?php include("include/bottom.php"); ?>
</body>
</html>
