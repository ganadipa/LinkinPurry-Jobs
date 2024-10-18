<?php

namespace App\Service;
use App\View\View;
use Core\DirectoryAlias;
use Core\Repositories;
use App\Model\Lamaran;
use App\Util\Enum\StatusLamaranEnum;
use DateTime;

class LamaranService {
// returns lamaran_id
    public static function applyJob(int $lowongan_id, int $user_id,mixed $cv, mixed $video): int {

        try {
            // Save the cv and video to the local
            $uploadsDir = DirectoryAlias::get('@uploads');

            // generate uuid for the cv and video
            $uniqueId = uniqid();

            if (isset($cv) && $cv['error'] === UPLOAD_ERR_OK) {
                $cvTmpPath = $cv['tmp_name'];
                $cvName = basename($cv['name']);
                $cvDestPath = $uploadsDir . '/' . $uniqueId . '-' . $cvName;
            
                // Move the CV to the uploads directory
                if (move_uploaded_file($cvTmpPath, $cvDestPath)) {
                    echo 'CV uploaded successfully: ' . $cvDestPath;
                } else {
                    throw new Exception('Error uploading CV.');
                }
            }
            
            // Check if Video was uploaded
            if (isset($video) && $video['error'] === UPLOAD_ERR_OK) {
                $videoTmpPath = $video['tmp_name'];
                $videoName = basename($video['name']);
                $videoDestPath = $uploadsDir . '/' . $uniqueId . '-' . $videoName;
            
                // Move the Video to the uploads directory
                if (move_uploaded_file($videoTmpPath, $videoDestPath)) {
                    echo 'Video uploaded successfully: ' . $videoDestPath;
                } else {
                    throw new Exception('Error uploading Video.');
                }
            }
        } catch (Exception $e) {
            // delete the uploaded files
            if (isset($cvDestPath)) {
                unlink($cvDestPath);
            }

            if (isset($videoDestPath)) {
                unlink($videoDestPath);
            }

            throw new Exception('Error uploading files');
        }

        $lamaran = new Lamaran(
            $user_id,
            $lowongan_id,
            $cvDestPath,
            $videoDestPath,
            StatusLamaranEnum::WAITING,
            '',
            new DateTime()
        );

        // Save the application to the database
        $lamaranRepo = Repositories::$lamaran;

        $lamaranRet = $lamaranRepo->save($lamaran);

        return $lamaranRet->lamaran_id;
    }
}


