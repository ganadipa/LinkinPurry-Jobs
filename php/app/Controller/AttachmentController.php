<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\AttachmentService;
use Exception;

class AttachmentController {
    public static function create(Request $req, Response $res): void {
        try {
            $inputJson = file_get_contents('php://input');
            $inputData = json_decode($inputJson, true);

            $attachment = AttachmentService::createAttachment($inputData);

            $res->json([
                'status' => 'success',
                'message' => 'Attachment created successfully.',
                'data' => $attachment
            ]);

        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function update(Request $req, Response $res): void {
        try {
            $id = $req->getUriParamsValue('id', null);
            $inputJson = file_get_contents('php://input');
            $postData = json_decode($inputJson, true);

            if (!isset($id)) {
                throw new Exception("Attachment ID is required.");
            }

            $updatedAttachment = AttachmentService::updateAttachment($id, $postData);

            $res->json([
                'status' => 'success',
                'message' => 'Attachment updated successfully.',
                'data' => $updatedAttachment
            ]);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function delete(Request $req, Response $res): void {
        try {
            $id = $req->getUriParamsValue('id', null);

            if (!isset($id)) {
                throw new Exception("Attachment ID is required.");
            }

            AttachmentService::deleteAttachment($id);

            $res->json([
                'status' => 'success',
                'message' => 'Attachment deleted successfully.'
            ]);
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function getList(Request $req, Response $res): void {
        try {
            $attachments = AttachmentService::getAttachmentList();


            $res->json([
                'status' => 'success',
                'message' => 'Attachment list retrieved successfully.',
                'data' => $attachments
            ]);

            $res->send();
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }   
    }

    public static function getPublicAttachment(Request $req, Response $res): void {
        try {
            $attachmentId = $req->getUriParamsValue('attachmentId', null);

            if (!isset($attachmentId)) {
                throw new Exception("Attachment ID is required.");
            }

            $img = AttachmentService::getAttachmentPath($attachmentId);

            $res->image($img);

            $res->send();
        } catch (Exception $e) {
            $res->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
