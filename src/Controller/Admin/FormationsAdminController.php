<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/formations')]
class FormationsAdminController extends AbstractController
{
    private $formationRepository;
    private $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/new', name: 'admin.formations.new')]
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/formations/form.html.twig', [
            'formFormation' => $form->createView(),
            'mode' => 'Ajout'
        ]);
    }

    #[Route('/edit/{id}', name: 'admin.formations.edit')]
    public function edit($id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if (!$formation) {
            return $this->redirectToRoute('admin.formations');
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/formations/form.html.twig', [
            'formFormation' => $form->createView(),
            'mode' => 'Modification'
        ]);
    }

    #[Route('/delete/{id}', name: 'admin.formations.delete')]
    public function delete($id): Response
    {
        $formation = $this->formationRepository->find($id);

        if ($formation) {
            $this->formationRepository->remove($formation);
        }

        return $this->redirectToRoute('admin.formations');
    }
}