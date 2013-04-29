# siteinit #

The sitinit tool automates setting up an Apache web space and MySQL database for development with a default set of files in the http documents folder. 

## Configuration ##

1. Create a folder called .siteinit from your home.
2. Change to that folder and symlink Sites to your real Sites folder. The Sites folder is where you keep the sites where you're doing development. Mine is ~/Sites.
3. Create another symlinks to the folder which contains the virtual hosts files for each site. On my Mac this is /etc/apache2/other/.
4. Create a config.json file which has the MySQL access information and database setup script.  For example:
	<pre>
	{
		"mysql": {
			"host": "localhost",
			"userName": "root",
			"password": "",
			"initScript": [
				"create database {{user}}",
				"grant all on {{user}}.* to '{{user}}'@'localhost' identified by '{{password}}'"
			]
		}
	}
	</pre>
Note two things... The authentication information here has to be for an account which has rights to execute the initScript.  Also, {{user}} corresponds to the contents SI_USERNAME environment variable, and {{password}} is populated by SI_PASSWORD. The same convention is used elsewhere. You can copy a template for this from the siteinit distribution under tests/.siteinit/config.json.
5. Create a skeleton folder, and fill it with any files you want to pre-populate the sites created with this tool. The following tokens will be populated from their corresponding environment variables: {{host}} -> SI_HOSTNAME, {{title}} -> SI_TITLE, {{user}} -> SI_USERNAME and {{password}} -> SI_PASSWORD.  You may want to create a composer.json file here, or create a default .gitignore or other things like that.
6. Create a templete for additions to the hosts file to resolve the development host name to your development machine. The template is in ~/.siteinit/hosts.
7. (optional) Create a finalize.sh to complete the installation. This is run after everything else and has access to the SI_* environment variables. I use this to reload the Apache config and chown the files back to myself since siteinit has to run as root.
8. (optional) Run buildPhar.php to create siteinit.phar, turn on its executable bit, and copy it somewhere in your path.

For example, so set up siteinit on my desktop after cloning the repository into ~/prog I might do the following:

	Vics-MacBook-Pro:~ vic$ mkdir .siteinit
	Vics-MacBook-Pro:~ vic$ cd .siteinit
	Vics-MacBook-Pro:.siteinit vic$ ln -s ~/Sites
	Vics-MacBook-Pro:.siteinit vic$ ln -s /etc/apache2/other vhosts
	Vics-MacBook-Pro:.siteinit vic$ cp \
		~/prog/siteinit/tests/.siteinit/config.json \
		~/prog/siteinit/tests/.siteinit/finalize.sh \
		~/prog/siteinit/tests/.siteinit/hosts \
		.
	Vics-MacBook-Pro:.siteinit vic$ mkdir skeleton
	Vics-MacBook-Pro:.siteinit vic$ cd skeleton/
	Vics-MacBook-Pro:skeleton vic$ cat > .gitignore
	scratch.php
	*Ctrl-D*
	Vics-MacBook-Pro:skeleton vic$ cd ~/prog/siteinit/
	Vics-MacBook-Pro:siteinit vic$ php buildPhar.php
	Vics-MacBook-Pro:siteinit vic$ chmod +x siteinit.phar
	Vics-MacBook-Pro:siteinit vic$ mv siteinit.phar ~/bin/siteinit

*The trick with cat above might be new to you... You can redirect cat to a file, type its contents and then press ctrl-D when you're done.*

## What it Does ##

It performs the following tasks:

1. Collect virtual host information if not already present in environment variables.  Specifically it asks for the site's title, host name, user name and password.  These values can also come from the environment using SI_TITLE, SI_HOSTNAME, SI_USERNAME and SI_PASSWORD.
2. It copies a default set of files to the target folder. The default files come from ~/.siteinit/skeleton, and they are written to ~/.siteinit/Sites. The idea is that you symlink ~/.siteinit/Sites to wherever you really want to store your development sites.
3. It writes an apache config to ~/.siteinit/vhosts. Again, the idea is that this is a symlink to the folder which should contain your virtual host configs.
4. It appends hosts entries to /etc/hosts from the template in ~/.siteinit/hosts.
5. It sets up the MySQL database according to the login and init script found in ~/.siteinit/config.json.
6. It runs the ~/.siteinit/finalize.sh script if it finds one. Typically this reloads the Apache config and sets permissions on the new folder in Sites.

## Future Plans ##
* Support for other database types and PDO.
* Support for alternate skeleton folders for different project types.