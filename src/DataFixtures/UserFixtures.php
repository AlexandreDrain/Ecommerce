<?php
 
namespace App\DataFixtures;
 
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
 
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On desactive les logs SQL pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
        $userNames = ['Bidule', 'Alexandre', 'Machin'];
        foreach($userNames as $userName)
        {
            $user = new User();
            $user->setFirstName($userName);
            $user->setLastName($userName);
            $user->setEmail($user->getFirstName() . "@gmail.com");
            $user->setPassword(
                password_hash($user->getFirstName(), PASSWORD_DEFAULT)
            );
            $user->setRoles(["ROLE_SUPER_ADMIN"]);
            $user->setRegistredAt(new \Datetime);
            $user->setAddress("69 Rue des Rois");

            $manager->persist($user);
        }

        $manager->flush();  
    }
}