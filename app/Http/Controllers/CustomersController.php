<?php

namespace App\Http\Controllers;

use App\Exports\ActivityLogExport;
use App\Exports\CustomersExport;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Customer;
use App\Http\Requests\CreateCustomerRequest;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class CustomersController extends Controller
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var customersPerPage
     */
    private $customersPerPage = 5;

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

        $customers = Customer::where('user_id', '=', $currentUserId)
            ->latest()
            ->paginate($this->customersPerPage);

        return response()->json([
            "customers" => $customers
        ], 200);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
       return Excel::download(new CustomersExport(), 'customers.xlsx');
    }

    /**
     * @param $field
     * @param $query
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($field, $query)
    {
        $currentUserId = \Auth::user()->id;

        $customers = Customer::where('user_id', '=', $currentUserId)
            ->where($field, 'LIKE', "%$query%")
            ->latest()
            ->paginate($this->customersPerPage);

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

        $customer = Customer::whereId($id)
            ->where('user_id', '=', $currentUserId)
            ->first();

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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = Customer::find($id)->forceDelete();

        if($result) {
            return response()->json([
                "customer" => $result,
            ], 200);
        }

        return false;
    }

    /**
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function log()
    {
        $currentUserId = \Auth::user()->id;

        $customers = Activity::where('causer_id', '=', $currentUserId)->latest()->get();

        if($customers) {
            return response()->json([
                "log" => $customers,
            ], 200);
        }

        return false;
    }

    /**
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function activityLog()
    {
        $currentUserId = \Auth::user()->id;

        $customers = Activity::where('causer_id', '=', $currentUserId)->latest()->get();

        if($customers) {
            return response()->json([
                "activityLog" => $customers,
            ], 200);
        }

        return false;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function activityExport()
    {
        return Excel::download(new ActivityLogExport(), 'activity-log.xlsx');
    }

    public function activityExportToCsv()
    {
        return Excel::download(new ActivityLogExport(), 'activity-log.csv');
    }
}
