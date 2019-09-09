<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\JobLocation;
 
class JobLocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->JobLocation = new JobLocation();
    }

    /**
     * Get list of locations with pagination, sorting, filtering and search
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        $result = $this->JobLocation->getJobLocationsList($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Get details of job location by id
     * 
     * @param string $id
     * @return string
     */
    public function details($id) {
        $result = $this->JobLocation->getJobLocationDetails($id);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and save new job location
     * 
     * @param object $request
     * @return string
     */
    public function save(Request $request)
    {
        $this->validate($request, $this->JobLocation->getValidationRules(), $this->JobLocation->getValidationMessages());
        $result = $this->JobLocation->saveJobLocation($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and update existing job location
     * 
     * @param object $request
     * @return string
     */
    public function update(Request $request) {
        $this->validate($request, $this->JobLocation->getValidationRules(), $this->JobLocation->getValidationMessages());
        $result = $this->JobLocation->updateJobLocation($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete job location by id
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->JobLocation->deleteJobLocation($id);
        return response($result, $result['status']['code']);
    }

}