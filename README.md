# Lastquakechile-server
[![CodeFactor](https://www.codefactor.io/repository/github/figonzal1/lastquakechile-server/badge)](https://www.codefactor.io/repository/github/figonzal1/lastquakechile-server) 
[![codecov](https://codecov.io/gh/figonzal1/LastQuakeChile-server/branch/development/graph/badge.svg)](https://codecov.io/gh/figonzal1/LastQuakeChile-server) 
[![Known Vulnerabilities](https://snyk.io//test/github/figonzal1/LastQuakeChile-server/badge.svg?targetFile=composer.lock)](https://snyk.io//test/github/figonzal1/LastQuakeChile-server?targetFile=composer.lock) 
[![Heroku](http://heroku-badge.herokuapp.com/?app=lastquakechile-server-prod)](https://dashboard.heroku.com/apps/lastquakechile-server-prod)

| MasterBranch | DevBranch |
|--------|------------|
|[![Build Status](https://travis-ci.com/figonzal1/LastQuakeChile-server.svg?branch=master)](https://travis-ci.com/figonzal1/LastQuakeChile-server)|[![Build Status](https://travis-ci.com/figonzal1/LastQuakeChile-server.svg?branch=development)](https://travis-ci.com/figonzal1/LastQuakeChile-server)|

Servidor de sismos, encargado de consultar la página de [www.sismologia.cl](https://www.sismología.cl)

## Scripts funcionales
### Script Sismos
Encargado de recopilar sismos desde [www.sismologia.cl](https://www.sismología.cl)
```ssh
    $ php src/configs/script_quake.php
```
### Script Reportes
Encargado de generar reporte de sismos mensuales.
```ssh
    $ php src/configs/script_report.php
```

## PhpUnit test
Ejecución phpunit mediante composer
```ssh
$ .\vendor\bin\phpunit --bootstrap .\vendor\autoload.php --testsuite unit
```


