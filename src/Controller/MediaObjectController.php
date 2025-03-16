<?php

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MediaObjectController extends AbstractController {

    public function __invoke(Request $request)
    {
        $file = $request->files->get('file');

        
    }



}
