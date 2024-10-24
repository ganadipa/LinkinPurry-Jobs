<?php


namespace App\Repository\Local;
use App\Repository\Interface\RFile;
use App\Model\File;
use Core\DirectoryAlias;
use Exception;

class LocalFileRepository implements RFile {
    public function save(File $file): File {
        try {
            $uploadsDir = DirectoryAlias::get('@uploads');

            // generate uuid for the cv and video
            $uniqueId = uniqid();

            $tmpPath = $file->tmpPath;
            $name = $file->name;
            $destPath = $uploadsDir . '/' . $uniqueId . '-' . $name;
        
            // Move the file to the uploads directory
            if (move_uploaded_file($tmpPath, $destPath)) {
                
            } else {
                throw new Exception('Error uploading file.');
            }

            // Set the absolute path 
            $file->absolutePath = $destPath;
            return $file;
        } catch (Exception $e) {
            throw new Exception('Error saving file');
        }

    }

    public function delete(string $path): void {
        try {
            unlink($path);
        } catch (Exception $e) {
            throw new Exception('Error deleting file');
        }
    }
}
