<?php
 
namespace App\Models;

use App\Models\BaseModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class ListEmail extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];

    /**
     * Get list of emails
     * 
     * @throws QueryException
     * @param object $request
     * @return array
     */
    public function getEmailsList(Object $request) :array
    {
        $result = [];
        
        $orderBy = 'id';
        $orderDir = 'asc';

        if (!empty($request->get('orderBy'))) $orderBy = $request->get('orderBy');
        if (!empty($request->get('orderDir'))) $orderDir = $request->get('orderDir');

        // validate input (including page)

        try {
            $types = DB::table('list_emails')
                    ->select(
                        'list_emails.id AS id',
                        'list_emails.email AS email'
                    )
                    ->where('list_emails.deleted', '0')
                    ->orderBy('list_emails.'.$orderBy, $orderDir)
                    ->paginate($this->perPage);

            $result = $this->setResult('success', '', $types);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    public function saveEmail($request)
    {
        $result = [];

        $newEmail = [
            'email' => $request->input('email'),
        ];

        try {
            $email = ListEmail::create($newEmail);
            $result = $this->setResult('success', '', $email);
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

    public function deleteEmail($id)
    {
        $result = [];

        try {
            $email = ListEmail::find($id);
            if ($email) {
                $email->deleted = 1;
                $email->save();
                $result = $this->setResult('success', '', $email);
            } else {
                $result = $this->setResult('not_found', 'Email ne postoji');
            }
        } catch (QueryException $e) {
            $result = $this->setResult('error', $e->getMessage());
        }

        return $result;
    }

}
