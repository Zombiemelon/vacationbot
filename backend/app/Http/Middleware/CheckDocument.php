<?php

namespace App\Http\Middleware;

use Closure;
use http\Client\Response;

class CheckDocument
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $errors = [];
        if (!is_int($request['number'])) {
            $errors[] = 'Incorrect Number';
        };
        if (!is_string($request['dateForLoading'])) {
            $errors[] = 'Incorrect Date for Loading';
        };
        if (!is_int($request['truckNumber'])) {
            $errors[] = 'Incorrect Truck Number';
        };
        if (!is_string($request['driver'])) {
            $errors[] = 'Incorrect Driver';
        };
        if (!is_string($request['driverPassport'])) {
            $errors[] = 'Incorrect Driver Passport';
        };
        $documentType = ['distribution_list'];
        if (!in_array($request['document_type'], $documentType)) {
            $errors[] = "${$request['document_type']} is not in the list";
        };
        $documentFormat = ['pdf'];
        if (!in_array($request['document_format'], $documentFormat)) {
            $errors[] = "${$request['document_format']} is not supported";
        };
        $deliveryMethod = ['email', 'download'];
        if (!in_array($request['delivery_method'], $deliveryMethod)) {
            $errors[] = "${$request['delivery_method']} is not supported";
        };

        if (!empty($errors))
        {
            $errors = implode($errors, ", ");
            return response($errors, 400);
        }

        return $next($request);
    }
}
