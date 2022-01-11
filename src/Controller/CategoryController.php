<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(ManagerRegistry $doctrine): Response
    {   

        $categorias = $doctrine->getRepository(Category::class)->findAll();
        return $this->json([
            'status' => true,
            'categorias' => $categorias
        ]);
    }


    #[Route('/category/resgistro', name: 'categoryRegister', methods:"POST")]
    public function registro(ManagerRegistry $doctrine, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);    
        $entityManager = $doctrine->getManager();
        //new \DateTime('now')

        $categoria = new Category();
        $categoria->setName($data["name"]);
        $categoria->setActive($data["active"]);
        $categoria->setCreatedAt(new \DateTime('now'));
        $categoria->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($categoria);
        $entityManager->flush();

        
        return $this->json([
            'categoria' => $categoria,
            //'llega'=> $data
        ]);
    }

    #[Route('/category/update', name: 'categoryUpdate', methods:"PUT")]
    public function update(ManagerRegistry $doctrine, Request $request): Response
    {
        //$data = json_decode($request->getContent(), true);    
        //$entityManager = $doctrine->getManager();
        //new \DateTime('now')

        // $categoria = new Category();
        // $categoria->setName($data["name"]);
        // $categoria->setActive($data["active"]);
        // $categoria->setCreatedAt(new \DateTime('now'));
        // $categoria->setUpdatedAt(new \DateTime('now'));

        // $entityManager->persist($categoria);
        // $entityManager->flush();

        
        return $this->json([
            //'categoria' => $categoria,
            'llega'=> 'a update category'
        ]);
    }
}
