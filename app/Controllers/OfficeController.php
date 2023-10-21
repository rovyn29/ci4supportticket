<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;

class OfficeController extends ResourceController
{
    public function index()
    {
        return view('offices/index');
    }

    public function show($id = null)
    {
        $office = new \App\Models\Office();
        $data = $office->find($id);
        return $this->response->setJSON($data);
    }

    public function getall() 
    {
        $request = service('request');
        $postData = $request->getPost();
    
        $response = array();
        
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value']; // Search value
        $sortby = $postData['order'][0]['column']; // Sort column index
        $sortdir = $postData['order'][0]['dir']; // Sort direction
        $sortcolumn = $postData['columns'][$sortby]['data']; // Sort column name
        
        ## Total number of records without filtering
        $office = new \App\Models\Office();
        $totalRecords = $office->select('id')->countAllResults();

        ## Total number of records with filtering
        $totalRecordwithFilter = $office->select('id')
            ->orLike('office_name', $searchValue)
            ->orLike('description', $searchValue)
            ->countAllResults();
            
        ## Fetch records
        $records = $office->select('*')
            ->orLike('office_name', $searchValue)
            ->orLike('description', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id" => $record['id'],
                "office_name" => $record['office_name'],
                "description" => $record['description'],
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

    public function create()
    {
        $office = new \App\Models\Office();
        $data = $this->request->getJSON();

        if(!$office->validate($data)){
            $response = array(
                "status" => "error",
                "message" => $office->errors(),
                "token" => csrf_hash()
            );

            return $this->response->setJSON($response)->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $office->insert($data);
        $response = array(
            "status" => "success",
            "message" => "Office created successfully",
            "token" => csrf_hash()
        );
        return $this->response->setJSON($response)->setStatusCode(Response::HTTP_CREATED);
    }

    public function update($id = null)
    {
        $office = new \App\Models\Office();
        $data = $this->request->getJSON();
        unset($data->id);
       
        if(!$office->validate($data)){
            $response = array(
                "status" => "error",
                "message" => $office->errors(),
                "token" => csrf_hash()
            );

            return $this->response->setJSON($response)->setStatusCode(Response::HTTP_NOT_MODIFIED);
        }

        $office->update($id, $data);
        $response = array(
            "status" => "success",
            "message" => "Office updated successfully",
            "token" => csrf_hash()
        );
        return $this->response->setJSON($response)->setStatusCode(Response::HTTP_OK);
    }

    public function delete($id = null)
    {
        $office = new \App\Models\Office();

        if ($office->delete($id)) {
            $response = array(
                "status" => "success",
                "message" => "Office deleted successfully",
                "token" => csrf_hash()
            );
            return $this->response->setJSON($response)->setStatusCode(Response::HTTP_OK);
        }

        $response = array(
            "status" => "error",
            "message" => "Office not found",
            "token" => csrf_hash()
        );
        return $this->response->setJSON($response)->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
