<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chorister;
use AppBundle\Entity\Rehearsal;
use AppBundle\Entity\Mark;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Attendance controller.
 *
 * @Route("attendance")
 */
class AttendanceController extends Controller
{
    /**
     * Lists all chorister entities.
     *
     * @Route("/", name="attendance_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $voice = $request->query->get('v');
        $rehearsal_id = $request->query->get('r');
        $rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($rehearsal_id);
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $choristers = $em->getRepository('AppBundle:Chorister')
            ->findBy(
                array('choir' => $choir, 'voice' => $voice),
                array('id' => 'ASC')
            );
        $chorister_list = [];
        foreach ($choristers as $key => $chorister) {
            $marked = $em->getRepository('AppBundle:Mark')
              ->findBy(
                array('chorister' => $chorister, 'rehearsal' => $rehearsal),
                array('id' => 'ASC')
              );
            $chorister_list[] = [$chorister, $marked];
        }
        
        return $this->render('attendance/index.html.twig', array(
            'choir' => $choir,
            'choristers' => $chorister_list,
            'voice' => $voice,
            'rehearsal' => $rehearsal,
        ));
    }

    /**
     * Lists all rehearsal entities.
     *
     * @Route("/list/rehearsals", name="rehearsal_list")
     * @Method("GET")
     */
    public function rehearsalsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $rehearsals = $em->getRepository('AppBundle:Rehearsal')
            ->findBy(
                array('choir' => $choir),
                array('id' => 'ASC')
            );

        return $this->render('attendance/rehearsals.html.twig', array(
            'choir' => $choir,
            'rehearsals' => $rehearsals,
        ));
    }

    /**
     * edit mark entry.
     *
     * @Route("/edit/{id}", name="mark_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mark = $em->getRepository('AppBundle:Mark')->find($id);
        return $this->render('attendance/edit.html.twig', ['mark' => $mark]);
    }

    /**
     * show page for deleting a mark.
     *
     * @Route("/delete/{id}", name="mark_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $mark = $em->getRepository('AppBundle:Mark')->find($id);

        return $this->render('attendance/delete.html.twig', array(
            'mark' => $mark,
        ));
    }


    /**
     * Lists all voices entities.
     *
     * @Route("/voices", name="attendance_voices")
     * @Method("GET")
     */
    public function voicesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $today = \DateTime::createFromFormat('Y-m-d H:i:s',  date("Y-m-d").'00:00:00');

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        if(null !== $request->query->get('r')){
        	$rehearsal_id = $request->query->get('r');
        	$rehearsal = $em->getRepository('AppBundle:Rehearsal')->find($rehearsal_id);
        } else {
	        $rehearsal = $em->getRepository('AppBundle:Rehearsal')
	        	->findOneFutureRehearsal($today, $choir);
        }

        $today = \DateTime::createFromFormat('Y-m-d',  date("Y-m-d"));

        $choristers = $em->getRepository('AppBundle:Chorister')
            ->findBy(
                array('choir' => $choir),
                array('id' => 'ASC')
            );


        $voices = [];
        foreach ($choristers as $key => $chorister) {
            $voices[$chorister->getVoice()] = $chorister->getVoice();
        }
        return $this->render('attendance/voices.html.twig', array(
            'choir' => $choir,
            'voices' => $voices,
            'rehearsal' => $rehearsal,
        ));
    }

    /**
     * Finds and displays a chorister entity.
     *
     * @Route("/{id}", name="attendance_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Chorister $chorister)
    {
        $em = $this->getDoctrine()->getManager();

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('attendance/show.html.twig', array(
            'chorister' => $chorister,
            'choir' => $choir,
        ));
    }

    /**
     *
     * @Route("/list/att", name="attendance_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //select a range instead of fetching all
        $choir_id = $request->query->get('c');
        $chorister_id = $request->query->get('ch');

        $now = new \DateTime(date("Y-m-d H:i:s"));
        $today = new \DateTime(date("Y-m-d H:i:s"));
        $month_ago = $today->modify("first day of this month");
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);

        if($request->query->get('s') !== null){
            $start = $request->query->get('s');
            $end = $request->query->get('e');
            $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', $request->query->get('s')." 00:00:00");
            $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s', $request->query->get('e')." 00:00:00");
            list($start, $end) = $this->includeFirstAndLastDay($prepared_starttime, $prepared_endtime);
            $rehearsals = $this->em()->getRepository('AppBundle:Rehearsal')
              ->findPreviousAndCurrentRehearsalsWithRange($start, $end, $choir);
        } else {
            list($start, $end) = $this->includeFirstAndLastDay($month_ago, $now);
            $rehearsals = $this->em()->getRepository('AppBundle:Rehearsal')
              ->findPreviousAndCurrentRehearsals($end, $start, $choir);
        }

        $chorister = $em->getRepository('AppBundle:Chorister')->find($chorister_id);

        $bulk_info = [];

        foreach ($rehearsals as $key => $rehearsal) {
            // range,
            // rehearsal plans
            $marking = $this->em()->getRepository('AppBundle:Mark')
              ->findOneBy(
                array('rehearsal' => $rehearsal, 'chorister' => $chorister),
                array('id' => 'ASC')
              );

            if($marking){
                $plans_for_this_rehearsal = $rehearsal->getPlans();

                $time_arrived = $marking->getTimein();
                $time_left = $marking->getTimeOut();

                $plan_found = $this->songInProgress($rehearsal, $chorister, "In");
                $plan_left = $this->songInProgress($rehearsal, $chorister, "Out");
                $full_plans_attended = $this->attendedFullPlans($plans_for_this_rehearsal, $time_arrived, $time_left);
                $fully_missed_plans = $this->completelyMissedPlans($plans_for_this_rehearsal, $time_arrived, $time_left);
                $found_song_minutes_done = $this->sangSongDuration($plan_found, $time_arrived, "In");
                $left_song_minutes_done = $this->sangSongDuration($plan_left, $time_left, "Out");

                $bulk_info[$rehearsal->getId()] = ['rehearsal' => $rehearsal, 'plans' => $plans_for_this_rehearsal,
                                                    'time_in' => $time_arrived, 'time_out' => $time_left, 
                                                    'plan_found' => $plan_found, 'plan_left' => $plan_left, 
                                                    'plans_attended' => $full_plans_attended, 'plans_missed' => $fully_missed_plans, 
                                                    'found_done' => $found_song_minutes_done, 'left_done' => $left_song_minutes_done
                                                ];
            } else {
                $plans_for_this_rehearsal = $rehearsal->getPlans();
                $bulk_info[$rehearsal->getId()] = ['rehearsal' => $rehearsal, 'plans' => $plans_for_this_rehearsal,
                                                    'time_in' => null, 'time_out' => null, 
                                                    'plan_found' => null, 'plan_left' => null, 
                                                    'plans_attended' => null, 'plans_missed' => null, 
                                                    'found_done' => null, 'left_done' => null
                                                ];

            }
            
            return $this->render('chorister/attendance.html.twig', array(
                'chorister' => $chorister,
                'choir' => $choir,
                'bulk_info' => $bulk_info,
            ));

        }
    }

    /**
     *
     * @Route("/list/song/att", name="song_attendance_list")
     * @Method("GET")
     */
    public function songListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //select a range instead of fetching all
        $choir_id = $request->query->get('c');
        $song_id = $request->query->get('sng');
        $song = $this->em()->getRepository('AppBundle:Song')->find($song_id);

        $now = new \DateTime(date("Y-m-d H:i:s"));
        $today = new \DateTime(date("Y-m-d H:i:s"));
        $month_ago = $today->modify("first day of this month");
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);

        if($request->query->get('s') !== null){
            $start = $request->query->get('s');
            $end = $request->query->get('e');
            $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', $request->query->get('s')." 00:00:00");
            $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s', $request->query->get('e')." 00:00:00");
            list($start, $end) = $this->includeFirstAndLastDay($prepared_starttime, $prepared_endtime);
            $plans = $this->em()->getRepository('AppBundle:Plan')
              ->findPreviousAndCurrentPlansWithRange($start, $end, $choir);
        } else {
            list($start, $end) = $this->includeFirstAndLastDay($month_ago, $now);
            $plans = $this->em()->getRepository('AppBundle:Plan')
              ->findPreviousAndCurrentPlans($end, $start, $choir);
        }


        list($plans_for_this_song, $total_time_for_this_song) = $this->plansAndTimeForThisSong($song, $plans);            
        $rehearsals_with_this_song = $this->getRehearsalsWithThisSong($song);

        $choristers_who_attended_this_song = $this->plansChoristersAttendance($plans_for_this_song, $song); // in array with number of minutes attended for each chorister
        $attendance_percentage = $this->getSongAttendancePercentage($song, $plans_for_this_song);

        return $this->render('song/attendance.html.twig', array(
            'plans' => $plans_for_this_song,
            'rehearsals' => $rehearsals_with_this_song,
            'song' => $song,
            'song_time' => $total_time_for_this_song,
            'choir' => $choir,
            'choristers_attendance' => $choristers_who_attended_this_song,
            'attendance_percentage' => $attendance_percentage,
        ));
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function songInProgress($rehearsal, $chorister, $marking = 'In'){

        $choristers_mark_for_this_rehearsal = $this->em()->getRepository('AppBundle:Mark')
            ->findOneBy(
                array('rehearsal' => $rehearsal, 'chorister' => $chorister),
                array('id' => 'ASC')
            );

        $rehearsal_commencing_time = $rehearsal->getStarttime();
        $rehearsal_concluding_time = $rehearsal->getEndtime();

        $chorister_arrived_at = $choristers_mark_for_this_rehearsal->getTimein();
        $chorister_left_at = $choristers_mark_for_this_rehearsal->getTimeOut();

        $plans_for_this_rehearsal = $rehearsal->getPlans();

        if($marking == 'In'){
            $plan_in_progress = $this->duringWhichPlan($plans_for_this_rehearsal, $chorister_arrived_at);    
        } elseif ($marking == "Out"){
            $plan_in_progress = $this->duringWhichPlan($plans_for_this_rehearsal, $chorister_left_at);
        }

        $plan_that_was_being_done = $plan_in_progress;

        // return $plan_in_progress;
        return $plan_that_was_being_done;

    }

    private function sangSongDuration($plan, $compare_with, $marking = "In"){
        $sang_duration = null;
        if($plan !== null ){
            $commence = $plan->getCommence();
            $conclude = $plan->getConclude();
            $plan_duration = $this->dateDifference($commence, $conclude); //15 minutes
            if($marking == "In") {
                if($commence < $compare_with){
                    $sang_duration = $this->dateDifference($compare_with, $conclude);
                }
            } elseif ($marking == "Out") {
                if($conclude > $compare_with){
                    $sang_duration = $this->dateDifference($commence, $compare_with);
                }
            }

        }
        return $sang_duration;
    }

    private function dateDifference($datetime1, $datetime2){
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%R%i');
    }

    private function includeFirstAndLastDay($start_date, $end_date){
        $start_date ->setTime(0, 0, 0);
        $end_date->modify('+1 day');  
        return [$start_date, $end_date];       
    }

    private function duringWhichPlan($plans, $arrival){
        $current_plan = null;
        foreach ($plans as $key => $plan) {
            $commence = $plan->getCommence();
            $conclude = $plan->getConclude();
            if($arrival >= $commence && $arrival <= $conclude){
                $current_plan = $plan;
            }
        }
        return $current_plan;
    }

    private function attendedFullPlans($plans, $arrival, $departure){
        $plans_attended = [];
        foreach ($plans as $key => $plan) {
            $commence = $plan->getCommence();
            $conclude = $plan->getConclude();
            if($arrival <= $commence && $departure >= $conclude){
                $plans_attended[] = $plan;
            }
        }

        return $plans_attended;
    }

    private function completelyMissedPlans($plans, $arrival, $departure){
        $plans_missed = [];
        foreach ($plans as $key => $plan) {
            $commence = $plan->getCommence();
            $conclude = $plan->getConclude();
            if($arrival > $conclude || $departure < $commence){
                $plans_missed[] = $plan;
            }
        }

        return $plans_missed;
    }

    private function plansAndTimeForThisSong($song, $plans){
        $plans_for_this_song = [];
        $total_time_for_this_song = 0;
        foreach ($plans as $key => $plan) {
            if($plan->getSong() == $song){
                $plans_for_this_song[] = $plan;
                $diff = $this->dateDifference($plan->getCommence(), $plan->getConclude());
                $total_time_for_this_song += $diff;
            }
        }
        
        return [$plans_for_this_song, $total_time_for_this_song];
    }

    private function choristerAttendanceForThisSong($plans_for_this_song, $chorister){
        $marks_for_this_song = [];
        foreach ($plans_for_this_song as $key => $plan) {
            $mark = $this->em()->getRepository('AppBundle:Mark')
              ->findBy(
                array('rehearsal' => $plan->getRehearsal(), 'chorister' => $chorister),
                array('id' => 'ASC')
              );
            $marks_for_this_song[] = $mark;
        }
        return $marks_for_this_song;
    }

    private function getPlansForThisSong($song){
        $plans = $this->em()->getRepository('AppBundle:Plan')
          ->findBy(
            array('song' => $song),
            array('id' => 'ASC')
          );
        return $plans;
    }

    private function getRehearsalsWithThisSong($song){
        $rehearsals_with_this_song = [];
        $plans = $this->getPlansForThisSong($song);
        foreach ($plans as $plan) {
            $rehearsals_with_this_song[] = $plan->getRehearsal();
        }
        return $rehearsals_with_this_song;
    }

    private function getSongAttendancePercentage($song, $plans){ //given only plans with this song
        $required_time_for_this_song = $this->plansAndTimeForThisSong($song, $plans)[1];
        $number_of_choristers = count($song->getChoir()->getChoristers());
        $required_attendance_time = $required_time_for_this_song * $number_of_choristers;
        $time_attended_for_this_plan = 0;
        foreach ($plans as $plan) {
            $minutes_attended = $this->plansChoristersAttendance($plans, $song)['plan'.$plan->getId()]['minutes'];
            if($minutes_attended > 0 ){
                $time_attended_for_this_plan += $minutes_attended;
            }
        }
        if($required_attendance_time > 0){
            $percentage = ($time_attended_for_this_plan / $required_attendance_time) * 100;
        } else {
            $percentage = 0;
        }
        
        return $percentage;
    }

    private function choristersWhoAttendedThisSong($plan){
        $rehearsal = $plan->getRehearsal();
        $marks = $rehearsal->getMarks();
        $plan_starts = $plan->getCommence();
        $plan_ends = $plan->getConclude();
        $choristers_list = [];
        $duration = $this->dateDifference($plan_starts, $plan_ends);
        $total_attendance_time = 0;
        $required_attendance_time = count($plan->getChoir()->getChoristers()) * $duration;

        foreach ($marks as $mark) {
            $time_in = $mark->getTimein();
            $time_out = $mark->getTimeOut();
            if($time_in > $plan_starts){
                $minutes_done = $this->sangSongDuration($plan, $time_in, "In");
            } elseif ($time_out < $plan_ends){
                $minutes_done = $this->sangSongDuration($plan, $time_out, "Out");
            } elseif ($time_in <= $plan_starts && $time_out >= $plan_ends){
                $minutes_done = $this->dateDifference($plan_starts, $plan_ends);
            }
            if($minutes_done > 0 ){
                $total_attendance_time += $minutes_done;
            }
            
            $choristers_list["chorister".$mark->getChorister()->getId()] = ['chorister' => $mark->getChorister(), 'minutes' => $minutes_done];
        }
        $percentage = ($total_attendance_time / $required_attendance_time) * 100;

        return ['chorister_list' => $choristers_list, 'minutes' => $total_attendance_time, 'plan' =>$plan, 'duration' => $duration, 'percentage' => $percentage];
    }

    private function plansChoristersAttendance($plans, $song){
        $choristers_list = [];
        foreach ($plans as $key => $plan) {
            if($plan->getSong() == $song){
                $choristers_list['plan'.$plan->getId()] = $this->choristersWhoAttendedThisSong($plan);
            }
            
        }
        return $choristers_list;
    }

}
