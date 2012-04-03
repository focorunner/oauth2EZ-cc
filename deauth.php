<?php
// Sets cookie expiry in the past, so browser destroys them and code.php then requires reauthentication
setcookie("ctctauth","",time() - 3600);

echo '<meta http-equiv="refresh" content="0;url=auth.php">';

?>