<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/kategoriler")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="kategoriler_index", methods="GET")
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('admin/categories/listele.twig', [
            'categories' => $categoriesRepository->findAll(),
            'title' => 'Kategoriler',
            'pagetitle' => '',

        ]);
    }

    /**
     * @Route("/yeni", name="kategori_ekle", methods="GET|POST")
     */
    public function new(Request $request,CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy(['parentid'=>'0']);
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success','Kategori başarı ile eklenmiştir!');
            return $this->redirectToRoute('kategoriler_index');
        }

        return $this->render('admin/categories/yeni.twig', [
            'title' => 'Kategoriler',
            'pagetitle' => '',
            'category' => $category,
            'cats' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/goster/{id}", name="kategori_goster", methods="GET")
     */
    public function show(Categories $category,CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('admin/categories/goster.twig', [
            'category' => $category,
            'categories' => $categoriesRepository->findAll(),
            'title' => 'Kategoriler',
            'pagetitle' => '',
        ]);
    }

    /**
     * @Route("/{id}/guncelle", name="kategori_guncelle", methods="GET|POST")
     */
    public function edit(Request $request, Categories $category,CategoriesRepository $categoriesRepository): Response
    {

        $categories = $categoriesRepository->findBy(['parentid'=>'0']);
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Kategori başarı ile güncellenmiştir!');
            return $this->redirectToRoute('kategoriler_index');
        }

        return $this->render('admin/categories/guncelle.twig', [
            'title' => 'Kategoriler',
            'pagetitle' => 'Kategori Güncelle',
            'cats' => $categories,
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/sil", name="kategori_sil")
     */
    public function delete(Request $request, Categories $category): Response
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->addFlash('success','Kategori başarı ile silinmiştir!');


        return $this->redirectToRoute('kategoriler_index');
    }
}
