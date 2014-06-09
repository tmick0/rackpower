<?php

set_title("login");

if(!get_conf('use_auth')) exit('error: auth disabled');

if(isset($_GET['post'])){
    // check access password against config
	echo "logging in...";
    if($_POST['pass'] == get_conf('access_pw')){
	    echo "ok ";
        auth_user();
        echo "<script type='text/javascript'>window.opener.location.reload();window.close();</script>";
    }
    else{
        echo "invalid password... care to try again?";
    }
}
elseif(isset($_GET['logout'])){
    // logo ut
    deauth_user();
    echo "<script type='text/javascript'>window.opener.location.reload();window.close();</script>";
}

if(!is_user_authed() && !isset($_GET['logout'])){
    // show login form
    show_content("login.inc.html");
}
