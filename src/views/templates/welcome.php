<?php

?>
<html>
<body>
<?php
echo "PageName: " . $pagename . "\n";
echo "Hello " . $username;
?>
<form action="/logout" method="get">
    <input type="submit" value="Hacer logout"
           name="Submit" id="frm1_submit"/>
</form>
</body>
</html>