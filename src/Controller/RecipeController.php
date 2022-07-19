<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    /**
     * This controller display all recipes
     *
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recipe.index', methods: ['GET'])]
    public function index(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $recipes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * This controller allow to create a new recipe
     *
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/recette/creation', name: 'recipe.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash('success', 'La recette a bien été créée.');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller display a form to edit a recipe
     *
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/edition/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {
        // On utilise symfony param converter pour récupérer la recette en fonction de l'id
        $form = $this->createForm(RecipeType::class, $recipe); // Create a new form

        $form->handleRequest($request); // Handle the request
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData(); // Get the data from the form

            $manager->persist($recipe); // Persist the recipe
            $manager->flush(); // Flush the data

            $this->addFlash('success', 'la recette a bien été modifié'); // Add a flash message

            return $this->redirectToRoute('recipe.index'); // Redirect to the index page
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This controller display a form to delete an recipe
     *
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/suppression/{id}', name: 'recipe.delete', methods: ['GET'])]
    public function delete(Recipe $recipe, EntityManagerInterface $manager): Response
    {

        $manager->remove($recipe); // Remove the recipe
        $manager->flush(); // Flush the data

        $this->addFlash('success', 'La recette a bien été supprimée'); // Add a flash message

        return $this->redirectToRoute('recipe.index'); // Redirect to the index page
    }
}
