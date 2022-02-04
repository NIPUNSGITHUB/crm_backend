<?php

namespace App\Repositories; 
use App\Models\Customer;
use App\Interfaces\ICustomerRepo;

$responseArr = ["status"=>false,"data"=>null,"message"=>null];
class CustomerRepo implements ICustomerRepo
{

    
    function getAllCustomers(){ 
        $responseArr["data"] = Customer::all()->where('is_active',1);   
        $responseArr["status"] = true;  
        $responseArr["message"] = "Sucessfull";
        return $responseArr;
    }
    
    function getCustomerByValue($value){
        $responseArr["data"] = Customer::where('is_active',1)
        ->orWhere('first_name','like','%'.$value.'%')  
        ->orWhere('last_name','like','%'.$value.'%')
        ->orWhere('email','like','%'.$value.'%')
        ->orWhere('phone_number','like','%'.$value.'%')
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
        $responseArr["data"] = $this->getAllCustomers();
        return $responseArr;
    }
}



?>