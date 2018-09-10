<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rehearsal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Rehearsal controller.
 *
 * @Route("rehearsal")
 */
class RehearsalController extends Controller
{
    /**
     * Lists all rehearsal entities.
     *
     * @Route("/", name="rehearsal_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $rehearsals = $em->getRepository('AppBundle:Rehearsal')
            ->findBy(
                array('choir' => $choir),
                array('id' => 'ASC')
            );

        return $this->render('rehearsal/index.html.twig', array(
            'choir' => $choir,
            'rehearsals' => $rehearsals,
        ));
    }

    /**
     * new rehearsal page.
     *
     * @Route("/new", name="rehearsal_new")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('rehearsal/new.html.twig', ['choir' => $choir]);
    }

    /**
     * edit rehearsal page.
     *
     * @Route("/edit/{id}", name="rehearsal_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($id);
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('rehearsal/edit.html.twig', ['choir' => $choir, 'rehearsal' => $rehearsal]);
    }

    /**
     * Finds and displays a rehearsal entity.
     *
     * @Route("/{id}", name="rehearsal_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Rehearsal $rehearsal)
    {
        $em = $this->getDoctrine()->getManager();

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('rehearsal/show.html.twig', array(
            'rehearsal' => $rehearsal,
            'choir' => $choir,
        ));
    }

    /**
     * show page for deleting a rehearsal.
     *
     * @Route("/delete/{id}", name="rehearsal_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($id);

        return $this->render('rehearsal/delete.html.twig', array(
            'rehearsal' => $rehearsal,
        ));
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

}
