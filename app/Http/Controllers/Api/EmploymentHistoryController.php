<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmploymentHistory;
use Illuminate\Support\Facades\Auth;

class EmploymentHistoryController extends Controller
{
    /**
     * Get the authenticated user's employment history.
     */
    public function index()
    {
        $employee = Employee::firstOrCreate(['user_id' => Auth::id()]);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $histories = $employee->employmentHistories;

        return response()->json($histories);
    }

    /**
     * Store a new employment history for the authenticated user's employee.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employer_name' => 'required|string',
            'position' => 'required|string',
            'occupation' => 'required|string',
            'manager_name' => 'required|string',
            'manager_email' => 'required|email',
            'manager_phone' => 'required|string',
            'start_date' => 'required|date',
        ]);

        $employee = Employee::firstOrCreate(['user_id' => Auth::id()]);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $data['employee_id'] = $employee->id;
        $history = EmploymentHistory::create($data);

        return response()->json(['success' => true, 'message' => 'Employment history added', 'data' => $history]);
    }

    /**
     * Update the employment history of a specific employee.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $data = $request->validate([
            'employer_name' => 'required|string',
            'position' => 'required|string',
            'occupation' => 'required|string',
            'manager_name' => 'required|string',
            'manager_email' => 'required|email',
            'manager_phone' => 'required|string',
            'start_date' => 'required|date',
        ]);

        $history = EmploymentHistory::find($id);

        if (!$history) {
            return response()->json(['error' => 'Employment history not found'], 404);
        }

        $history->update($data);

        return response()->json(['success' => true, 'message' => 'Employment history updated', 'data' => $history]);
    }

    /**
     * Delete an employment history record.
     */
    public function destroy($id)
    {
        $history = EmploymentHistory::find($id);

        if (!$history) {
            return response()->json(['error' => 'Employment history not found'], 404);
        }

        $history->delete();

        return response()->json(['success' => true, 'message' => 'Employment history deleted']);
    }


    /**
     * Details of an employment history record.
     */
    public function edit($id)
    {
        $employment = EmploymentHistory::find($id);
        return response()->json($employment);
        // return $employment;
        // return view('employment_details', compact('employment'));
    }

}

