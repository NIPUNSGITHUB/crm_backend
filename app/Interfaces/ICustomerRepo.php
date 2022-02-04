<?php

namespace App\Interfaces;

Interface ICustomerRepo
{
    public function getAllCustomers();
    public function getCustomerByValue($value); 
    public function deleteCustomer($orderId);
    /*public function createCustomer(array $orderDetails);   
    public function updateCustomer($orderId, array $newDetails);*/

     
}

?>