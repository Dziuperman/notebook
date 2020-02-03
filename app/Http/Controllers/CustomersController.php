<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Http\Requests\CreateCustomerRequest;

class CustomersController extends Controller
{
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

    public function update(CreateCustomerRequest $request, $id)
    {

    }
}
