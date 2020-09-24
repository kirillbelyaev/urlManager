#!/usr/bin/python

import getopt, sys
import argparse


def main():

    #print "hello"
    
    try:
        opts, args = getopt.getopt(sys.argv, "ha:f:", ["help", "add_url="])
    except getopt.GetoptError:
    # print help information and exit:
    #print "usage help"
        usage()
        sys.exit(2)
    for opt, arg in opts:
        if opt in ("-h", "--help"):
        #print "usage help"
            usage()
            sys.exit()

    parser = argparse.ArgumentParser(description='Process some integers.')
    parser.add_argument('integers', metavar='N', type=int, nargs='+',
                    help='an integer for the accumulator')
    parser.add_argument('--sum', dest='accumulate', action='store_const',
                    const=sum, default=max,
                    help='sum the integers (default: find the max)')

    args = parser.parse_args()
    print args.accumulate(args.integers)


#if __name__ == "__main__":
#main()


def process_cmd_options():
    parser = argparse.ArgumentParser(description='Tool to add/delete urls in the url list, add/delete association of network ACL to the url list')
    #parser.add_argument('url', metavar='N', type=string, nargs='+',
    #                    help='an integer for the accumulator')
    parser.add_argument('-add-url', dest='add_url', action='store', help='URL string')
    parser.add_argument('-url-list-name', dest='url_list_name', action='store', help='existing URL list name is expected')
                        
    args = parser.parse_args()
    #print args.add_url(args.integers)
    print args.add_url
    print args.url_list_name



def usage():
    print "usage help"



process_cmd_options()

