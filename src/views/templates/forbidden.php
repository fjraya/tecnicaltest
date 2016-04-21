<?php

header('HTTP/1.0 403 Forbidden');
echo 'No tienes permisos para ver esta página!';

?>
<meta charset="utf-8" />
<form action="/logout" method="post">
    <input type="hidden" name="uri" value="<?php echo $uri?>" />
    <input type="submit" value="Cerrar sesión"
         name="Submit" id="frm1_submit" />
</form>