<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * This controller display all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    // Injecting the repository
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }

    /**
     * This controller display a form to create a new ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredient = new Ingredient(); // Create a new ingredient
        $form = $this->createForm(IngredientType::class, $ingredient); // Create a new form
        $form->handleRequest($request); // Handle the request
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData(); // Get the data from the form
            $manager->persist($ingredient); // Persist the ingredient
            $manager->flush(); // Flush the data

            $this->addFlash('success', 'L\'ingrédient a bien été créé'); // Add a flash message

            return $this->redirectToRoute('ingredient.index'); // Redirect to the index page
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller display a form to edit an ingredient
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/edition/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {
        // On utilise symfony param converter pour récupérer l'ingredient en fonction de l'id
        $form = $this->createForm(IngredientType::class, $ingredient); // Create a new form

        $form->handleRequest($request); // Handle the request
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData(); // Get the data from the form
            $manager->persist($ingredient); // Persist the ingredient
            $manager->flush(); // Flush the data

            $this->addFlash('success', 'L\'ingrédient a bien été modifié'); // Add a flash message

            return $this->redirectToRoute('ingredient.index'); // Redirect to the index page
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * This controller display a form to delete an ingredient
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods: ['GET'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {

        $manager->remove($ingredient); // Remove the ingredient
        $manager->flush(); // Flush the data

        $this->addFlash('success', 'L\'ingrédient a bien été supprimé'); // Add a flash message

        return $this->redirectToRoute('ingredient.index'); // Redirect to the index page
    }
}

//Commande terminal pour créer un controller : php bin/console make:controller IngredientController

// Installer une pagination 
//     Commande terminal :
//     composer require knplabs/knp-paginator-bundle
//     url : http://knplabs.github.io/knp-paginator-bundle/
//     YAML config/packages/
//     Créer un fichier knp_paginator.yaml
//     Copier le fichier de config
//     Une fois le fichier crée, dans le controller on insère: PaginatorInterface $paginator , Request $request après le $repository.
// Dans la view on insère : <div class="navigation">
//     {{ knp_pagination_render(pagination) }}
// </div>
// Changer le design de la pagination en fonction de la page sur laquelle on se trouve.
//  ctrl + F pour trouver le code à modifier. (bootstrap) sur le site de la pagination.
// puis dans le fichier YAML on change le theme en bootstrap.
// exemple : @KnpPaginator/Pagination/bootstrap_v5_pagination.html.twig
// Template : Pagination :