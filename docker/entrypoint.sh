#!/bin/bash

filename=config.php
if [ ! -e $filename ]; then
	{
		echo "<?php"
		echo "\$database_username = '${DATABASE_USERNAME}';"
		echo "\$database_password = '${DATABASE_PASSWORD}';" 
		echo "\$database_servername = '${DATABASE_SERVERNAME}';"
        echo "\$database_port = '${DATABASE_PORT}';"
		echo "\$database_database = '${DATABASE_DATABASE}';"
        echo "\$memcached_hostname = '${MEMCACHED_HOSTNAME}';"
	} >> $filename
fi

exec "$@"