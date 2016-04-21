# tecnicaltest

Se requiere PHP 5.5.x

Para construir el proyecto, hay que ir al directorio raiz y ejecutar ./build.sh

    - Baja las dependencias con el composer
    - Crea las bases de datos, (test y prod)
    - Ejecuta test unitarios y de integración

Para lanzar el servidor PHP, hay que ir al directorio raiz y ejecutar: php -S localhost:80

Se ha implementado el content negotiation en la api REST, solo se podrá elegir uno. Los posibles son:
    - 'application/json'
    - 'application/xml'
    
    Si no se indica nada, el formato de salida será json.

Ejemplo de llamadas a la API:

Listado usuarios: curl -H "Accept:application/json" -u admin:password4 -X GET 'http://localhost:80/users'|python -mjson.tool 
Listado del user2: curl -H "Accept:application/json" -u user2:password2 -X GET 'http://localhost:80/users'|python -mjson.tool
Borrado de usuarios: curl -H "Accept:application/json" -u admin:password4 -X DELETE 'http://localhost:80/users/user3'|python -mjson.tool

Modificación de usuario: curl -H "Accept:application/json" -u admin:password4 -X PUT 'http://localhost:80/users/user1/PAGE_1,PAGE_2,PAGE_3/passwd'|python -mjson.tool

Creación de usuario: curl -u admin:password4 -v -X POST 'http://localhost:80/users' -d 'username=newUser&password=newPasswd&roles=PAGE_1,PAGE_3'|python -mjson.tool

El uso de "python -mjson.tool" sirve para hacer pretty printing del json de salida.

LIMITACIÓN: La modificación exige siempre que se ponga el password (se puede poner el mismo, o se puede cambiar). Es un parámetro obligatorio.

LIMITACIÓN: El admin siempre lista todos los usuarios.


