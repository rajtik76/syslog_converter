SYSLOG parser + converter
=========================
Parse SYSLOG to CSV and convert timestamp to a different timestamp if needed.

Usage
-----
php convert.php --log_file=syslog --output_timestamp_format="d.m.Y H:i:s" --csv_file=output.csv

--log_file - mandatory option with path to SYSLOG<br />
--csv_file - path to output CSV file<br />
--output_timestamp_format - [optional] SYSLOG timestamp format

Example
-------
Check included syslog + output.csv files
