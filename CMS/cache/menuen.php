<?php function newnav($MenuID) { global $cfg,$lang,$db,$user; if($MenuID==1) {?><div class="mh">Menu</div><div class="menu"><ul><li><a href="index.php">Strona g��wna
</a></li><li><a href="?co=archive">Archiwum nowo�ci
</a></li><li><a href="?co=cats&amp;id=4">Polecane strony
</a></li><li><a href="?co=cats&amp;id=3">Galeria zdj��
</a></li><li><a href="?co=users">U�ytkownicy
</a></li><li><a href="?co=groups">Grupy u�ytkownik�w
</a></li></ul></div><div class="mh">Statystyki
</div><div class="menu"><?php include 'mod/panels/online.php'?></div><?php } else {?><div class="mh">Twoje konto
</div><div class="menu"><?php include 'mod/panels/user.php'?></div><div class="mh">Ankieta
</div><div class="menu"><?php include 'mod/panels/poll.php'?></div><div class="mh">Najnowsze</div><div class="menu">Coming soon...</div><?php } } ?>