<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Available implements Rule
{
    private $table;
    /**
     * Create a new rule instance.
     *
     * @param $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool|Model|Builder|object
     */
    public function passes($attribute, $value)
    {
        // return DB::table($this->table)->where('primary_id', primaryID())
        //     ->where($attribute, $value)->count();
        return DB::table($this->table)->where($attribute, $value)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not Found';
    }
}
