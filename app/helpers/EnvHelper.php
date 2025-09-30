<?php
class EnvHelper
{
    /**
     * Cargar variables de entorno desde un archivo .env
     *
     * @param string $path Ruta al archivo .env
     * @throws Exception Si no existe el archivo
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception(".env file not found at: " . $path);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignorar comentarios y líneas vacías
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            // Separar nombre y valor
            if (strpos($line, '=') === false) {
                continue; // Línea mal formada, ignorar
            }

            list($name, $value) = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value);

            // Eliminar comillas simples o dobles si existen
            $value = trim($value, '"\''); 

            // Definir variable de entorno
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }

    /**
     * Obtener variable de entorno
     *
     * @param string $key Nombre de la variable
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value !== false) {
            return $value;
        }

        // Buscar en $_ENV o $_SERVER si no está en getenv
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return $default;
    }
}
