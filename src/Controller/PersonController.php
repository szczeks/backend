<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/person")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/", name="person_index", methods={"GET"})
     */
    public function index(): Response
    {
        $people = $this->getDoctrine()
            ->getRepository(Person::class)
            ->findAll();

        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }

    /**
     * @Route("/new", name="person_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="person_show", methods={"GET"})
     * @param Person $person
     * @return Response
     */
    public function show(Person $person): Response
    {
        return $this->render('person/show.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Person $person
     * @return Response
     */
    public function edit(Request $request, Person $person): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="person_delete", methods={"DELETE"})
     * @param Request $request
     * @param Person $person
     * @return Response
     */
    public function delete(Request $request, Person $person): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $person->setState(Person::STATE_USUNIETY);
            $entityManager->persist($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('person_index');
    }
}
