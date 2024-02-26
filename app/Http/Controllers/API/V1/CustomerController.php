<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::get();
        return new CustomerCollection($customers); 
    }

    public function store(StoreCustomerRequest $request)
    {
        $newCustomer = Customer::create($request->all());
        return new CustomerResource($newCustomer);
    }

    public function show(Customer $customer)
    {
        $customerResources = new CustomerResource($customer, true);
        return  $customerResources;
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $updateCustomer = $customer->update($request->all());

        if($updateCustomer){
            $updatedCustomer = Customer::find($customer->id);
            return new CustomerResource($updatedCustomer);
        } else {
            return response()->json(['message' => 'Failed to update resource'], 500);
        }

    }

    public function destroy(Customer $customer)
    {
        $isDeleted = $customer->delete();

        if($isDeleted){
            return response()->json(['message' => 'No Content'], 204);
        } else {
            return response()->json(['message' => 'Failed to delete resource'], 500);
        }

    }
}
