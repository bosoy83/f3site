<?php exit; ?>
Instalator F3Site - polskiego systemu wortalowego
Witamy w kreatorze instalacji polskiego i szybkiego systemu wortalowego F3Site. Przed kontynuacj± zapoznaj siê z plikiem CZYTAJ PL.txt, który znajduje siê w pobranej przez Ciebie paczce ze skryptem.<br /><br />Instalator utworzy tabele w bazie danych SQL, wprowadzi do nich podstawowe rekordy i zapisze dane dostêpowe do pliku <b>cfg/db.php</b>. <br /><br />F3Site teraz sprawdzi, czy serwer spe³nia wszystkie wymagania.
Wymaganie
Uwagi
Nie znaleziono
Wersja PHP (co najmniej 5.2)
Bezpieczeñstwo serwera
Opcja "register_globals" jest w³±czona w konfiguracji PHP. Aby zwiêkszyæ bezpieczeñstwo, wy³±cz j±, o ile to mo¿liwe.
Opcja "magic_quotes" jest w³±czona. Dodatkowe uko¶niki danych wej¶ciowych bêd± czyszczone przy ka¿dym uruchomieniu skryptu. Aby zwiêkszyæ wydajno¶æ, wy³±cz j±, o ile to mo¿liwe.
Brak sterownika MySQL lub SQLite
Zapis do plików i folderów
Ustaw atrybut (CHMOD) 777 folderom <b>cfg</b> i <b>cache</b>, a tak¿e 766 wszystkim plikom znajduj±cym siê w nich. Opcjonalnie: 777 dla katalogów <b>files</b> i <b>img</b>, je¶li chcesz do nich pliki z poziomu wortalu.
Nie mo¿na kontynuowaæ instalacji.
Dalej &raquo;

Je¶li nie wiesz, co wpisaæ w poni¿sze pola, zajrzyj do FAQ lub dzia³u pomocy hostingu: 
Dane dostêpowe do bazy danych
Typ bazy danych:
Adres serwera MySQL:
Nazwa u¿ytkownika:
Has³o:
Nazwa bazy:
Prefix:
Jest to tekst dodawany do nazwy ka¿dej tabeli, przydatny szczególnie wtedy, gdy do dyspozycji masz tylko jedn± bazê danych.
Twoje dane, za pomoc± których bêdziesz logowa³ siê
Twój login:
Twoje has³o:
Bez spacji, dozwolone:
Powtórz has³o:
Pozosta³e dane (np. e-mail) mo¿esz zmieniæ po instalacji F3Site w&nbsp;edycji profilu. Po klikniêciu <b>OK</b> rozpocznie siê instalacja. <b>UWAGA!</b> Je¶li w bazie danych istniej± tabele z poprzednich instalacji F3Site, zostan± usuniête.

Przebieg instalacji
Nie mo¿na po³±czyæ siê z baz± danych. Sprawd¼, czy ustawienia na poprzedniej stronie s± poprawne. B³±d:
Tworzenie tabel...
£±czenie z baz± danych...
Strona g³ówna
U¿ytkownicy
Administratorzy
Twoje konto
Ankieta
Opcje (?)
Statystyki
Archiwum nowo¶ci
Polecane strony
Galeria zdjêæ
Grupy u¿ytkowników
Zapis danych dostêpowych...
Nie uda³o siê zapisaæ danych do pliku CFG/DB.PHP. Utwórz go rêcznie i umie¶æ w nim poni¿szy kod.
B³±d: Wpisane has³a dostêpu do twojego konta nie zgadzaj± siê lub s± puste. Wpisz has³o ponownie.
Instalacja zakoñczona. W celu <b>bezpieczeñstwa</b> usuñ katalog INSTALL.
Usuñ poprzednie tabele
W przypadku SQLite - adres pliku bazy.
Wprowadzanie pierwszych danych...
Najnowsze