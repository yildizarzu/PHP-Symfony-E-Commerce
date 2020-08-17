<?php

namespace App\Controller\Admin;


use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrderDetailRepository;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class adminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.twig', [
            'title' => 'Admin Paneli',
            'pagetitle' => 'Anasayfa',
            'content' => 'anasayfa',
        ]);
    }

    /**
     * @Route("/admin/orders/{slug}", name="admin_orders")
     */
    public function ordersindex($slug,OrdersRepository $ordersRepository)
    {
        $orders = $ordersRepository->findBy(['status' => $slug]);
        switch ($slug){
            case "new":
                $title = "Yeni Siparişler";
                break;
            case "accepted":
                $title = "Onaylanan Siparişler";
                break;
            case "inshipping":
                $title = "Kargodaki Siparişler";
                break;
            case "canceled":
                $title = "İptal Edilen Siparişler";
                break;
            case "completed":
                $title = "Tamamlanan Siparişler";
                break;

        }


        return $this->render('admin/orders/index.html.twig', [
            'title' => $title,
            'pagetitle' => 'Siparişler',
            'content' => 'anasayfa',
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/admin/orders/show/{id}", name="admin_order_show", methods="GET|POST")
     */
    public function ordershow($id,Orders $orders,OrderDetailRepository $detailRepository,Request $request): Response
    {
        $details = $detailRepository->findBy(['orderid'=> $orders->getId()]);

        $form = $this->createForm(OrdersType::class, $orders);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Ayarlar başarı ile güncellenmiştir!');
            return $this->redirectToRoute('admin_order_show', ['id' => $orders->getId()]);
        }

        return $this->render('admin/orders/show.html.twig', [
            'title' => 'Admin Paneli',
            'pagetitle' => 'Siparişler',
            'content' => 'anasayfa',
            'order' => $orders,
            'details' => $details,
            'form' => $form->createView(),
        ]);
    }




}
