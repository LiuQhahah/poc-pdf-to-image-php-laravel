<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Exceptions\PageDoesNotExist;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Spatie\PdfToImage\Pdf;


class FileController extends Controller
{
    /**
     * @throws PdfDoesNotExist
     * @throws PageDoesNotExist
     */
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->isValid()) {
                // 获取上传文件的原始名称
                $originalName = $file->getClientOriginalName();

                // 存储上传文件并返回存储路径
                $path = $file->store('uploads');


                $absolutePath = Storage::path($path);



                $pdf = new Pdf($absolutePath);


                $size = $pdf->getNumberOfPages(); //returns an int


                for ($i = 1; $i <= $size; $i++) {

                    echo "new pdf file $absolutePath";
                    $timestamp = time();
                    $fileName = $timestamp . $i. '.jpg';
                    $directory = 'uploads/';
                    $pathToWhereImageShouldBeStored = $absolutePath.$fileName;
                    echo "$pathToWhereImageShouldBeStored pdf file $pathToWhereImageShouldBeStored";

                    $pdf->setPage($i)
                        ->saveImage($pathToWhereImageShouldBeStored);

                    echo $i . " ";
                }







                return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
            } else {
                return response()->json(['message' => 'Invalid file'], 400);
            }
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
