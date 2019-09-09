<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\Job;
 
class JobController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Job = new Job();
    }

    /**
     * Get list of jobs with pagination, sorting, filtering and search
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        // if (Gate::allows('list')) {
            $result = $this->Job->getJobsList($request);
            return response($result, $result['status']['code']);
        // } else {
        //     $result = ['Not Allowed'];
        //     return response($result, 401);
        // }
    }

    /**
     * Get details of job by id
     * 
     * @param string $id
     * @return string
     */
    public function details($id) {
        $result = $this->Job->getJobDetails($id);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and save new job
     * 
     * @param object $request
     * @return string
     */
    public function save(Request $request)
    {
        $this->validate($request, $this->Job->getValidationRules(), $this->Job->getValidationMessages());
        $result = $this->Job->saveJob($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and update existing job
     * 
     * @param object $request
     * @return string
     */
    public function update(Request $request) {
        $this->validate($request, $this->Job->getValidationRules(), $this->Job->getValidationMessages());
        $result = $this->Job->updateJob($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete job by id
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->Job->deleteJob($id);
        return response($result, $result['status']['code']);
    }

}
