<?php
 
namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class JobCategory extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Validation
     * 
     * @var array
     */
    protected $validation = [
        'name' => [
            'nameRule1' => [
                'rule' => ['required'],
                'message' => 'Ime nije definisano'
            ],
        ]
    ];

    /**
     * Get list of categories
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getJobCategoriesList(Object $request) :array
    {
        $result = [];

        try {
            $categories = DB::table('job_categories')
                    ->select(
                        'job_categories.id AS id',
                        'job_categories.name AS name'
                    )
                    ->where('job_categories.deleted', '0')
                    ->orderBy('job_categories.id', 'asc')
                    ->get();

            $result = $this->setResult('success', '', $categories);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Get job category details
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function getJobCategoryDetails(string $id) :array
    {
        $result = [];

        try
        {
            $jobCategory = JobCategory::find($id);

            if ($jobCategory)
            {
                $result = $this->setResult('success', '', $jobCategory);
            }
            else
            {
                $result = $this->setResult('not_found', 'Kategorija ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Save job category
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function saveJobCategory(Object $request) :array
    {
        $result = [];

        $newJobCategory = [
            'name' => $request->input('name'),
        ];

        try
        {
            $jobCategory = JobCategory::create($newJobCategory);
            $result = $this->setResult('success', '', $jobCategory);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Update job ctegory
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function updateJobCategory(Object $request) :array
    {
        $result = [];

        try
        {
            $jobCategory = JobCategory::find($request->input("id"));

            if ($jobCategory)
            {
                $jobCategory->name = $request->input("name");
                $jobCategory->save();
                $result = $this->setResult('success', '', $jobCategory);
            }
            else
            {
                $result = $this->setResult('not_found', 'Kategorija ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Delete job category
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function deleteJobCategory(string $id) :array
    {
        $result = [];

        try
        {
            $jobCategory = JobCategory::find($id);

            if ($jobCategory)
            {
                $jobCategory->deleted = 1;
                $jobCategory->save();
                $result = $this->setResult('success', '', $jobCategory);
            }
            else
            {
                $result = $this->setResult('not_found', 'Kategorija ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
