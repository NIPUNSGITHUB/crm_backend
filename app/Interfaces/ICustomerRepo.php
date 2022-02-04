<?php

namespace App\Interfaces;

Interface ICustomerRepo
{
    public function getAllCustomers();
    public function getCustomerByValue($value); 
    public function deleteCustomer($customerId);
    public function createCustomer(array $customer);   
   /* public function updateCustomer($customerId, array $newDetails);*/

     
}

?>