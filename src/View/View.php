<?php

namespace PhpMvc\View;

class View
{
    public static function make($view, $params = [])
    {
        // load layout page main views/layouts/main.php
        $basecontent = self::getBaseContent();
        
        // load main content views/{$view}.php
        $viewContent = self::getViewContent($view, params: $params);

        // replace {{content}} with main content
        echo str_replace('{{content}}', $viewContent, $basecontent);
        
    }
    protected static function getBaseContent()
    {
        ob_start();
        include base_path() . "views/layouts/main.php";
        return ob_get_clean();
    }
    public static function makeError($error)
    {
        self::getViewContent($error, true);
    }
    protected static function getViewContent($view, $isError = false, $params = [])
    {
        $path = $isError ? view_path() . 'errors/' : view_path();
        
        if(str_contains($view, '.')){
            $views = explode('.', $view);
            foreach($views as $view) {
                if(is_dir($path . $view)){
                    $path .= $view . '/';
                }
            }
            $view = $path . end($views) . '.php';
        } else {
            $view = $path . $view . '.php';
        }

        foreach($params as $key => $value){
            $$key = $value;
        }
        
        if($isError){
            include $view;
        } else {
            ob_start();
            include $view;
            return ob_get_clean();
        }

        
    }
}