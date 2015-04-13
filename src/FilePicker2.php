<?php

namespace Hilfe;

class FilePicker
{
    private $allowedDirs;
    private $path;

    function __construct()
    {
        $this->allowedDirs = [ 'php', 'jquery', 'css', 'yaml' ];
        $this->path = __DIR__ . '/../examples/';
    }
    
    public function renderList($lang)
    {
        if (!$this->checkIfAllowed($lang))
            return false;

        $list = scandir($this->path . $lang);

        return $this->filterFiles($list);
    }

    public function getCode($lang, $file)
    {
        if (!$this->checkIfAllowed($lang))
            return false;

        $filePath = $this->path . $lang . '/' . $file . $this->typeToExtension($lang);

        if (!is_file($filePath))
            return false;

        $code = file_get_contents($filePath);

        return $this->filterCode($code);
    }

    private function checkIfAllowed($lang)
    {
        if (!is_dir($this->path . $lang))
            return false;

        return in_array($lang, $this->allowedDirs);
    }

    private function filterFiles($list)
    {
        foreach ($list as $key => $file)
        {
            if ($file === '.' || $file === '..')
            {
                unset($list[$key]);
                continue;
            }

            $list[$key] = explode('.', $file)[0];
        }

        return $list;
    }

    private function filterCode($code)
    {
        //$code = str_replace('<?php', '&lt;?php', $code);

        return $code;
    }

    private function typeToExtension($type)
    {
        $ext = [
        'php' => '.php',
        'yaml' => '.yml',
        'css' => '.css',
        'jquery' => '.js',
        ];

        return $ext[$type];
    }
}