<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $apiBaseUrl = getenv('KANBAN_API_URL') ?: 'http://localhost/fs2026/kanba_bd/api';

        return view('kanban_spa', [
            'apiBaseUrl' => rtrim($apiBaseUrl, '/'),
        ]);
    }
}
