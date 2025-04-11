<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponseTrait;

class UserController extends Controller {

    use ApiResponseTrait;

    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function getUsers(){
        $users = User::with('job')->get(); // Include job data
        return response()->json($users, 200);
    }

    public function add(Request $request) {
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
            'job_id' => 'required|exists:tbluserjob,job_id',
        ];
        $this->validate($request, $rules);
        $user = User::create($request->all());
        return response()->json($user->load('job'), 201); // Load job
    }

    public function show($identifier) {
        $user = is_numeric($identifier) 
            ? User::with('job')->find($identifier) 
            : User::with('job')->where('username', $identifier)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return response()->json($user, 200);
    }

    public function update(Request $request, $identifier) {
        $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'gender' => 'in:Male,Female',
            'job_id' => 'exists:tbluserjob,job_id',
        ];
        $this->validate($request, $rules);

        $user = is_numeric($identifier) 
            ? User::find($identifier) 
            : User::where('username', $identifier)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->fill($request->all());

        if ($user->isClean()) {
            return response()->json(['error' => 'At least one value must change'], 422);
        }

        $user->save();
        return response()->json($user->load('job'), 200); // Load job
    }

    public function patchUpdate(Request $request, $identifier) {
        $user = is_numeric($identifier) 
            ? User::find($identifier) 
            : User::where('username', $identifier)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Optional fields
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('password')) {
            $user->password = $request->password; // No Hashing (not recommended)
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        if ($request->has('job_id')) {
            $user->job_id = $request->job_id;
        }

        $user->save();
        return response()->json($user->load('job'), 200); // Load job
    }

    public function delete($identifier) {
        $user = is_numeric($identifier) 
            ? User::find($identifier) 
            : User::where('username', $identifier)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}
