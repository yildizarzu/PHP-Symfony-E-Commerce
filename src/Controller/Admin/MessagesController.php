<?php

namespace App\Controller\Admin;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/messages")
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/", name="messages_index", methods="GET")
     */
    public function index(MessagesRepository $messagesRepository): Response
    {
        return $this->render('admin/messages/index.html.twig', ['messages' => $messagesRepository->findAll()]);
    }



    /**
     * @Route("/{id}", name="messages_show", methods="GET")
     */
    public function show(Messages $message): Response
    {
        return $this->render('admin/messages/show.html.twig', ['message' => $message]);
    }


    /**
     * @Route("/{id}/delete", name="messages_delete")
     */
    public function delete(Request $request, Messages $message): Response
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
            $this->addFlash('success','Mesaj başarı ile silinmiştir!');

        return $this->redirectToRoute('messages_index');
    }
}
