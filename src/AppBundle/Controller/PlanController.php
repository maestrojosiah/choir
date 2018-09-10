<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Plan;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Plan controller.
 *
 * @Route("plan")
 */
class PlanController extends Controller
{
    /**
     * Lists all plan entities.
     *
     * @Route("/", name="plan_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $rehearsal_id = $request->query->get('r');
        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($rehearsal_id);
        $plans = $em->getRepository('AppBundle:Plan')
            ->findBy(
                array('choir' => $choir, 'rehearsal' => $rehearsal),
                array('id' => 'ASC')
            );

        return $this->render('plan/index.html.twig', array(
            'choir' => $choir,
            'rehearsal' => $rehearsal,
            'plans' => $plans,
        ));
    }

    /**
     * new plan page.
     *
     * @Route("/new", name="plan_new")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $rehearsal_id = $request->query->get('r');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($rehearsal_id);
        return $this->render('plan/new.html.twig', ['choir' => $choir, 'rehearsal' => $rehearsal]);
    }

    /**
     * edit plan page.
     *
     * @Route("/edit/{id}", name="plan_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $plan = $em->getRepository('AppBundle:Plan')->find($id);
        $rehearsal_id = $request->query->get('r');
        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($rehearsal_id);
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('plan/edit.html.twig', ['choir' => $choir, 'plan' => $plan, 'rehearsal' => $rehearsal]);
    }

    /**
     * Finds and displays a plan entity.
     *
     * @Route("/{id}", name="plan_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Plan $plan)
    {
        $em = $this->getDoctrine()->getManager();
        
        $rehearsal_id = $request->query->get('r');
        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($rehearsal_id);

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('plan/show.html.twig', array(
            'plan' => $plan,
            'choir' => $choir,
            'rehearsal' => $rehearsal,
        ));
    }

    /**
     * show page for deleting a plan.
     *
     * @Route("/delete/{id}", name="plan_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $plan = $em->getRepository('AppBundle:Plan')->find($id);

        return $this->render('plan/delete.html.twig', array(
            'plan' => $plan,
        ));
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

}
