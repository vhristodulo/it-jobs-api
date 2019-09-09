<?php
 
namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class JobLocation extends BaseModel implements AuthenticatableContract, AuthorizableContract
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
     * Get list of locations
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getJobLocationsList(Object $request) :array
    {
        $result = [];

        // validate input (including page)

        try {
            $locations = DB::table('job_locations')
                    ->select(
                        'job_locations.id AS id',
                        'job_locations.name AS name'
                    )
                    ->where('job_locations.deleted', '0')
                    ->orderBy('job_locations.id', 'asc')
                    ->get();

            $result = $this->setResult('success', '', $locations);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Get job location details
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
            $jobLocation = JobLocation::find($id);

            if ($jobLocation)
            {
                $result = $this->setResult('success', '', $jobLocation);
            }
            else
            {
                $result = $this->setResult('not_found', 'Lokacija ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Save job location
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function saveJobLocation(Object $request) :array
    {
        $result = [];

        $newJobLocation = [
            'name' => $request->input('name'),
        ];

        try
        {
            $jobLocation = JobLocation::create($newJobLocation);
            $result = $this->setResult('success', '', $jobLocation);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Update job location
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function updateJobLocation(Object $request) :array
    {
        $result = [];

        try
        {
            $jobLocation = JobLocation::find($request->input("id"));

            if ($jobLocation)
            {
                $jobLocation->name = $request->input("name");
                $jobLocation->save();
                $result = $this->setResult('success', '', $jobLocation);
            }
            else
            {
                $result = $this->setResult('not_found', 'Lokacija ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Delete job location
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function deleteJobLocation(string $id) :array
    {
        $result = [];

        try
        {
            $jobLocation = JobLocation::find($id);

            if ($jobLocation)
            {
                $jobLocation->deleted = 1;
                $jobLocation->save();
                $result = $this->setResult('success', '', $jobLocation);
            }
            else
            {
                $result = $this->setResult('not_found', 'Lokacija ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
