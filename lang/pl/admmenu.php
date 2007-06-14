<?php
if(iCMSa=='X159E') {
mnew('Zawarto¶æ','');
echo '<ul>';
amenu('Kategorie','cats','C');
amenu('Artyku³y','list','A');
amenu('Pobieranie','list&amp;co=2','F');
amenu('Odno¶niki','list&amp;co=4','L');
amenu('Nowo¶ci','list&amp;co=5','N');
amenu('Galerie','list&amp;co=3','G');
amenu('Wolne strony','pages','IP');
amenu('Sondy i ankiety','poll','f3s');
echo '</ul>';
mend();
mnew('U¿ytkownicy','');
echo '<ul>';
amenu('Zarz±dzaj...','users','U');
amenu('Administratorzy','adms','AD');
amenu('Grupy u¿ytkowników','groups','UG');
amenu('Indeks zdarzeñ','log','LOG');
amenu('Masowy list','ml','MM');
echo '</ul>';
mend();
mnew('Witryna','');
echo '<ul>';
amenu('Ustawienia','conf','CFG');
amenu('Kopia bazy danych','db','CDB');
amenu('Bloki menu','nav','NM');
amenu('Bannery reklamowe','bn','B');
amenu('Wtyczki','pi','PI');
echo '</ul>';
mend();
ShowMP();
}
?>
