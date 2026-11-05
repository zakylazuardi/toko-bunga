<?php
class Controller {
    protected function view(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . '/../' . $view . '.php';
    }
    protected function redirect(string $path): void {
        header('Location: ' . base_url($path));
        exit;
    }
}
