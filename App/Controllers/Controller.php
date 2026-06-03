<?php
namespace App\Controllers;

use App\Data;
use Exception;

class Controller
{
    static function render(string $page_title = "Main", string $path = "index", string $layout = "main", array $data = null)
    {
        try {
            if ($data != null) {
                foreach ($data as $key => $value) {
                    $$key = $value;
                }
            }
            $title = $page_title;
            $childView = "./Resources/views/" . $path . ".php";
            include("./Resources/views/layouts/$layout.php");

        } catch (Exception $e) {
            Data::Error($e);
        }
    }
}