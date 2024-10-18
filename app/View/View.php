<?php

namespace App\View;
use Core\DirectoryAlias;

class View
{
    /**
     * Returns view's content
     *
     * @param string $dir the directory where the view file is located
     * @param string $view the view file name
     * @return string
     * @throws Exception
     */
    public static function render(string $dir, string $view, array $vars = [
    ]): string
    {

        
        $file = DirectoryAlias::get('@view'). "/$dir/$view.php";
        if(file_exists($file)) {
            return self::renderFile($file, $vars);
        } else {
            throw new \Exception("'$file' not found.");
        }
    }

    /**
     * Render the view file with main layout
     */
    public static function view(string $dir, string $view, array $vars = []): string
    {
        $html = self::render('Layout', 'Main', array_merge_recursive([
            'content' => self::render($dir, $view, $vars)
        ], $vars));
        return $html;
    }

    protected static function renderFile($path, $vars = []): string
    {
        ob_start();
        extract($vars);
        include $path;
        return ob_get_clean();
    }
}