<?php

namespace Hilfe;

/**
* FilePicker
*/
class FilePicker
{
    private $path;
    private $allDirectories;

    function __construct()
    {
        $this->path = __DIR__ . '/../examples/';
        $this->allDirectories = scandir($this->path);
    }

    public function getExample($dirName)
    {
        $selectedDir = false;
        foreach ($this->allDirectories as $currentDir)
        {
            if (false !== strpos($currentDir, $dirName))
            {
                $selectedDir = $currentDir;
            }
        }

        if (!$selectedDir)
            return false;

        $fileNames = scandir($this->path . $selectedDir);

        $files = [];

        foreach ($fileNames as $fileName)
        {
            if (in_array($fileName, [ '.', '..' ]))
                continue;

            $file = new \stdClass();
            $file->type = $this->getFileType($fileName);
            $file->code = file_get_contents($this->path . $selectedDir . '/' . $fileName);
            $file->name = $this->getFileName($fileName);

            $files[] = $file;
        }

        return $files;
    }

    public function getExampleList($tag = null)
    {
        $examples = [];

        foreach ($this->allDirectories as $key => $dir)
        {
            if (!in_array($dir, [ '.', '..' ]))
            {
                if (!$tag || in_array($tag, $this->getTags($dir)))
                    $examples[] = $this->getExampleName($dir);
            }
        }

        return $examples;
    }

    public function getAllTags()
    {
        $allTags = [];

        foreach ($this->allDirectories as $dir)
        {
            if (!in_array($dir, [ '.', '..' ]))
                $allTags = array_merge($allTags, $this->getTags($dir));
        }

        return array_unique($allTags);
    }

    private function getTags($dir)
    {
        preg_match('/\[(.*?)\]/', $dir, $matches);
        $tags = explode(',', $matches[1]);

        return $tags;
    }

    private function getExampleName($dir)
    {
        //return preg_replace('/(?<! )(?<!^)[A-Z]/', ' $0', explode('[', $dir)[0]);
        return explode('[', $dir)[0];
    }

    private function getFileType($fileName)
    {
        $types = [
        'yml' => 'yaml',
        'js' => 'javascript'
        ];

        $ext = explode('.', $fileName)[1];

        return isset($types[$ext]) ? $types[$ext] : $ext;
    }

    private function getFileName($fileName)
    {
        return explode('.', $fileName)[0];
    }
}