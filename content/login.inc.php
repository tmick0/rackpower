<?php

set_title("login");

if(isset($_GET['post'])){
    // check login
    if($_POST['pass'] == getconf('access_pw')){
        auth_user();
        echo "<script type='text/javascript'>window.opener.location.reload();window.close();</script>";
    }
    else{
        echo "invalid password... care to try again?";
    }
}
elseif(isset($_GET['logout'])){
    deauth_user();
    echo "<script type='text/javascript'>window.opener.location.reload();window.close();</script>";
}

if(!is_user_authed() && !isset($_GET['logout'])){
    // show form
    show_content("login.inc.html");
}
