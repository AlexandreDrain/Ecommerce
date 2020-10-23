<?php
 
namespace App\DataFixtures;
 
use App\Entity\Product;
 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
 
class ProductFixtures extends Fixture
{
 
    public function load(ObjectManager $manager)
    {
        // On desactive les logs pour accélerer le processus
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);
 
        $productsByCateg = [
            'FPS' => [
                [
                    'title'        => "Escape From Tarkov",
                    'description' => "Jeu encore en beta, battle Royal like, l'originalité est dans le système d'inventaire...",
                    'picture'     => 'images/eft.webp',
                    'price'        => 49.99
                ],
            ],
            'ROGUELIKE' => [
                [
                    'title'        => "Isaac Afterbirth",
                    'description' => "tout simplement un rogue like, avec des rooms généré aléatoirement",
                    'picture'     => 'images/isaacAfterbirth.jpg',
                    'price'         => 24.99
                ],
                [
                    'title'        => "Hades",
                    'description' => "Rogue like très récent qui fait fureur sur twitch, pas mal d'innovation, rooms généré aléatoirement",
                    'picture'     => 'images/hades.png',
                    'price'         => 29.99
                ]
            ],
            'MMORPG' => [
                [
                    'title'        => "Sword art online",
                    'description' => "l'un des mmorpg les plus complet et avancé du monde de la VR",
                    'price'        => 59.99
                ],
            ],
            'GACHAGAME' => [
                [
                    'title'        => "Genshin Impact",
                    'description' => "Gacha original de part son genre : open world très développé, a l'instart de BOTW, auquel il est pas mal comparé",
                    'picture'     => 'images/genshin.webp',
                    'price'        => 0
                ],
            ],
            'Action-aventure' => [
                [
                    'title'        => "The Legend of Zelda: Breath of the Wild",
                    'description' => "L'un des meilleurs Zelda à ce jour, de par ses mécaniques novatrice, pour la licence, un open world immense, une grosse durée de vie. Les graphismes sont notamment à relever ! Sublime",
                    'picture'     => 'images/botw.jpg',
                    'price'        => 69.99
                ]
            ]
        ];
 
 
        foreach($productsByCateg as $category => $products) 
        {
            foreach($products as $info) {
                $product = new Product();
                $product->setTitle($info['title']);
                $product->setDescription($info['description']);
                $product->setPicture(isset($info['picture']) ? $info['picture'] : '');
                $product->setPrice($info['price']);
                $product->setIsAvailable(1);
                $product->addCategory($this->getReference($category));
                $product->setCreatedAt(new \Datetime);
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
 
    public function getDependencies()
    {
        // ici on peut lister les fixtures nécessaires avant 
        // d'exécuter cette fixture (Product)
        return array(
            CategoryFixtures::class,
        );
    }
}
