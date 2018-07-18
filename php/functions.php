<?php

function hashPassword($password, $salt)
{
    $hash_password = hash("SHA512", base64_encode(str_rot13(hash("SHA512", str_rot13($salt . $password)))));
    return $hash_password;
}

function phpAlert($msg)
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

