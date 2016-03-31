# RESTful Technical Test

## Prerequisites
* PHP >= 5.6 - To install php 5.6 on Mac: http://justinhileman.info/article/reinstalling-php-on-mac-os-x/
* Mod-rewrite enabled
* htaccess is redable : https://help.ubuntu.com/community/EnablingUseOfApacheHtaccessFiles
    make sure that the htaccess is in the root of you server htpdocs and points to the correct path for the index.php file.
* MySQL
    mysql username and password left as root, in production a new user would be created for mysql.
    Add the correct MySQL config to Library/Config/Config.php
* Composer - https://getcomposer.org/doc/00-intro.md -
    then run $ composer.phar install


## Importing the data:
### Option 1
import database file located at: Library/DB/cve.sql

### Option 2
Create a database cve
Edit phinx.yml with correct Database Settings.
to run migrations:
$ php vendor/bin/phinx migrate -e development
Download the allitems.csv from: https://cve.mitre.org/data/downloads/allitems.csv
 and place it into the directory 'cve_files', if the file has a different name make sure to update the config. currently only csv upload is supported.
not hit the url as a POST Request: {{url for this application}}/CveFiles
the data will then upload to the databse, it take less than 5 minutes.

# Running the code
| Method | Interface | Description | example |
| ------ | --------- | ----------- | --------|
| GET | /cve/:cveNumber  | Return a single CVE resource | /cve/CVE-2000-0001 |
| GET | /cve  | Return multiple CVE resources. It should support: limiting of results, result offsets, the year of the vulnerabilities publishing  | /cve?limit=2&offset=3&year=1999 OR  /cve/?limit=2&offset=3&year=1999 |

##Setting Headers
* X-LogLevel: INFO|DEBUG|WARN|ERR will set the debug level, this currently set to the highest level INFO
* ENVIRONMENT: dev if this is set to dev, then error display will be turned on.
* Accept: set to 'appliction/json' for json output or anything else for xml (as xml is the default, and if it can't find json it returns to default)

##Notes
Default get limit of 200 due to php running out of memory if all results are returned and then formatted
Reason for db driver: application can be slightly modified to allow new db drivers such as mongo.
