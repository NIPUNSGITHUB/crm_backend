<?php

namespace App\Repositories; 
use App\Models\Customer;
use App\Interfaces\ICustomerRepo;
use Illuminate\Support\Facades\Log;

$responseArr = ["status"=>false,"data"=>null,"message"=>null];
class CustomerRepo implements ICustomerRepo
{ 
    
    function getAllCustomers(){ 
        $responseArr["data"] = Customer::all()->where('is_active',1);   
        $responseArr["status"] = true;  
        $responseArr["message"] = "Sucessfull!";
        return $responseArr;
    }
    
    function getCustomerByValue($value){
        $responseArr["data"] = Customer::where('is_active',1)
        ->where('first_name','like','%'.$value.'%')  
        ->orWhere('last_name','like','%'.$value.'%')
        ->orWhere('email','like','%'.$value.'%')
        ->Where('phone_number','like','%'.$value.'%')
        ->get(); 
         
        $responseArr["status"] = true; 
        $responseArr["message"] = ($responseArr["data"]) ? "Found!" : "Not Available" ;
        return $responseArr;
    }

    function deleteCustomer($customerId){  
        try {
            $responseArr["status"] = Customer::whereId($customerId)->update(['is_active'=> 0]); 
            $responseArr["message"] = "Sucessfull!";   
        } catch (Exception $ex) {
            $responseArr["status"] = false; 
            $responseArr["message"] = $ex->message();

        }
        $responseArr["data"] = $this->getAllCustomers()['data'];
        return $responseArr;
    }

    function createCustomer($customer){             
        $result = Customer::create($customer);
        $responseArr["status"] = ($result) ? true : false;
        $responseArr["message"] = ($result) ? "Sucessfull!" : "Fail!";
        $responseArr["data"] = $this->getAllCustomers()['data'];
        return $responseArr;

    }

    function updateCustomer($customerId, $newDetails) 
    {   
        $result = Customer::whereId($customerId)->where('is_active',1)->update($newDetails->only('first_name',"last_name","phone_number")); 
        $responseArr["status"] = ($result) ? true : false;
        $responseArr["message"] = ($result) ? "Sucessfull!" : "Fail!";
        $responseArr["data"] = $this->getAllCustomers()['data'];
        return $responseArr;
    }

    function getCustomerById($customerId){  
        try {
            $responseArr["status"] = true;
            $responseArr["data"] = Customer::whereId($customerId)->where('is_active', 1)->get(); 
            $responseArr["message"] = "Sucessfull!";   
        } catch (Exception $ex) {
            $responseArr["status"] = false; 
            $responseArr["data"] = [];
            $responseArr["message"] = $ex->message();

        }
         
        return $responseArr;
    }
}

?>