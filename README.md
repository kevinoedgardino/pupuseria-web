<p align="center"><img src="https://raw.githubusercontent.com/kevinoedgardino/pupuseria-web/main/public/img/logo-pupuseria-web.png" width="400"></p>

# PupuseriaWEB

Aplicacion web enfocada a los negocios de pupuserias. Creada con Laravel 9 y Vue 2.

## Instalación

Instala las dependencias de npm

```bash
  npm install
```
Instala las dependencias de composer

```bash
  composer install
```

## Configuración

Usa el archivo ` .env-example ` para crear el archivo `.env` y hacer la conexión con la base de datos.

### Ejecuta las migraciones

```bash
  php artisan migrate
```

### Genera la llave

```bash
  php artisan key:generate
```

### Compila los archivos js y css

```bash
  npm run dev || npm run watch
```

### Ejecuta el servidor y todo deberia funcionar

```bash
  php artisan serve
```

## License
Hecho por Kevin Chacón 2022.
[Laravel MIT license](https://opensource.org/licenses/MIT).