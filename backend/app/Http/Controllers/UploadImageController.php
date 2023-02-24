<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadImagesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class UploadImageController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        if ($request->files->get('file')) {
            try {
                $image = UploadImagesService::save(
                    2,
                    $request->entity_id,
                    [$request->files->get('file')]
                );

                return response()->json([
                    'success' => true,
                    $image
                ]);
            } catch (RuntimeException|Exception $exception) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'text' => [
                            $exception->getMessage()
                        ]
                    ]
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ]);
    }
}
