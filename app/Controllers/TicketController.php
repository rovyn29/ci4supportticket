<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;

class TicketController extends ResourceController
{
    public function index()
    {
        $office = new \App\Models\Office();
        $data['offices'] = $office->findAll();
        
        return view('tickets/index', $data);
    }

    public function show($id = null)
    {
        $ticket = new \App\Models\Ticket();
        $data = $ticket->find($id);
        return $this->response->setJSON($data);
    }

    public function getall() 
    {
        $request = service('request');
        $postData = $request->getPost();
        
        $user = auth()->user()->id;
        if (auth()->user()->inGroup('user')) {
            $response = array();

            ## Read value
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $searchValue = $postData['search']['value']; // Search value
            
            ## Total number of records without filtering
            $ticket = new \App\Models\Ticket();
            $totalRecords = $ticket->select('id')->where('user_id',$user)->countAllResults();

            ## Total number of records with filtering
            $totalRecordwithFilter = $ticket->select('tickets.*, offices.office_name')
                ->join('offices', 'offices.id = tickets.office_id')
                ->where('tickets.user_id',$user)
                ->countAllResults();
                
            ## Fetch records
            $records = $ticket->select("tickets.*, offices.office_name")
                ->join('offices', 'offices.id = tickets.office_id')
                ->where('tickets.user_id',$user)
                ->findAll($totalRecords, $start);
                //->limit($rowperpage)
                //    ->offset($start)
                //    ->getResults();
            
            $data = array();

            foreach ($records as $record) {
                //if ($record['user_id'] == $user) {
                    $data[] = array(
                        "id" => $record['id'],
                        "office_name" => $record['office_name'],
                        "name" => $record['name'],
                        "title" => $record['title'],
                        "description" => $record['description'],
                        "severity" => $record['severity'],
                        "status" => $record['status'],
                    );
                //}
            }
            
            ## Response
            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordwithFilter,
                "data" => $data,
                "token" => csrf_hash() // New token hash
            );
            return $this->response->setJSON($response);
        }else {
            $response = array();

            ## Read value
            $draw = $postData['draw'];
            $start = $postData['start'];
            $rowperpage = $postData['length']; // Rows display per page
            $searchValue = $postData['search']['value']; // Search value
            
            ## Total number of records without filtering
            $ticket = new \App\Models\Ticket();
            $totalRecords = $ticket->select('id')->countAllResults();

            ## Total number of records with filtering
            $totalRecordwithFilter = $ticket->select('tickets.id, offices.office_name')
                ->join('offices', 'offices.id = tickets.office_id')
                ->orLike('offices.office_name', $searchValue)
                ->orLike('tickets.title', $searchValue)
                ->orLike('tickets.description', $searchValue)
                ->orLike('tickets.severity', $searchValue)
                ->orLike('tickets.status', $searchValue)
                ->countAllResults();
                
            ## Fetch records
            $records = $ticket->select("tickets.*, offices.office_name")
                ->join('offices', 'offices.id = tickets.office_id')
                ->orLike('offices.office_name', $searchValue)
                ->orLike('tickets.title', $searchValue)
                ->orLike('tickets.description', $searchValue)
                ->orLike('tickets.severity', $searchValue)
                ->orLike('tickets.status', $searchValue)
                ->findAll($rowperpage, $start);
            
            $data = array();

            foreach ($records as $record) {

                $data[] = array(
                    "id" => $record['id'],
                    "office_name" => $record['office_name'],
                    "name" => $record['name'],
                    "title" => $record['title'],
                    "description" => $record['description'],
                    "severity" => $record['severity'],
                    "status" => $record['status'],
                );
            }
            
            ## Response
            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecordwithFilter,
                "data" => $data,
                "token" => csrf_hash() // New token hash
            );
            return $this->response->setJSON($response);
        }
        
    }

    public function create()
    {
        $ticket = new \App\Models\Ticket();
        $data = $this->request->getJSON();
        
        if(!$ticket->validate($data)){
            $response = array(
                "status" => "error",
                "message" => $ticket->errors(),
                "token" => csrf_hash()
            );

            return $this->response->setJSON($response)->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $ticket->insert($data);
        $response = array(
            "status" => "success",
            "message" => "Ticket created successfully",
            "token" => csrf_hash()
        );
        return $this->response->setJSON($response)->setStatusCode(Response::HTTP_CREATED);
    }

    public function update($id = null)
    {
        $ticket = new \App\Models\Ticket();
        $data = $this->request->getJSON();
        unset($data->id);
       
        if(!$ticket->validate($data)){
            $response = array(
                "status" => "error",
                "message" => $ticket->errors(),
                "token" => csrf_hash()
            );

            return $this->response->setJSON($response)->setStatusCode(Response::HTTP_NOT_MODIFIED);
        }

        $ticket->update($id, $data);
        $response = array(
            "status" => "success",
            "message" => "Ticket updated successfully",
            "token" => csrf_hash()
        );
        return $this->response->setJSON($response)->setStatusCode(Response::HTTP_OK);
    }

    public function delete($id = null)
    {
        $ticket = new \App\Models\Ticket();

        if ($ticket->delete($id)) {
            $response = array(
                "status" => "success",
                "message" => "Ticket deleted successfully",
                "token" => csrf_hash()
            );
            return $this->response->setJSON($response)->setStatusCode(Response::HTTP_OK);
        }

        $response = array(
            "status" => "error",
            "message" => "Ticket not found",
            "token" => csrf_hash()
        );
        return $this->response->setJSON($response)->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
