<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChatWindowController extends AbstractController
{
    /**
     * @Route("/chat/window", name="chat_window")
     */
    public function index()
    {
        $session = $this->get('session');
        if (empty($session->get('user_id'))) {
            return $this->redirectToRoute('auth', [
                'error' => 'Пожалуйста, авторизуйтесь'
            ]);
        }

        $repository = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repository->findBy([ ], [ 'datetime' => 'DESC'], 20);

        $messagesArray = [];
        foreach ($messages as $message) {
            $messagesArray[] = [
                'text' => $message->getMessage(),
                'time' => date_format($message->getDatetime(), 'd/m/Y H:i:s'),
                'login' => $message->getLogin()->getLogin()
            ];
        }

        return $this->render('chat_window/index.html.twig', [
            'messages' => array_reverse($messagesArray),
        ]);
    }
}
