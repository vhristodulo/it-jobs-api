<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class BaseModel extends Model
{
    /**
     * Results
     * 
     * @var array
     */
    protected $result = [
        'success' => [
            'status' => [
                'success' => true,
                'code' => 200,
                'message' => ''
            ],
            'data' => []
        ],
        'not_found' => [
            'status' => [
                'success' => false,
                'code' => 400,
                'message' => ''
            ],
            'data' => []
        ],
        'error' => [
            'status' => [
                'success' => false,
                'code' => 500,
                'message' => ''
            ],
            'data' => []
        ]
    ];

    /**
     * Pagination per page items
     * 
     * @var int
     */
    protected $perPage = 20;

    /**
     * Validation
     * 
     * @var array
     */
    protected $validation = [];

    /**
     * Get validation rules
     * 
     * @return array
     */
    public function getValidationRules() :array
    {
        $validationRules = [];

        foreach ($this->validation as $field => $rules)
        {
            $rulesArray = [];
            foreach ($rules as $rule)
            {
                if (!isset($rule['rule'][1]))
                    $rulesArray[] = $rule['rule'][0];
                else
                    $rulesArray[] = $rule['rule'][0].':'.$rule['rule'][1];
            }
            $validationRules[$field] = implode('|', $rulesArray);
        }

        return $validationRules;
    }

    /**
     * Get validation messages
     * 
     * @return array
     */
    public function getValidationMessages() :array
    {
        $validationMessages = [];

        foreach ($this->validation as $field => $rules)
        {
            foreach ($rules as $rule)
            {
                $validationMessages[$field.'.'.$rule['rule'][0]] = $rule['message'];
            }
        }

        return $validationMessages;
    }

    /**
     * Set result
     * 
     * @return array
     */
    protected function setResult(string $type, string $message, object $data = null) :array
    {
        $result = $this->result[$type];

        $result['status']['message'] = $message;
        $result['data'] = (empty($data)) ? [] : $data;

        return $result;
    }

}