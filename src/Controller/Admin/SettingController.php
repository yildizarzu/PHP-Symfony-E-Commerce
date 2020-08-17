<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use App\Form\SettingType;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/setting")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("/", name="setting_index", methods="GET|POST")
     */
    public function index(SettingRepository $settingRepository,Request $request): Response
    {
        $setting = $settingRepository->findBy(['id'=> 1]);
        $form = $this->createForm(SettingType::class, $setting[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Ayarlar başarı ile güncellenmiştir!');
            return $this->redirectToRoute('setting_index');
        }

        return $this->render('admin/setting/index.html.twig',

        [   'setting' => $setting[0],
            'form' => $form->createView() ]
        );
    }

}
