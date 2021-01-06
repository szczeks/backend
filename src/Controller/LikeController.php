<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Product;
use App\Form\LikeType;
use App\Repository\LikeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/like")
 */
class LikeController extends AbstractController
{
    /**
     * @Route("/", name="like")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(LikeType::class);
        $form->handleRequest($request);
        $people = [];
        $product = null;
        $likes = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            /**
             * @var Person $person
             * @var Product $product
             */
            if(is_object($data['Person'])) {
                $people[] = $data['Person'];
            } else {
                $people = $em->getRepository(Person::class)->findAll();
            }

            if(is_object($data['Product'])) {
                $product = $data['Product'];
            }

            foreach ($people as $person){
                /**
                 * @var Product $productObj
                 */
                foreach($person->getProduct()->toArray() as $productObj)
                {
                    if(empty($product) || $product === $productObj) {
                        $likes[] = array(
                            'person' => $person,
                            'product' => $productObj
                        );
                    }
                }
            }
        }
        return $this->render('like/index.html.twig', [
            'LikeType' => $form->createView(),
            'likes' => $likes
        ]);
    }

    /**
     * @Route("/new", name="like_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(LikeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if(is_object($data['Person']) && is_object($data['Product'])) {
                /**
                 * @var Person $person
                 * @var Product $product
                 */
                $person = $data['Person'];
                $product = $data['Product'];
                $person->addProduct($product);
                $em = $this->getDoctrine()->getManager();
                $em->persist($person);
                $em->flush();
            }
            return $this->redirectToRoute('like');
        }
        return $this->render('like/new.html.twig', [
            'LikeType' => $form->createView()
        ]);
    }

    /**
     * @Route("/{person}/{product}/edit", name="like_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Person $person
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Person $person, Product $product): Response
    {
        $form = $this->createForm(LikeType::class);
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
     * @Route("/{person}/{product}", name="like_delete", methods={"DELETE"})
     * @param Request $request
     * @param Person $person
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Person $person, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete_like', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $person->removeProduct($product);
            $entityManager->persist($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('like');
    }

}



















































































