# Paquetes que hay que instalar para que la web funcione

## Una vez descargado el preoyecto desde GitHub, hay que entrar a la carpeta foodtruck y ejecutar *"composer install"* y luego *"npm install"*

## Crear base de datos en mysql

## Crear un archivo .env, ir a .env.example, copiar todo el contenido y meterlo dentro del .env

# nombre de la bd DB_DATABASE=foodtruck
# nombre del usuario gestor de la bd DB_USERNAME=root
# contraseña del usuario de la bd DB_PASSWORD=root
  
## Para arrancar el sitio web, dentro de la carpeta foodtruck, en el terminal de vscode ejecutar, ejecutar  *"php artisan serve"*; en otro terminal también dentro de la carpeta foodtruck ejecutar *"npm run dev"*, y en un tercer terminal dentro también de foodtruck ejecutar *"php artisan migrate:fresh --seed"*

# USUARIOS PARA PRUEBAS
## Este usuario es admin y permite saltarse el horario para hacer pedidos, ya que fuera del horario de 19:00 a 7:00 no se puede hacer pedidos

## Usuario Cliente/Admin:
### testing@foodtruck.com
### password

# Cuando se pague en la pasarela de pago pon como numero de tarjeta 4242 4242 4242 4242 para las pruebas