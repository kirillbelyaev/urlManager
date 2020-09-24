#!/usr/bin/python

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

import string
import os
import sys

import ConfigParser

import argparse

from globals import *

from functions_module import *

config = ConfigParser.ConfigParser()
config.add_section("files")
config.read(CONFIG_FILE_PATH + CONFIG_FILE)

# if the dbase-files do not exist they are created automatically
squid_cfg = config.get("files","squid_cfg")
#static_cfg = config.get("files","static_cfg")
squid_reconfigure_command = config.get("commands","squid_reconfigure_command")
case_sensitive = (config.get("parameters","case_sensitive") == 'TRUE')


def main():
    
    parser = argparse.ArgumentParser(description='Tool to lookup Net list IP range')
    parser.add_argument('-net-list-name', required=True, dest='net_list_name', action='store', help='existing Net list name is expected')
    
    args = parser.parse_args()
    
    net_list_name = args.net_list_name
    
    
    if args.net_list_name != None :
        if len(net_list_name) == 0:
            print "string has zero length"
            sys.exit()
    else:
        print "string has zero length"
        sys.exit()


    if build_Net_lists(squid_cfg) != 0:
        sys.exit()

    get_Net_list_tag_IP(net_list_name)
    

main()

