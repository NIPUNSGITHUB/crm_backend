<?php

namespace App\Imports;

use App\Models\Customer;
use App\Interfaces\ICustomerRepo;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomerImport implements ToModel
{  
    private $inserted = 0;
    private $failed = 0;
    private $already = 0;

    public function model(array $row)
    {
        if ($row[0] != "first_name" && $row[1] != "last_name" && $row[2] != "phone_number" && $row[3] != "email") {
            if ($row[0] != null && $row[1] != null && $row[2] != null && $row[3] != null) {               
                $alreadyAvb = Customer::where('email', $row[3])->count();
                if ($alreadyAvb == 0) {
                    ++$this->inserted;
                    return new Customer([            
                            'title' => "Mr",          
                            'first_name' => $row[0],
                            'last_name' => $row[1],
                            'phone_number' => $row[2],
                            'email' => $row[3],
                            'is_active' => 1
                    ]);
                }
                else
                    ++$this->already;
            }
            else
            ++$this->failed;
            
        }
        else{
            Log::alert($row[0]);
        }
            
    }

    public function getRowResult(): array
    {
        $responseArr = ["status"=>false,"data"=>null,"message"=>null];
        $responseArr["status"] = ($this->inserted) ? true : false;
        $responseArr["message"] = ($this->inserted) ? "Sucessfull!" : "Fail!";
        $responseArr["data"] = ["inserted"=>$this->inserted,"failed"=>$this->failed,"already"=>$this->already];
       
        return $responseArr;
    }
        
}
