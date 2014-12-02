<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $alert = new Alert();

        $form = $this->createFormBuilder($alert)
            ->add('email', 'text')
            ->add('min', 'number')
            ->add('max', 'number')
            ->add('save', 'submit', array('label' => 'Create Alert'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alert);
            $em->flush();

            return $this->redirect('/');
        }
        return $this->render('AppBundle:Default:index.html.twig', ['form' => $form->createView()]);
    }
}
