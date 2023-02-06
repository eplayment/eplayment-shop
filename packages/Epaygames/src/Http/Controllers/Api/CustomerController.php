<?php
namespace Epaygames\Http\Controllers\Api;


use Illuminate\Foundation\Auth\ResetsPasswords;
use Epaygames\Http\Controllers\Controller;


class CustomerController extends Controller
{
    use ResetsPasswords;

    public function store()
    {
        try {
            $this->validate(request(), [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed|min:6',
            ]);

            $response = $this->broker()->reset(
                request(['email', 'password', 'password_confirmation', 'token']), function ($customer, $password) {
                    $this->resetPassword($customer, $password);
                }
            );

            return response()->json([
                'message' => trans($response)
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'message' => trans($e->getMessage())
            ]);
        }
    }
}
