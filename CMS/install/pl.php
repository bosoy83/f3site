<?php exit; ?>
Instalator F3Site - polskiego systemu wortalowego
Witamy w kreatorze instalacji polskiego i szybkiego systemu wortalowego F3Site. Przed kontynuacj� zapoznaj si� z plikiem CZYTAJ PL.txt, kt�ry znajduje si� w pobranej przez Ciebie paczce ze skryptem.<br /><br />Instalator utworzy tabele w bazie danych SQL, wprowadzi do nich podstawowe rekordy i zapisze dane dost�powe do pliku <b>cfg/db.php</b>. <br /><br />F3Site teraz sprawdzi, czy serwer spe�nia wszystkie wymagania.
Wymaganie
Uwagi
Nie znaleziono
Wersja PHP (co najmniej 5.2)
Bezpiecze�stwo serwera
Opcja "register_globals" jest w��czona w konfiguracji PHP. Aby zwi�kszy� bezpiecze�stwo, wy��cz j�, o ile to mo�liwe.
Opcja "magic_quotes" jest w��czona. Dodatkowe uko�niki danych wej�ciowych b�d� czyszczone przy ka�dym uruchomieniu skryptu. Aby zwi�kszy� wydajno��, wy��cz j�, o ile to mo�liwe.
Brak sterownika MySQL lub SQLite
Zapis do plik�w i folder�w
Ustaw atrybut (CHMOD) 777 folderom <b>cfg</b> i <b>cache</b>, a tak�e 766 wszystkim plikom znajduj�cym si� w nich. Opcjonalnie: 777 dla katalog�w <b>files</b> i <b>img</b>, je�li chcesz do nich pliki z poziomu wortalu.
Nie mo�na kontynuowa� instalacji.
Dalej &raquo;

Je�li nie wiesz, co wpisa� w poni�sze pola, zajrzyj do FAQ lub dzia�u pomocy hostingu: 
Dane dost�powe do bazy danych
Typ bazy danych:
Adres serwera MySQL:
Nazwa u�ytkownika:
Has�o:
Nazwa bazy:
Prefix:
Jest to tekst dodawany do nazwy ka�dej tabeli, przydatny szczeg�lnie wtedy, gdy do dyspozycji masz tylko jedn� baz� danych.
Twoje dane, za pomoc� kt�rych b�dziesz logowa� si�
Tw�j login:
Twoje has�o:
Bez spacji, dozwolone:
Powt�rz has�o:
Pozosta�e dane (np. e-mail) mo�esz zmieni� po instalacji F3Site w&nbsp;edycji profilu. Po klikni�ciu <b>OK</b> rozpocznie si� instalacja. <b>UWAGA!</b> Je�li w bazie danych istniej� tabele z poprzednich instalacji F3Site, zostan� usuni�te.

Przebieg instalacji
Nie mo�na po��czy� si� z baz� danych. Sprawd�, czy ustawienia na poprzedniej stronie s� poprawne. B��d:
Tworzenie tabel...
��czenie z baz� danych...
Strona g��wna
U�ytkownicy
Administratorzy
Twoje konto
Ankieta
Opcje (?)
Statystyki
Archiwum nowo�ci
Polecane strony
Galeria zdj��
Grupy u�ytkownik�w
Zapis danych dost�powych...
Nie uda�o si� zapisa� danych do pliku CFG/DB.PHP. Utw�rz go r�cznie i umie�� w nim poni�szy kod.
B��d: Wpisane has�a dost�pu do twojego konta nie zgadzaj� si� lub s� puste. Wpisz has�o ponownie.
Instalacja zako�czona. W celu <b>bezpiecze�stwa</b> usu� katalog INSTALL.
Usu� poprzednie tabele
W przypadku SQLite - adres pliku bazy.
Wprowadzanie pierwszych danych...
Najnowsze