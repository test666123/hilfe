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
    private $fp;

    function __construct()
    {
        $this->fp = new FilePicker();

        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../view');
        $this->twig = new \Twig_Environment($loader, [ 'cache' => false ]);
        $this->twig->addGlobal('tags', $this->fp->getAllTags());

        $this->klein = new Klein();
    }

    function __destruct()
    {
        $this->klein->dispatch();
    }

    public function run()
    {
        $this->klein->respond('GET', '/hilfe/', function ($request) {
            return $this->twig->render('index.html.twig', [ 'list' => $this->fp->getExampleList() ]);
        });

        $this->klein->respond('GET', '/hilfe/[:exampleName]', function ($request) {
            return $this->twig->render('displayExample.html.twig', [ 'files' => $this->fp->getExample($request->exampleName) ]);
        });

        $this->klein->respond('GET', '/hilfe/tag/[:tag]', function ($request) {
            return $this->twig->render('index.html.twig', [ 'list' => $this->fp->getExampleList($request->tag) ]);
        });
    }

    private function return404()
    {

    }
}