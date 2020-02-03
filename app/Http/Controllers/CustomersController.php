<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Customer;
use App\Http\Requests\CreateCustomerRequest;

class CustomersController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerController constructor.
     */
    public function __construct()
    {
        $this->customerRepository = app(CustomerRepository::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $currentUserId = \Auth::user()->id;
        $customers = Customer::where('user_id', '=' , $currentUserId)->get();

        return response()->json([
            "customers" => $customers
        ], 200);
    }

    public function get($id)
    {
        $customer = Customer::whereId($id)->first();

        return response()->json([
            "customer" => $customer
        ], 200);
    }

    public function new(CreateCustomerRequest $request)
    {
        $data = $request->only(["name", "email", "phone", "website"]);
        $data['user_id'] = \Auth::user()->id;

        $customer = Customer::create($data);

        return response()->json([
            "customer" => $customer
        ], 200);
    }

//    public function update($id)
//    {
//        $item = $this->customerRepository->getEdit($id);
//
//        $data = $request->all();
//
//        $result = $item->update($data);
//
//        if ($result) {
//            return response()->json([
//                "customer" => $result
//            ], 200);
//
//        }
//
//        return false;
//    }
}
