# Lastquakechile-server

[![CodeFactor](https://www.codefactor.io/repository/github/figonzal1/lastquakechile-server/badge)](https://www.codefactor.io/repository/github/figonzal1/lastquakechile-server) 

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

### Script respaldo de datos
Encargado de respaldar desde base de datos de producción hacia Amazon DynamoDB [Amazon ]()

## PhpUnit test
Ejecución phpunit mediante composer
```ssh
$ .\vendor\bin\phpunit --bootstrap .\vendor\autoload.php --testsuite unit
```


