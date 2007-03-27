<?php
if(iCMSa=='X159E') {
mnew('Content','');
amenu('Categories','cats','C');
amenu('Articles','list','A');
amenu('Downloads','list&amp;co=2','F');
amenu('Links','list&amp;co=4','L');
amenu('News','list&amp;co=5','N');
amenu('Images','list&amp;co=3','G');
amenu('Infopages','pages','IP');
amenu('Polls','f3s','f3s');
mend();
mnew('Users','');
amenu('Manage...','users','U');
amenu('Administrators','adms','AD');
amenu('User groups','groups','UG');
amenu('Events log','log','LOG');
amenu('Mass merge','ml','MM');
mend();
mnew('Website','');
amenu('Site settings','conf','CFG');
amenu('Database backup','db','CDB');
amenu('Navigation menu','nav','NM');
amenu('Advertisements','bn','B');
amenu('Plug-ins','pi','PI');
mend();
ShowMP();
}
?>
