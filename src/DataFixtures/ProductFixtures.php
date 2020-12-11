<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=1; $i<=10;$i++){

        	$category = new Category();

        	$category->setCategory($faker->word());

        	$manager->persist($category);

	        for($j=1; $j<=10; $j++) {
	        	$product = new Product();
	        	$product->setName($faker->word())
	        			->setDescription($faker->paragraph())
	        			->setPrice($faker->numberBetween(5,80))
	        			->setImage($faker->imageUrl(1280, 960))
	        			->setCategory($category);
	        	$manager->persist($product);

	        	for($k=1; $k<=10; $k++){
	        		$purchase = new Purchase();
	        		$purchase->setValidated($faker->boolean())
	        				 ->setValidatedDate($purchase->getValidated() ? $faker->dateTimeBetween('-1 years') : Null)
	        				 ->setIdUser($faker->numberBetween(1,20))
	        				 ->setProduct($product);
	        		$manager->persist($purchase);
	        	}
	        }
        }
        $manager->flush();
    }
}
