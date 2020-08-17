<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/uyeler")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();


        return $this->render('admin/users/listele.twig', [
            'title' => 'Üye Listesi',
            'pagetitle' => '',
            'users' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('admin/users/goster.twig', [

            'user' => $user,
            'title' => $user->getName(),
            'content' => 'üyegöster',
            'pagetitle' => '',

        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
//        dump($user);
//        die();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('adminuseredit-form', $submittedToken)) {
            if ($form->isSubmitted()) {
                $role = $request->request->get('role');
                $user->setRoles(array($role));
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Üye başarı ile güncellenmiştir!');
                return $this->redirectToRoute('user_index');
            }
        }

        return $this->render('admin/users/guncelle.twig', [
            'title' => 'Üye Güncelle',
            'pagetitle' => 'Üye Güncelle',
            'content' => 'üyegüncelle',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods="GET")
     */
    public function delete(Request $request, User $user): Response
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

        return $this->redirectToRoute('user_index');
    }
}
