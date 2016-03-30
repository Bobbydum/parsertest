<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.03.16
 * Time: 22:24
 */

namespace App\Classes;

use App\Import\Managers\Import;
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

        $users = [
            1 => 'Andrey',
            2 => 'Vladimir',
            3 => 'Ivan'
        ];
        $form = $app['form.factory']
            ->createBuilder('form')
            ->add('FileUpload', 'file')
            ->add('user', 'choice', array(
                'label' => 'Chose user',
                'choices' => $users,
            ))
            ->getForm();
        $request = $app['request'];
        $message = 'Upload a file';
        $user = 'Please chose owner of file';
        if ($request->isMethod('POST')) {

            $form->bind($request);

            if ($form->isValid()) {
                $files = $request->files->get($form->getName());
                /* Make sure that Upload Directory is properly configured and writable */
                $path = UPLOAD_PATH;

                $filename = $files['FileUpload']->getClientOriginalName();

                $files['FileUpload']->move($path, $filename);

                $data = $request->request->get('form');

                $user = "userID is: " . $data['user'] . ' ';

                $message = Import::checkFile($files['FileUpload']);
            }
        }

        $response = $app['twig']->render(
            'index.html.twig',
            array(
                'user' => $user,
                'message' => $message,
                'form' => $form->createView()
            )
        );

        return $response;
    }

}