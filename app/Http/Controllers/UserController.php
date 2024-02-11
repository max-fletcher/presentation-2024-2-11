<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Examples\Examples;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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

  // THE DANGER WITH PASSING $request->all();
  public function requestAll(Request $request, string $id) : JsonResponse
  {
    $valdiated = $request->validate([
      'amount' => ['required', 'numeric'],
    ]);

    // dd($valdiated);
    // dd($request->all());

    Transaction::where('id', $id)->update($request->all());

    $transaction = Transaction::where('id', $id)->first();

    return response()->json(['data' => $transaction]);
  }


  // SERVICE CLASS & QUERY SCOPES & ARRAYS & SELECT STATEMENTS
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

    return response()->json(['data' => $sum, 'size' => $size, 'user' => $user]);
  }

  // JOINS AND SUBQUERY
  public function joinsVsSubquery() : JsonResponse
  {
    // FETCHING DATA

    // QUERY BUILDER
    // $users = DB::table('users')->join('accounts', 'users.id', '=', 'accounts.user_id')->select('users.*', 'accounts.remaining_balance')->get();

    // MODEL WITH JOIN
    // $users = User::join('accounts', 'users.id', '=', 'accounts.user_id')->select('users.*', 'accounts.remaining_balance')->get();

    // MODEL WITH EAGER LOADING
    // $users = User::with([
    //                 'account' => function($q){
    //                   return $q->select('id', 'remaining_balance');
    //                 }
    //               ])->get();



    // MAKING A QUERY WITH IMPOSING CONDITIN ON CHILD TABLE

    // QUERY BUILDER WITH WHEREBETWEEN
    $users = DB::table('users')
              ->join('accounts', 'users.id', '=', 'accounts.user_id')
              ->select('users.*', 'accounts.*')
              ->whereBetween('accounts.remaining_balance', [2000, 5000])
              ->get();

    // QUERY BUILDER WITH SUBQUERY
    // $users = DB::table('users')
    //           ->whereRaw('exists (SELECT * FROM accounts WHERE users.id = accounts.user_id AND remaining_balance BETWEEN 2000 AND 5000)')
    //           ->get();

    // MODEL WITH SIMPLE WHEREHAS
    // $users = User::whereHas(
    //                 'account', function($q){
    //                   return $q->whereBetween('accounts.remaining_balance', [2000, 5000]);
    //                 }
    //               )->get();

    return response()->json(['data' => $users]);
  }


  // VALIDATION WITH TRY-CATCH AND CUSTOM EXCEPTIONS. THE DILEMMA BEING, I DON'T WANT TO USE Rule::exists BECAUSE I AM FETCHING $user AWYWAY
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
