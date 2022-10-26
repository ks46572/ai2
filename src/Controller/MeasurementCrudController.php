<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Form\MeasurementType;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/measurement/crud')]
class MeasurementCrudController extends AbstractController
{
    #[Route('/', name: 'app_measurement_crud_index', methods: ['GET'])]
    public function index(MeasurementRepository $measurementRepository): Response
    {
        return $this->render('measurement_crud/index.html.twig', [
            'measurements' => $measurementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_measurement_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MeasurementRepository $measurementRepository): Response
    {
        $measurement = new Measurement();
        $form = $this->createForm(MeasurementType::class, $measurement, ["validation_groups" => "new"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $measurementRepository->save($measurement, true);

            return $this->redirectToRoute('app_measurement_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('measurement_crud/new.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurement_crud_show', methods: ['GET'])]
    public function show(Measurement $measurement): Response
    {
        return $this->render('measurement_crud/show.html.twig', [
            'measurement' => $measurement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_measurement_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        $form = $this->createForm(MeasurementType::class, $measurement, ["validation_groups" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $measurementRepository->save($measurement, true);

            return $this->redirectToRoute('app_measurement_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('measurement_crud/edit.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_measurement_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measurement->getId(), $request->request->get('_token'))) {
            $measurementRepository->remove($measurement, true);
        }

        return $this->redirectToRoute('app_measurement_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
