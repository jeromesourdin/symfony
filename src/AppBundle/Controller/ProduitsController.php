<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

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
        ->getRepository(Produit::class)
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Po le droit !');

        if (is_null($code))
        {
            $produit = new Produit();
        }
        else 
        {
            $entityManager = $this->getDoctrine()->getManager();
            $produit = $entityManager->getRepository(Produit::class)->findOneByCode($code);
    
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

            $this->addFlash('Produit Ajouté');
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
        $produit = $entityManager->getRepository(Produit::class)->findOneByCode($code);

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->render('@App/default/produitSupp.html.twig', array(
            'produit' => $code
        ));
    }

    /**
     * @Route("/produit/cherche/{date}", name="cherche")
     */
    public function chercheAction($date)
    {
        $date = date($date);

        $repository = $this->getDoctrine()->getRepository(Produit::class);

        $produits = $repository->findParDate2($date);

        return $this->render('@App/default/cherche.html.twig', array(
            'produits' => $produits,
            'date' => $date
        ));

    }

    /**
     * @Route("/api", name="api")
     */
    public function apiAction()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api-adresse.data.gouv.fr/api',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $data = $client->request(
                'GET', 
                'search/?q=13 bd Carnt&postcode=22000',
                [
                    'stream' => true,
                    'stream_context' => ['ssl' => ['allow_self_signed' => true]]
                ]
            )
            ->getBody()
            ->getContents();
        $data = json_decode($data);

        $client2 = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://datarmor.cotesdarmor.fr/dataserver/cg22/data/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $data2 = $client2->request(
                'GET', 
                'i3ikmnzn?$inlinecount=allpages&$select=RSANA&$format=json&$filter=AN_PREL gt 2007 and substringof(tolower('. chr(39) .'417300'. chr(39) .'),tolower(CDSTATIONMESUREEAUXSURFACE)) and NOMPARAMETRE eq '. chr(39) .'Nitrates'. chr(39) .'&$top=1000&$skip=0',
                [
                    'stream' => true,
                    'stream_context' => ['ssl' => ['allow_self_signed' => true]]
                ]
            )
            ->getBody()
            ->getContents();
        $data2 = json_decode($data2);



        return $this->render(
            '@App/default/api.html.twig', 
            array(
                'data' => $data->features[0]->geometry->coordinates,
                'data2' => $data2
            )
        );

    }
}
