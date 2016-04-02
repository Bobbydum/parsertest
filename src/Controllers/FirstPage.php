<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 03.04.2016
 * Time: 0:08
 */


namespace App\Controllers;

use Silex\Application;

class FirstPage
{

    public function index(Application $app)
    {
        $response = $app['twig']->render(
            'first.html.twig',
            array(
                'message' => 'This is place for admin pages!'

            )
        );

        return $response;
    }

}