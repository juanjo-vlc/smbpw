<?php
require(dirname(__FILE__) . '/smbpwd.inc.php');

$user = "john.doe";
$oldpw = 'badpassword';
$newpw = 'IuseAG00Dpa$$word';

if (isset($argv[1])) $user  = $argv[1];
if (isset($argv[2])) $oldpw = $argv[2];
if (isset($argv[3])) $newpw = $argv[3];

$result = smbpw_changepassword($user, $oldpw, $newpw);
print(smbpw_outputmessage($result) . "\n");
if ($result === SMBPW_CHANGED) print(smbpw_checkconnection($user, $newpw));

?>
