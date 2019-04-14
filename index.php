<?php
require(dirname(__FILE__) . '/smbpwd.inc.php');
define("SUBMITVALUE", "Update password");
$message = "";
$changed = FALSE;

if (!empty($_POST["cambiar"]) && $_POST["cambiar"] == SUBMITVALUE) {
    if(empty($_POST['username']) or strlen($_POST['username']) < 5) {
        $message = "El nombre de usuario no es correcto. " .  $_POST['usuario'];
    } elseif(empty($_POST['oldpassword']) or strlen($_POST['oldpassword']) < 2) {
        $message = "La contraseña antigua no puede ser tan corta. " . $_POST['oldpassword'];
    } elseif (empty($_POST['newpassword']) or strlen($_POST['newpassword']) < 2) {
        $message = "La contraseña nueva no puede ser tan corta. " . $_POST['newpassword'];
    } elseif (empty($_POST['repeatpassword']) or $_POST['repeatpassword'] !== $_POST['newpassword']) {
        $message = "La verificación de contraseña no coincide.  " . $_POST['repeatpassword'];
    }
    else {
        $user = $_POST['username'];
        $oldpw = $_POST['oldpassword'];
        $newpw = $_POST['newpassword'];

        $result = smbpw_changepassword($user, $oldpw, $newpw);
        $message = smbpw_outpumessage($result);
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Samba password change</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/smbpasswd.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/additional-methods.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
</head>
<body>

<div class="container">
<div class="row">
<div class="col">
<h1>Self Service SAMBA Password Change</h1>

<form action="<?php print($_SERVER['PHP_SELF']);?>" method="POST" name="mainform" id="mainform" class="needs-validation">
<?php
    if (!empty($message)) {
        if ($changed) {
            printf("<div class=\"form-group valid-feedback\">%s</div>",$message);
        }
        else {
            printf("<div class=\"form-group invalid-feedback\">%s</div>",$message);
        }
    }
?>
    <div class="form-group">
        <label for="username">SAMBA Username</label>
        <input  class="form-control col-lg-8" type="text" placeholder="username" name="username"  minlength="4" maxlength="20" required/>
    </div>
    <div class="form-group">
        <label for="oldpassword">Current password</label>
        <input  class="form-control col-lg-8" type="text" placeholder="********" name="oldpassword"  minlength="5" required/>
    </div>
    <div class="form-group">
        <label for="newpassword">New password</label>
        <input  class="needs-validation form-control col-lg-8" type="text" placeholder="********" name="newpassword" id="newpassword" minlength="8" required/>
        <meter max="4" id="password-strength-meter"></meter><br/>
        <div class="valid-feedback text-muted" id="password-strength-text">&nbsp;</div>
    </div>
    <div class="form-group">
        <label for="repeatpassword">Confirm password</label>
        <input  class="needs-validation form-control col-lg-8" type="text" placeholder="********" name="repeatpassword" id="repeatpassword"  minlength="8" required/>
    </div>
    <div class="form-group">
        <input id="submitbtn" class="btn btn-primary inicio" type="submit" name="cambiar" value="<?php print(SUBMITVALUE)?>" disabled="disabled" />
    </div>

    <?php if($result === SMBPW_CHANGED) {?>
    <div class="form-group">
        <h3>Connection test</h3><br/><pre><?php print(smbpw_checkconnection($user, $newpw));?></pre>
    </div>
    <?php } ?>
</form>
</div>
</div>
</div>
</div>
<script src="js/smbpasswd.js"></script>
</body>
</html>
