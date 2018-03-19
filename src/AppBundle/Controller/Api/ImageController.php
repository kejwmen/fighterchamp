<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{

    public function uploadAction()
    {
        $upload_images = array();
        $upload_dir = "img/user";
        foreach($_FILES['images_upload']['name'] as $key=>$val){
            $file_path = $upload_dir.$_FILES['images_upload']['name'][$key];
            $filename = $_FILES['images_upload']['name'][$key];
            if(is_uploaded_file($_FILES['images_upload']['tmp_name'][$key])) {
                if(move_uploaded_file($_FILES['images_upload']['tmp_name'][$key],$file_path)){
                    $upload_images[] = $file_path;
                }
            }
        }

        return new Response();
    }
}
