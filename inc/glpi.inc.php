<?php

// global sql connection handle (for glpi)
$GLOBALS['_GLPI_'] = null;

// returns sql connection handle, connecting if not already connected
function glpi(){
    if($GLOBALS['_GLPI_'] == null)
        $GLOBALS['_GLPI_'] = mysqli_connect(get_conf('glpi_host'), get_conf('glpi_user'), get_conf('glpi_pass'), get_conf('glpi_db'));

    if($GLOBALS['_GLPI_']->connect_errno) exit("glpi connection failed: <br/> {$GLOBALS['_GLPI_']->connect_error}");
    
    return $GLOBALS['_GLPI_'];
}

// wrapper for mysqli_query function
function glpi_query($q){
    return mysqli_query(glpi(), $q);
}

// wrapper for mysqli_real_escape_string function
function glpi_esc($s){
    return mysqli_real_escape_string(glpi(), $s);
}

// looks up glpi information of entity by rackpower id
function print_glpi_table($id){
    // look up glpi id from racks table
    $q = sql_query("SELECT `GlpiId` FROM `entities` WHERE `ID` = '$id'");
    $r = mysqli_fetch_row($q);
    $glpi_id = $r[0];
    mysqli_free_result($q);

    if($glpi_id != 0){
        // link to glpi page
        $glpi_url = get_conf('glpi_url') . $glpi_id;
    
        // look up model
        $q = glpi_query("SELECT `name` FROM `glpi_dropdown_model` WHERE `ID` = (SELECT `model` FROM `glpi_computers` WHERE `ID` = '$glpi_id')");
        echo mysqli_error(glpi());
        $r = mysqli_fetch_row($q);
        $model = $r[0];
        mysqli_free_result($q);
    
        // look up CPU
        $q = glpi_query(
            "SELECT t2.designation, t2.comment, t1.specificity FROM `glpi_computer_device` AS t1 ".
            "INNER JOIN `glpi_device_processor` AS t2 ON t1.FK_device = t2.ID ".
            "WHERE t1.FK_computers = '$glpi_id' AND t1.device_type = '2'"
          );
        $r = mysqli_fetch_row($q);
        $cpu = $r[0];
        
        if($r[2] != "")
            $freq= $r[2];
        else
            $freq = "";

        
        $m = array();
        if(preg_match("/^.*BENCHMARK\=([0-9]+).*$/", $r[1], $m)){ //find benchmark score in comment field
            $bench = $m[1];
        }
        else{
            $bench = "";
        }   
        mysqli_free_result($q);

        // look up RAM
        $q = glpi_query(
            "SELECT t1.specificity FROM `glpi_computer_device` AS t1 ".
            "WHERE t1.FK_computers = '$glpi_id' AND t1.device_type = '3'"
          );
        $r = mysqli_fetch_row($q);
        $ram = $r[0];
        mysqli_free_result($q);

        // look up MAC
        $q = glpi_query(
            "SELECT t1.specificity FROM `glpi_computer_device` AS t1 ".
            "WHERE t1.FK_computers = '$glpi_id' AND t1.device_type = '5'"
          );
        $r = mysqli_fetch_row($q);
        $mac = $r[0];
        mysqli_free_result($q);

        // print out table
        echo "<table class='provtable glpitable'>";
        echo "<tr><td style='width:60px;'>GLPI ID</td><td><a href='$glpi_url' target='_blank'>$glpi_id</a></td></tr>";
        echo "<tr><td style='width:60px;'>Model</td><td>$model</td></tr>";
        echo "<tr><td style='width:60px;'>CPU</td><td><table class='glpitable provtable'>";
        echo "<tr><td style='width:80px;'>Model</td><td>$cpu</td></tr>";
        echo "<tr><td style='width:80px;'>Frequency</td><td>$freq</td></tr>";
        echo "<tr><td style='width:80px;'>Benchmark</td><td>$bench</td></tr>";
        echo "</table></td></tr>";
        echo "<tr><td style='width:60px;'>RAM</td><td>$ram</td></tr>";
        echo "<tr><td style='width:60px;'>MAC</td><td>$mac</td></tr>";
        echo "</table>";
    }
    else{
        echo "Not linked to GLPI";
    }
}
