<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use App\Interfaces\ICustomerRepo;
use Maatwebsite\Excel\Facades\Excel; 
use Illuminate\Support\Facades\Validator; 

class CustomerController extends Controller
{

    private ICustomerRepo $customerRrepo;
    public function __construct(ICustomerRepo $customerRrepo)
    {
        $this->customerRrepo = $customerRrepo;
    } 

    public function index()
    {
        $result = $this->customerRrepo->getAllCustomers(); 
        return response()->json([
            'status' => $result["status"],
            'data' => $result["data"],
            'message' => $result["message"]
        ]);
    }  

    public function store(Request $request)
    {         
        $response = null;
        $rules = [
            'title' => 'required',
            'first_name' => 'required|max:20|unique:customers',
            'last_name' => 'required|max:20',
            'email' => 'required|email|unique:customers',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10' 
        ]; 
        $validator = Validator::make($request->all(), $rules ); 
        if ($validator->fails()) {
            $response = response()->json([
                'status' => false,
                'data' => null,
                'message' =>  $validator->messages()
            ]); 
        } else {
            
            $result = $this->customerRrepo->createCustomer($request->all()); 
            $response = $result;
        }
 
        return $response;  
    }
    
    public function show(Request $request)
    {
        $value = $request->route('value'); 
        $result = $this->customerRrepo->getCustomerByValue($value); 
        return response()->json([
            'status' => $result["status"],
            'data' => $result["data"],
            'message' => $result["message"]
        ]);
    }
    
    public function update($id,Request $request)
    {
        $rules = [
            'title' => 'required',
            'first_name' => 'required|max:20|unique:customers',
            'last_name' => 'required|max:20',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10' 
        ]; 
        $validator = Validator::make($request->all(), $rules ); 
        if ($validator->fails()) {
            $response = response()->json([
                'status' => false,
                'data' => null,
                'message' =>  $validator->messages()
            ]); 
        }
        else{
            $result = $this->customerRrepo->updateCustomer($id,$request);  
            $response = $result;
        }
        return $response;  
        
    }
 
    public function destroy($id)
    {         
        $result = $this->customerRrepo->deleteCustomer($id); 
        return response()->json([
            'status' => $result["status"],
            'data' => $result["data"],
            'message' => $result["message"]
        ]);
    }

    public function importFromCSV(Request $request){

        $rules = [
            'file' => 'required',
        ]; 
        $validator = Validator::make($request->file(), $rules ); 
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' =>  $validator->messages()
            ]); 
        }
        else
        {
            $import = new CustomerImport();
            $result = Excel::import($import, $request->file);         
            return response()->json($import->getRowResult());
        }        
    }
}
