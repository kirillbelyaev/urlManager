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
    parser = argparse.ArgumentParser(description='Tool to add/delete urls in the url list, add/delete association of network ACL to the url list')
    
    parser.add_argument('-add-url', dest='add_url', action='store', help='URL string')
    parser.add_argument('-del-url', dest='del_url', action='store', help='URL string')
    
    parser.add_argument('-url-list-name', required=True, dest='url_list_name', action='store', help='existing URL list name is expected')
    parser.add_argument('-lookup-url', dest='lookup_url', action='store', help='URL string to lookup')
    
    parser.add_argument('-add-url-block-access-acl', dest='add_url_block_access_acl', action='store', help='existing Net ACL name string')
    parser.add_argument('-del-url-block-access-acl', dest='del_url_block_access_acl', action='store', help='existing Net ACL name string')
    
    args = parser.parse_args()
    
    add_url = args.add_url
    del_url = args.del_url
    
    url_list_name = args.url_list_name
    lookup_url = args.lookup_url
    
    add_url_block_access_acl = args.add_url_block_access_acl
    del_url_block_access_acl = args.del_url_block_access_acl
    

    if build_URL_lists(squid_cfg) != 0:
        sys.exit()
    
    if build_Net_lists(squid_cfg) != 0:
        sys.exit()
    
    if args.add_url != None and args.del_url != None and args.url_list_name != None:
        print "please specify either add or delete URL option but not both"
        sys.exit()

    elif args.url_list_name != None and args.add_url_block_access_acl != None and args.del_url_block_access_acl != None:
        print "please specify either add or delete URL block access acl option but not both"
        sys.exit()

    elif args.url_list_name != None and args.add_url_block_access_acl != None and args.del_url_block_access_acl != None and args.lookup_url != None:
        print "please specify lookup URL alone - do not mix with other operations"
        sys.exit()

    elif args.url_list_name != None and args.add_url != None and args.del_url != None and args.lookup_url != None:
        print "please specify lookup URL alone - do not mix with other operations"
        sys.exit()


    elif args.add_url != None and args.url_list_name != None:
        if len(add_url) > 0 and len(url_list_name) > 0:
            if (add_URL(url_list_name, add_url) == 0):
                reload_squid()
        else:
            print "string has zero length"
            sys.exit()

    elif args.del_url != None and args.url_list_name != None:
        if len(del_url) > 0 and len(url_list_name) > 0:
            if (del_URL(url_list_name, del_url) == 0):
                reload_squid()
        else:
            print "string has zero length"
            sys.exit()

    elif args.add_url_block_access_acl != None and args.url_list_name != None:
        if len(add_url_block_access_acl) > 0 and len(url_list_name) > 0:
            if (add_URL_block_access_ACL(url_list_name, add_url_block_access_acl) == 0):
                reload_squid()
        else:
            print "string has zero length"
            sys.exit()

    elif args.del_url_block_access_acl != None and args.url_list_name != None:
        if len(del_url_block_access_acl) > 0 and len(url_list_name) > 0:
            if (del_URL_block_access_ACL(url_list_name, del_url_block_access_acl) == 0):
                reload_squid()

    elif args.lookup_url != None and args.url_list_name != None:
         if len(lookup_url) > 0 and len(url_list_name) > 0:
             lookup_URL(url_list_name, lookup_url)
         else:
            print "string has zero length"
            sys.exit()

    else:
        print "invalid arguments provided. exiting."
        sys.exit()


main()

