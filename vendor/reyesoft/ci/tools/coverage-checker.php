<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

$config = [
    'file' => 'clover.xml',
    'thresholds' => [
        'global' => [
        ]
    ]
];

// LOAD PARAMS FROM PHPUNIT.XML
$xml = json_decode(file_get_contents('composer.json')) or die("Error reading phpunit.xml");
//var_dump($xml->extra->coverage->thresholds);exit;
foreach ($xml->extra->coverage->thresholds ?? [] as $key => $threshold) {
    @$config['thresholds'][$key]['lines'] = (string) $threshold->lines;
    @$config['thresholds'][$key]['functions'] = (string) $threshold->functions;
}

// merge params
$config['file'] = $argv[1] ?? $xml->extra->coverage->file ?? $config['file'];
$config['file'] = getcwd() . '/' . $config['file'];
if (!file_exists($config['file'])) {
    throw new InvalidArgumentException('Invalid input file provided (' . $config['file'] . ')');
}

$config['thresholds']['global']['lines'] = $argv[2] ?? $config['thresholds']['global']['lines'];
if (!$config['thresholds']['global']['lines']) {
    throw new InvalidArgumentException('An integer checked percentage must be given as second parameter');
}

foreach ($config['thresholds'] as $filepattern => $values) {
    $config['thresholds'][$filepattern]['_lines'] =
    $config['thresholds'][$filepattern]['_c_lines'] =
    $config['thresholds'][$filepattern]['_functions'] =
    $config['thresholds'][$filepattern]['_c_functions'] = 0;
}

// READ CLOVER FILE
$xml = new SimpleXMLElement(file_get_contents($config['file']));
$files = $xml->xpath('//file');
foreach ($files as $file) {
    $filename = (string)$file->attributes()->name;

    $elements = (int) $file->metrics->attributes()->elements;
    $c_lines = (int) $file->metrics->attributes()->coveredelements;

    $methods = (int) $file->metrics->attributes()->methods;
    $c_functions = (int) $file->metrics->attributes()->coveredmethods;

    foreach ($config['thresholds'] as $filepattern => $values) {
        if (strpos($filename, getcwd().$filepattern) === 0 || strpos($filename, $filepattern) === 0) {
            $config['thresholds'][$filepattern]['_lines'] += $elements;
            $config['thresholds'][$filepattern]['_c_lines'] += $c_lines;
            $config['thresholds'][$filepattern]['_functions'] += $methods;
            $config['thresholds'][$filepattern]['_c_functions'] += $c_functions;
        }
    }

    $config['thresholds']['global']['_lines'] += $elements;
    $config['thresholds']['global']['_c_lines'] += $c_lines;
    $config['thresholds']['global']['_functions'] += $methods;
    $config['thresholds']['global']['_c_functions'] += $c_functions;
}

echo PHP_EOL .
    ' 👉  Check file://' . getcwd() . '/bootstrap/cache/reports/coverage/index.html for more information'
    . PHP_EOL . PHP_EOL;


$error = false;
function evaluateOrWarn(string $filepattern, string $element): void {
    global $error, $config;

    $element_value = intval($config['thresholds'][$filepattern]['_'.$element] ?? 1);
    $element_value = $element_value >= 1 ? $element_value : 1;
    $config['thresholds'][$filepattern]['_'.$element.'_percentage'] = intval(
            intval($config['thresholds'][$filepattern]['_c_'.$element] ?? 0)
            / $element_value
            * 10000) / 100;
    if (
        $config['thresholds'][$filepattern][$element] &&
        $config['thresholds'][$filepattern]['_'.$element.'_percentage'] < $config['thresholds'][$filepattern][$element]
    ) {
        echo 'FAIL: '.$filepattern.' '.$element.' coverage '.$config['thresholds'][$filepattern]['_'.$element.'_percentage']
            . '; ' .$config['thresholds'][$filepattern][$element].' required.'. PHP_EOL;
        $error = true;
    }

    if (
        $config['thresholds'][$filepattern][$element] &&
        (float)$config['thresholds'][$filepattern]['_'.$element.'_percentage'] > (float)$config['thresholds'][$filepattern][$element] * 1.05
    ) {
        echo 'WARN: '.$filepattern.' '.$element.' coverage '.$config['thresholds'][$filepattern]['_'.$element.'_percentage']
            . '; ' .$config['thresholds'][$filepattern][$element].' required. You can increase it.'. PHP_EOL;
    }
}

foreach ($config['thresholds'] as $filepattern => $values) {
    evaluateOrWarn($filepattern, 'lines');
    evaluateOrWarn($filepattern, 'functions');
}

$coverage = intval($config['thresholds']['global']['_c_lines'] / $config['thresholds']['global']['_lines'] * 10000) / 100;

if ($error || $coverage < $config['thresholds']['global']['lines']) {
    echo PHP_EOL
        . "\033[0;30m\033[43m"   // white on yellow
        . ' ⚠ ERROR: Global code coverage is ' . $coverage . '%, which is below the accepted ' . $config['thresholds']['global']['lines'] . '% '
        . "\033[0m"
        . PHP_EOL . PHP_EOL;
    exit(1);
}

echo "\033[0;32m" // green
    . ' ✓  Global code coverage is ' . $coverage . '% 👌'
    . "\033[0m"
    . PHP_EOL . PHP_EOL;
