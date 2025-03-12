<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CategorieEntity;
use App\Entity\ProductEntity;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $categories = [
            [
                "name"           => "Plomberie",
                "description"    => "Carégorie plomberie, description de cette entité.",
                "isFeatured"     => true,
                "isSubCategory"  => false,
                "subsCategory"   =>
                [
                    [
                        "name" => "Plomberie domestique",
                        "description" => "Plomberie domestique",
                        "isFeatured"  => true,
                        "isSubCategory"  => true,
                        "subsCategory" => null
                    ],
                    [
                        "name" => "Plomberie industrielle",
                        "description" => "Plomberie industrielle",
                        "isFeatured"  => true,
                        "isSubCategory"  => true,
                        "subsCategory" => null
                    ]
                ]
            ],
            [
                "name" => "Courant Faible",
                "description" => "",
                "isFeatured"  => true,
                "isSubCategory"  => false,
                "subsCategory" =>
                [
                    [
                        "name" => "vidéo surveillance",
                        "description" => "vidéo surveillance",
                        "isFeatured"  => false,
                        "subsCategory" => null,
                        "isSubCategory"  => true,
                    ],
                    [
                        "name" => "sonorisation",
                        "description" => "sonorisation",
                        "isFeatured"  => false,
                        "subsCategory" => null,
                        "isSubCategory"  => true,
                    ],
                    [
                        "name" => "système de sécurité incendie",
                        "description" => "système de sécurité informatique",
                        "isFeatured"  => false,
                        "subsCategory" => null,
                        "isSubCategory"  => true,
                    ],
                    [
                        "name" => "informatique",
                        "description" => "informatique",
                        "isFeatured"  => false,
                        "subsCategory" => null,
                        "isSubCategory"  => true,
                    ],

                ]
            ],

            [
                "name" => "Courant fort",
                "description" => "Courant fort",
                "isFeatured"  => true,
                "isSubCategory"  => false,
                "subsCategory" => [

                    [
                        "name" => "électricité domestique",
                        "description" => "électricité domestique",
                        "isFeatured"  => true,
                        "subsCategory" => null,
                        "isSubCategory"  => true,
                    ],
                    [
                        "name" => "électricité industrielle",
                        "description" => "électricité industrielle",
                        "isFeatured"  => true,
                        "subsCategory" => null,
                        "isSubCategory"  => true,
                    ],
                ]
            ],
        ];

        foreach ($categories as $category) {
            $categorie = new CategorieEntity();
            $categorie->setTitle($category["name"]);
            $categorie->setDescription($category["description"]);
            $categorie->setIsSubCategory($category["isSubCategory"]);
            if ($category["subsCategory"]) {
                foreach ($category["subsCategory"] as $subCategory) {
                    $subCategorie = new CategorieEntity();
                    $subCategorie->setTitle($subCategory["name"]);
                    $subCategorie->setDescription($subCategory["description"]);
                    $subCategorie->setIsFeatured($subCategory["isFeatured"]);
                    $subCategorie->setIsSubCategory(true);
                    $categorie->addSubsCategory($subCategorie);
                }
            }


            $manager->persist($categorie);
        }
        $manager->flush();


        $allCategories = $manager->getRepository(CategorieEntity::class)->findAll();

        if ($allCategories) {
            $i = 0;
            foreach ($allCategories as $categorie) {
                $newProducti = new ProductEntity();
                $newProducti->setproductName("tp link wireless" . $i);
                $newProducti->setJustIn($i % 2 == 0 ? false : true);
                $newProducti->setisFeatured($i % 2 == 0 ? false : true);
                $newProducti->setCategory($categorie);
                $newProducti->setPreviousPrice(3000);
                $newProducti->setCurrentPrice(4500);
                $newProducti->setPiecesSold(40);
                $newProducti->setCoverImage("https://shareefcorner.sa/pub/media/catalog/product/mpiowebpcache/82d05cd6abfc8fcfb8bdbf5accf96e1b/t/p/tp-link_wireless_n_ceiling_mount_access_point_300mbps_eap110_-_white.webp");
                $manager->persist($newProducti);
                $i++;
            }

            $manager->flush();
        }
    }
}
