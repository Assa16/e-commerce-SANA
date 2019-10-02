<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
   /**
    * @Route("/home", name="home")
    */
   public function index()
    {
       return $this->render('home/index.html.twig');
    }
   /**
    * @Route("/shop", name="shop", methods={"GET"})
    */
   public function indexarticles(ArticleRepository $articleRepository,CategoryRepository $categoryRepository): Response
    {   
       
       return $this->render('home/shop.html.twig', [
           'article' => $articleRepository->findBy(array(),['views'=>'DESC']),
           'categories' => $categoryRepository->findAll(),
        ]);
    }
   /**
    * @Route("/shop/category/{id}", name="shop_category", methods={"GET"})
    */
    public function showCategory(Category $category): Response
    {
        return $this->render('home/show_category.html.twig', [
            'category' => $category,
        ]);
    }
   /**
    * @Route("/shop/category/article/{id}", name="shop_article", methods={"GET"})
    */
    public function show(Article $article): Response
    {
        $entityManager=$this->getDoctrine()->getManager();
        $view=$entityManager->getRepository(Article::class)->find($article);
        $view->setViews($article->getViews()+1);
        $entityManager->persist($view);
        $entityManager->flush();
        return $this->render('home/show_article.html.twig', [
            'article' => $article,
        ]);
    }
}
