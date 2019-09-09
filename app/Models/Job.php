<?php
 
namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

// use Illuminate\Auth\Authenticatable;
// use Laravel\Lumen\Auth\Authorizable;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
 
class Job extends BaseModel //implements AuthenticatableContract, AuthorizableContract
{
    // use Authenticatable, Authorizable;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_category_id',
        'job_type_id',
        'job_location_id',
        'title',
        'description',
        'application_deadline',
        'name',
        'email',
        'link'
    ];

    /**
     * Validation
     * 
     * @var array
     */
    protected $validation = [
        'job_category_id' => [
            'jobCategoryRule1' => [
                'rule' => ['required'],
                'message' => 'Kategorija nije definisana'
            ],
            'jobCategoryRule2' => [
                'rule' => ['exists', 'job_categories,id'],
                'message' => 'Kategorija ne postoji'
            ]
        ],
        'job_type_id' => [
            'jobTypeRule1' => [
                'rule' => ['required'],
                'message' => 'Tip nije definisan'
            ],
            'jobTypeRule2' => [
                'rule' => ['exists', 'job_types,id'],
                'message' => 'Tip ne postoji'
            ]
        ],
        'job_location_id' => [
            'jobLocationRule1' => [
                'rule' => ['required'],
                'message' => 'Lokacija nije definisana'
            ],
            'jobLocationRule2' => [
                'rule' => ['exists', 'job_locations,id'],
                'message' => 'Lokacija ne postoji'
            ]
        ],
        'title' => [
            'titleRule1' => [
                'rule' => ['required'],
                'message' => 'Naslov nije definisan'
            ],
        ],
        'description' => [
            'descriptionRule1' => [
                'rule' => ['required'],
                'message' => 'Opis nije definisan'
            ],
        ],
        'application_deadline' => [
            'applicationDeadlineRule1' => [
                'rule' => ['required'],
                'message' => 'Datum roka nije definisan'
            ],
            'applicationDeadlineRule2' => [
                'rule' => ['date'],
                'message' => 'Datum roka nije ispravan'
            ]
        ],
        'name' => [
            'nameRule1' => [
                'rule' => ['required'],
                'message' => 'Ime nije definisano'
            ],
        ],
        'email' => [
            'emailRule1' => [
                'rule' => ['required'],
                'message' => 'E-mail adresa nije definisana'
            ],
        ],
        'link' => [
            'linkRule1' => [
                'rule' => ['required'],
                'message' => 'Link nije definisan'
            ],
        ],
    ];

    /**
     * Filter request data
     * 
     * @return array
     */
    public function filterData(Object $request) :array
    {
        $params = [
            'orderBy' => 'id',
            'orderDir' => 'asc',
            'categoryId' => null,
            'categorySign' => '!=',
            'typeId' => null,
            'typeSign' => '!=',
            'search' => '%'
        ];

        if (!empty($request->get('orderBy'))) $params['orderBy'] = $request->get('orderBy');
        if (!empty($request->get('orderDir'))) $params['orderDir'] = $request->get('orderDir');

        if (!empty($request->get('categoryId')))
        {
            $params['categoryId'] = $request->get('categoryId');
            $params['categorySign'] = '=';
        }

        if (!empty($request->get('typeId')))
        {
            $params['typeId'] = $request->get('typeId');
            $params['typeSign'] = '=';
        }

        if (!empty($request->get('search'))) $params['search'] = '%'.$request->get('search').'%';

        return $params;
    }

    /**
     * Get list of jobs
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getJobsList(Object $request) :array
    {
        $result = [];
        $params = $this->filterData($request);

        global $search;
        foreach ($params as $param => $value) ${$param} = $value;

        try
        {
            $jobs = DB::table('jobs')
                    // joins
                    ->join('job_categories', 'jobs.job_category_id', '=', 'job_categories.id')
                    ->join('job_types', 'jobs.job_type_id', '=', 'job_types.id')
                    ->join('job_locations', 'jobs.job_location_id', '=', 'job_locations.id')
                    // data
                    ->select(
                        'jobs.id AS id',
                        'job_categories.name AS category',
                        'jobs.created_at AS created',
                        'jobs.description AS description',
                        'jobs.email AS email',
                        'jobs.highlighted AS highlighted',
                        'jobs.link AS link',
                        'job_locations.name AS location',
                        'jobs.name AS name',
                        'jobs.title AS title',
                        'job_types.name AS type',
                        'jobs.views AS views',
                        'jobs.application_deadline AS deadline'
                    )
                    // filters
                    ->where('jobs.job_category_id', $categorySign, $categoryId)
                    ->where('jobs.job_type_id', $typeSign, $typeId)
                    // search
                    ->where(function($query)
                    {
                        global $search;
                        $query->where('jobs.title', 'LIKE', $search)
                              ->orWhere('jobs.description', 'LIKE', $search);
                    })
                    // soft delete
                    ->where('jobs.deleted', '0')
                    // sorting
                    ->orderBy('jobs.'.$orderBy, $orderDir)
                    // pagination
                    ->paginate($this->perPage);

            $result = $this->setResult('success', '', $jobs);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Get job details
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function getJobDetails(string $id) :array
    {
        $result = [];

        try
        {
            $job = Job::find($id);

            if ($job)
            {
                $job = DB::table('jobs')
                        ->join('job_categories', 'jobs.job_category_id', '=', 'job_categories.id')
                        ->join('job_types', 'jobs.job_type_id', '=', 'job_types.id')
                        ->join('job_locations', 'jobs.job_location_id', '=', 'job_locations.id')
                        ->select(
                            'jobs.id AS id',
                            'job_categories.name AS category',
                            'jobs.created_at AS created',
                            'jobs.description AS description',
                            'jobs.email AS email',
                            'jobs.highlighted AS highlighted',
                            'jobs.link AS link',
                            'job_locations.name AS location',
                            'jobs.name AS name',
                            'jobs.title AS title',
                            'job_types.name AS type',
                            'jobs.views AS views',
                            'jobs.application_deadline AS deadline'
                        )
                        ->where('jobs.id','=',$id)
                        ->where('jobs.deleted', '0')
                        ->first();

                $result = $this->setResult('success', '', $job);
            }
            else
            {
                $result = $this->setResult('not_found', 'Posao ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Save job
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function saveJob(Object $request) :array
    {
        $result = [];

        $newJob = [
            'job_category_id' => $request->input('job_category_id'),
            'job_type_id' => $request->input('job_type_id'),
            'job_location_id' => $request->input('job_location_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'application_deadline' => $request->input('application_deadline'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'link' => $request->input('link'),
        ];

        try
        {
            $job = Job::create($newJob);
            $result = $this->setResult('success', '', $job);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Update job
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function updateJob(Object $request) :array
    {
        $result = [];

        try
        {
            $job = Job::find($request->input("id"));

            if ($job)
            {
                $job->title = $request->input("title");
                $job->job_category_id = $request->input("job_category_id");
                $job->save();
                $result = $this->setResult('success', '', $job);
            }
            else
            {
                $result = $this->setResult('not_found', 'Posao ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Delete job
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function deleteJob(string $id) :array
    {
        $result = [];

        try
        {
            $job = Job::find($id);

            if ($job)
            {
                $job->deleted = 1;
                $job->save();
                $result = $this->setResult('success', '', $job);
            }
            else
            {
                $result = $this->setResult('not_found', 'Posao ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
