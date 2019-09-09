<?php
 
namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class JobType extends BaseModel implements AuthenticatableContract, AuthorizableContract
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
     * Get list of types
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getJobTypesList(Object $request) :array
    {
        $result = [];

        try {
            $types = DB::table('job_types')
                    ->select(
                        'job_types.id AS id',
                        'job_types.name AS name'
                    )
                    // ->where('job_categories.deleted', '0')
                    ->orderBy('job_types.id', 'asc')
                    ->get();

            $result = $this->setResult('success', '', $types);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Get job type details
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function getJobTypeDetails(string $id) :array
    {
        $result = [];

        try
        {
            $jobType = JobType::find($id);

            if ($jobType)
            {
                $result = $this->setResult('success', '', $jobType);
            }
            else
            {
                $result = $this->setResult('not_found', 'Tip ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Save job type
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function saveJobType(Object $request) :array
    {
        $result = [];

        $newJobType = [
            'name' => $request->input('name'),
        ];

        try
        {
            $jobType = JobType::create($newJobType);
            $result = $this->setResult('success', '', $jobType);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Update job type
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function updateJobType(Object $request) :array
    {
        $result = [];

        try
        {
            $jobType = JobType::find($request->input("id"));

            if ($jobType)
            {
                $jobType->name = $request->input("name");
                $jobType->save();
                $result = $this->setResult('success', '', $jobType);
            }
            else
            {
                $result = $this->setResult('not_found', 'Tip ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Delete job type
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function deleteJobType(string $id) :array
    {
        $result = [];

        try
        {
            $jobType = JobType::find($id);

            if ($jobType)
            {
                $jobType->deleted = 1;
                $jobType->save();
                $result = $this->setResult('success', '', $jobType);
            }
            else
            {
                $result = $this->setResult('not_found', 'Tip ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
