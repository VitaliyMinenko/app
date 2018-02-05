# Documentation for Yaml parser.
* Autor: Vitalii Minenko
* vers: 1.0.0
* Stworzony: 2018-02-02
### Config in .htaccess.

```apacheconfig
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```
###
* After downloading application use composer install for downloading all dependence's.

### Work with application
* Main page for downloading.
``
http://baseUrl.info/
``
* Put into form file for parse in yml forrmat.
* Click send. You got flash massage with answer from application which contains info about parsing.

### Work with api
* Make GET request to the url and get all info from db.

``
http://baseUrl.info/Api/getAll
``



