<?php

namespace App\Controller\Home;

use App\Entity\Messages;
use App\Entity\Shopcart;
use App\Entity\User;
use App\Form\MessagesType;
use App\Form\ShopcartType;
use App\Form\UserType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use App\Repository\ResimlerRepository;
use App\Repository\SettingRepository;
use App\Repository\ShopcartRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoriesRepository $categoriesRepository, ResimlerRepository $resimlerRepository, ProductRepository $productRepository, ShopcartRepository $shopcartRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM product WHERE durum='true' ORDER BY ID DESC LIMIT 4";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $sliders = $statement->fetchAll();


        return $this->render('home/index.html.twig', [

            'categories' => $categoriesRepository->findAll(),
            'contenttitle' => 'Ürünler',
            'resimler' => $resimlerRepository->findAll(),
            'sliders' => $sliders,

        ]);

    }

    /**
     * @Route("/home/aboutus", name="home_aboutus")
     */
    public function aboutus(CategoriesRepository $categoriesRepository,SettingRepository $settingRepository, ProductRepository $productRepository, ShopcartRepository $shopcartRepository)
    {


        $setting = $settingRepository->findAll()[0];
        return $this->render('home/aboutus/aboutus.html.twig', [
            'setting' => $setting,
            'contenttitle' => 'Hakkımızda',
            'categories' => $categoriesRepository->findAll(),
        ]);

    }

    /**
     * @Route("/home/contact", name="home_contact", methods="GET|POST")
     */
    public function contact(CategoriesRepository $categoriesRepository,SettingRepository $settingRepository, ProductRepository $productRepository, ShopcartRepository $shopcartRepository, Request $request): Response
    {

        $setting = $settingRepository->findAll()[0];
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash('success', 'Mesajınız tarafımaza başarılı bir şekilde ulaşmıştır');
            return $this->render('home/contact/contact.html.twig', [
                'setting' => $setting,
                'contenttitle' => 'İletişim',
                'form' => $form->createView(),
                'categories' => $categoriesRepository->findAll(),

            ]);
        }


        return $this->render('home/contact/contact.html.twig', [
            'setting' => $setting,
            'contenttitle' => 'İletişim',
            'form' => $form->createView(),
            'categories' => $categoriesRepository->findAll(),
        ]);

    }

    /**
     * @Route("/home/products/{id}", name="home_products")
     */
    public function products($id, SettingRepository $settingRepository, CategoriesRepository $categoriesRepository, ProductRepository $productRepository, ShopcartRepository $shopcartRepository, ResimlerRepository $resimlerRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM product WHERE durum='true' ORDER BY ID DESC LIMIT 3";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $sliders = $statement->fetchAll();
        $products = $productRepository->findBy(['subcategoryid' => $id]);
        $setting = $settingRepository->findAll()[0];
        return $this->render('home/products/products.html.twig', [
            'setting' => $setting,
            'categories' => $categoriesRepository->findAll(),
            'resimler' => $resimlerRepository->findAll(),
            'products' => $products,
            'sliders' => $sliders,
            'categories' => $categoriesRepository->findAll(),
            'title' => $categoriesRepository->findBy(['id' => $id ])[0],
        ]);


    }

    /**
     * @Route("/home/product/{id}", name="product_detail" , methods = "GET|POST")
     */
    public function productdetail($id,CategoriesRepository $categoriesRepository, ProductRepository $productRepository, ShopcartRepository $shopcartRepository, ResimlerRepository $resimlerRepository, Request $request): Response
    {

        $resimler = $resimlerRepository->findBy(['urunid' => $id]);
        $product = $productRepository->findBy(['id' => $id]);
        $cart = new Shopcart();
        $form = $this->createForm(ShopcartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

            return $this->redirectToRoute('shopcart_index', [
            ]);
        }
        return $this->render('home/products/product-detail.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'resimler' => $resimler,
            'product' => $product[0],
            'form' => $form,
            'cart' => $cart,
        ]);


    }

    /**
     * @Route("/home/newuser/", name="new_user" , methods = "GET|POST")
     */
    public function userSignUp(CategoriesRepository $categoriesRepository,Request $request, UserRepository $userrepository): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('user-form', $submittedToken)) {
            if ($form->isSubmitted()) {
                $emailctrl = $userrepository->findBy(['email' => $request->get("user")]);
                $city = $request->request->get("user");

                if (!$emailctrl && $city["city"] != "") {
                    $user->setRoles(array("ROLE_USER"));
                    $user->setStatus("true");
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Kayıt başarı ile gerçekleşti! Artık giriş yapabilirsiniz.');
                    return $this->redirectToRoute('app_login', []);
                } else {
                    if ($city["city"] == "") {
                        $this->addFlash('error', 'Lütfen bir şehir seçiniz.');
                        return $this->redirectToRoute('new_user', []);
                    } else {
                        $this->addFlash('error', 'Yazmış olduğunuz E-Mail adresi kullanımdadır.');
                        return $this->redirectToRoute('new_user', []);
                    }
                }

            }
        }

        return $this->render('home/security/signup.html.twig', [

            'form' => $form,
            'contenttitle' => 'Kayıt Ol',
            'categories' => $categoriesRepository->findAll(),

        ]);


    }


    /**
     * @Route("/home/myaccount/{id}", name="home_account" , methods = "GET")
     */
    public function myAccount(User $user,CategoriesRepository $categoriesRepository, ShopcartRepository $shopcartRepository): Response
    {

        return $this->render('home/myaccount/myaccount.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'contenttitle' => 'Hesabım',
            'user' => $user

        ]);

    }

    /**
     * @Route("/home/myaccount/edit/{id}", name="account_edit" , methods = "GET|POST")
     */
    public function accountEdit(User $user, CategoriesRepository $categoriesRepository , Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('useredit-form', $submittedToken)) {
            if ($form->isSubmitted()) {
//                dump($request);
//                die();
                $role = $request->request->get('role');
                $user->setRoles(array($role));
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Bilgileriniz başarı ile güncellendi.');
                return $this->redirectToRoute('home', [
                    'categories' => $categoriesRepository->findAll(),
                    'contenttitle' => 'Hesabım',
                    'user' => $user
                ]);
            }
        }


        return $this->render('home/myaccount/accountedit.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'contenttitle' => 'Hesabım',
            'user' => $user

        ]);

    }


}
