<?php

namespace App\Core;

class View {
    /**
     * Renders the specified view file and returns the result.
     *
     * Before rendering, it includes the functions.php file located in the Views folder,
     * making its helper functions available only within the Views.
     *
     * @param string $view The view file name (without the extension).
     * @param array  $data The data to pass to the view.
     * @return string The rendered HTML output.
     */
    public static function render($view, $data = []) {
        $viewFile = VIEW_PATH . DIRECTORY_SEPARATOR . $view . '.php';
        
        if (file_exists($viewFile)) {
            // Extract data to variables for use in the view.
            extract($data);
            
            // Start output buffering.
            ob_start();
            
            // Include the functions.php file from the Views folder if it exists.
            $functionsFile = VIEW_PATH . DIRECTORY_SEPARATOR . 'functions.php';
            if (file_exists($functionsFile)) {
                include_once $functionsFile;
            }
            
            // Include the view file.
            include $viewFile;
            
            // Return the rendered content.
            return ob_get_clean();
        }
        
        return "View file not found.";
    }
}
