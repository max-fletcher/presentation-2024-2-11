<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Examples\Examples;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Services\ExampleService;
use App\Exceptions\api\v1\CustomException;
use App\Exceptions\api\v1\NotFoundException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  public function __construct(
    protected ExampleService $service
  ) {}

  // METHOD CHAINING
  public function getStudent() : JsonResponse
  {
    $obj = new Examples();
    $studentInfo = $obj->setStudentId(1212)
                        ->setStudentName('John Smith')
                        ->setStudentAddress('123 Baker Street')
                        ->getStudentInfo();

    return response()->json(['data' => $studentInfo]);
  }

  // SERVICE CLASS
  public function usingServiceClass(Request $request) : JsonResponse
  {
    $request->validate([
      'sum' => ['required', 'numeric', 'integer', 'gte:0'],
    ]);

    $before = memory_get_usage();

    $user = User::where('name', 'LIKE', '%a%')
                ->where('email', 'LIKE', '%example%')
                ->with([
                  'account' => function($q){
                    return $q->where('remaining_balance', '>', '20')
                              ->where('remaining_balance', '<', '10000');
                  }])
                ->get();

    $after = memory_get_usage();

    $size = $after - $before;

    $sum = $request->sum;

    for($i = 0; $i < 1000; $i++)
      if($i % 5 === 0){
        $sum += $i;
      }
      elseif($i % 3 === 0){
        $sum /= $i;
      }
      elseif($i % 2 === 0){
        $sum *= $i;
      }

    return response()->json(['data' => $sum, 'user' => $user, 'size' => $size]);
  }

  // VALIDATION WITH TRY-CATCH. THE DILEMMA BEING, I DON'T WANT TO USE Rule::exists BECAUSE I AM FETCHING $user AWYWAY
  // SO THAT IS ONE TOO MANY QUERIES(i.e IF VALIDATOR HAS TOO MANY Rule::exists)
  public function validateWithTryCatch(Request $request, string $id) : JsonResponse
  {
    $user = User::find($id);

    $validator = validator($request->all(), [
        'name' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
    ]);

    try {
      if(!$user)
        throw new NotFoundException('Team not found.');

      if($validator->fails())
          throw new ValidationException($validator);

      } catch (ValidationException $e){
          return $validator->validate();
      } catch (NotFoundException|CustomException $e) {
          return response()->json(['status' => 'failed', 'message' => $e->getMessage()], $e->getCode());
      } catch (Exception $e) {
          return response()->json(['error_code' => 500, 'error_message' => 'Something went wrong.']);
      } catch (\Error $e) {
          return response()->json(['error_code' => 500, 'error_message' => 'Something went wrong.']);
      }

    return response()->json(['data' =>'some data here bruh']);
  }
}
