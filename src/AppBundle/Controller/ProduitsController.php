<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Produits;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;

class ProduitsController extends Controller
{
    private function randString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @Route("/produits/liste", name="produitsliste")
     */
    public function listeAction()
    {

        $produits = $this->getDoctrine()
        ->getRepository(Produits::class)
        ->findAll();

        // replace this example code with whatever you need
        return $this->render('@App/default/produitsListe.html.twig', array(
            'produits' => $produits,
        ));


    }

    /**
     * @Route("/produit/ajout/{code}",defaults={"code" = null}, name="produitAjout")
     */
    public function ajoutAction(Request $request, $code)
    {
        if (is_null($code))
        {
            $produit = new Produits();
        }
        else 
        {
            $entityManager = $this->getDoctrine()->getManager();
            $produit = $entityManager->getRepository(Produits::class)->findOneByCode($code);
    
            if (!$produit) {
                throw $this->createNotFoundException(
                    'No product found for id '.$code
                );
            }
        }

        $form = $this->createFormBuilder($produit)
            ->add('code', TextType::class)
            ->add('description', TextType::class)
            ->add('famille', EntityType::class, array(
                'class' => 'AppBundle:ProduitFamille',
                'choice_label' => 'libelle'
            ))
            ->add('prixunitaire', MoneyType::class)
            ->add('description', TextareaType ::class)
            ->add('conditionnement', TextType::class)
            ->add('codebarre', NumberType::class)
            ->add('save', SubmitType::class, array('label' => 'Ajouter'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $produit = $form->getData();
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
    
            return $this->redirectToRoute('produitsliste');
        }
        
        return $this->render('@App/default/produitAjout.html.twig', array(
            'form' => $form->createView(), 'code' => $code
        ));

    }

    /**
     * @Route("/produit/supp/{code}", name="produitSupp")
     */
    public function suppAction($code)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produits::class)->findOneByCode($code);

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->render('@App/default/produitSupp.html.twig', array(
            'produit' => $code
        ));
    }
}
