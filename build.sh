#!/bin/bash

echo "Bajando composer...\n"
curl -sS https://getcomposer.org/installer | php
echo "[X] Composer bajado \n"
echo "Instalando dependencias...\n"
php composer.phar update
php composer.phar install
echo "[X] Dependencias instaladas\n"
echo "Creando base de datos...\n"
rm -fr db
mkdir  db
chmod a+w db

cd migration
php migration.php
echo "[X] Base de datos creada\n"
cd ..
chmod a+w db/project.sqlite
cd test
cd integration
rm -fr resources
mkdir  resources
chmod a+w resources
cd ..

cd migration
php migration.php
cd ..
chmod a+w integration/resources/test.sqlite
echo "Ejecutando tests...\n"
echo "Unitarios\n"
../vendor/bin/phpunit unit
echo "[X] Test unitarios pasados\n"
echo "Integración\n"
../vendor/bin/phpunit integration
echo "[X] Test integración pasados\n"


