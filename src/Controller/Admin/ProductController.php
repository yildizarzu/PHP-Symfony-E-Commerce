<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use App\Repository\ResimlerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods="GET")
     */
    public function index(ProductRepository $productRepository, CategoriesRepository $categoriesRepository): Response
    {

        return $this->render('admin/products/product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categoriesRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     */
    public function new(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('products-form', $submittedToken)) {
            if ($form->isSubmitted()) {
                $catid = $categoriesRepository->findBy(['id' => $request->request->get('product')['subcategoryid']])[0];
                $product->setCategoryid($catid->getParentid());
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                $this->addFlash('success', 'Ürün başarı ile eklenmiştir!');
                return $this->redirectToRoute('product_index');
            }
        }
        return $this->render('admin/products/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'categories' => $categoriesRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function show(Product $product, ResimlerRepository $resimlerRepository,CategoriesRepository $categoriesRepository): Response
    {

        $resimler = $resimlerRepository->findBy(['urunid' => strval($product->getId())]);
        return $this->render('admin/products/product/show.html.twig', [

            'product' => $product,
            'resimler' => $resimler,
            'categories' => $categoriesRepository->findAll()

        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     */
    public function edit(Request $request, Product $product, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $catid = $categoriesRepository->findBy(['id' => $request->request->get('product')['subcategoryid']])[0];
            $product->setCategoryid($catid->getParentid());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Ürün başarı ile güncellenmiştir!');
            return $this->redirectToRoute('product_index', ['id' => $product->getId()]);
        }

        return $this->render('admin/products/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'categories' => $categoriesRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
            $this->addFlash('success','Ürün başarı ile silinmiştir!');

        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/gallery/{id}", name="product_gallery", methods="GET|POST")
     */
    public function gallery(Request $request, Product $product, ResimlerRepository $resimlerRepository): Response
    {
        $id = $product->getId();
        $resimler = $resimlerRepository->findBy(['urunid' => strval($id)]);
        return $this->render('admin/products/product/gallery.html.twig', [

            'resimler' => $resimler

        ]);


    }
}
