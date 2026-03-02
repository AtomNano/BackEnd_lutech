<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // GET / (Pagination, Search by name)
        $query = Customer::query();
        if ($search = $request->input('search')) {
            $query->where('nama', 'like', "%{$search}%");
        }
        return CustomerResource::collection($query->paginate(15));
    }

    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->validated()));
    }
}
