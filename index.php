<?php

require_once 'class/class.zip.php';

// Zip dosyası oluşturma
$zipPath = 'dosya.zip';
$files = '/path/to/files';

$zip = new zip();

$success = $zip->zipFiles($zipPath, $files);
if ($success) {
    echo "Zip dosyası oluşturuldu.\n";
} else {
    echo "Zip oluşturma hatası.\n";
}

// Zip dosyasını açma
$unzipPath = 'unzip';
$password = 'gizli_sifre';

$success = $zip->unzipFile($zipPath, $unzipPath, $password);
if ($success) {
    echo "Zip dosyası başarıyla açıldı.\n";
} else {
    echo "Zip açma hatası.\n";
}