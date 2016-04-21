<?php

header('HTTP/1.0 403 Forbidden');
?>
<meta charset="utf-8" />
<h2>No tienes permisos para ver esta página</h2>
<form action="/logout" method="post">
    <input type="hidden" name="uri" value="<?php echo $uri?>" />
    <input type="submit" value="Cerrar sesión"
         name="Submit" id="frm1_submit" />
</form>