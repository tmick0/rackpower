<?php

function is_user_authed(){
    if(get_conf('use_auth') && (!isset($_SESSION['authed']) || $_SESSION['authed'] != true)){
        // using internal auth, and not logged in
        return false;
    }
    else{
        // if not using internal auth, assume user is already authed
        return true;
    }
}

function auth_user(){
    $_SESSION['authed'] = true;
}

function deauth_user(){
    $_SESSION['authed'] = false;
}
