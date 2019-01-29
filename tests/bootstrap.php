<?php


require dirname(__DIR__, 1) . '/vendor/autoload.php';

$host = 'localhost';
$port = 8000;

// Starts the built-in web server
$command = sprintf(
    'php -S %s:%d %s >. 2>&1 & echo $!',
    $host,
    $port,
    dirname(__DIR__) . '/tests/WebServer/router.php'
);


sleep(5);
// Execute the command and store the process ID
$output = array(); 
exec($command, $output);
$pid = (int) $output[0];
 
echo sprintf(
    '%s - Web server started on %s:%d with PID %d', 
    date('r'),
    $host, 
    $port, 
    $pid
) . PHP_EOL;
 
// Kill the web server when the process ends
register_shutdown_function(function() use ($pid) {
    echo sprintf('%s - Killing WebServer process with ID %d', date('r'), $pid) . PHP_EOL;
    exec('kill ' . $pid);
});
