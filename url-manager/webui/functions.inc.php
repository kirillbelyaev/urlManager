<?php

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

$URL_LISTS_TAGS_FILE = "url_lists_tags.txt";
$NET_LISTS_TAGS_FILE = "net_lists_tags.txt";
$INSTALL_LOCATION = "/root/url-manager/";
    
function validate_url($url_str)
{
    $trimmed = trim($url_str);

    if ( strlen($trimmed) == 0 )
    {
    return -1;
    }
    
    
    if (strpos($trimmed, "http://") == 0 && substr_count($trimmed, "http://") == 1 && substr_count($trimmed, "https://") == 0 && substr_count($trimmed, ":") == 1 && substr_count($trimmed, "://") == 1 && substr_count($trimmed, "//") == 1  && substr_count($trimmed, ",") == 0 && substr_count($trimmed, "\"") == 0) {
    return 0;
    } elseif (strpos($trimmed, "https://") == 0 && substr_count($trimmed, "https://") == 1 && substr_count($trimmed, "http://") == 0 && substr_count($trimmed, ":") == 1 && substr_count($trimmed, "://") == 1 && substr_count($trimmed, "//") == 1  && substr_count($trimmed, ",") == 0 && substr_count($trimmed, "\"") == 0) {
    return 0;
    } else {
        return -1;
    }

}

function add_urls($url_str, $url_list)
{
    global $INSTALL_LOCATION;
    $urls = explode("\n", $url_str); //get all urls into the array based on the separator
    $n=count($urls);
    $_SESSION['count_urls'] = $n;
    for ($i=0;$i<$n;$i++)
        if (validate_url($urls[$i]) == -1)
            return -1;
    
    for ($i=0;$i<$n;$i++)
    {
        $urls[$i]=trim($urls[$i]);
        $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -add-url=\"{$urls[$i]}\" -url-list-name=$url_list");
        
        if (strlen($output) > 0) //display the error from the backend on the page
        {
            $_SESSION['URL_MANAGER_ERR'] = "<font color=#FF0000> $output </font>";
            return -2;
        }
    }
    //system("/usr/bin/sudo -u root /root/squid-acl-gui/url-manager.py  -add-url=$i  -url-list-name=$url_list");
    
    return 0;
}


function delete_urls($url_str, $url_list)
{
        global $INSTALL_LOCATION;
        $urls = explode("\n", $url_str); //get all urls into the array based on the separator
        $n=count($urls);
        //$_SESSION['count_urls'] = $n;
        for ($i=0;$i<$n;$i++)
            if (validate_url($urls[$i]) == -1)
                return -1;
        
        for ($i=0;$i<$n;$i++)
        {
            $urls[$i]=trim($urls[$i]);
            $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -del-url=\"{$urls[$i]}\" -url-list-name=$url_list");
            
            if (strlen($output) > 0) //display the error from the backend on the page
            {
                $_SESSION['URL_MANAGER_ERR'] = "<font color=#FF0000> $output </font>";
                return -2;
            }
        }
        //system("/usr/bin/sudo -u root /root/squid-acl-gui/url-manager.py  -del-url=$i  -url-list-name=$url_list");
        
        return 0;
}

    
function block_url_net_access($net_list, $url_list)
{
        global $INSTALL_LOCATION;
    
        if (!is_array($url_list) ) return -1;
    
        //$ul = explode("\n", $url_list); //get all lists into the array based on the separator
        //$n=count($ul);
    
        $n=count($url_list);
    
        //$_SESSION['count_urls'] = $n;
        if ( empty($net_list) ) return -1;
    
        $net_list = trim($net_list);
        
        for ($i=0;$i<$n;$i++)
        {
            //$output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -add-url-block-access-acl=$net_list -url-list-name={$ul[$i]}");
            $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -add-url-block-access-acl=$net_list -url-list-name={$url_list[$i]}");
            
            if (strlen($output) > 0) //display the error from the backend on the page
            {
                $_SESSION['URL_MANAGER_ERR'] = "<font color=#FF0000> $output </font>";
                return -2;
            }
        }
        
        return 0;
}

    

function unblock_url_net_access($net_list, $url_list)
{
        global $INSTALL_LOCATION;
    
        if (!is_array($url_list) ) return -1;
    
        //$ul = explode("\n", $url_list); //get all lists into the array based on the separator
        //$n=count($ul);
    
        $n=count($url_list);
    
        //$_SESSION['count_urls'] = $n;
        if ( empty($net_list) ) return -1;
    
        $net_list = trim($net_list);
        
        for ($i=0;$i<$n;$i++)
        {
            //$output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -del-url-block-access-acl=$net_list -url-list-name={$ul[$i]}");
              $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -del-url-block-access-acl=$net_list -url-list-name={$url_list[$i]}");
            
            if (strlen($output) > 0) //display the error from the backend on the page
            {
                $_SESSION['URL_MANAGER_ERR'] = "<font color=#FF0000> $output </font>";
                return -2;
            }
        }
        
        return 0;
}
    
    
    
function lookup_url($url_str, $url_list)
{
        global $INSTALL_LOCATION;
        if (validate_url($url_str) == -1) return -1;
    
        if (empty($url_list)) return -2;
    
        $url_str = trim($url_str);
        $url_list = trim($url_list);
    
        $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}url-manager.py -lookup-url=\"$url_str\" -url-list-name=$url_list");
        $_SESSION['URL_MANAGER_LOOKUP_URL_STATUS'] = "<font color=#FF0000> $output </font>";
        return 0;
}
    
    

function lookup_net_list_ip($net_list)
{
    global $INSTALL_LOCATION;
    if (empty($net_list)) return -1;
    
    $net_list = trim($net_list);
    
    $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}lookup-net-ip.py -net-list-name=$net_list");
    $_SESSION['URL_MANAGER_LOOKUP_NET_IP_STATUS'] = "<font color=#579d1c> $output </font>";
    return 0;
}

    
function display_url_list($url_list)
{
    global $INSTALL_LOCATION;
    if (empty($url_list)) return -1;
        
    $url_list = trim($url_list);
    
    $output = `/usr/bin/sudo -u root {$INSTALL_LOCATION}display-url-list.py -url-list-name=$url_list`; //use backticks to save all the lines of the output
    $_SESSION['URL_MANAGER_DISPLAY_URL_LIST'] = $output;
    return 0;
}
    

function display_rules()
{
    global $INSTALL_LOCATION;
    if (empty($INSTALL_LOCATION)) return -1;
        
    $output = `/usr/bin/sudo -u root {$INSTALL_LOCATION}display-rules.py`; //use backticks to save all the lines of the output
    $_SESSION['URL_MANAGER_DISPLAY_RULES'] = $output;
    return 0;
}
    
    

function print_lines($v)
{
    if (empty($v)) return -1;
    
    $out = explode("\n", $v); //get all urls into the array based on the separator
    $n=count($out);
    
    for ($i=0;$i<$n;$i++)
    {
        echo "<p><font color=#0000FF> $out[$i] </font> </p>\n";
    }
}


function print_table($v)
{
    echo "<TABLE WIDTH=100% BORDER=1 CELLPADDING=4 CELLSPACING=4 STYLE=\"page-break-inside: avoid\">
    <COL WIDTH=256*>
    <TR>
    <TD WIDTH=100% VALIGN=TOP>
    <P ALIGN=CENTER><font color=#0000FF> $v </font></P>
    </TD>
    </TR>
    </TABLE>";
}
    

function print_lines_in_table($v)
{
    if (empty($v)) return -1;
        
    $out = explode("\n", $v); //get all urls into the array based on the separator
    $n=count($out);
        
    for ($i=0;$i<$n;$i++)
    {
        print_table($out[$i]);
    }
}
    
    
    
function call_write_url_tags_exec()
{
    global $INSTALL_LOCATION;
    $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}write-url-lists-tags.py");
    return $output;
}
    

function call_write_net_tags_exec()
{
    global $INSTALL_LOCATION;
    $output = system("/usr/bin/sudo -u root {$INSTALL_LOCATION}write-net-lists-tags.py");
    return $output;
}
    
    
function read_url_tags($file_str)
{
    call_write_url_tags_exec();
                     
    if ( !file_exists($file_str) )
        return -1;
    
    $lines = file($file_str);
    
    return $lines;
}
    

function read_net_tags($file_str)
{
    call_write_net_tags_exec();
        
    if ( !file_exists($file_str) )
        return -1;
        
    $lines = file($file_str);
        
    return $lines;
}
    

?>
