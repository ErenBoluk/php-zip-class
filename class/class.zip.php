<?php

class zip
{
    private $zip;
    function __construct()
    {
        
        $this->zip = new ZipArchive();
        
    }
    public  function unzipFile($zipPath, $destination, $password = null)
    {
        if ($this->zip->open($zipPath) === true) {
            if ($password !== null) {
                $this->zip->setPassword($password);
            }

            $this->zip->extractTo($destination);
            $this->zip->close();
            echo "Zip dosyası başarıyla açıldı.";
            return true;
        } else {
            echo "Zip dosyası açma hatası.";
            return false;
        }
    }
    public function zipFiles($zipPath, $files)
    {
        if (is_dir($files)) {
            $files = $this->getAllFilesFromDirectory($files);
        }

        $success = false;
        if ($this->zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $filePath = $file['path'];
                if (file_exists($filePath)) {
                    if (is_dir($filePath)) {
                        $this->zip->addEmptyDir($file['name']);
                        $dirIterator = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($filePath),
                            RecursiveIteratorIterator::SELF_FIRST
                        );
                        foreach ($dirIterator as $subFile) {
                            $subFilePath = $subFile->getRealPath();
                            $relativePath = substr($subFilePath, strlen($filePath) + 1);
                            if ($subFile->isDir()) {
                                $this->zip->addEmptyDir($file['name'] . '/' . $relativePath);
                            } else {
                                $this->zip->addFile($subFilePath, $file['name'] . '/' . $relativePath);
                            }
                        }
                    } else {
                        $this->zip->addFile($filePath, $file['name']);
                    }
                }
            }
            $this->zip->close();
            $success = true;
        } else {
            echo "Zip oluşturma hatası.";
        }
        return $success;
    }

    private function getAllFilesFromDirectory($directory)
    {
        $files = [];
        $dirIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($dirIterator as $file) {
            if ($file->isDir()) {
                continue;
            }
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($directory) + 1);
            $files[] = [
                'path' => $filePath,
                'name' => $relativePath
            ];
        }
        return $files;
    }
}
