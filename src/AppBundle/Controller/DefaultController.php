<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Util\SecureRandom;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $alert = new Alert();

        $generator = new SecureRandom();
        $rnd = $generator->nextBytes(10);
        $alert->setEnabled(true);
        $alert->setHash(sha1($rnd));
        $form = $this->createFormBuilder($alert)
            ->add('email', 'text')
            ->add('min', 'number')
            ->add('max', 'number')
            ->add('save', 'submit', array('label' => 'Create Alert'))
            ->getForm();

        $form->handleRequest($request);

        $currentTick = $em->getRepository('AppBundle:Tick')->getLast();

        if ($form->isValid()) {

            $em->persist($alert);
            $em->flush();

            return $this->redirect('/');
        }
        return $this->render('AppBundle:Default:index.html.twig', [
                'form' => $form->createView(),
                'lastTick' => $currentTick
            ]
        );
    }
}
