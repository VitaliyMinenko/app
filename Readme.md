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

### Work with 
