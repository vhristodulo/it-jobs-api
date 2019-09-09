<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use App\Models\ListEmail;
 
class ListEmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ListEmail = new ListEmail();
    }

    /**
     * Get list of emails
     * 
     * @param object $request
     * @return string
     */
    public function list(Request $request)
    {
        $result = $this->ListEmail->getEmailsList($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Save email
     * 
     * @param object $request
     * @return string
     */
    public function subscribe(Request $request)
    {
        $result = $this->ListEmail->saveEmail($request);
        return response($result, $result['status']['code']);
    }

    /**
     * Delete email
     * 
     * @param string $id
     * @return string
     */
    public function delete($id)
    {
        $result = $this->ListEmail->deleteEmail($id);
        return response($result, $result['status']['code']);
    }

}