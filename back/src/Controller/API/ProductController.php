<?php

namespace App\Controller\API;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    
    #[Route('/api/products', name: 'app_product', methods: ['GET'])]
    public function getAllProducts(ProductRepository $repo): JsonResponse {
        $products = $repo->findAll();
        return $this->json($products);
    }
    #[Route('/api/products/{id}', name: 'id_product', methods: ['GET'])]
    public function getProduct(int $id, ProductRepository $repo): JsonResponse {
        $product = $repo->find($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($product);
    }
    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $repo): JsonResponse {
        $product = $repo->find($id);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }
        $repo->remove($product, true);
        return $this->json(['message' => 'Product deleted successfully']);
    }

    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(int $id, Request $request, ProductRepository $repo): JsonResponse {
        $product = $repo->find($id);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setImage($data['image']);
        $product->setCategory($data['category']);
        $product->setPrice($data['price']);
        $product->setQuantity($data['quantity']);
        $product->setInternalReference($data['internal_reference']);
        $product->setShellId($data['shell_id']);
        $product->setInventoryStatus($data['inventory_status']);
        $product->setRating($data['rating']);
        $product->setCreatedAt(new \DateTimeImmutable($data['created_at']));
        $product->setUpdatedAt(new \DateTimeImmutable($data['updated_at']));
        $repo->save($product, true);
        return $this->json(['message' => 'Product updated successfully']);
    }
    #[Route('/api/products', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request, ProductRepository $repo): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setImage($data['image']);
        $product->setCategory($data['category']);
        $product->setPrice($data['price']);
        $product->setQuantity($data['quantity']);
        $product->setInternalReference($data['internal_reference']);
        $product->setShellId($data['shell_id']);
        $product->setInventoryStatus($data['inventory_status']);
        $product->setRating($data['rating']);
        $product->setCreatedAt(new \DateTimeImmutable($data['created_at']));
        $product->setUpdatedAt(new \DateTimeImmutable($data['updated_at']));
        $repo->save($product, true);
        return $this->json(['message' => 'Product created successfully']);
    }
}
