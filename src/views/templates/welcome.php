<?php

?>
<meta charset="utf-8" />
<html>
<body>
<?php
echo "PageName: " . $pagename . "\n";
echo "Hello " . $username;
?>
<form action="/logout" method="POST">
    <input type="hidden" name="uri" value="/<?php $uri?>" />
    <input type="submit" value="Hacer logout"
           name="Submit" id="frm1_submit"/>
</form>
</body>
</html>