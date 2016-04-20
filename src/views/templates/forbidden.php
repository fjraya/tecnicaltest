<?php

header('HTTP/1.0 403 Forbidden');
echo 'No tienes permisos para ver esta página!';

?>
<form action="/logout" method="get">
    <input type="submit" value="Cerrar sesión"
         name="Submit" id="frm1_submit" />
</form>