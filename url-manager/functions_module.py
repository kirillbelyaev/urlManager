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
import urllib

import ConfigParser

import argparse

from globals import *

config = ConfigParser.ConfigParser()
config.add_section("files")
config.read(CONFIG_FILE_PATH + CONFIG_FILE)

# if the dbase-files do not exist they are created automatically
squid_cfg = config.get("files","squid_cfg")
#static_cfg = config.get("files","static_cfg")
squid_reconfigure_command = config.get("commands","squid_reconfigure_command")
case_sensitive = (config.get("parameters","case_sensitive") == 'TRUE')


url_lists = []
url_tags = {} #dictionary

net_lists = []
net_tags = {} #dictionary

http_access_lists = []
http_access_tags = {} #dictionary

def print_conf_file():
    squid_file = open(squid_cfg, 'r')
    cfg = squid_file.readlines()
    
    for x in cfg[:]:
        print x


def build_URL_lists(file_name):
    squid_file = open(file_name, 'r')
    cfg = squid_file.readlines()
    
    for x in cfg[:]:
        if string.find(x, ' url_regex ') != -1:
            url_lists.append(x)

    for x in url_lists[:]:
        chunks =  string.split(x, " ")
        if len(chunks) != 4 and chunks[0] != "acl":
            print "build_URL_lists: incorrect acl definition"
            return -1

        url_tags[chunks[1]]=string.strip(chunks[3], '"\n') #assign key/value pairs and build the dictionary tag - filename association

    squid_file.close()
    return 0

def build_Net_lists(file_name):
    #print "file: " + file_name
    squid_file = open(file_name, 'r')
    cfg = squid_file.readlines()
    
    for x in cfg[:]:
        if string.find(x, ' src ') != -1:
            net_lists.append(x)

    for x in net_lists[:]:
        chunks = string.split(x, " ")
        #print split
        if len(chunks) != 4 and chunks[0] != "acl":
            print "build_Net_lists: incorrect acl definition"
            return -1

        net_tags[chunks[1]]=string.strip(chunks[3], '"\n') #assign key/value pairs and build the dictionary tag - filename association

    squid_file.close()
    return 0


def read_URL_file(key):
    if key in url_tags:
        file_str = url_tags[key]
        url_file = open(file_str, 'rw')
        urls = url_file.readlines()
        
        for x in urls[:]:
            print x
        
        return 0
    else:
        return -1


def del_URL(key, url_str):
    
    if validate_url(url_str) == -1:
        print "del_URL: invalid url string"
        return -1
    
    if retransform_url_ends(url_str) == -1:
        print "del_URL: invalid url string"
        return -1

    urls_stripped = []
    urls_final = []
    url_found = FALSE


    if key in url_tags:
        file_str = url_tags[key]
        url_file = open(file_str, 'r')
        urls = url_file.readlines()
        
        ts = urllib.unquote(url_str)
        ts = escape_special_url_characters(ts)
        #ts = retransform_url_ends(url_str)
        ts = retransform_url_ends(ts)
        
        for x in urls[:]: #put urls with all white spaces stripped into a separate list
            x = string.strip(x)
            urls_stripped.append(x)
        
        for x in urls_stripped[:]:
            if x == ts:
                #print "match!"
                url_found = TRUE
                urls_stripped.remove(x)

        if url_found == FALSE:
            print "del_URL: url does not exist in url list"
            url_file.close()
            return -1

        for x in urls_stripped[:]:
            y = x + "\n" #add \n for every record
            urls_final.append(y)


        url_file.close()
        url_file = open(file_str, 'w')
        url_file.writelines(urls_final)
        url_file.close()
        return 0
    else:
        print "del_URL: url list name does not exist"
        return -1


def add_URL(key, url_str):
    
    if validate_url(url_str) == -1:
        print "add_URL: invalid url string"
        return -1
    
    if transform_url(url_str) == -1:
        print "add_URL: invalid url string"
        return -1

    if retransform_url_ends(url_str) == -1:
        print "add_URL: invalid url string"
        return -1

    urls_stripped = []
    urls_final = []
    url_found = FALSE

    if key in url_tags:
        file_str = url_tags[key]
        url_file = open(file_str, 'r')
        urls = url_file.readlines()
        
        ts = transform_url(url_str)
        
        #ts = urllib.unquote(url_str)
        ts = urllib.unquote(ts)
        ts = escape_special_url_characters(ts)
        #ts = retransform_url_ends(url_str)
        ts = retransform_url_ends(ts)
        
        for x in urls[:]: #put urls with all white spaces stripped into a separate list
            x = string.strip(x)
            urls_stripped.append(x)

        for x in urls_stripped[:]:
            if x == ts:
                url_found = TRUE
                break   #url already exists
                

        if url_found == FALSE:
            urls_stripped.append(ts)

            for x in urls_stripped[:]:
                y = x + "\n" #add \n for every record
                urls_final.append(y)
        else:
            print "add_URL: url already exists in url list"
            url_file.close()
            return -1

        url_file.close()
        url_file = open(file_str, 'w')
        url_file.writelines(urls_final)
        url_file.close()
        return 0
    else:
        print "add_URL: url list name does not exist"
        return -1


def lookup_URL(key, url_str):
    
    if validate_url(url_str) == -1:
        print "del_URL: invalid url string"
        return -1
    
    if retransform_url_ends(url_str) == -1:
        print "del_URL: invalid url string"
        return -1

    urls_stripped = []
    urls_final = []


    if key in url_tags:
        file_str = url_tags[key]
        url_file = open(file_str, 'r')
        urls = url_file.readlines()
        
        ts = urllib.unquote(url_str)
        ts = escape_special_url_characters(ts)
        #ts = retransform_url_ends(url_str)
        ts = retransform_url_ends(ts)
        
        for x in urls[:]: #put urls with all white spaces stripped into a separate list
            x = string.strip(x)
            urls_stripped.append(x)

        for x in urls_stripped[:]:
            if x == ts:
                url_file.close()
                print "URL found"
                return 0
        
        url_file.close()
        print "URL not found"
        return -1
    else:
        print "del_URL: url list name does not exist"
        return -1


#remove special characters from both ends of the string
def transform_url_ends(url_str):
    if len(url_str) > 0:
        ts = url_str.strip()
        ts = ts.strip("\^\$")
        return ts
    else:
        return -1


#add / to the end of the url string if absent - and shorten it if # is encountered
def transform_url(url_str):
    if len(url_str) > 0:
        
        ts = url_str.strip()
        
        if ts.find("http://") == -1 and ts.find("https://") == -1:
            return -1
        
        index = ts.find("//");
        
        if ts.find("#", index+2) != -1:
            tmp = ts.partition("#")
            ts = tmp[0]
        
        
        if ts.find("/", index+2) == -1: #if no / besides :// is present - add it to the end of the string
            ts = ts + "/"

        return ts

    else:
        return -1


##remove special characters
#def transform_url_ends(url_str):
#    if len(url_str) > 0:
#        ts = string.strip(url_str, "\^\$")
#        return ts
#    else:
#        return -1

#add special characters to both ends of the string
def retransform_url_ends(url_str):
    if len(url_str) > 0:
        start = "^"
        end = "$"
        
        if str.startswith(url_str, "^") and str.endswith(url_str, "$"):
            return -1
        
        ts = start + url_str + end
        return ts
    else:
        return -1

#escape special characters
def escape_special_url_characters(url_str):
    if len(url_str) > 0:
        ts = url_str.replace("?", "\?")
        ts = ts.replace("&", "\&")
        ts = ts.replace("$", "\$")
        #ts = ts.replace("%", "\%")
        return ts
    else:
        return -1

#escape special characters
def unescape_special_url_characters(url_str):
    if len(url_str) > 0:
        ts = url_str.replace("\?", "?")
        ts = ts.replace("\&", "&")
        ts = ts.replace("\$", "$")
        #ts = ts.replace("\%", "%")
        return ts
    else:
        return -1


def validate_url(url_str):
    if len(url_str) > 0:
        if url_str.startswith("http://") and url_str.count("http://") == 1 and url_str.count("https://") == 0 and url_str.count(":") == 1 and url_str.count("://") == 1 and url_str.count("//") == 1:
            #or url_str.startswith("https://"):
            return 0
        elif url_str.startswith("https://") and url_str.count("https://") == 1 and url_str.count("http://") == 0 and url_str.count(":") == 1 and url_str.count("://") == 1 and url_str.count("//") == 1:
            #url_str.count("http://") == 1 or url_str.count("https://") == 1:
            return 0
        else:
            return -1
    else:
        return -1


def add_URL_block_access_ACL(url_list_str, net_acl_str):
    if len (url_list_str) == 0 and len (net_acl_str) == 0:
        return -1
            
    access_deny = "http_access deny"

    if url_list_str in url_tags and net_acl_str in net_tags:
        access_deny_w = access_deny + " " + url_list_str + " " + net_acl_str
        #print access_deny_w
        squid_file = open(squid_cfg, 'r')
        cfg = squid_file.readlines()
        squid_file.close()

        for x in cfg[:]:
            if access_deny_w == string.strip(x):
                print "add_URL_block_access_ACL: acl already exists in acl list"
                return -1
        
        cfg.append(access_deny_w + "\n")
        squid_file = open(squid_cfg, 'w')
        squid_file.writelines(cfg)
        squid_file.close()
        return 0
    else:
        print "add_URL_block_access_ACL: net acl name or url list name does not exist"
        return -1


def del_URL_block_access_ACL(url_list_str, net_acl_str):
    if len (url_list_str) == 0 and len (net_acl_str) == 0:
        return -1
    
    access_deny = "http_access deny"
    found = FALSE

    if url_list_str in url_tags and net_acl_str in net_tags:
        access_deny_w = access_deny + " " + url_list_str + " " + net_acl_str
        #print access_deny_w
        squid_file = open(squid_cfg, 'r')
        cfg = squid_file.readlines()
        squid_file.close()
        
        for x in cfg[:]:
            if access_deny_w == string.strip(x):
                found = TRUE
                cfg.remove(x)
                break

        if found:
            squid_file = open(squid_cfg, 'w')
            squid_file.writelines(cfg)
            squid_file.close()
            return 0
        else:
            print "del_URL_block_access_ACL: acl does not exist in acl list"
            return -1
    else:
        print "del_URL_block_access_ACL: net acl name or url list name does not exist"
        return -1


def reload_squid():
    os.system(squid_reconfigure_command)
    return 0


def display_http_access_rules():
    
    ordered_list = []
    
    print "The following Rules exist to block access to URL lists:"
    for x in http_access_lists[:]:
        chunks = string.split(x, " ")
        #print "<--- : Network : " + chunks[3].strip()  + " : is denied access for web resources in URL list : " + chunks[2].strip() + " : --->"
        y = chunks[3].strip() + " " + chunks[2]
        ordered_list.append(y)

    ordered_list.sort()
    
    for x in ordered_list[:]:
        chunks = string.split(x, " ")
        print "<--- : Network : " + chunks[0].strip()  + " : is denied access for web resources in URL list : " + chunks[1].strip() + " : --->"


def build_http_access_lists(file_name):
    #print "file: " + file_name
    squid_file = open(file_name, 'r')
    cfg = squid_file.readlines()
    
    for x in cfg[:]:
        if string.find(x, ' deny ') != -1:
            http_access_lists.append(x)

    for x in http_access_lists[:]:
        chunks = string.split(x, " ")
        #print split
        if len(chunks) != 4 and chunks[0] != "http_access":
            print "build_http_access_lists: incorrect acl definition"
            return -1

    #http_access_tags[chunks[2]]=string.strip(chunks[3], '"\n') #assign key/value pairs and build the dictionary tag - filename association

    squid_file.close()
    return 0


def display_URL_list_file(key):
    if key in url_tags:
        file_str = url_tags[key]
        url_file = open(file_str, 'r')
        urls = url_file.readlines()
        i = 0
        
        print "total number of URL lines is: " + str(len(urls))
        
        for x in urls[:]:
            i=i+1
            #print str(i) + ": " + transform_url_ends(x)
            x = transform_url_ends(x)
            print str(i) + ": " + unescape_special_url_characters(x)
        
        url_file.close()
        return 0
    else:
        print "no such URL list"
        return -1

def get_Net_list_tag_IP(net_tag):
    tags = net_tags.keys()
        
    if net_tags.has_key(net_tag):
        print "Net list "+ net_tag + " has IP range: " + net_tags[net_tag]
    else:
        print "net list does not exist."


def write_Net_lists_tags():
    file_str = "net_lists_tags.txt"
    net_tags_file = open(file_str, 'w')
        
    tags = net_tags.keys()
    #tags = tags.sort()
        
    for x in tags[:]:
        net_tags_file.writelines(x)
        net_tags_file.writelines("\n")

    net_tags_file.close()
    return 0


def write_URL_lists_tags():
    file_str = "url_lists_tags.txt"
    url_tags_file = open(file_str, 'w')
        
    tags = url_tags.keys()
    #tags = tags.sort()
        
    for x in tags[:]:
        url_tags_file.writelines(x)
        url_tags_file.writelines("\n")

    url_tags_file.close()
    return 0



###################
#TESTS
###################

def do_tests():

    build_URL_lists(squid_cfg)


    #read_URL_file('BlockedUrls')


    url = "^http://www.youtube.com/channels$"
    url0 = "^https://www.youtube.com/watch\?v=GDlm84gSRv4\&feature=BFa\&list=PLFBD52515F8739359$"
    url1 = "http://www.youtube.com/channels"
    url2 = "https://www.youtube.com/watch\?v=GDlm84gSRv4\&feature=BFa\&list=PLFBD52515F8739359"


    url3 = "http://http://abc"
    url4 = "https://https://abc"

    url5 = "https://http://abc"
    url6 = "http://https://abc"

    url7 = "https://www.youtube.com/watch?v=-Phqu9flf4E&index=2&list=PLFBD52515F8739359"

    print transform_url_ends(url)
    print transform_url_ends(url0)
    print retransform_url_ends(url1)
    print retransform_url_ends(url2)

    print del_URL('BlockedUrls', url1)

    print add_URL('BlockedUrls', url1)

    print del_URL('BlockedUrls', url2)

    print add_URL('BlockedUrls', url2)


    build_Net_lists(squid_cfg)

    print net_tags

    print validate_url(url)
    print validate_url(url0)

    print validate_url(url1)
    print validate_url(url2)


    print validate_url(url3)
    print validate_url(url4)


    print validate_url(url5)
    print validate_url(url6)


    print validate_url(url7)

#print transform_url("https://www.youtube.com/watch?v=GDlm84gSRv4&feature=BFa&list=PLFBD52515F8739359")

#print add_block_access_acl("BlockedUrls", "gw")
#print del_block_access_acl("BlockedUrls", "gw")

#print_conf_file()

#print read_URL_file('BlockedUrls')


#print read_URL_file('BlockedUrls-')

