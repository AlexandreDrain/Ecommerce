<?php
 
namespace App\DataFixtures;
 
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
 
class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On desactive les logs SQL pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
       
        $categories = ['Social deduction game', 'FPS', 'ROGUELIKE', 'MMORPG', 'GACHAGAME', 'Action-aventure'];
        foreach($categories as $name) 
        {
            $category = new Category();
            $category->setTitle($name);
            $category->setCreatedAt(new \Datetime);
            $category->setIsCategoryAvailable(1);
            // Le fait de conserver en référence nous permettra
            // d'utiliser cette entité dans d'autres fixtures
            $this->addReference($name, $category);
            $manager->persist($category);
        }
        $manager->flush();  
    }
}