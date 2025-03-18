<?

$MESSAGES['US']['DEFAULT'] = 'Default Message';
$MESSAGES['US']['MESSAGE_AGENDA'] = 'This part of the programe will guide you through a less or more complex setup procedure.<br />
<br />
This will include the following steps:
<ol>
<li><b>File Access Rights</b>: Check if the WebServer has write access to the files and directories which HEM uses</li>
<li><b>Application Paths</b>: Check if the paths are set correctly in your WebServers Configuration</li>
<li><b>SMTP Server</b>: Configuring the settings for HEM to send -e-mails</li>
<li><b>Database Connection</b>: The Configuration of a Database Connection (currently MySQL and SQLite are supported)</li>
<li><b>Security Measures and Completion</b>: The file access rights for the config file are changed and some security tipps are given</li>
</ol>

';
$MESSAGES['US']['MESSAGE_PATHS'] = 'A WebApplication has two paths:
<ul>
<li>The absolute Path on your WebServers Filesystem (e.g.: Windows: c:/www/path/to/hem, Unix: /www/path/to/hem )</li>
<li>The relative Path inside your WebServers Document Root (the part after the hostname in the url)</li>
</ul>

';
$MESSAGES['US']['MESSAGE_FILES_REPORTS'] = '<h2>Reports Directory</h2>The WebServer cannot write to the directory &lt;hem-dir&gt;/reports . HEM stores the generated HE Reports there.<br />You can try one of these possibilities:
<ul>
<li>If you have sufficient rights, you  can change the owner of the file &lt;hem-dir&gt;/reports to the user the Webserver runs with.</li>
<li>If you have ftp or shell access you can give write access to everyone (shell command line: chmod 0777 &lt;hem-dir&gt;/reports)</li>
</ul>
<br />';
$MESSAGES['US']['MESSAGE_FILES_IMAGE_DB'] = '<h2>Screenshot Directory</h2>The WebServer cannot write to the directory &lt;hem-dir&gt;/image_db . HEM stores the uploaded Screenshots there.<br />You can try one of these possibilities:
<ul>
<li>If you have sufficient rights, you  can change the owner of the file &lt;hem-dir&gt;/image_db to the user the Webserver runs with.</li>
<li>If you have ftp or shell access you can give write access to everyone (shell command line: chmod 0777 &lt;hem-dir&gt;/image_db)</li>
</ul>
<br />';
$MESSAGES['US']['MESSAGE_FILES_CONF'] = '<h2>Configuration File</h2>The config file &lt;hem-dir&gt;/conf/conf.host.php is not writeable by the WebServer.<br />You can try one of these possibilities:
<ul>
<li>If you have sufficient rights, you  can change the owner of the file &lt;hem-dir&gt;/conf/conf.host.php to the user the Webserver runs with.</li>
<li>If you have ftp or shell access you can give write access to everyone (shell command line: chmod 0666 &lt;hem-dir&gt;/conf/conf.host.php)</li>
</ul>
<br />';
$MESSAGES['US']['MESSAGE_FILES_OK'] = 'All files and directories HEM needs to write to are writeable.<br />';
$MESSAGES['US']['COULD_NOT_WRITE_CONFIG_FILE'] = 'Could not write the config file';
$MESSAGES['US']['NO_RECIPIENT_GIVEN'] = 'Please specify a Recipient';
$MESSAGES['US']['SERVER_ANSWER'] = 'SMTP Error';
$MESSAGES['US']['TEST_MAIL_SENT'] = 'Test e-mail sent';
$MESSAGES['US']['MESSAGE_MYSQL'] = 'Use a MySQL Database Server: You need to have a ready to Use MySQL Account at some Server';
$MESSAGES['US']['MESSAGE_SQLITE'] = 'Use SQLite as Database: You need to have PHP5, or a SQLite enabled Version of PHP4. <b>Not working yet! Please edit conf/conf.host.php manually!</b>';
$MESSAGES['US']['MESSAGE_SQLITE_CONNECTION_GOOD'] = 'Sqlite Connection OK';
$MESSAGES['US']['MESSAGE_SQLITE_CONNECTION_NOT_GOOD'] = 'Sqlite Connection OK';
$MESSAGES['US']['MESSAGE_NOSQLITE'] = 'Server has no SQLite Support!';
$MESSAGES['US']['MESSAGE_MYSQL_CONNECTION_GOOD'] = 'MySQL Connection OK';
$MESSAGES['US']['MESSAGE_MYSQL_CONNECTION_NOT_GOOD'] = 'MySQL Connection not OK';
$MESSAGES['US']['MESSAGE_SETUP_DEACTIVATION'] = 'You have to deactivate the setup part! You can do this by
<ul>
<li>activating a die() Function, by uncommenting the first line in "setup/run.Setup.php"</li>
<li>set the access mode of the "setup" directory to 0, this can be done by removing all rights in a ftp-client</li>
</ul>';


$MESSAGES['DE']['DEFAULT'] = 'Standard Nachricht';
$MESSAGES['DE']['MESSAGE_AGENDA'] = 'Dieser Teil des Programms f&uuml;hrt Sie durch einen mehr oder weniger komplexen Konfigurationsproze&szlig;<br>
<br />
Dies umfa&szlig;t folgende Schritte:
<ol>
<li><b>Datei Zugriffsrechte</b>: Einstellung der korrekten Zugriffsrechte auf Dateien und Verzeichnisse in denen HEM schreiben mu&szlig;</li>
<li><b>Pfade</b>: &uuml;berppr&uuml;fung ob die Pfade in der WebServer Konfiguration korrekt gesetzt sind</li>
<li><b>SMTP Server</b>: Einstellung der Parameter die HEM ben&ouml;tigt um e-Mails zu versenden.</li>
<li><b>Datenbank Verbindung</b>: Die Konfiguration der Parameter Ihrer Datenbank Verbinsung (derzeit werden MySQL und SQLite unterst&uuml;tzt)</li>
<li><b>Absicherung und Abschlu&szlig;</b>: Die Zugriffsrechte auf die Konfigurationsdatei werden angepa&szlig;t und einige Sicherheitshinweise werden besprochen</li>
</ol>
';
$MESSAGES['DE']['MESSAGE_PATHS'] = 'Eine Web Applikation ben&ouml;nigt 2 Pfadangaben:
<ul>
<li>Den absolute Pfad im Dateisystem Ihres WebServers (z.B.: Windows: c:/www/path/to/hem, Unix: /www/path/to/hem )</li>
<li>Den relativen Pfad innerhalb des Verzeichnisses in dem Ihr WebServer seine Dateien speichert (der Teil nach dem Hostnamen in der URL)</li>
</ul>

';
$MESSAGES['DE']['MESSAGE_FILES_REPORTS'] = '<h2>Verzechnis f&uuml;r generierte Berichte</h2>Der WebServer kann im Verzeichnis &lt;hem-dir&gt;/reports/ nicht schreiben. Dort werden die generierten HE Berichte gespeichert<br />Sie haben folgende M&ouml;glichkeiten:
<ul>
<li>Wenn Sie ausreichende Rechte daf&uuml;r haben, k&ouml;nnen Sie den Eigent&uuml;mer der Datei auf den Benutzer &auml;ndern unter dem der WebServer betrieben wird. </li>
<li>Sollten Sie nur ftp oder shell(ssh, telnet) Zugang haben k&ouml;nnen sie jederman Schreibzugriff gew&auml;hren indem (shell Kommandozeile: chmod 0777 &lt;hem-dir&gt;/reports)</li>
</ul>';
$MESSAGES['DE']['MESSAGE_FILES_IMAGE_DB'] = '<h2>Verzechnis f&uuml;r Screenshots</h2>Der WebServer kann im Verzeichnis &lt;hem-dir&gt;/image_db/ nicht schreiben. Dort werden die hochgeladenen Screenshots gespeichert<br />Sie haben folgende M&ouml;glichkeiten:
<ul>
<li>Wenn Sie ausreichende Rechte daf&uuml;r haben, k&ouml;nnen Sie den Eigent&uuml;mer der Datei auf den Benutzer &auml;ndern unter dem der WebServer betrieben wird. </li>
<li>Sollten Sie nur ftp oder shell(ssh, telnet) Zugang haben k&ouml;nnen sie jederman Schreibzugriff gew&auml;hren indem (shell Kommandozeile: chmod 0666 &lt;hem-dir&gt;/image_db)</li>
</ul>';
$MESSAGES['DE']['MESSAGE_FILES_CONF'] = '<h2>Konfigurationsdatei</h2>Die Konfigurationsdatei &lt;hem-dir&gt;/conf/conf.host.php kann vom Setup nicht ver&auml;ndert werden.<br />Sie haben folgende M&ouml;glichkeiten:
<ul>
<li>Wenn Sie ausreichende Rechte daf&uuml;r haben, k&ouml;nnen Sie den Eigent&uuml;mer der Datei auf den Benutzer &auml;ndern unter dem der WebServer betrieben wird. </li>
<li>Sollten Sie nur ftp oder shell(ssh, telnet) Zugang haben k&ouml;nnen sie jederman Schreibzugriff gew&auml;hren indem (shell Kommandozeile: chmod 0666 &lt;hem-dir&gt;/conf/conf.host.php)</li>
</ul>
';
$MESSAGES['DE']['MESSAGE_FILES_OK'] = 'Alle Dateien und Verzeichnisse in denen HEM schreiben mu&szlig; sind korrekt konfiguriert.<br />';
$MESSAGES['DE']['COULD_NOT_WRITE_CONFIG_FILE'] = 'Konnte die Konfigurationsdatei nicht speichern';
$MESSAGES['DE']['NO_RECIPIENT_GIVEN'] = 'Bitte geben Sie einen Empf&auml;nger an';
$MESSAGES['DE']['SERVER_ANSWER'] = 'SMTP Fehler';
$MESSAGES['DE']['TEST_MAIL_SENT'] = 'Test e-mail wurde gesendet';
$MESSAGES['DE']['MESSAGE_MYSQL'] = 'Einen MySQL Datenbank Server verwenden: Dazu ben&ouml;tigen Sie einen bereits fertig konfiguerierten Zugang zu einer MySQL Datenbank.';
$MESSAGES['DE']['MESSAGE_SQLITE'] = 'Einen SQLite als Datenbank verwenden: Dazu ben&ouml;tigen Sie PHP5, oder eine Version von PHP4 die das SQLite Modul geladen hat. ';
$MESSAGES['DE']['MESSAGE_SQLITE_CONNECTION_GOOD'] = 'Verbindung zu SQLite funktioniert';
$MESSAGES['DE']['MESSAGE_SQLITE_CONNECTION_NOT_GOOD'] = 'Verbindung zu SQLite funktioniert nicht';
$MESSAGES['DE']['MESSAGE_NOSQLITE'] = 'Server hat keine SQLite Unterst&uuml;tzung!';
$MESSAGES['DE']['MESSAGE_MYSQL_CONNECTION_GOOD'] = 'Verbindung zum MySQL Server hergestellt';
$MESSAGES['DE']['MESSAGE_MYSQL_CONNECTION_NOT_GOOD'] = 'Keine Verbindung zum MySQL Server hergestellt';
$MESSAGES['DE']['MESSAGE_SETUP_DEACTIVATION'] = 'Sie m&uuml;ssen die Setup Funktion deaktivieren! Folgenden M&ouml;glichkeiten bestehen: 
<ul>
<li>Akivieren Sie die die() Function in "setup/run.Setup.php"</li>
<li>Setzen Sie die Zugriffsrechte des Verzeichnisses "setup" auf 0, dies kann man auch mit einem FTP Client erreichen.</li>
</ul>';

?>