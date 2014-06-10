<?php

require("inc/common.inc.php");
require("inc/conf.inc.php");
require("config.inc.php");
require("inc/sql.inc.php");
require("inc/glpi.inc.php");
require("inc/auth.inc.php");
require("inc/content.inc.php");
require("inc/formula.inc.php");

// if we're using internal auth, we need to create a session
if(get_conf('use_auth')){
    session_start();
}

// use output buffering to store output of content scripts for later use
ob_start();


if(isset($_GET['p'])){
    //if a ?p= parameter is sent, go to the specified page
    show_content("{$_GET['p']}.inc.php");
}
else{
    // default to main.inc.php
    show_content("main.inc.php");
}

// get contents of output buffer and stop buffering
$body_content = ob_get_contents();
ob_end_clean();

?>
<!DOCTYPE html>
<html>
<head>
    <title>rackpower / <?php echo get_title();?></title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <script type="text/javascript" src="funcs.js"></script>
</head>
<body>
    <?php
     // content of output buffer used here
     echo $body_content;
    ?>
</body>
</html>
