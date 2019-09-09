<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\JobCategory;
 
class JobCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->JobCategory = new JobCategory();
    }

    /**
     * Get list of categories with pagination, sorting, filtering and search
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        $result = $this->JobCategory->getJobCategoriesList($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Get details of job category by id
     * 
     * @param string $id
     * @return string
     */
    public function details($id) {
        $result = $this->JobCategory->getJobCategoryDetails($id);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and save new job category
     * 
     * @param object $request
     * @return string
     */
    public function save(Request $request)
    {
        $this->validate($request, $this->JobCategory->getValidationRules(), $this->JobCategory->getValidationMessages());
        $result = $this->JobCategory->saveJobCategory($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Validate request and update existing job category
     * 
     * @param object $request
     * @return string
     */
    public function update(Request $request) {
        $this->validate($request, $this->JobCategory->getValidationRules(), $this->JobCategory->getValidationMessages());
        $result = $this->JobCategory->updateJobCategory($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete job category by id
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->JobCategory->deleteJobCategory($id);
        return response($result, $result['status']['code']);
    }

}