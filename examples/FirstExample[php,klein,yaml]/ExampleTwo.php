<?php

namespace Hilfe;

use Klein\Klein;

/**
* App
*/
class App
{
    private $twig;
    private $klein;

    function __construct()
    {
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../view');
        $this->twig = new \Twig_Environment($loader, [ 'cache' => false ]);

        $this->klein = new Klein();
    }

    function __destruct()
    {
        $this->klein->dispatch();
    }

    public function run()
    {
        $fp = new FilePicker();

        $this->klein->respond('GET', '/hilfe/list/[:language]', function ($request) use ($fp) {

            if (!($list = $fp->renderList($request->language)))
                $this->return404();

            return $this->twig->render('displayList.html.twig', [ 
                'language' => $request->language,
                'list' => $list
                ]);
        });

        $this->klein->respond('GET', '/hilfe/list/[:language]/[:file]', function ($request) use ($fp) {

            if (!($code = $fp->getCode($request->language, $request->file)))
                $this->return404();

            return $this->twig->render('displayCode.html.twig', [ 
                'language' => $request->language,
                'code' => $code
                ]);
        });
    }

    private function return404()
    {

    }
}