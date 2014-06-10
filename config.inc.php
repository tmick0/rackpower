<?php

// mysql connection parameters for racks database
set_conf('sql_host', 'localhost');
set_conf('sql_user', 'racks');
set_conf('sql_pass', '');
set_conf('sql_db',   'racks');

// mysql connection parameters for glpi database
set_conf('use_glpi',  true);
set_conf('glpi_host', 'localhost');
set_conf('glpi_user', 'glpiview');
set_conf('glpi_pass', '');
set_conf('glpi_db',   'glpi');
set_conf('glpi_url',  'https://localhost/glpi/front/computer.form.php?ID=');

// if using http auth, we don't need the internal access controls
set_conf('use_auth',  false);
set_conf('access_pw', '');
