<?php

namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Role extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get list of roles
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getRolesList(Object $request) :array
    {
        $result = [];

        try {
            $roles = DB::table('roles')
                    ->select(
                        'roles.id AS id',
                        'roles.name AS name'
                    )
                    ->where('roles.deleted', '0')
                    ->orderBy('roles.id', 'asc')
                    ->get();

            $result = $this->setResult('success', '', $roles);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Get role details
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function getRoleDetails(string $id) :array
    {
        $result = [];

        try
        {
            $role = Role::find($id);

            if ($role)
            {
                $result = $this->setResult('success', '', $role);
            }
            else
            {
                $result = $this->setResult('not_found', 'Uloga ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Save role
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function saveRole(Object $request) :array
    {
        $result = [];

        $newRole = [
            'name' => $request->input('name')
        ];

        try
        {
            $role = Role::create($newRole);
            $result = $this->setResult('success', '', $role);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Update role
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function updateRole(Object $request) :array
    {
        $result = [];

        try
        {
            $role = Role::find($request->input("id"));

            if ($role)
            {
                $role->name = $request->input("name");
                $role->save();
                $result = $this->setResult('success', '', $role);
            }
            else
            {
                $result = $this->setResult('not_found', 'Uloga ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Delete role
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function deleteRole(string $id) :array
    {
        $result = [];

        try
        {
            $role = Role::find($id);

            if ($role)
            {
                $role->deleted = 1;
                $role->save();
                $result = $this->setResult('success', '', $role);
            }
            else
            {
                $result = $this->setResult('not_found', 'Uloga ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
