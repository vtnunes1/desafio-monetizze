<?php

namespace App;

class Router
{
    private $routes = [];

    /**
     * Registra uma nova rota na aplicação.
     *
     * @param string $method O método HTTP que a rota deve responder (GET, POST, PUT, DELETE, etc.).
     * @param string $path O caminho (URL) para o qual a rota será registrada.
     * @param callable $handler A função ou o manipulador que será executado quando a rota for acessada.
     * 
     * @return void Não retorna nada, apenas registra a rota.
     */
    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Processa a requisição HTTP e executa o manipulador da rota correspondente.
     *
     * @param string $method O método HTTP da requisição (GET, POST, PUT, DELETE, etc.).
     * @param string $path O caminho (URL) da requisição que será verificado contra as rotas registradas.
     * 
     * @return mixed Retorna o resultado do manipulador da rota se uma correspondência for encontrada, caso contrário, retorna um erro 404.          
     */
    public function handleRequest($method, $path)
    {
        $method = strtoupper($method);

        // Percorre todas as rotas registradas
        foreach ($this->routes as $route) {
            // Verifica se o método corresponde
            if ($route['method'] === $method) {

                // Verifica se o caminho da rota contém parâmetros dinâmicos
                $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $route['path']); // Substitui {id_tripulante} por (\d+) para capturar números
                $pattern = '#^' . $pattern . '$#'; // Cria uma expressão regular completa

                // Verifica se o caminho da URL corresponde ao padrão
                if (preg_match($pattern, $path, $matches)) {

                    array_shift($matches);

                    return call_user_func_array($route['handler'], $matches);
                }
            }
        }

        http_response_code(404);
        echo "404 - Not Found";
    }
}
