<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Choir;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Choir controller.
 *
 * @Route("choir")
 */
class ChoirController extends Controller
{
    /**
     * Lists all choir entities.
     *
     * @Route("/", name="choir_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->user();

        $choirs = $em->getRepository('AppBundle:Choir')
            ->findBy(
                array('user' => $user),
                array('id' => 'ASC')
            );

        return $this->render('choir/index.html.twig', array(
            'choirs' => $choirs,
        ));
    }

    /**
     * show page for adding a choir.
     *
     * @Route("/new", name="choir_new")
     * @Method("GET")
     */
    public function newAction()
    {
        return $this->render('choir/new.html.twig');
    }

    /**
     * show page for editing a choir.
     *
     * @Route("/edit/{id}", name="choir_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $choir = $em->getRepository('AppBundle:Choir')->find($id);

        return $this->render('choir/edit.html.twig', array(
            'choir' => $choir,
        ));
    }

    /**
     * show page for deleting a choir.
     *
     * @Route("/delete/{id}", name="choir_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $choir = $em->getRepository('AppBundle:Choir')->find($id);

        return $this->render('choir/delete.html.twig', array(
            'choir' => $choir,
        ));
    }

    /**
     * Finds and displays a choir entity.
     *
     * @Route("/{id}", name="choir_show")
     * @Method("GET")
     */
    public function showAction(Choir $choir)
    {

        return $this->render('choir/show.html.twig', array(
            'choir' => $choir,
        ));
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }
    
}
