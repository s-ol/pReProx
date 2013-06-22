#!/bin/bash

#$1 "internal" port
#$2 IP
#$3 port
#$4 lifetime (seconds)
 
./redirect.sh $1 $2 $3 & #run in background
pid=$! #process ID in $!
 
sleep $4
 
kill -9 $pid #kill it (-9 = extra hard)
