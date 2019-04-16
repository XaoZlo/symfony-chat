<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/", name="auth")
     */
    public function index(Request $request)
    {
        $error = $request->get('error');
        $session = $request->getSession();
        if (!empty($session->get('user_id'))) {
            return $this->redirectToRoute('chat');
        }

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class)
            ->add('password', TextType::class)
            ->add('auth', SubmitType::class, ['label' => 'Войти'])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $userForm = $form->getData();
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneBy([
                'login' => $userForm->getLogin(),
                'password' => $userForm->getPassword(),
            ]);
            if(!empty($user)) {
                $session->set('user_id', $user->getId());
                return $this->redirectToRoute('chat');
            } else {
                $error =  'Неверный логин или пароль';
            }

        }

        return $this->render('auth/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
}
