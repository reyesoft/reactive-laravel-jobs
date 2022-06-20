#!/bin/bash

## Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
## This file is part of a Reyesoft Project. This can not be copied and/or
## distributed without the express permission of Reyesoft

## run script and dump errors if fails
implode () {
    local IFS="$1";
    shift;
    echo "$*"
}
