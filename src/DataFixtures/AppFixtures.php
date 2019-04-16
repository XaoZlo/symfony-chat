<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setlogin('User_nickname');
        $user->setPassword('1234');
        $manager->persist($user);

        $user2 = new User();
        $user2->setlogin('Nickname_username');
        $user2->setPassword('1234');
        $manager->persist($user2);

        $msg = new Message();
        $msg->setLogin($user2);
        $msg->setDatetime(new \DateTime());
        $msg->setMessage('<script>alert("XSS")</script>');
        $manager->persist($msg);

        $msg = new Message();
        $msg->setLogin($user2);
        $msg->setDatetime(new \DateTime());
        $msg->setMessage('Привет, нормально)');
        $manager->persist($msg);

        $msg = new Message();
        $msg->setLogin($user);
        $msg->setDatetime(new \DateTime());
        $msg->setMessage('Как дела?');
        $manager->persist($msg);

        $msg = new Message();
        $msg->setLogin($user);
        $msg->setDatetime(new \DateTime());
        $msg->setMessage('Привет');
        $manager->persist($msg);

        $manager->flush();
    }
}
