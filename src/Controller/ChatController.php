<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();

        if ($request->get('exit')) {
            $session->clear();
        }

        $userId = $session->get('user_id');
        if (empty($userId)) {
            return $this->redirectToRoute('auth', [
                'error' => 'Пожалуйста, авторизуйтесь'
            ]);
        }
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy([
            'id' => $userId
        ]);
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
            $timestamp = $now->getTimestamp();
            $lastMessage = $this->getDoctrine()->getRepository(Message::class)->findBy([
                'login' => $user
            ], ['datetime' => 'DESC'], 1);
            if ($lastMessageTimestamp = $lastMessage[0]->getDatetime()->getTimestamp()) {
                /**
                 * Если юзер отправял сообщение в течение 30 секунд, то выдаем ошибку
                 */
                if ($timestamp - 30 < $lastMessageTimestamp) {
                    $diff = $lastMessageTimestamp - $timestamp + 30 ;
                    return new JsonResponse([
                        'status' => 'error',
                        'msg' => 'Сообщения можно отправлять раз в 30 секунд. Следующее сообщение через: ' . $diff
                    ]);
                }
            }

            $message = $form->getData();
            $message->setDatetime($now);
            $message->setLogin($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return new JsonResponse([
                'status' => 'success'
            ]);

        }

        return $this->render('chat/index.html.twig', [
            'login' => $user->getLogin(),
            'form' => $form->createView()
        ]);
    }
}
