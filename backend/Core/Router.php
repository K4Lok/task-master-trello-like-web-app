<?php

Class Router {
    protected $routes = [];

    protected function add($method, $path, $callback) {
        $this->routes[] = compact('method', 'path', 'callback');
    }

    public function get($path, $callback) {
        $this->add('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->add('POST', $path, $callback);
    }

    public function patch($path, $callback) {
        $this->add('PATCH', $path, $callback);
    }

    public function delete($path, $callback) {
        $this->add('DELETE', $path, $callback);
    }

    public function run() {
        $path = parse_url($_SERVER['REQUEST_URI'])['path'];

        foreach ($this->routes as $route) {
            if ($route['path'] === $path) {
                return $route['callback']();
            }
        }

        $this->abort();
    }

    protected function abort($status = 404) {
        http_response_code($status);
        $response = [
            'message' => 'path not found.',
            'status' => $status,
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
        exit();
    }
}