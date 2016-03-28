<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.03.16
 * Time: 22:24
 */

namespace App\Classes;

use Silex\Application;
use Silex\Provider\{
    FormServiceProvider, TranslationServiceProvider, ValidatorServiceProvider
};
use Symfony\Component\HttpFoundation\{
    Request
};

class Upload
{
    public function index(Application $app, Request $request)
    {
        $app->register(new ValidatorServiceProvider());
        $app->register(new FormServiceProvider());
        $app->register(new TranslationServiceProvider(), array(
            'translator.messages' => array(),
        ));


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
                $path = UPLOAD_PATH;
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
    }

}