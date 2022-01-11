<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use App\Entity\Category;

class ProductController extends AbstractController
{   


    #[Route('/product', name: 'product')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $productos = $doctrine->getRepository(Product::class)->findAll();

        return $this->json([
            'status' => true,
            'productos' => $productos
        ]);
    }


    #[Route('/product/get/{id}', name: 'productGet', methods:"GET")]
    public function getProducto(ManagerRegistry $doctrine, $id): Response
    {
        $producto = $doctrine->getRepository(Product::class)->find($id);

        return $this->json([
            'status' => true,
            'producto' => $producto
        ]);
    }

    

    #[Route('/product/resgistro', name: 'productRegistro', methods:"POST")]
    public function registro(ManagerRegistry $doctrine, Request $request): Response
    {
         $data = json_decode($request->getContent(), true);    
         $entityManager = $doctrine->getManager();         

        $producto = new Product();
        $producto->setCode($data["code"]);
        $producto->setName($data["name"]);
        $producto->setDescription($data["description"]);
        $producto->setBrand($data["brand"]);
        $producto->setPrice($data["price"]);
        $producto->setCategory($doctrine->getRepository(Category::class)->find($data["category_id"]));        
        $producto->setCreatedAt(new \DateTime('now'));
        $producto->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($producto);
        $entityManager->flush();

        
        return $this->json([
            'producto' => $producto
        ]);
    }

    #[Route('/product/update', name: 'productUpdate', methods:"PUT")]
    public function update(ManagerRegistry $doctrine, Request $request): Response
    {
          $data = json_decode($request->getContent(), true);    
          $producto = $doctrine->getRepository(Product::class)->find($data["id"]);
          $entityManager = $doctrine->getManager();         

        // $producto = new Product();
        $producto->setCode($data["code"]);
        $producto->setName($data["name"]);
        $producto->setDescription($data["description"]);
        $producto->setBrand($data["brand"]);
        $producto->setPrice($data["price"]);
        //$producto->setCategory($doctrine->getRepository(Category::class)->find($data["category_id"]));        
        //$producto->setCreatedAt(new \DateTime('now'));
        $producto->setUpdatedAt(new \DateTime('now'));
        // $entityManager->remove($product);
        $entityManager->persist($producto);
        $entityManager->flush();

        
        return $this->json([
            'producto' => $producto
        ]);
    }

    #[Route('/product/delete/{id}', name: 'productDelete', methods:"DELETE")]
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        $producto = $doctrine->getRepository(Product::class)->find($id);
        if($producto == null){
            return $this->json([
                'message' => 'el producto no existe',
                'status' => false
            ]);
        }

        $entityManager = $doctrine->getManager();   
        $entityManager->remove($producto);
        $entityManager->flush();  

        return $this->json([
            'message' => 'producto eliminado con exito',
            'status' => true
        ]);
    }

    

    #[Route('/product/filtro/{id}', name: 'productFiltro')]
    public function filtro(ManagerRegistry $doctrine, $id): Response
    {
        if($id != 0){
            $categoria = $doctrine->getRepository(Category::class)->find($id);
            $producto = $doctrine->getRepository(Product::class)->findBy(['category' => $categoria]);

            return $this->json([
                'status' => true,
                'productos' => $producto
            ]);

        }else{
            $producto = $doctrine->getRepository(Product::class)->findAll();
            return $this->json([
                'status' => true,
                'productos' => $producto
            ]);
        }

    }


    
}
