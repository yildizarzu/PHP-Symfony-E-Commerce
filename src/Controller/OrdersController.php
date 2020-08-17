<?php

namespace App\Controller;

use App\Entity\OrderDetail;
use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\CategoriesRepository;
use App\Repository\OrderDetailRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\ShopcartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("home/orders")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/", name="orders_index", methods="GET")
     */
    public function index(OrdersRepository $ordersRepository,CategoriesRepository $categoriesRepository,ShopcartRepository $shopcartRepository): Response
    {
        $user = $this->getUser();
        $shopcart =  $shopcartRepository->urunler($user->getId());
        $amount = $shopcartRepository->toplam($user->getId());
        if(count($shopcart) == 0){$cartstatus = 0;}
        else{$cartstatus = 1;}
        $orders = $ordersRepository->findBy(['userid'=>$user->getId()]);
        if($orders){$ctitle= "Siparişlerim";}
        else{$ctitle = "Siparişiniz Bulunmamaktadır";}
        return $this->render('orders/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'orders' => $orders,
            'shopcart' => $shopcart,
            'amount' => $amount,
            'cartstatus' => $cartstatus,
            'contenttitle'=> $ctitle,
        ]);
    }

    /**
     * @Route("/new", name="orders_new", methods="GET|POST")
     */
    public function new(Request $request,CategoriesRepository $categoriesRepository,ShopcartRepository $shopcartRepository,ProductRepository $productRepository): Response
    {

            $user = $this->getUser();
            $shopcart =  $shopcartRepository->urunler($user->getId());
            $amount = $shopcartRepository->toplam($user->getId());
            if(count($shopcart) == 0){$cartstatus = 0;}
            else{$cartstatus = 1;}


            $order = new Orders();
            $form = $this->createForm(OrdersType::class, $order);
            $form->handleRequest($request);


            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
                $orderid = $order->getId();
                $shopcart = $shopcartRepository->urunler($user->getId());


                foreach($shopcart as $item){

                    $orderdetail = new OrderDetail();

                    $orderdetail->setOrderid($orderid);
                    $orderdetail->setUserid($user->getId());
                    $orderdetail->setProductid($item["productid"]);
                    $orderdetail->setPrice($item["fiyat"]);
                    $orderdetail->setQuantity($item["quantity"]);
                    $orderdetail->setAmount($item["total"]);
                    $orderdetail->setName($item["urunadi"]);
                    $orderdetail->setStatus("Ordered");

                    $em->persist($orderdetail);
                    $em->flush();

                }

                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery('DELETE FROM App\Entity\Shopcart s WHERE s.userid=:id')->setParameter('id',$user->getId());
                $query->execute();


                return $this->redirectToRoute('orders_index');
            }

            return $this->render('orders/new.html.twig', [
                'order' => $order,
                'form' => $form->createView(),
                'user' => $user,
                'amount' => $amount,
                'shopcart' => $shopcart,
                'cartstatus' => $cartstatus,
                'contenttitle' => 'Alışveriş ',
                'categories' => $categoriesRepository->findAll(),

            ]);

    }

    /**
     * @Route("/{id}", name="orders_show", methods="GET")
     */
    public function show(Orders $order,CategoriesRepository $categoriesRepository,ShopcartRepository $shopcartRepository,OrderDetailRepository $detailRepository): Response
    {
        $user = $this->getUser();
        $shopcart =  $shopcartRepository->urunler($user->getId());
        $amount = $shopcartRepository->toplam($user->getId());
        if(count($shopcart) == 0){$cartstatus = 0;}
        else{$cartstatus = 1;}

        $details = $detailRepository->findBy(['userid' => $user->getId()]);

        return $this->render('orders/show.html.twig', [
            'order' => $order,
            'amount' => $amount,
            'shopcart' => $shopcart,
            'cartstatus' => $cartstatus,
            'details' => $details,
            'categories' => $categoriesRepository->findAll(),
            'contenttitle' => 'Sipariş Detayı',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="orders_edit", methods="GET|POST")
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_index', ['id' => $order->getId()]);
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_delete", methods="DELETE")
     */
    public function delete(Request $request, Orders $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($order);
            $em->flush();
        }

        return $this->redirectToRoute('orders_index');
    }
}
