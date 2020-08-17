<?php

namespace App\Controller\Home;

use App\Entity\Shopcart;
use App\Form\ShopcartType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use App\Repository\ShopcartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("home/shopcart")
 */
class ShopcartController extends AbstractController
{
    /**
     * @Route("/", name="shopcart_index", methods="GET")
     */
    public function index(ShopcartRepository $shopcartRepository,ProductRepository $productRepository,CategoriesRepository $categoriesRepository): Response
    {
            $user = $this->getUser();
            $shopcart =  $shopcartRepository->urunler($user->getId());
            $amount = $shopcartRepository->toplam($user->getId());
            if(count($shopcart) == 0){$cartstatus = 0;}
            else{$cartstatus = 1;}


            return $this->render('home/shopcart/index.html.twig', [
                'shopcart' => $shopcart,
                'amount' => $amount,
                'cartstatus' => $cartstatus,
                'contenttitle' => 'Sepetinizdeki Ürünler',
                'categories' => $categoriesRepository->findAll(),

            ]);

    }


    /**
     * @Route("/{id}", name="shopcart_show", methods="GET")
     */
    public function show(Shopcart $shopcart): Response
    {
        return $this->render('home/shopcart/show.html.twig', ['shopcart' => $shopcart]);
    }

//    /**
//     * @Route("/{id}/edit", name="shopcart_edit", methods="GET|POST")
//     */
//    public function edit(Request $request, Shopcart $shopcart): Response
//    {
//        $form = $this->createForm(ShopcartType::class, $shopcart);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('shopcart_index', ['id' => $shopcart->getId()]);
//        }
//
//        return $this->render('home/shopcart/edit.html.twig', [
//            'shopcart' => $shopcart,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/{id}", name="shopcart_delete", methods="DELETE")
     */
    public function delete(Request $request, Shopcart $shopcart): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shopcart);
            $em->flush();

        return $this->redirectToRoute('shopcart_index');
    }




}
