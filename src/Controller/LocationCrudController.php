<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/location/crud')]
class LocationCrudController extends AbstractController
{
    #[Route('/', name: 'app_location_crud_index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location_crud/index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_location_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocationRepository $locationRepository): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location, ["validation_groups" => "new"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locationRepository->save($location, true);

            return $this->redirectToRoute('app_location_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location_crud/new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_location_crud_show', methods: ['GET'])]
    public function show(Location $location): Response
    {
        return $this->render('location_crud/show.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_location_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Location $location, LocationRepository $locationRepository): Response
    {
        $form = $this->createForm(LocationType::class, $location, ["validation_groups" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locationRepository->save($location, true);

            return $this->redirectToRoute('app_location_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location_crud/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_location_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Location $location, LocationRepository $locationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $locationRepository->remove($location, true);
        }

        return $this->redirectToRoute('app_location_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
