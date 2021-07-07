<?php

$opt = getopt(null, [
    "log_file:",
    "csv_file:",
    "output_timestamp_format:"
]);

/*
 * Checks
 */
if (!(isset($opt['log_file']))) {
    die("Option --log_file is mandatory");
}
if (!(file_exists($opt['log_file']))) {
    die("File {$opt['file']} not exists");
}
if (!(isset($opt['csv_file']))) {
    die("Option --csv_file is mandatory");
}

$inputTimestampFormat = null;
if (isset($opt['input_timestamp_format'])) {
    $inputTimestampFormat = $opt['input_timestamp_format'];
}
$outputTimestampFormat = null;
if (isset($opt['output_timestamp_format'])) {
    $outputTimestampFormat = $opt['output_timestamp_format'];
}

$handle = @fopen($opt['log_file'], "r");
if ($handle) {
    $csvArray = [];
    while (($buffer = fgets($handle)) !== false) {
        preg_match('/^.*[0-9]+:[0-9]+:[0-9]+ /U', $buffer, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches)) {
            echo "No timestamnp found !" . PHP_EOL;
            exit;
        }
        $timestamp = $matches[0][0];
        try {
            $timestampDateTime = new DateTime(trim($timestamp));
        } catch (Throwable $throwable) {
            die("Timestamp format unknown");
        }
        $host = (substr($buffer, strlen($timestamp), strpos($buffer, " ", strlen($timestamp)) - strlen($timestamp)));
        $module = (substr($buffer, strlen($timestamp) + strlen($host) + 1, strpos($buffer, ":", strlen($timestamp) + strlen($host) + 1) - (strlen($timestamp) + strlen($host) + 1)));
        $message = substr(strstr($buffer, ": "), 2);

        $csvArray[] = [
            $outputTimestampFormat ? $timestampDateTime->format($outputTimestampFormat) : trim($timestamp),
            $host,
            $module,
            $message
        ];
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail" . PHP_EOL;
    }
    fclose($handle);

    $fp = fopen($opt['csv_file'], 'w');

    foreach ($csvArray as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);
}