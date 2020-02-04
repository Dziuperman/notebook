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
//        $paginator = $this->customerRepository->getAllWithPaginate();

        $currentUserId = \Auth::user()->id;

//        $customers = Customer::where('user_id', '=' , $currentUserId)->get();
        $customers = Customer::paginate(2);

        return response()->json([
            "customers" => $customers
        ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $currentUserId = \Auth::user()->id;

        $customer = Customer::whereId($id)->where('user_id', '=', $currentUserId)->first();

        return response()->json([
            "customer" => $customer
        ], 200);
    }

    /**
     * @param CreateCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function new(CreateCustomerRequest $request)
    {
        $data = $request->only(["name", "email", "phone", "website"]);
        $data['user_id'] = \Auth::user()->id;

        $customer = Customer::create($data);

        return response()->json([
            "customer" => $customer
        ], 200);
    }

    /**
     * @param CreateCustomerRequest $request
     * @param $id
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function update(CreateCustomerRequest $request, $id)
    {
        $item = $this->customerRepository->getEdit($id);

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return response()->json([
                "customer" => $result
            ], 200);

        }

        return false;
    }
}
