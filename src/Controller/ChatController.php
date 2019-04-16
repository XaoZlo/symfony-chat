<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(Request $request)
    {
        $session = $this->get('session');
        if (empty($session->get('login'))) {
            return $this->redirectToRoute('auth', [
                'error' => 'Пожалуйста, авторизуйтесь'
            ]);
        }
        $login = $session->get('login');

        $messages = new Message();

        $form = $this->createFormBuilder($messages)
            ->add('message', TextType::class, [
                'label' => 'Текст сообщения'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Отправить сообщение'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $now = new \DateTime();
            /**
             * Если юзер отправял сообщение в течение 30 секунд, то выдаем ошибку
             */
            $timestamp = $now->getTimestamp();
            $lastMessage = $this->getDoctrine()->getRepository(Message::class)->findOneBy([
                'login' => $login
            ]);

            $lastMessageTimestamp = $lastMessage->getDatetime()->getTimestamp();
            if ($timestamp-30 < $lastMessageTimestamp) {
                $error = 'Сообщения можно отправлять раз в 30 секунд.';
                return $this->render('chat/index.html.twig', [
                    'login' => $login->getLogin(),
                    'error' => $error,
                    'form' => $form->createView()
                ]);
            }

            $message = $form->getData();
            $message->setDatetime($now);
            $message->setLogin($login);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($login);
            $entityManager->persist($message);
            $entityManager->flush();

        }

        return $this->render('chat/index.html.twig', [
            'login' => $login->getLogin(),
            'form' => $form->createView()
        ]);
    }
}
