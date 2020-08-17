<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Resimler;
use App\Form\ResimlerType;
use App\Repository\ProductRepository;
use App\Repository\ResimlerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/urunresimleri")
 */
class ResimlerController extends AbstractController
{
    /**
     * @Route("/", name="resimler_index", methods="GET")
     */
    public function index(ResimlerRepository $resimlerRepository,ProductRepository $productRepository): Response
    {
        return $this->render('admin/products/resimler/index.html.twig', [
            'resimlers' => $resimlerRepository->findAll(),
            'products' => $productRepository->findAll()
            ]);
    }

    /**
     * @Route("/new", name="resimler_new", methods="GET|POST")
     */
    public function new(Request $request,ProductRepository $productRepository): Response
    {

        $id = $request->query->get('id');
        $product = $productRepository->findBy(['id'=> $id]);
        $resimler = new Resimler();
        $form = $this->createForm(ResimlerType::class, $resimler);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

              $file = $request->files->get('resimyolu');
              $filename = $this->generateUniqueFileName() .'.'.$file->guessExtension();
              try{

                  $file->move(
                      $this->getParameter('image_directory'),
                      $filename
                  );

              }catch(FileException $e){

            }
            $productid = strval($product[0]->getId());
            $resimler->setResim($filename);
            $resimler->setUrunid($productid);

            $em = $this->getDoctrine()->getManager();
            $em->persist($resimler);
            $em->flush();
            $this->addFlash('success','Resim başarı ile güncellenmiştir!');
            return $this->redirectToRoute('resimler_index');
        }

        return $this->render('admin/products/resimler/new.html.twig', [
            'resimler' => $resimler,
            'pagetitle' => 'Resim Ekleme',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resimler_show", methods="GET")
     */
    public function show(Resimler $resimler): Response
    {
        return $this->render('admin/products/resimler/show.html.twig', [
            'resimler' => $resimler,
            'pagetitle' => 'Resim'
            ]);
    }

    /**
     * @Route("/{id}/edit", name="resimler_edit", methods="GET|POST")
     */
    public function edit(Request $request, Resimler $resimler): Response
    {
        $form = $this->createForm(ResimlerType::class, $resimler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('resimyolu');
            $filename = $this->generateUniqueFileName() .'.'.$file->guessExtension();
            try{

                $file->move(
                    $this->getParameter('image_directory'),
                    $filename
                );

            }catch(FileException $e){

            }
            $resimler->setResim($filename);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Resim başarı ile güncellenmiştir!');
            return $this->redirectToRoute('resimler_index', ['id' => $resimler->getId()]);
        }

        return $this->render('admin/products/resimler/edit.html.twig', [
            'resimler' => $resimler,
            'pagetitle' => 'Resim Düzenleme',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resimler_delete", methods="DELETE")
     */
    public function delete(Request $request, Resimler $resimler): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resimler->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($resimler);
            $em->flush();
            $this->addFlash('success','Resim başarı ile silinmiştir!');
        }

        return $this->redirectToRoute('resimler_index');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName(){

        return md5(uniqid());

    }
}
