<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Genus;
use AppBundle\Form\GenusFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use \Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class GenusAdminController extends Controller
{

    public function indexAction()
    {
        /*if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            throw $this->createAccessDeniedException('GO AWAY');
        }*/

        $genuses = $this->getDoctrine()
            ->getRepository('AppBundle:Genus')
            ->findAll();

        return $this->render('admin/genus/list.html.twig', array(
            'genuses' => $genuses
        ));
    }


    public function newAction(Request $request)
    {
        $form = $this->createForm(GenusFormType::class);

        //only handle data on post
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
//            dump($form->getData());die;
            $genus = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Genus created - (%s) ', $this->getUser()->getEmail())
            );

            return $this->redirectToRoute('admin_genus_list');
        }

        return $this->render('admin/genus/new.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }

    public function editAction(Request $request, Genus $genus)
    {
        $form = $this->createForm(GenusFormType::class, $genus);

        //only handle data on post
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
//            dump($form->getData());die;
            $genus = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $em->flush();

            $this->addFlash('success', 'A Genus updated Successfully');

            return $this->redirectToRoute('admin_genus_list');
        }

        return $this->render('admin/genus/edit.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }
}