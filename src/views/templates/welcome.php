<?php

?>
<meta charset="utf-8" />
<html>
<body>
<h2>PageName: <?php echo $pagename?></h2>
<h2>Hello <?php echo $username?></h2>
<form action="/logout" method="post">
    <input type="hidden" name="uri" value="/<?php echo $pagename?>" />
    <input type="submit" value="Hacer logout"
           name="Submit" id="frm1_submit"/>
</form>
</body>
</html>