<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Permet d'afficher l'ensemble des annonces 
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    {

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }


    /**
     * Permet de créer une annonce
     * @Route("/ads/new", name="ads_create")
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $manager){

        $ad = new Ad();
        //$title = $request->request->get('annonce');
        // dump($title);
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //dump($title);
            //dump($ad);
            $manager->persist($ad);
            //dump($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée! "
            );
   
            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);

        }



        return $this->render("ad/new.html.twig",[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    public function show($slug, Ad $ad)
    {

        // $repo = $this->getDoctrine()->getRepository(Ad::class);
        // $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',[
            'ad' => $ad
        ]);
    }

    
}
