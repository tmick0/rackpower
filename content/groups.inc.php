<?php

set_title("groups");

if(!is_user_authed()) exit("not logged in");

// check if there is postdata
if(isset($_GET['post'])){
	if(isset($_GET['id']) && $_POST['action'] == 'Modify'){
		// modify
		
		$id = intval($_GET['id']);
		$name = sql_esc($_POST['name']);
		$color = sql_esc($_POST['color']);
		
		sql_query("UPDATE `groups` SET `Name`='$name', `Color`='$color' WHERE `ID`='$id'");
		
		if(mysqli_errno(sql()))
			echo mysqli_error(sql());
		else
			echo "<script type='text/javascript'>window.opener.location.reload();window.location='./?p=groups';</script>";
	}
	elseif(isset($_GET['id']) && $_POST['action'] == 'Delete'){
	    // delete
        $id = intval($_GET['id']);

        // unset group from entities before deleting
        sql_query("UPDATE `entities` SET `Group`='0' WHERE `Group`='$id'");
        sql_query("DELETE FROM `groups` WHERE `ID`='$id'");
        
        if(mysqli_errno(sql()))
			echo mysqli_error(sql());
		else
			echo "<script type='text/javascript'>window.opener.location.reload();window.location='./?p=groups';</script>";
	}
	else{
		// add
		$name = sql_esc($_POST['name']);
		$color = sql_esc($_POST['color']);
		
		sql_query("INSERT INTO `groups` SET `Name`='$name', `Color`='$color'");
		
		if(mysqli_errno(sql()))
			echo mysqli_error(sql());
		else
			echo "<script type='text/javascript'>window.opener.location.reload();window.location='./?p=groups';</script>";
	}
}
else{

	$q = sql_query("SELECT * FROM `groups`");
	
	if(mysqli_num_rows($q)){
	    // print table of groups
		echo "<i>Current groups:</i>";
		echo "<table border='1'>";
		echo "<tr><td>Name</td><td>Color</td><td>&nbsp;</td></tr>";
		while($r = mysqli_fetch_array($q)){
			echo "<tr><form action='./?p=groups&post&id={$r['ID']}' method='post'>";
			echo "<td><input name='name' value='{$r['Name']}'/></td>";
			echo "<td><input name='color' type='color' value='{$r['Color']}'/></td>";
			echo "<td><input type='submit' name='action' value='Modify'/>";
			echo "<input type='submit' name='action' value='Delete' onclick=\"return confirm('Are you sure you want to delete this?')\"/></td>";
			echo "</form></tr>";
		}
		echo "</table>";
	}
	else{
		echo "<i>No groups currently exist.</i>";
	}
	mysqli_free_result($q);
	
	echo "<hr/>";
	echo "<i>Add group:</i>";
	echo "<form action='./?p=groups&post' method='post'>";
	echo "<table border='1'><tr><td>Name</td><td><input name='name'/></td></tr>";
	echo "<tr><td>Color</td><td><input name='color' type='color'/></td></tr>";
	echo "<tr><td>&nbsp;</td><td><input type='submit' value='Add'/></td></tr>";
	echo "</form>";
}
