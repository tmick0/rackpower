<?php

require("inc/conf.inc.php");
require("inc/sql.inc.php");
require("inc/auth.inc.php");
require("inc/content.inc.php");
require("inc/formula.inc.php");
require("config.inc.php");

session_start();
ob_start();

if(isset($_GET['p']))
    show_content("{$_GET['p']}.inc.php");
else
    show_content("main.inc.php");

$body_content = ob_get_contents();
ob_end_clean();

?>
<!DOCTYPE html>
<html>
<head>
    <title>rackpower/<?php echo get_title();?></title>
    <style type="text/css">
        body{
            margin:4px 4px 4px 4px;
            padding:0px;
        }
        div.racks_container{
            width:100%;
            overflow:auto;
            text-align:center;
            display:table-row;
        }
        div.rack{
            font-size:9px;
            display:table-cell;
            vertical-align:top;
            border:1px solid;
            margin:1px;
            cell-spacing:2px;
        }
        div.rack+div.rack{
            border-left:none;
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
        div.rack table{
            border-collapse:collapse;
        }
    </style>
    <script type="text/javascript">
        function windowpop(url) {
            var width = 650;
            var height = 450;
            var leftPosition, topPosition;
            leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
            topPosition = (window.screen.height / 2) - ((height / 2) + 50);
            var ref = window.open(url, "Window2", "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
            //ref.onunload = function(){ 
            //   ref.opener.location.reload();
            //}; 
            return false;
        }
    </script>
</head>
<body>
    <?php echo $body_content;?>
</body>
</html>
