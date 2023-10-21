<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;

class DashboardController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $office = new \App\Models\Office();
        $ticket = new \App\Models\Ticket();
        $user = auth()->user()->id;

        if (auth()->user()->inGroup('user')){
            $totalticket = $ticket->select('id')->where('user_id',$user)->countAllResults();
            $totalhigh = $ticket->select('id')->where('severity','High')->where('user_id',$user)->countAllResults();
            $totalmedium = $ticket->select('id')->where('severity','Medium')->where('user_id',$user)->countAllResults();
            $totallow = $ticket->select('id')->where('severity','Low')->where('user_id',$user)->countAllResults();
            $totalpending = $ticket->select('id')->where('status','Pending')->where('user_id',$user)->countAllResults();
            $totalprocessing = $ticket->select('id')->where('status','Processing')->where('user_id',$user)->countAllResults();
            $totalresolved = $ticket->select('id')->where('status','Resolved')->where('user_id',$user)->countAllResults();
            $data = [
                'totaltickets' => $totalticket,
                'totalhighs' => $totalhigh, 
                'totalmediums' => $totalmedium,
                'totallows' => $totallow,
                'totalpendings' => $totalpending,
                'totalprocessings' => $totalprocessing,
                'totalresolveds' => $totalresolved
            ];
            return view('dashboard/index',$data);
        }else {
            $totalticket = $ticket->select('id')->countAllResults();
            $totalhigh = $ticket->select('id')->where('severity','High')->countAllResults();
            $totalmedium = $ticket->select('id')->where('severity','Medium')->countAllResults();
            $totallow = $ticket->select('id')->where('severity','Low')->countAllResults();
            $totalpending = $ticket->select('id')->where('status','Pending')->countAllResults();
            $totalprocessing = $ticket->select('id')->where('status','Processing')->countAllResults();
            $totalresolved = $ticket->select('id')->where('status','Resolved')->countAllResults();
            $data = [
                'totaltickets' => $totalticket,
                'totalhighs' => $totalhigh, 
                'totalmediums' => $totalmedium,
                'totallows' => $totallow,
                'totalpendings' => $totalpending,
                'totalprocessings' => $totalprocessing,
                'totalresolveds' => $totalresolved
            ];
            return view('dashboard/index',$data);   
        }
    }
}
