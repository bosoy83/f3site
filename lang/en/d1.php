<?php
cTable('BBCode manual',1);
echo '<tr>
 <td class="txt" style="line-height: 20px">
 <b>1. Bold text</b><br />[b]text[/b]<br />result: <b>text</b>
 <hr />
 <b>2. Italics</b><br />[i]text[/i]<br />result: <i>text</i>
 <hr />
 <b>3. Underlined text</b><br />[u]text[/u]<br />result: <u>text</u>
 <hr />
 <b>4. Indexes</b><br />superscript: [g]12345[/g] - <sup>12345</sup><br />subscripts: [d]text[/d] - <sub>text</sub>
 <hr />
 <b>5. Centered text</b><br />[center]tekst[/center]
 <hr />
 <b>6. Text size</b><br />[big]text[/big] - larger text (result: <big>text</big>)<br />[small]text[/small] - smaller text (result: <span class="txtm">text</span>)
 <hr />
 <b>7. Code / quote</b><br />[code]a code[/code] - code (equal chars width), e.g.<br /><div style="padding: 5px"><b>'.$lang['code'].':</b><div class="code"><code>a code</code></div></div><br />[quote]a quote[/quote] - quote, e.g.<div style="padding: 5px"><b>'.$lang['quote'].':</b><div class="quote">a quote</div></div>
 <hr />
 <b>8. Link</b><br />[url]http://www.onet.pl[/url] - result: <a href="http://www.onet.pl">http://www.onet.pl</a><br />[url=http://www.onet.pl]text[/url] - result: <a href="http://www.onet.pl">text</a>
 <hr />
 <b>9. E-mail address</b><br />[email]x@test.pl[/email] - result: <a href="mailto:x@test.pl">x@test.pl</a>
 <hr />
 <b>10. Colors</b><br />[color=green]text[/color] - <span style="color: green">text</span><br />[color=#3366CC]text[/color] - <span style="color: #3366CC">text</span>
 </td>
</tr>';
eTable();
?>
