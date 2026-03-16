<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/playlists')]
class PlaylistsAdminController extends AbstractController
{
    private $playlistRepository;
    private $formationRepository;
    private $categorieRepository;

    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAllOrderByName('ASC');
                break;
        }

        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/new', name: 'admin.playlists.new')]
    public function new(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
    
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->playlistRepository->add($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    return $this->render('admin/playlists/form.html.twig', [
        'formPlaylist' => $form->createView(),
        'mode' => 'Ajout',
        'playlist' => $playlist
    ]);
    }

    #[Route('/edit/{id}', name: 'admin.playlists.edit')]
    public function edit($id, Request $request): Response
    {
    $playlist = $this->playlistRepository->find($id);

    if (!$playlist) {
        return $this->redirectToRoute('admin.playlists');
    }

    $form = $this->createForm(PlaylistType::class, $playlist);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->playlistRepository->add($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    return $this->render('admin/playlists/form.html.twig', [
        'formPlaylist' => $form->createView(),
        'mode' => 'Modification',
        'playlist' => $playlist
    ]);
}

    #[Route('/delete/{id}', name: 'admin.playlists.delete')]
    public function delete($id): Response
    {
        $playlist = $this->playlistRepository->find($id);

        if ($playlist && $playlist->getNbFormations() === 0) {
            $this->playlistRepository->remove($playlist);
        }

        return $this->redirectToRoute('admin.playlists');
    }
}