<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
 
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->User = new User();
    }

    /**
     * Get list of users with pagination, sorting, filtering and search
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        $result = $this->User->getUsersList($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Get details of user by id
     * 
     * @param string $id
     * @return string
     */
    public function details($id) {
        $result = $this->User->getUserDetails($id);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and save new user
     * 
     * @param object $request
     * @return string
     */
    public function save(Request $request)
    {
        // $this->validate($request, $this->JobCategory->getValidationRules(), $this->JobCategory->getValidationMessages());
        $result = $this->User->saveUser($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and update existing user
     * 
     * @param object $request
     * @return string
     */
    public function update(Request $request) {
        // $this->validate($request, $this->JobCategory->getValidationRules(), $this->JobCategory->getValidationMessages());
        $result = $this->User->updateUser($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete user by id
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->User->deleteUser($id);
        return response($result, $result['status']['code']);
    }

}
