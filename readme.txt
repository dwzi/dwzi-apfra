--------------------------------------------------------------------------------
                 (  ___  )(  ____ )(  ____ \(  ____ )(  ___  )
                 | (   ) || (    )|| (    \/| (    )|| (   ) |
                 | (___) || (____)|| (__    | (____)|| (___) |
                 |  ___  ||  _____)|  __)   |     __)|  ___  |
                 | (   ) || (      | (      | (\ (   | (   ) |
                 | )   ( || )      | )      | ) \ \__| )   ( |
                 |/     \||/       |/       |/   \__/|/     \|
--------------------------------------------------------------------------------
Welcome to ApFra (Application Framework)

The idea was creating a framework for a very fast development of web apps.
(instead of handling a lot of data with word or excel sheets ;-) )
--------------------------------------------------------------------------------

1. File Structure

	httpdocs (webserver public dir)
		|- css (stylesheets)
		|- fonts (glyphicons)
	*	|- img (images)
		|- js (javascript files)
	logs (webserver statistics)
	private (werbserver private dir)
		|- _src (open source components incl. urls)
		|- apfra (application framework files)
			|- class (apfra classes)
				|- datafile (class for file handling, web explorer)
				|- datasql (class for mysql database handling, data explorer)
			|- config
				|- datasql (needed datasql configuration for apfra)
			|- lib (include files for apfra)
			|- mod (modules for apfra)
	*	|- config (configuration)
	*		|- datafile (class for file handling, web explorer)
	*		|- datasql (class for mysql database handling, data explorer)
	*		|- config.inc.php (apfra configuration)
	*	|- doc (file space for class datafile)
	*	|- mod (user modules)
		|- tplc (compiled smarty templates, temp directory)

	*) theses directories are for the user, dont touch the other one ;-)


2. Installation

	a) download
	b) extract
	c) move httpdocs to the public webserver directory
	d) move private to the private webserver directory (recommended)
	e) change the parameters in /private/config/config.inc.php
	- DEF_URL: define the url "http://apfra.dev/"
	- DEF_PATH: define the absolute path to the public webserver directory "/Users/thomas/Sites/apfra.dev/httpdocs/"
	- DEF_PATH_PRIVATE: define the absolute path to the private directory "/Users/thomas/Sites/apfra.dev/private/" (.htaccess recommended -> deny from all)
	- DEF_DB: define the mysql-database "apfra"
	- DEF_DB_HOST: define the mysql-host "localhost"
	- DEF_DB_USER: define the mysql-user "u_apfra"
	- DEF_DB_PASS: define the password for mysql-user "fFma3ngG!)fgsGsgJ"
	- DEF_SESSION: define an individual session name for the session handling "lgNGB51a"
	- DEF_DEBUG: if you want to enable debugging and error messages, set "true", otherwise "false" (recommended)
	- DEF_APPNAME: define your application name "apfra"
	f) create a mysql-database and user and import the sql.dump
	g) open browser and launch the url (http://apfra.dev), user/password = admin

	mysql: my.ini
	# Force case sensitive storage but case insensitive lookup
	lower_case_table_names=2


3. Database Information (for Table, Field and Module creation)

	table name: [0-9a-zA-Z]
	- ref1n_<table1>_<table2> means 1:n-reference from table <table1> to table <table2> (created automatically)

	a table needs folowing fields:
	- id int(11) unsigned not null autoincrement primary key
	- letztesupdate datetime
	- refid_benutzer int(11) unsigned

	field name: [0-9a-zA-Z]
	- refid_<table> means a reference id to table <table>
	- refid_<table>_info means "_info" is only for reference field description
	- refid_<table>: a table <table> and a field <table> must exists, otherwise update definition.xml with the correct field name
	- if a field name is defined like "ContractDateSigned" the description looks like "Contract Date Signed"

	modules:
	- the name of the modul has to be equal to the database table

4. class "datasql" Information

	look at a sample definition

	config-types:
	- checkbox
	- date
	- datetime
	- lookup
	- password
	- readonly
	- reference
	- reference_combobox
	- reference_select
	- reference1n
	- reference1nsub
	- text
	- image
	- file

	config:
	- links: #www# means webseite
	- links: #mail# means mailto:
	- links: http://test.def?id=#field# means http://test.def?id=1
	- datasql.config: tab-name = table-name
	- field: eg "concat(lastname,', ',firstname) as fullname" instead of a single field eg "lastname"
	- search: seperate fields with "/", "eg firstname/lastname"
	- orderby: seperate fields with "/", "eg firstname/lastname"
	- possible types: text, date, datetime, reference, reference1n, reference1nsub
	- tab name = module name for rights!
	- format= see moment.js, minlength=x, required=1


5. create a new table / module






todo:
- datasql_main.php insert _fileinfo column if image/file detected for -> image-view in table
- improve error handling! (error page for: datasql_edit-file/-image)
- improve aImport -> if table exists
- import date-format? (sql: load data infile '/Users/thomas/Documents/Protokoll.txt' into table Protokoll FIELDS TERMINATED BY ';'  OPTIONALLY enclosed by '"' ignore 1 LINES (id, refid_Kontakt, @var1, Protokoll) SET Datum = STR_TO_DATE(@var1,'%e.%c.%Y %H:%i:%s');)
- optimize apfra_db_desc "table.field" ( datasql_a_edit_fixed_fields.tpl.php + datasql_a_edit.php )
- finish datasql_a_edit_fixed_fields.tpl.php + datasql_a_edit.php (sql_edit_fields)
- update aimport/aexport -> changed table aModule!
- !legend = tmpftype=date; look datasql_main.tpl.php
- create smarty function for encoded urls! (like years before!)
- optimize index (every column) for ref1n_* tables automatically
- make backup from import -> importable! -> look at export files

ideas:
- secure import
- improve javascript library field-parameters eg reference_select (aFields; data-size=10)
- implement openoffice/libreoffice export like word/excel
- insert logDB in aSync
- aSync, Modal for delete, update, insert, create
- fieldtype file/image: <input type=file multiple maxlength, etc>
- fieldtype file/image: ajax, drag/drop (http://www.dropzonejs.com/)
- improve form error handling ($errors in php, tpl: password)
- add checkboxes to delete more than one entry in table view
- missing drop table ref1n_ table after delete in module aRef1n
- link #mail#, #www#, etc also in datasql-main (table-view, not only edit-view)
- add new datafield-types (email, text, date, currency, encrypted) and behind email=varchar, currency=double, ecrypted=salt with user password?, ec
- table locking, with userid
- improve aModule und aTable (auto sync)
- improve right/role concept (with fields)
- improve readonly with special tabs (person/adresse)
- language support
- replace data[field] to data[table.field] also add_default[] (review! why?)
- add report name to report_export and report_print like sql_export/sql_print
- implement error page (no insert, no module, etc), everywhere php die()
- extended search (like report generator)
