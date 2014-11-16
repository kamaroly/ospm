ospm
====

Open Source Project Management Software application built using the PHP CodeIgniter framework.

** System Guide

Open Source Projects is a web based open source project management software application built using the PHP CodeIgniter framework.

The main features of the application are Project and Task Collaboration; Client Management and Invoicing; User Management and Timetracking. All these features are integrated together to increase the productivity and management of your team and projects.

The software was previously known as PHP Project Manager and Cyberience Project Manager, however, has been completely redesigned and rewritten to offer a powerful, user friendly, customisable web based application.

To install OpenSource Projects you will need a web-server and a database. These can be installed on your home machine if required.

OpenSource Projects is developed by Cyberience Internet Solutions who are based in Livingston, Scotland, United Kingdom

** Features

Create Tasks, Bugs and Milestones in a simplified ticketing system to ensure all data is in the one place
Generate automatic Invoices and Receipts in PDF Format
Link time expenditure with hourly rates to automate your invoicing process
Simplified Time tracking for monitoring how long you spend on each project
Allows an unlimited number of users, projects and clients
OpenSource Projects is actively used and developed.
OpenSource Projects is developed using modern coding standards.
Built using the PHP CodeIgniter framework.
OpenSource Projects is released as open source under the GPL.
Requirements

OpenSource Projects is built using PHP 5, and requires a database. It is built and tested on MySQL.

If you want to get rid of the "index.php" page, you'll also need "mod_rewrite" available to you (more on this below).

Note: We have tried to make OpenSource Projects as reliable and stable as possible, however you are using it at your own risk. You may lose data. As always, please take regular backups of your data.

Upgrading from a previous version of OpenSource Projects

OpenSource Projects comes with an update script.

Logout of OpenSource Projects, and make a backup of your files, and your database. I recommend you use tools provided by your host for this (for example PHPMyAdmin).
Make copies of:
cyberience_projects/application/config/config.php
cyberience_projects/application/config/database.php
cyberience_projects/application/config/projects.php
Replace all files with the new OpenSource Projects files, and restore config.php, database.php and projects.php from your backups from step 2.
Re-Run the installer by visiting http://www.yoursite.com/index.php/install
Any version specific notes will now appear on screen. Please read and follow.
Delete the file /cyberience_projects/application/controllers/install.php
Confirm everything is working - If OpenSource Projects was working previously, and now you get a "500" error, or a blank screen, double check the files have correct permissions with your host.
Installing OpenSource Projects

The basic installation process is as follows.

Download the OpenSource Projects files
Create a OpenSource Projects database
Modify the configuration files
Upload your files
Run the installation script
Login
Delete the installation and update scripts
Step by Step Configuration

Download the OpenSource Projects Files
The files needed to run OpenSource Projects are available from the site, http://www.cyberience.co.uk/projects. Simply download the zip file, and extract the contents to your computer. If you intend on uploading OpenSource Projects to your website, anywhere on your computer is fine, but if you intend on running OpenSource Projects of your current machine, you'll need to place it in a folder available to your webserver.

Create a OpenSource Projects Database
Create a database called "cyberience_projects". You can use a different name if you want. You need not put any data into it, the install script covers that.

Modify the Configuration Files
Because of the nature of different computers and environments, you need to tell OpenSource Projects a little about how it will be running. The following configuration files need to be updated.

Open /cyberience_projects/application/config/config.php with a text editor and set your $config['base_url']. If you are running this off a website it will be "http://www.yoursite.com" or "http://www.yoursite.com/cyberience_projects" or similar. This will then look like: $config['base_url'] = "http://www.yoursite.com/cyberience_projects";
Open /cyberience_projects/application/config/database.php and enter your database information.
Upload your files
Place the files on your webserver.

Run the installation script
If you visit your setup now, you'll be prompted to install OpenSource Projects. Just visit the URL you entered as your "base_url" above.

Login
Login using the username and password you created.

Delete the installation and update scripts
Delete the installation file - /cyberience_projects/application/controllers/install.php

Personalising OpenSource Projects
There are a few refinements you might want to make at this point.
Update your "Settings": Before you can really use the system, you'll want to enter your own information. This would include entering your organisation details and invoice information.

Update the information in the following file to customise the details to suit

/cyberience_projects/application/config/projects.php
Making OpenSource Projects more secure

There are a few things that I would recommend you change now.

In cyberience_projects/application/config/config.php you'll see a $config['encryption_key']. Change what it is equal to. Random gibberish is fine here.
In /config/config.php you'll find $config['sess_use_database'] = FALSE; around line 240. You may want to set it to "TRUE" (without quotes) after you've installed OpenSource Projects.
In cyberience_projects/application/config/config.php you'll find $config['sess_match_ip'] = FALSE; around line 243. You may want to set it to "TRUE" (without quotes) after you've installed the application. Some people are unable to login after this, as their ISP rotates their IP address, so this may not work for you, but for most people it does, and is a generally good idea.
You may want to turn off database debugging in cyberience_projects/application/config/database.php with $db['default'] ['db_debug'] = FALSE;
Delete the installation file as suggested above.
Changing Languages
Currently OpenSource Projects is only available in English. For future release we plan to have additional language files available. If you wish to assist with translating into other languages please contact me.

To change to other languages when available, simply open up cyberience_projects/config/config.php and set $config['language'] = "english"; to "french", "german", "dutch", "romanian", "spanish", "portuguese", "bulgarian", "swedish", or "italian"

Removing "index.php" from the address.
Due to the nature of CodeIgniter (the framework OpenSource Projects is built on), there is an "index.php" as part of the address for every page. For example, http://localhost/index.php/tasks. That annoys some people, so to get rid of it, you can use an htaccess command if you're on an Apache server with mod_rewrite enabled. Note: Not all webservers have this option. You might have to experiment, or contact your webhost for further advice here.

Open /.htaccess in a text editor. Modify it to match your server. It looks like this:

RewriteEngine on 
RewriteRule ^$ /index.php [L] 
RewriteCond $1 !^(index\.php|img|css|js|robots\.txt|favicon\.ico) 
RewriteRule ^(.*)$ /index.php/$1 [L]

Feedback
I hope you enjoy using the software and that it improves your productivity and organisation

If you have any feedback on the software, contact me at contact@cyberience.co.uk
