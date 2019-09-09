<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\Role;
 
class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Role = new Role();
    }

    /**
     * Get list of roles with pagination, sorting, filtering and search
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        $result = $this->Role->getRolesList($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Get details of role by id
     * 
     * @param string $id
     * @return string
     */
    public function details($id) {
        $result = $this->Role->getRoleDetails($id);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and save new role
     * 
     * @param object $request
     * @return string
     */
    public function save(Request $request)
    {
        // $this->validate($request, $this->JobCategory->getValidationRules(), $this->JobCategory->getValidationMessages());
        $result = $this->Role->saveRole($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and update existing role
     * 
     * @param object $request
     * @return string
     */
    public function update(Request $request) {
        // $this->validate($request, $this->JobCategory->getValidationRules(), $this->JobCategory->getValidationMessages());
        $result = $this->Role->updateRole($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete role by id
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->Role->deleteRole($id);
        return response($result, $result['status']['code']);
    }

}
