<?php

namespace App\Controller;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
    * @Route("/cart", name="cart")
    */
    public function index()
    {
        return $this->render('panier/cart.html.twig', [
            'controller_name' => 'CartController',
            ]);
        }
        
        /**
        * @Route("/cart/{id}", name="shopping_cart", methods={"GET"})
        */
        public function add($id,Request $request,Article $article,SessionInterface $session)
        {
            $session= $request->getSession();
            if (!$session->has('panier'))
            {
                $session->set('panier', array());
            }
            $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                if ($request->query->get('qte') != null)
                $panier[$id] = $request->query->get('qte');
            }else {
                if ($request->query->get('qte') != null)
                $panier[$id] = $request->query->get('qte');
                else
                $panier[$id] = 1;
                $this->addFlash('success', 'Article a été ajouté avec succès !');
            }
            $session->set('panier', $panier);
            return $this->redirectToRoute('shopping_cartAction'); 
        }
           
        /**
        * @Route("/cartAction/", name="shopping_cartAction", methods={"GET"})
        */
        public function cart(Request $request,SessionInterface $session)
        {
            $session= $request->getSession();
            if (!$session->has('panier'))
            {
                
                $session->set('panier', array());
                
            }
            $panier = $session->get('panier');
            
            $em=$this->getDoctrine();
      
             $produit=$em->getRepository(Article::class)->findArray(array_keys($session->get('panier')));
            
             return $this->render('panier/cart.html.twig', [
                 "produits"=>$produit,
                 "panier"=>$session->get('panier')
                ]); 
        }
     
    }