<?php

namespace OC\platformBundle\Controller;

use OC\platformBundle\Entity\Advert;
use OC\platformBundle\Entity\Image;
use OC\platformBundle\Entity\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use OC\platformBundle\Form\AdvertType;

class AdvertController extends Controller {

    public function indexAction($page) {
        if ($page < 1) {
            throw new NotFoundHttpException("Page" . $page . " inexistante");
        }

        $em = $this->getDoctrine()->getManager();
        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 3;
        //$advert = $em->getRepository("OCplatformBundle:Advert")->find('15');
//        $listAdverts = $em->getRepository("OCplatformBundle:Advert")->findAll();
        $listAdverts = $em->getRepository("OCplatformBundle:Advert")->getAdverts($page, $nbPerPage);

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            //throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue

        return $this->render('OCplatformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $listAdverts,
                    'nbPages' => $nbPages,
                    'page' => $page,
        ));
    }

    public function viewAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("OCplatformBundle:Advert");
        $advert = $repository->find($id);


        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        $listApplications = $em->getRepository("OCplatformBundle:Application")->findBy(array('advert' => $advert));
        $listadvertSkill = $em->getRepository("OCplatformBundle:AdvertSkill")->findBy(array('advert' => $advert));

        // $listApplication = $repositoryApplication->findBy(array('advert', $advert));


        return $this->render('OCplatformBundle:Advert:view.html.twig', array(
                    'advert' => $advert,
                    'listApplcations' => $listApplications,
                    'listadvertSkill' => $listadvertSkill
        ));
    }

    public function viewSlugAction($year, $slug, $format) {
        return new Response("On pourrait afficher  l'annonce correspondant au slug $slug, créée en $year et au format $format");
    }

    public function addAction(Request $request) {
        $advert = new Advert();
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('OCplatformBundle:Advert:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('OCplatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Inutile de persister ici, Doctrine connait déjà notre annonce
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('OCplatformBundle:Advert:edit.html.twig', array(
                    'advert' => $advert,
                    'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('OCplatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirectToRoute('oc_platform_home');
        }

        return $this->render('OCplatformBundle:Advert:delete.html.twig', array(
                    'advert' => $advert,
                    'form' => $form->createView(),
        ));
    }

    public function menuAction($limit) {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('OCplatformBundle:Advert')->findBy(
                array(), // Pas de critère
                array('date' => 'desc'), // On trie par date décroissante
                $limit, // On sélectionne $limit annonces
                0                        // À partir du premier
        );
        return $this->render('OCplatformBundle:Advert:menu.html.twig', array(
                    'listAdverts' => $listAdverts
        ));
    }

    public function addAction_hold(Request $request) {
        // Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // On lie l'image à l'annonce
        $advert->setImage($image);

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

//        $id="14";
//        $advert = $em->getRepository("OCplatformBundle:Advert")->find($id);
//        if (null === $advert) {
//            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
//        }
        // Étape 1 : On « persiste » l'entité
        //$em->persist($advert);
        // Création d'une première candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une deuxième candidature par exemple
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        // On lie les candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em->persist($application1);

        $em->persist($application2);




        // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
        // on devrait persister à la main l'entité $image
        // $em->persist($image);
        // Étape 2 : On déclenche l'enregistrement
        $em->flush();

        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'Annonce enregistrée avec succès');

        return $this->redirectToRoute("oc_platform_home");
    }

    public function purgeAction($days) {
        //$int_days = $request->query->get('days');
        //return new Response("int ===== ".$days);
        $purger = $this->get('oc_platform.purger.advert');
        $count = $purger->purge($days);
        return new Response("Nombres de lignes supprimées " . $count);
    }

}

?>
