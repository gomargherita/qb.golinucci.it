<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProjectDeclarationsRule implements Rule
{

    private $declarations;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($declarations)
    {
        $this->declarations = $declarations;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $flag_all_selected = true;
        foreach($this->declarations as $declaration){
            if(!in_array($declaration->id, $value)){
                $flag_all_selected = false;
            }
        }
        return $flag_all_selected;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'All declarations must be selected. If you cannot proceed contact us.';
    }
}
