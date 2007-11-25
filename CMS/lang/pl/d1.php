<?php
OpenBox('Pomoc BBCode',1);
echo '<tr>
 <td class="txt" style="line-height: 20px">
 <b>1. Pogrubienie</b><br />[b]tekst[/b]<br />co da w rezultacie: <b>tekst</b>
 <hr />
 <b>2. Kursywa</b><br />[i]tekst[/i]<br />co da w rezultacie: <i>tekst</i>
 <hr />
 <b>3. Podkre¶lenie</b><br />[u]tekst[/u]<br />co da w rezultacie: <u>tekst</u>
 <hr />
 <b>4. Indeksy</b><br />górny: [g]12345[/g] - <sup>12345</sup><br />dolny: [d]tekst[/d] - <sub>tekst</sub>
 <hr />
 <b>5. Wycentrowany tekst</b><br />[center]text[/center]
 <hr />
 <b>6. Wielko¶æ tekstu</b><br />[big]tekst[/big] - wiêkszy tekst (rezultat: <big>tekst</big>)<br />[small]tekst[/small] - mniejszy tekst (rezultat: <span class="txtm">tekst</span>)
 <hr />
 <b>7. Kod / cytat</b><br />[code]jaki¶ kod[/code] - kod (jednakowa szeroko¶æ liter), np.<br /><div style="padding: 5px"><b>'.$lang['code'].':</b><div class="code"><code>jaki¶ kod</code></div></div><br />[quote]cytat[/quote] - cytat, np.<div style="padding: 5px"><b>'.$lang['quote'].':</b><div class="quote">cytat</div></div>
 <hr />
 <b>8. Odno¶nik</b><br />[url]http://www.onet.pl[/url] - da rezultat: <a href="http://www.onet.pl">http://www.onet.pl</a><br />[url=http://www.onet.pl]tekst[/url] - da rezultat: <a href="http://www.onet.pl">tekst</a>
 <hr />
 <b>9. Adres e-mail</b><br />[email]x@test.pl[/email] - da w rezultacie: <a href="mailto:x@test.pl">x@test.pl</a>
 <hr />
 <b>10. Kolory</b><br />[color=green]text[/color] - <span style="color: green">text</span><br />[color=#3366CC]text[/color] - <span style="color: #3366CC">text</span>
 </td>
</tr>';
CloseBox();
?>
