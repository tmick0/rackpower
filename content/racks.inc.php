<?php

set_title("racks");
if(!is_user_authed()) exit("not logged in");

if(isset($_GET['post'])){
	if(isset($_GET['del'])){
		$i = intval($_GET['del']);
		sql_query("DELETE FROM `racks` WHERE `RackId`='$i'");
	}
	elseif(isset($_GET['add'])){
		$i = intval($_GET['add']);
		sql_query("INSERT INTO `racks` SET `RackId`='$i'");
	}
	echo "<script type='text/javascript'>window.opener.location.reload();window.location='./?p=racks';</script>";
}
else{
	$q = sql_query("SELECT COUNT(*) FROM `racks` ORDER BY `RackId` ASC");
	$n = mysqli_fetch_row($q);
        $n = $n[0];
	mysqli_free_result($q);
	
	$q = sql_query("SELECT `RackId` FROM `racks` ORDER BY `RackId` DESC LIMIT 0,1");
	$m = mysqli_fetch_row($q);
        $m = $m[0];
	mysqli_free_result($q);
	
	echo "<i>There are currently $n racks.</i>.<table border='1'>";
	for($i = 0; $i <= $m + 1; $i++){
		$q = sql_query("SELECT COUNT(*) FROM `racks` WHERE `RackId` = '$i'");
		$n = mysqli_fetch_row($q);
		$n = $n[0];
		mysqli_free_result($q);
		echo "<tr>";
		echo "<td>";
		if($n) echo "Rack $i"; else echo "&mdash;";
		echo "</td>";
		echo "<td>";
		if($n) echo "<a href='./?p=racks&post&del=$i'>Delete</a>"; else echo "<a href='./?p=racks&post&add=$i'>Add</a>";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
}
