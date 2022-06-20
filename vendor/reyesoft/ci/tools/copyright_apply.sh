#!/bin/sh

## Copyright (C) 1997-2017 Reyesoft <info@reyesoft.com>.
## This file is part of a Reyesoft Project. This can not be copied and/or
## distributed without the express permission of Reyesoft

echo Aplicando Copyright...

TEXT=$(cat <<-END
/**
 * Copyright (C) 1997-2017 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Multinexo. Multinexo can not be copied and/or
 * distributed without the express permission of Reyesoft
 */
END
)

PHPSCRIPT=$(cat <<-END
\$data = stream_get_contents(fopen("php://stdin", "r"));
\$data = preg_replace('/<\?php\n/', '<?php'.PHP_EOL.'$TEXT'.PHP_EOL, \$data, 1);
echo \$data;
END
)

find ./app -name \*.php | (
    while read file; do
        if ! grep -q Copyright "$file"
        then
            echo \> $file
            cat "$file" | php -r "$PHPSCRIPT" > /tmp/copyrightreplace.tmp
            cat /tmp/copyrightreplace.tmp > $file
        fi
    done
)

echo DONE!
