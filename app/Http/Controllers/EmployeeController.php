<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
        /**
     * Display all employees list.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {   
        $employees = Employee::with(['department' => function ($query) {
            return $query->select(['id', 'name']);
        }])->paginate(10);
        
        return EmployeeResource::collection($employees);
    }


    /**
     * Show a specified employee by id.
     *
     * @param  Employee $employee
     * @return EmployeeResource
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource(
            $employee->load(['department' => function ($query) {
                        return $query->select(['id', 'name']);
                    }])
        );
    }


    /**
     * Store a newly created employee.
     *
     * @param  StoreEmployeeRequest  $request
     * @return EmployeeResource
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        $employee->refresh();
        return new EmployeeResource($employee);
    }

    /**
     * Update a specified employee by id. 
     * @param Request $request
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->all());
        return new EmployeeResource($employee);
    }

    /**
     * Delete a specified employee.
     *
     * @param  Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response('employee deleted successfully', 200);
    }
}

