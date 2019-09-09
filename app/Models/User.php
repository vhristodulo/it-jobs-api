<?php

namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get list of users
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getUsersList(Object $request) :array
    {
        $result = [];

        try {
            $users = DB::table('users')
                    ->select(
                        'users.id AS id',
                        'users.name AS name',
                        'users.email AS email'
                    )
                    ->where('users.deleted', '0')
                    ->orderBy('users.id', 'asc')
                    ->get();

            $result = $this->setResult('success', '', $users);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Get user details
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function getUserDetails(string $id) :array
    {
        $result = [];

        try
        {
            $user = User::find($id);

            if ($user)
            {
                $result = $this->setResult('success', '', $user);
            }
            else
            {
                $result = $this->setResult('not_found', 'Korisnik ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Save user
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function saveUser(Object $request) :array
    {
        $result = [];

        $newUser = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        try
        {
            $user = User::create($newUser);
            $result = $this->setResult('success', '', $user);
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Update user
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function updateUser(Object $request) :array
    {
        $result = [];

        try
        {
            $user = User::find($request->input("id"));

            if ($user)
            {
                $user->name = $request->input("name");
                $user->email = $request->input("email");
                $user->save();
                $result = $this->setResult('success', '', $user);
            }
            else
            {
                $result = $this->setResult('not_found', 'Korisnik ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    /**
     * Delete user
     * 
     * @throws QueryException
     * @param string $id
     * @return array
     */
    public function deleteUser(string $id) :array
    {
        $result = [];

        try
        {
            $user = User::find($id);

            if ($user)
            {
                $user->deleted = 1;
                $user->save();
                $result = $this->setResult('success', '', $user);
            }
            else
            {
                $result = $this->setResult('not_found', 'Korisnik ne postoji');
            }
        }
        catch (QueryException $e)
        {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
