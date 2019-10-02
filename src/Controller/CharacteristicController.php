<?php

namespace App\Controller;

use App\Entity\Characteristic;
use App\Form\CharacteristicType;
use App\Repository\CharacteristicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/characteristic")
 */
class CharacteristicController extends AbstractController
{
    /**
     * @Route("/", name="characteristic_index", methods={"GET"})
     */
    public function index(CharacteristicRepository $characteristicRepository): Response
    {
        return $this->render('characteristic/index.html.twig', [
            'characteristics' => $characteristicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="characteristic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $characteristic = new Characteristic();
        $form = $this->createForm(CharacteristicType::class, $characteristic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($characteristic);
            $entityManager->flush();

            return $this->redirectToRoute('characteristic_index');
        }

        return $this->render('characteristic/new.html.twig', [
            'characteristic' => $characteristic,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="characteristic_show", methods={"GET"})
     */
    public function show(Characteristic $characteristic): Response
    {
        return $this->render('characteristic/show.html.twig', [
            'characteristic' => $characteristic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="characteristic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Characteristic $characteristic): Response
    {
        $form = $this->createForm(CharacteristicType::class, $characteristic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('characteristic_index');
        }

        return $this->render('characteristic/edit.html.twig', [
            'characteristic' => $characteristic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="characteristic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Characteristic $characteristic): Response
    {
       
        if ($this->isCsrfTokenValid('delete'.$characteristic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($characteristic);
            $entityManager->flush();
       }

        return $this->redirectToRoute('characteristic_index');
    }
}
