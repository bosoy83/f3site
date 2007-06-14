<?php
if(iCMS!=1) { exit; }
global $lang;
echo '
<form action="http://www.google.com/search">
<div align="center" style="padding: 2px; line-height: 23px">
 <input name="as_q" style="width: 90%; margin: 1px; height: 15px" />
 <input type="hidden" name="as_sitesearch" value="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'" />
 <input type="submit" value="'.$lang['search'].'" />
 <br />
 <span class="txtm">Powered by <a href="http://www.google.com">Google</a>.</span>
</div>
</form>
';
?>
