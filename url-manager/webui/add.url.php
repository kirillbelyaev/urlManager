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
    
$urls = $_POST['urls'];
$urllist = $_POST['listbox'];
    
$_SESSION['urls'] = $urls;
$_SESSION['urllist'] = $urllist;
    
    
$pattern = "(\.|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\`|\<|\>)";
$ipattern = "(\`|\?|\@|\!|\~|\#|\%|\^|\&|\*|\(|\)|\+|\=|\\\|\||\{|\}|\[|\]|\;|\:|\'|\"|\,|\||\/|\\$|[A-Z]|[a-z]|\-|\_|\<|\>)";
//$urlcheck = validate_url($urls);
$check = add_urls($urls, $_SESSION['urllist']);

    if (empty($urls))
    {
        $_SESSION['error_urls'] = "<font color=#FF0000>URL text area is empty!</font>";
        header('Location: add-url.php');
    } elseif (!empty($urls) && $check == -1) {
        $_SESSION['error_urls'] = "<font color=#FF0000>Illegal characters in URL list!</font>";
	    header('Location: add-url.php');
    } elseif (!empty($urls) && $check == -2) { // $_SESSION['URL_MANAGER_ERR'] is set inside the function
        header('Location: add-url.php');
    } elseif (!empty($urls) && $check == 0) {
        $_SESSION['SUCCESS'] = "<font color=#579d1c>Operation went OK</font>";
        //system("/usr/bin/sudo -u root /root/web.ip.add $ip $tx $rx");
        header('Location: add-url.php');
	}
?>

