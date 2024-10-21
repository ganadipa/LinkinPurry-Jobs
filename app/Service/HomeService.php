<?php

namespace App\Service;
use App\View\View;
use Core\DirectoryAlias;
use Core\Repositories;

class HomeService {
    public static function getHomeJobSeeker(): string {
        return self::render('HomeJobSeeker', [
            'css' => ['home/home.css'],
            'js' => ['home/home.js'],
            'title' => 'Home Page (Job Seeker)',
        ]);
    }

    public static function getHomeCompany(): string {
        return self::render('HomeCompany', [
            'css' => ['home/home.css'],
            'js' => ['home/home.js'],
            'title' => 'Home Page (Company)',
        ]);
    }

    private static function render(string $view, array $vars = []): string {
        return View::render('Layout', 'Main', array_merge_recursive($vars, 
            [
                'content' => View::render('Page', $view, $vars),
                'css' => ['company/shared.css'],
            ]
        ));
    }
}