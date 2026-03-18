<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories')]
class CategoriesAdminController extends AbstractController
{
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/add', name: 'admin.categories.add')]
    public function add(Request $request): Response
    {
        $name = $request->get('name');

        if ($name) {
            // Vérifie si existe déjà
            $existing = $this->categorieRepository->findOneBy(['name' => $name]);

            if (!$existing) {
                $categorie = new Categorie();
                $categorie->setName($name);
                $this->categorieRepository->add($categorie);
            }
        }

        return $this->redirectToRoute('admin.categories');
    }

    #[Route('/delete/{id}', name: 'admin.categories.delete')]
    public function delete($id): Response
    {
        $categorie = $this->categorieRepository->find($id);

        if ($categorie && count($categorie->getFormations()) === 0) {
            $this->categorieRepository->remove($categorie);
        }

        return $this->redirectToRoute('admin.categories');
    }
}