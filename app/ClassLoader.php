<?php

class ClassLoader {

    protected $className;
    protected $scanFolder;
    protected $excludes;
    protected $requireFile = false;
    protected $classFile;
    protected $scans;

    public function __construct($className, $scanFolder, $excludes = ['..', '.'])
    {
        $this->className = $className;
        $this->scanFolder = $scanFolder;
        $this->excludes = $excludes;

        $this->requireFile = $this->load();
    }

    public function doRequire($once = false) {
        if ($once) {
            require_once $this->requireFile;
        }

        require $this->requireFile;
    }

    protected function load()
    {
        $this->classFile = $this->getCleanFile($this->className);
        $this->scans = $this->getScans($this->scanFolder, $this->excludes, $this->scanFolder);
        return $this->getClassFile();
    }

    protected function getCleanFile($className)
    {
        return preg_replace(
                '/.*'. preg_quote('\\') .'/',
                '',
                $className
        ) . '.php';
    }

    protected function getScans($scanFolder, $exclude = ['..', '.'], $parent = '')
    {
        $scans = [ '/' . trim($scanFolder, '/') ];
        $scan = array_diff( scandir($scanFolder), $exclude );

        if (!$scan) {
            return [];
        }

        foreach ($scan as $item) {

            if (!is_dir($scanFolder . $item) || !file_exists($scanFolder . $item)) {
                continue;
            }

            $subScans = $this->getScans(
                $scanFolder . $item . '/',
                ['..', '.'],
                $item . '/'
            );

            $scans = array_merge($scans, $subScans);
        }

        return $scans;
    }

    protected function getClassFile()
    {
        $stop = false;
        $tryFile = $this->classFile;
        return array_reduce($this->scans,
            function($file, $path) use (&$stop, $tryFile) {
                if (!$stop && file_exists($path . '/' . $tryFile)) {
                    $file = $path . '/' . $tryFile;
                    $stop = true;
                }
                return $file;
            },
            false
        );
    }
}