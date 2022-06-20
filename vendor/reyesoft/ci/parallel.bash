#!/bin/bash

## Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
## This file is part of a Reyesoft Project. This can not be copied and/or
## distributed without the express permission of Reyesoft

## usage
# bash parallel.sh [-s] ..."script 1"
# -s    Stop on first fails

## params
## https://stackoverflow.com/questions/16483119/an-example-of-how-to-use-getopts-in-bash?answertab=votes#tab-top
while getopts ":s" o; do
    case "${o}" in
        s)
            stop=1
            ;;
    esac
done
shift $((OPTIND-1))

## fix problem when parallel.bash runs inside a composer script
sleep .1 &

## includes
DIR=`dirname "$(readlink -f "$0")"`
source ${DIR}/lib/runners.bash

if [ "${stop}" ] ; then
    parallel_and_stop "$@"
    RC=$?

    ## fix when like composer script
    kill -TERM 0

    exit $RC
else
    echo nostop
    parallel "$@"
fi;



