<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/get/users", name="get_users")
     */
    public function getUsers()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        if (empty($users)) {
            return $this->json([
                'status' => 'error',
                'msg' => 'Users not found'
            ]);
        }
        $arr = [];
        foreach ($users as $user) {
            $arr[] = $user->jsonSerialize();
        }
        return $this->json([
            $arr
        ]);
    }

    /**
     * @Route("/get/user/{id}", name="get_user", requirements={"id"="\d+"})
     */
    public function getUser($id = null)
    {
        if (empty($id)) {
            return $this->json([
                'status' => 'error',
                'msg' => 'Missing user id'
            ]);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'id' =>$id
        ]);
        if (empty($user)) {
            return $this->json([
                'status' => 'error',
                'msg' => 'User not found'
            ]);
        }
        return $this->json($user->jsonSerialize());
    }

    /**
     * @Route("/get/messages", name="get_messages")
     */
    public function getMessages()
    {
        $messages = $this->getDoctrine()->getRepository(Message::class)->findAll();
        if (empty($messages)) {
            return $this->json([
                'status' => 'error',
                'msg' => 'Users not found'
            ]);
        }
        $arr = [];
        foreach ($messages as $message) {
            $arr[] = $message->jsonSerialize();
        }
        return $this->json([
            $arr
        ]);
    }
}
