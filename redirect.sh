#!/bin/bash

#$1 = "internal" port
#$2 = IP
#$3 = port
 
fifoname = "revprox" + $1 #unique backpipe
nc -l $1 0<$fifoname | nc $2 $3 1>$fifoname #redirect doublepipe
