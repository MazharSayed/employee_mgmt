<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $employee = Employee::firstOrCreate(['user_id' => $user->id]);

        return view('dashboard', compact('user', 'employee'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'phone'            => 'nullable|string|max:15',
            'skills'           => 'nullable|string|max:255',
            'profile_picture'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $employee = Employee::firstOrCreate(['user_id' => Auth::id()]);

        $data = [
            'phone'  => $request->input('phone'),
            'skills' => $request->input('skills'),
        ];

        if($request->file('profile_picture')) {

            if ($employee->profile_picture && file_exists(public_path('uploads/profile_picture/' . $employee->profile_picture))) {
                unlink(public_path('uploads/profile_picture/' . $employee->profile_picture));
            }

            $upload = $request->file('profile_picture');
            $fileformat = time() . $upload->getClientOriginalName();
            if ($upload->move('uploads/profile_picture/', $fileformat)) {
                $employee->profile_picture = $fileformat;
            }
        }

        $employee->update($data);

        $response = [
            'success'   => true,
            'message'   => 'Profile updated successfully.',
        ];

        return response()->json($response);
    }
}
