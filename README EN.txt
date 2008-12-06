Distribution and license
========================

Copyright 2008 COMPMaster
F3Site 2008 is a free software. You can redistribute and modify it under the terms of GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

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


Notices
=======

1. At intervals create SECURITY COPY OF DATABASE.
2. To stop masking your URL by domain, redirect it to domain.php file.
3. Skins are compiled as PHP code into CACHE directory, where F3Site loads them from. 
4. To increase SECURITY, set 444 privilege to cfg/db.php (read only) after installation.
5. You can get into admin panel by typing ADMIN directory manually in web browser. To login if you hide `Your account` block, go to login.php.