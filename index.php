<?php

require("inc/conf.inc.php");
require("config.inc.php");
require("inc/sql.inc.php");
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
    <style type="text/css">
        body{
            margin:1px;
            padding:0px;
            font-size:14px;
        }
        .head{
        	display:inline-block;
        	vertical-align:middle;
        }
        h2.head{
        	font-size:20px;
        	padding-left:10px;
        	padding-right:10px;
        	margin-right:10px;
        	border-right:1px solid;
        	height:25px;
        }
        #header{
        	border-bottom:1px solid;
        	margin-bottom:6px;
        }
        ul.head{
        	margin:0px;
        	padding:0px;
        }
        ul.head li{
        	display:inline-block;
        	list-style:none;
        	height:25px;
        	margin-left:10px;
        	padding-right:15px;
        	text-align:center;
        	border-right:1px solid;
        }
        ul.head li *{
        	position:relative;
        	top:5px;
        }
        div.racks_container{
            text-align:center;
            margin:auto;
        }
        div.rack{
            font-size:10.5px;
            line-height:12px;
            display:inline-block;
            vertical-align:top;
            border:1px solid;
            margin:1px;
            page-break-inside:avoid;
        }
        div.rack>table{
            page-break-inside:avoid;
            border-collapse:collapse;
        }
        tr.rack_title td{
            text-align:center;
            border-bottom:1px solid;
            font-weight:bold;
            text-decoration:underline;
            padding-bottom:4px;
        }
        tr.rack_head td, tr.rack_row td{
            border:1px solid;
        }
        tr.rack_row td:first-child, tr.rack_head td:first-child, tr.rack_empty_row td:first-child{
            border:none;
            border-top:1px solid;
            border-right:1px solid;
        }
        tr.rack_row:last-child td{
            border-bottom:none;
        }
        tr>td:last-child{
            border-right:none;
        }
        td[title], span[title], s[title]{
            cursor:help;
        }
        div.rack table{
            border-collapse:collapse;
        }
    </style>
    <script type="text/javascript">
        function windowpop(url) {
            var width = 650;
            var height = 570;
            var leftPosition, topPosition;
            leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
            topPosition = (window.screen.height / 2) - ((height / 2) + 50);
            var ref = window.open(url, "Window2", "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
            return false;
        }

        function addCssClass ( selector, styles ){
        // method borrowed from http://taggedzi.com/articles/display/adding-css-to-a-page-using-javascript-without-jquery
        try {
            style = document.getElementById('custom_css_element');
            temp = style.innerHTML;
            style.innerHTML = temp + selector + "{ " + styles + "}\n";
        }
        catch (err)
        {
            style = document.createElement("style");
            style.id = 'custom_css_element'
            style.setAttribute('type', 'text/css');
            style.innerHTML = selector + "{ " + styles + " }\n";
            document.head.insertBefore(style,document.head.childNodes[0]);   
        }
    }
    </script>
</head>
<body>
    <?php
     // content of output buffer used here
     echo $body_content;
    ?>
</body>
</html>
