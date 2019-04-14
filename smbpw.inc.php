<?php
ini_set("expect.loguser", "Off");
define("SMBPW_CHANGED", 0);
define("SMBPW_WRONGPW", 1);
define("SMBPW_ERROR",   2);
define("SMBPW_UNKNOWN", 3);

$smbpw_messages = array(
    SMBPW_CHANGED => "The password has been changed.",
    SMBPW_WRONGPW => "The actual password was not accepted.",
    SMBPW_ERROR => "An error occured while updating the password.",
    SMBPW_UNKNOWN => "Unknown result."
);

function smbpw_changepassword($user,$oldpw,$newpw) {
    $result = SMBPW_UNKNOWN;

    $stream = fopen("expect://smbpasswd -r localhost -U $user", "r");

    $cases = array(
        array(0 => "Old SMB password:", 1 => "OLDPASSWORD"),
        array(0 => "New SMB password:", 1 => "NEWPASSWORD"),
        array(0 => "Retype new SMB password:", 1 => "RETYPEPASSWORD"),
        array(0 => "Password changed for user $user", 1 => "CHANGEDPASSWORD"),
        array(0 => "NT_STATUS_LOGON_FAILURE", 1 => "WRONGPASSWORD")
    );

    while (TRUE) {
        switch (expect_expectl ($stream, $cases, $matches)) {
            case "OLDPASSWORD":
                fwrite ($stream, "$oldpw\n");
                break;
            case "NEWPASSWORD":
            case "RETYPEPASSWORD":
                fwrite ($stream, "$newpw\n");
                break;
            case "CHANGEDPASSWORD":
                $result = SMBPW_CHANGED;
                break 2;
            case "WRONGPASSWORD":
                $result = SMBPW_WRONGPW;
                break 2;
            default:
                $result = SMBPW_ERROR;
                break 2;
        }
    }
    fclose ($stream);
    return $result;
}

function smbpw_outputmessage($result) {
    return $smbpw_messages[$result];
}

function smbpw_checkconnection($user,$newpw) {
    $cases = array(array(0 => "password:", 1 => "PASSWORD"));
    $stream = fopen("expect://smbclient -L localhost -U $user", "r");
    switch (expect_expectl ($stream, $cases)) {
        case "PASSWORD":
            fwrite ($stream, "$newpw\n");
            break;
        default:
            break;
    }
    $output = "";
    while ($line = fgets($stream)) {
        $output .= $line;
    }
    fclose ($stream);
    return $output;
}
