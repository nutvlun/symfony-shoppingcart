<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function returnSuccess($message = 'success', $data = null)
    {
        if($data === null) {
            return $this->json([
                'status' => 'success',
                'message' => $message,
            ]);
        }
        return $this->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function returnError($message = 'error')
    {
        return $this->json([
            'response' => 'error',
            'message' => $message
        ]);
    }
}
