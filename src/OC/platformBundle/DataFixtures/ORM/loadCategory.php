<?php

// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace OC\platformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\platformBundle\Entity\Category;
use OC\platformBundle\Entity\Advert;
use OC\platformBundle\Entity\Skill;
use OC\platformBundle\Entity\Image;

class LoadCategory implements FixtureInterface {

    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager) {
        // Liste des noms de catégorie à ajouter
        $names = array(
            'Développement web',
            'Développement mobile',
            'Graphisme',
            'Intégration',
            'Réseau'
        );

        foreach ($names as $name) {
            // On crée la catégorie
            $category = new Category();
            $category->setName($name);
            // On la persiste
            $manager->persist($category);
        }

        // On déclenche l'enregistrement de toutes les catégories
        //========================================== AJOUT D'image =========================================================

        $image = new Image();
        $image->setUrl("http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg");
        $image->setAlt("Job de rêve");
        $manager->persist($image);
        //$manager->flush();

        //========================================== FIN AJOUT D'image =========================================================
        //========================================== AJOUT DE skill =========================================================

        $names = array('PHP', 'Symfony', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');

        foreach ($names as $name) {
            // On crée la compétence
            $skill = new Skill();
            $skill->setName($name);

            // On la persiste
            $manager->persist($skill);
        }


        //========================================== AJOUT DE ADVERT =========================================================
        //$image = $manager->getRepository("OCplatformBundle:Image")->findOneByUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image = $manager->getRepository("OCplatformBundle:Image")->findOneBy(array('url'=>'http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg'));
        
        
        $datas = array(
            array('date'=>'2018-01-30 12:10:10','title'=>'Recherche Developpeur','author'=>'Moi meme','content'=>'Je recherche des developpeur java','image_id'=>'1','published'=>'1','slug'=>'recherche-developpeur-java'),
            array('date'=>'2017-12-15 12:10:10','title'=>'Recherche Graphiste','author'=>'Genevieve','content'=>'Recherche de graphiste','image_id'=>'1','published'=>'1','slug'=>'recherche-graphiste'),
            array('date'=>'2018-03-01 12:10:10','title'=>'Recherche Administrateur Systeme','author'=>'Réné','content'=>'Besoin d\'un administrateur system exerienté','image_id'=>'1','published'=>'1','slug'=>'besoin-administrateur-systeme')
        );

        foreach ($datas as $data) {
            //$repository = $manager->getRepository("OCplatformBundle:Image");
            //$image = $repository->find($data['image_id']);
            $advert = new Advert();
            $advert->setAuthor($data['author']);
            $date = new \DateTime(date($data['date']));
            $advert->setDate($date);
            $advert->setTitle($data['title']);
            $advert->setContent($data['content']);
            $advert->setPublished($data['published']);
            $advert->setImage($image);
            $advert->setSlug($data['slug']);

            $manager->persist($advert);


            //========================================== AJOUT DE ADVERT =========================================================
        }
        $manager->flush();
    }

}
