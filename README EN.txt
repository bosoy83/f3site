Distribution and license
========================

F3Site 2013 - (C) 2005-2013 COMPMaster - compmaster.prv.pl

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.

F3Site uses the following components:
* Silk icons - http://www.famfamfam.com/lab/icons/silk
* Function icons - http://www.wefunction.com
* Google Code Prettify - http://google-code-prettify.googlecode.com
* Slimbox 2 - http://www.digitalia.be/software/slimbox2
* reCAPTCHA - http://recaptcha.net
* Microsoft Asirra - http://research.microsoft.com/asirra
* TinyMCE - http://tinymce.moxiecode.com


Requirements
============

* PHP 5.2 or newer with enabled PDO extension
* Database: MySQL 5.0.2+ or SQLite 3+
* PHP sessions
* mod_rewrite support if you want nice URLs


Installation
============

1. Load content of SYSTEM directory into server using FTP client or file manager. Delete useless languages.

2. Set privileges (CHMOD) to files:
* folders: CFG, CACHE, RSS - 777
* all folders in above catalogs - 777
* all files in above directories - 666
* folders: FILES, IMG - 777 (optional)
* folders: IMG/USER - 777 (required to let users upload photos)

3. Go to INSTALL directory in your web browser - e.g. http://your-site/install/
To finish the installation, database user must have full privileges.

4. If you see a blank page or 500 Internal Server Error, read the last section of this README file about .htaccess.

5. All tables with the same name prefix will be DROPPED from database!

6. After the installation DELETE INSTALL FOLDER!

7. Log in and customize the system in admin panel.


Nice URL
========

To use nice URLs your server must support mod_rewrite. Read help contents of your hosting company. If module is enabled but doesn't work, add RewriteBase command into .htaccess. Example:

RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.+) index.php?go=$1 [L,QSA]

Second option enables nice URLs without mod_rewrite. Server must support PATH_INFO.


Updates and help
================

Visit compmaster.prv.pl to get help and find updates. WARNING! Websites with illegal or sexual contents will NOT get support.

Current development version of F3Site with SVN repository, issue system and Wiki at Google Code service: f3site.googlecode.com


Extensions & skins
==================

You can add new features with extensions. Before you install any add-on, make sure whether it comes from trusted source because it may damage your site or steal confidential data.


Security
========

1. Create database copy at least once a month.

2. Set 444 privilege to cfg/db.php (read only) after installation.

3. KEEP YOUR PASSWORD STRICTLY PRIVATE! IF YOU GO ONLINE ON PUBLIC COMPUTERS, ALWAYS LOG OFF AFTER YOU HAVE FINISHED YOUR WORK! USE PRIVATE MODE IN A WEB BROWSER IF POSSIBLE.

4. Protect your computer with a good ANTIVIRUS SOFTWARE. A dangerous trojan horse is active in the Internet which steals passwords from FTP clients and injects hidden <iframe>s leading to malicious websites and nasty <script> tags into files on the server! If you notice any symptom, scan your computer, CHANGE your passwords to all FTP servers and upload again clean files.

5. If you use SQLite database, place .db file OUTSIDE the main directory.


Notices
=======

1. To stop masking your URL by domain, redirect to domain.php file.
2. Skins are compiled as PHP code into CACHE directory, where F3Site loads them from. 


Admin panel
===========

After you have logged in, a link to admin panel should appear. Only you and privileged users may see it. You can also access AP typing the path manually into ADMIN directory or adm.php.


Content management
==================

Only users who have Editor, Admin or Owner privilege may edit content. After you have logged in, a link "Manage content" should appear. To add new item in category view, just click + icon.


Changing PHP options
====================

F3Site package contains 3 .htaccess files to enable Nice URL feature and affect some PHP options. However, they may be unsupported depending on server's configuration.

To improve security, add the following options manually into .htaccess

php_flag session.use_only_cookies 1
php_flag session.use_trans_sid 0
php_flag register_globals 0
php_flag magic_quotes_gpc 0

If your server does NOT support php_flag command, an empty page or error 500 may be displayed. Some hostings can interpret SetEnv instruction. Check if your hosting's admin panel lets you change options.

If you cannot get rid of the problem, delete all .htaccess files from the main directory, ADMIN and by necessity - from CFG folder. But only limited Nice URL feature will work!