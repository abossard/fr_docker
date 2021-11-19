<?php

require_once('config.php');

$fileExist = true;
set_error_handler("warning_handler", E_WARNING);
function warning_handler($errno, $errstr) { 
    global $fileExist;
    if($errstr == "file_get_contents(/var/upload/test.txt): failed to open stream: No such file or directory"){
        $fileExist = false;
    }
}

function checkMemcached()
{
    global $memcached_hostname;
    try {
        $mem_var = new Memcached();
        $mem_var->addServer($memcached_hostname, 11211);
        $mem_var->set("key", "value_saved");
        if ($mem_var->get("key") == "value_saved") {
            echo "<strong>Memcached</strong>: OK";
        }
        else {
            echo "<strong>Memcached</strong>: ERROR";
        }
    }
    catch (Throwable $th) {
        echo '<strong>Memcached</strong>: ' .  $th->getMessage();
    }
}

function checkMSSQL()
{
    global $database_servername, $database_port, $database_database, $database_username, $database_password;
    try {
        $database_servername = "$database_servername, $database_port";
        $connectionInfo = array( "Database"=>$database_database, "UID"=>$database_username, "PWD"=>$database_password);
        $conn = sqlsrv_connect( $database_servername, $connectionInfo);

        if( $conn ) {
            echo "<strong>MSSQL</strong>: OK";
        }else{
            echo "<strong>MSSQL</strong>: " . print_r( sqlsrv_errors(), true);
        }
    }
    catch (Throwable $th) {
        echo '<strong>MSSQL</strong>: ' .  $th->getMessage();
    }
}

function checkWriteFileInMappedVolume()
{
    global $fileExist;
    try {
        $file = '/var/upload/test.txt';
        $current = file_get_contents($file);
        if($current == ""){
            $current = "OK";
        }
        file_put_contents($file, $current);
        echo '<strong>Write file in a mapped volume</strong>: ' . $current;
        if(!$fileExist){
            echo ' (file created)';
        }
        else{
            echo ' (file already exists)';
        }
    }
    catch (Throwable $th) {
        echo '<strong>Write file in a mapped volume</strong>: ' .  $th->getMessage();
    }
}

echo '<h1>Check container environment</h1>';
echo '<strong>Current PHP version</strong>: ' . phpversion();
echo '<br>';
checkMemcached();
echo '<br>';
checkMSSQL();
echo '<br>';
checkWriteFileInMappedVolume();
