<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Interfaces\ICustomerRepo;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    private ICustomerRepo $customerRrepo;
    public function __construct(ICustomerRepo $customerRrepo)
    {
        $this->customerRrepo = $customerRrepo;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->customerRrepo->getAllCustomers(); 
        return response()->json([
            'status' => $result["status"],
            'data' => $result["data"],
            'message' => $result["message"]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {         
        $result = $this->customerRrepo->deleteCustomer($id); 
        return response()->json([
            'status' => $result["status"],
            'data' => $result["data"],
            'message' => $result["message"]
        ]);
    }
}
