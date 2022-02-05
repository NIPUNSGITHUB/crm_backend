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

     /**
     * @OA\Get(
     ** path="/api/customers",
     *      operationId="index",
     *      tags={"Customers"},
     *      summary="Get all active customer ",
     *      description="Returns customer data", 
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *      )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
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
     * @OA\Post(
     ** path="/api/customers",
     *      operationId="store",
     *      tags={"Customers"},
     *      summary="Create a new customer", 
     *
     *  @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ), 
     *   @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),  
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function store(Request $request)
    {         
        $response = null;
        $rules = [
            'title' => 'required',
            'first_name' => 'required|max:20',
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
            ],400); 
        } else {
            
            $result = $this->customerRrepo->createCustomer($request->all()); 
            $response = response()->json([
                'status' => true,
                'data' => $result["data"],
                'message' =>  $result["message"]
            ],200);
        }
 
        return $response;  
    }
    
     /**
     * @OA\Get(
     ** path="/api/customers/{value}",
     *      operationId="show",
     *      tags={"Customers"},
     *      summary="Search active customer",
     *      description="Returns customer data", 
     *      @OA\Parameter(
     *          name="value",
     *          description="Any Feilds",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *      )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
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
     * @OA\Put(
     *     path="/api/customers/{id}",
     *      operationId="update",
     *      tags={"Customers"},
     *      summary="Search active customer",
     *     @OA\Parameter(
     *         description="Customer Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     **  @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="phone_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),  
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */

    public function update($id,Request $request)
    {
        $rules = [
            'first_name' => 'required|max:20',
            'last_name' => 'required|max:20',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10' 
        ]; 
        $validator = Validator::make($request->all(), $rules ); 
        if ($validator->fails()) {
            $response = response()->json([
                'status' => false,
                'data' => null,
                'message' =>  $validator->messages()
            ],400); 
        } else {
            
            $result = $this->customerRrepo->updateCustomer($id,$request);  
            $response = response()->json([
                'status' => true,
                'data' => $result["data"],
                'message' =>  $result["message"]
            ],200);
        }
         
        return $response;  
        
    }
 

     /**
     * @OA\Delete(
     *      path="/api/customers/{id}",
     *      operationId="destroy",
     *      tags={"Customers"},
     *      summary="Delete existing customers",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="customers id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
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

    public function getCustomerById($id)
    {         
        $result = $this->customerRrepo->getCustomerById($id); 
        return response()->json([
            'status' => $result["status"],
            'data' => $result["data"],
            'message' => $result["message"]
        ]);
    }
}
