#!/bin/bash

## Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
## This file is part of a Reyesoft Project. This can not be copied and/or
## distributed without the express permission of Reyesoft

## run script and dump errors if fails
run_and_dump () {
    echo " ⌚  $1..."
    RET=$(($1) 2>&1)
  
    RC=$?
    if [ $RC -eq 0 ]; then
        echo " ✓  $1"
        return 0
    else
        echo " ✖  $1"
        echo "$RET"
        return 1
    fi
}


__launch_in_parallel () {
    while [ -n "$1" ]; do
        run_and_dump "$1" &
        #Pass $1 to some bash function or do whatever
        shift
    done
}

## run all scripts (separated with space) in parallel
parallel () {
    __launch_in_parallel "$@"
    wait
}

## run scripts (separated with space) in parallel and stop if anyone fails
parallel_and_stop () {
    __launch_in_parallel "$@"
    
    ## ignore INT and TERM while shutting down
    trap '' INT TERM     
    
    wait -n
    while [ $? -eq 0 ]; do
       wait -n #returns 127 on last one
    done
    
    RRR=$?
    if [ $RRR -eq 0 ] || [ $RRR -eq 127 ]; then
       exit 0
    else
       exec 2> /dev/null
       pkill -9 -P $$
       return $RRR
    fi
}


