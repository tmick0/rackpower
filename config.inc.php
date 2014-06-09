<?php

// mysql connection parameters
set_conf('sql_host','localhost');
set_conf('sql_user','rackpower');
set_conf('sql_pass','rackpower');
set_conf('sql_db','rackpower');

// if using http auth, we don't need the internal access controls
set_conf('use_auth', false);
set_conf('access_pw', 'rackpower');
