Distribution and license
========================

Copyright 2009 COMPMaster
F3Site 2009 is a free software. You can redistribute and modify it under the terms of GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

F3Site uses the following components:
* Silk icons - http://www.famfamfam.com/lab/icons/silk


Requirements
============

* PHP 5.2 or newer with enabled PDO extension
* Database: MySQL 5.0.2+ or SQLite 3+
* PHP sessions support


Installation
============

1. Load content of SYSTEM directory into server using FTP client or file manager. You can delete useless languages and skins (except DEFAULT) before. Then set privileges (CHMOD) to files:
* folders: CFG, CACHE - 0777
* all folders in above catalogs - 0777
* all files in above directories - 0766
* folders: FILES, IMG - 0777 (optional)
* folders: IMG/USER - 0777 (required to let users upload photos)

2. Go to INSTALL directory in your web browser - e.g. http://your.site.pl/install/
To finish the installation, database user must have full privileges.

3. After the installation DELETE INSTALL FOLDER!

4. Log in and customize the system in admin panel.


Updates and help
================

Official F3Site website: compmaster.go.pl (vortal COMPMaster). You can get support at forum. WARNING! Websites with illegal or sexual contents will NOT get support.

Current development version of F3Site with SVN repository, issue system and Wiki you can find at Google Code service: f3site.googlecode.com


Extensions & skins
==================

You can add new features with extensions. Before you install any add-on, make sure whether it comes from trusted source because it may damage your site or steal confidential data.


Security
========

1. Create a COPY OF DATABASE at least once a month.
2. Set 444 privilege to cfg/db.php (read only) after installation.
3. KEEP YOUR PASSWORD STRICTLY PRIVATE! IF YOU GO ONLINE ON PUBLIC COMPUTERS, ALWAYS LOG OFF AFTER YOU HAVE FINISHED YOUR WORK!


Notices
=======

1. To stop masking your URL by domain, redirect to domain.php file.
3. Skins are compiled as PHP code into CACHE directory, where F3Site loads them from. 
5. 


Admin panel
===========

After you have logged in, a link to admin panel should appear. Only you and privileged users may see it. You can also access AP typing the path manually into ADMIN directory or adm.php.


Content management
==================

Only users who have Editor, Admin or Owner privilege may edit content. After you have logged in, a link "Manage content" should appear. To add new item in category view, select: Options -> Add item (only if displaying category hierarchy is enabled).


Known issues
============

If your website is NOT displayed or a server error occurs, modify or delete .htaccess file from main and CFG directory. Read your hosting documentation in order to find out more information. However, .htaccess file adds some important security options. Figure out whether they are enabled on the server by default. If not, maybe you may apply them in another way.