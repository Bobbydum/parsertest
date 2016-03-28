<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.03.16
 * Time: 22:24
 */

namespace App\Classes;

use Silex\Application;
use Symfony\Component\HttpFoundation\{
    Request, Response
};

class Upload
{
    public function index(Application $app)
    {
        $app->match('/', function (Request $request) use ($app) {
            $form = $app['form.factory']
                ->createBuilder('form')
                ->add('FileUpload', 'file')
                ->getForm();
            $request = $app['request'];
            $message = 'Upload a file';
            if ($request->isMethod('POST')) {

                $form->bind($request);

                if ($form->isValid()) {
                    $files = $request->files->get($form->getName());
                    /* Make sure that Upload Directory is properly configured and writable */
                    $path = __DIR__ . '/web/upload/';
                    $filename = $files['FileUpload']->getClientOriginalName();
                    $files['FileUpload']->move($path, $filename);
                    $message = 'File was successfully uploaded!';
                }
            }
            $response = $app['twig']->render(
                'index.html.twig',
                array(
                    'message' => $message,
                    'form' => $form->createView()
                )
            );

            return $response;

        }, 'GET|POST');
        $app->error(function (\Exception $e, $code) use ($app) {
            $response = null;

            if (!$app['debug']) {
                switch ($code) {
                    case 404:
                        $message = 'The requested page could not be found.';
                        break;
                    default:
                        $message = 'We are sorry, but something went terribly wrong.';
                }
                $response = new Response($message, $code);
            }

            return $response;
        });
    }

}