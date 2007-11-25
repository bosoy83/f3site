<?php function newnav($MenuID) { global $cfg,$lang,$db,$user; if($MenuID==1) { mnew('Menu',''); ?><ul><li><a href="index.php">Strona g³ówna
</a></li><li><a href="?co=arch">Archiwum nowo¶ci
</a></li><li><a href="?co=cats&amp;id=4">Polecane strony
</a></li><li><a href="?co=cats&amp;id=3">Galeria zdjêæ
</a></li><li><a href="?co=users">U¿ytkownicy
</a></li><li><a href="?co=groups">Grupy u¿ytkowników
</a></li></ul><?php mend(); mnew('Opcje (?)
',''); include 'mod/panels/msets.php'; mend();  } else { mnew('Twoje konto
',''); include 'mod/panels/user.php'; mend(); mnew('Ankieta
',''); echo 'Coming soon...'; mend(); mnew('Statystyki
',''); include 'mod/panels/online.php'; mend(); mnew('Najnowsze',''); echo 'Coming soon...'; mend();  } } ?>