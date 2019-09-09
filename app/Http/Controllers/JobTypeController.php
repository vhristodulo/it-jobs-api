<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\JobType;
 
class JobTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->JobType = new JobType();
    }

    /**
     * Get list of types with pagination, sorting, filtering and search
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        $result = $this->JobType->getJobTypesList($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Get details of job type by id
     * 
     * @param string $id
     * @return string
     */
    public function details($id) {
        $result = $this->JobType->getJobTypeDetails($id);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and save new job type
     * 
     * @param object $request
     * @return string
     */
    public function save(Request $request)
    {
        $this->validate($request, $this->JobType->getValidationRules(), $this->JobType->getValidationMessages());
        $result = $this->JobType->saveJobType($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and update existing job type
     * 
     * @param object $request
     * @return string
     */
    public function update(Request $request) {
        $this->validate($request, $this->JobType->getValidationRules(), $this->JobType->getValidationMessages());
        $result = $this->JobType->updateJobType($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete job type by id
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->JobType->deleteJobType($id);
        return response($result, $result['status']['code']);
    }

}
