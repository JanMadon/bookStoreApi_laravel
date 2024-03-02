<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/customers",
     *     summary="Get a list of customers",
     *     tags={"Customers"},
     *     @OA\Response(response=200, description="Successful operation"),
     * )
     */
    public function index()
    {
        $customers = Cache::remember('customers_all', 60*60*24 , function () {
            return Customer::get();
        });

        return new CustomerCollection($customers);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customers",
     *     summary="Create a new customer",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON data representing the new customer",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Johny"),
     *             @OA\Property(property="surname", type="string", example="Bravo"), 
     *         ),
     *     ),
     *     @OA\Response(response=201, description="Customer created successfully"),
     *     @OA\Response(response=422, description="Validation error or other client error"),
     * )
     */
    public function store(StoreCustomerRequest $request)
    {
        $newCustomer = Customer::create($request->all());
        return new CustomerResource($newCustomer);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers/{customerId}",
     *     summary="Get a single customer by ID with list of borrow books",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         description="ID of the customer",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Customer not found"),
     * )
     */
    public function show(Customer $customer) // należy dodać pobierać id a nie ninstacje elogwienta
                                        // inacej catch nie ma sensu
    {
        // Cache::clear();
        // $key = 'customer.' . $customerId;
        // $customerResource = Cache::remember($key, 300, function () use ($customerId) {
        //     return new CustomerResource(Customer::find($customerId), true);
        // });
        // return  $customerResource;
        return new CustomerResource($customer, true);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/customers/1/{customerId}",
     *     summary="Upade customer data",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="customerIda",
     *         in="path",
     *         required=true,
     *         description="ID of the customer",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON data representing the new customer",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="new name"),
     *             @OA\Property(property="surname", type="string", example="new surname"), 
     *         ),
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Customer not found"),
     * )
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $updateCustomer = $customer->update($request->all());

        if ($updateCustomer) {
            $updatedCustomer = Customer::find($customer->id);
            return new CustomerResource($updatedCustomer);
        } else {
            return response()->json(['message' => 'Failed to update resource'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/customers/{customerId}",
     *     summary="Delete castumer",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         description="ID of the customer",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=500, description="Customer not found"),
     * )
     */
    public function destroy(Customer $customer)
    {
        $isDeleted = $customer->delete();

        if ($isDeleted) {
            return response()->json(['message' => 'No Content'], 204);
        } else {
            return response()->json(['message' => 'Failed to delete resource'], 500);
        }
    }
}
