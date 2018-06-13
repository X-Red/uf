# uf
Extrae el valor de la UF desde el sitio http://mindicador.cl y la guarda en una base de datos MySQL

Comienza desde la fecha actual y obtiene los datos que el web service entrega, si no hay datos se detiene.

El script se ejecuta con

php uf.php [fecha en formato dd-mm-yyy]

Ejemplo: php uf.php 20-05-2018

Se debe crear una base de datos en MySQL y crear la tabla uf utilizando el script que esta en la carpeta sql.
