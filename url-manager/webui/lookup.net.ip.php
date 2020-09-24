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
    
#include_once("extern.php");
require_once("functions.inc.php");
    
$netlist = $_POST['net_listbox'];
    
$_SESSION['netlist'] = $netlist;
    
$pattern = "(\.|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\`|\<|\>)";
$ipattern = "(\`|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\<|\>)";
//$urlcheck = validate_url($urls);
$check = lookup_net_list_ip($_SESSION['netlist']);

    if (empty($netlist))
    {
        $_SESSION['error_netl'] = "<font color=#FF0000>No Net list selection made!</font>";
        header('Location: lookup-net-ip.php');
    } elseif (!empty($netlist) && $check == -1) {
        $_SESSION['error_netl'] = "<font color=#FF0000>Error. Empty Net list string.</font>";
	    header('Location: lookup-net-ip.php');
    } elseif (!empty($netlist) && $check == 0) { // $_SESSION['URL_MANAGER_LOOKUP_NET_IP_STATUS'] is set inside the function
        header('Location: lookup-net-ip.php');
	}
?>

