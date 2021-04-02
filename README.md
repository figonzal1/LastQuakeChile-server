<h1 align="center"><a href="https://figonzal.cl/lastquakechile">LastQuakeChile</a></h1>

<p align="center">
  
  <a href="https://www.codefactor.io/repository/github/figonzal1/lastquakechile-server">
    <img src="https://www.codefactor.io/repository/github/figonzal1/lastquakechile-server/badge" alt="CodeFactor" />
  </a>
  
  <a href="https://img.shields.io/github/repo-size/figonzal1/lastquakechile-server">
    <img alt="GitHub size" src="https://img.shields.io/github/repo-size/figonzal1/lastquakechile-server">
  </a>
  
  <a href="https://img.shields.io/github/last-commit/figonzal1/lastquakechile-server?color=yellow">
    <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/figonzal1/lastquakechile-server?color=yellow">
  </a>
  
  <a href="https://img.shields.io/uptimerobot/status/m785915204-fb6a8da6a3d79113696f212a?label=website%20status">
    <img alt="Uptime Robot status" src="https://img.shields.io/uptimerobot/status/m785915204-fb6a8da6a3d79113696f212a?label=website%20status">
  
  <a href="https://securityheaders.com/?q=figonzal.cl&hide=on&followRedirects=on">
  <img alt="Security Headers" src="https://img.shields.io/security-headers?url=https%3A%2F%2Ffigonzal.cl">
  </a>

  <a href="https://img.shields.io/badge/HH-150.97%20[hr]-blueviolet" alt="Hours Spent">
     <img alt="Hours Spent" src="https://img.shields.io/badge/HH-150.97%20[hr]-blueviolet">
  </a>
  
</p>

| MasterBranch | DevBranch |
| -------------------- | ----------------- |
| [![Build Status](https://travis-ci.com/figonzal1/LastQuakeChile-server.svg?branch=master)](https://travis-ci.com/figonzal1/LastQuakeChile-server) | [![Build Status](https://travis-ci.com/figonzal1/LastQuakeChile-server.svg?branch=development)](https://travis-ci.com/figonzal1/LastQuakeChile-server) |

Servidor de sismos, encargado de consultar la página de [www.sismologia.cl](https://www.sismología.cl)

## Scripts funcionales
### Script Sismos
Encargado de recopilar sismos desde [www.sismologia.cl](https://www.sismología.cl)
#### PHP
```ssh
    $ php src/scripts/script_quake.php prod
```
#### PHP + docker
```ssh
    $ docker exec lqch_server php /var/www/src/scripts/script_quake.php prod 
```
### Script Reportes
Encargado de generar reportes mensuales de sismos.

#### PHP
```ssh
    $ php src/scripts/script_report.php prod
```
#### PHP + docker
```ssh
    $ docker exec lqch_server php /var/www/src/scripts/script_reports.php prod
```

## PhpUnit test
Ejecución phpunit mediante composer
```ssh
$ .\vendor\bin\phpunit --bootstrap .\vendor\autoload.php --testsuite unit
```


