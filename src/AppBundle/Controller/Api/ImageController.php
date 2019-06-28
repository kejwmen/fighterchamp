<?php

namespace AppBundle\Controller\Api;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->files->get('file');
        $destination = $this->getParameter('kernel.project_dir') . '/web/img/user';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename . '-' . uniqid()) . '.' . $uploadedFile->guessClientExtension();

            $uploadedFile->move(
                $destination,
                $newFilename
            );

        return new Response($newFilename);
    }



//    public function uploadAction()
//    {
//        $upload_images = array();
//        $upload_dir = "img/user";
//
//
//            $file_path = $upload_dir.$_FILES['images_upload']['name'][$key];
//            $filename = $_FILES['images_upload']['name'][$key];
//            if(is_uploaded_file($_FILES['images_upload']['tmp_name'][$key])) {
//                if(move_uploaded_file($_FILES['images_upload']['tmp_name'][$key],$file_path)){
//                    $upload_images[] = $file_path;
//                }
//            }
//
//
//
//
//        return new Response();
//    }
}
