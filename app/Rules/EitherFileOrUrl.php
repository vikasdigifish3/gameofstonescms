<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class EitherFileOrUrl implements Rule
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function passes($attribute, $value)
    {
        $data = $this->request->all();
        return $this->request->hasFile('file_path') || !empty($data['file_url']);
    }

    public function message()
    {
        return 'Please provide either a file or a URL.';
    }
}
