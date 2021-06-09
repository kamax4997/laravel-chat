<?php

namespace App\Http\Controllers;

use App\Alias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\User;
use App\Role;
use App\GeoCountry;
use App\GeoRegion;
use App\GeoCity;
use App\AvatarDefault;

class AuthController extends Controller
{
  /**
   * Create user
   *
   * @param  [string] name
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @return [string] message
   */
  public function signup(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|string|email|unique:users',
      'password' => 'required|string|confirmed'
    ]);
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->first_name = $request->firstname;
    $user->last_name = $request->lastname;
    $user->birthday = $request->birthday;
    $user->gender = $request->gender;
    $user->martial = $request->martial;
    $user->country = $request->country;
    $user->region = $request->region;
    $user->city = $request->city;
    $user->question = $request->question;
    $user->secret = $request->secret;
    $user->avatar = "";

    if($request->file) {
      $fileName = time().'.'.$request->file->extension();
      $request->file->move(public_path('uploads'), $fileName);
      $user->avatar = $fileName;
    }

    // send mail
    $consts = config('const');

    $to = $request->email;
    $from = $consts['register_mail'];
    $subject = "Welcome";
    $message = file_get_contents(resource_path('views/email/register.blade.php'));
    $message = str_replace('{username}', $request->name, $message);
    $message = str_replace('{usermail}', $request->email, $message);

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $message = str_replace('{activecode}', $randomString, $message);
    $user->activate_code = $randomString;
    
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: $from"; 
    $ok = @mail($to, $subject, $message, $headers, "-f " . $from);
    if($ok) {
      $user->save();
      return response()->json([
        'message' => 'Successfully created user!'
      ], 201);
    } else {
      return response()->json([
        'message' => 'Failed to send mail'
      ], 401);
    }
  }

  /**
   * Login user and create token
   *
   * @param  [string] email
   * @param  [string] password
   * @param  [boolean] remember_me
   * @return [string] access_token
   * @return [string] token_type
   * @return [string] expires_at
   */
  public function login(Request $request)
  {

    $params = request()->all();
    $guessRaw = $params['guess'];
    $guess = strtolower($guessRaw);
    $gender = $params['gender'];

    // We check if login is guess and create a user.
    if ($guess) {
      try {
        $cleanName = preg_replace('/\s+/', '_', $guess);
        $name = "guest_{$cleanName}";
        $alias = "Guest ". ucwords($guess);
        $password = 'p@ssw0rd@123';

        $user = User::create([
          'name' => $guessRaw,
          'email' => "{$name}@chathorizon.net",
          'gender' => $gender,
          'password' => Hash::make($password),
        ]);

        // Add guest role to the user.
        $guestRole = Role::find(12);
        $user->roles()->attach($guestRole);

        // Create guest alias.
        $alias = Alias::create([
          'alias' => $alias,
          'slug' => $name,
          'gender' => $gender,
          'role_id' => $guestRole->id,
          'user_id' => $user->id,
        ]);

        // Save default avatar
        $avatar =  AvatarDefault::where('gender', $gender)->first();

        $alias->bodies = $avatar['bodies'];
        $alias->hair = $avatar['hair'];
        $alias->faces = $avatar['faces'];
        $alias->pants = $avatar['pants'];
        $alias->shirts = $avatar['shirts'];
        $alias->coats = $avatar['coats'];
        $alias->shoes = $avatar['shoes'];
        $alias->head_accessories = $avatar['head_accessories'];
        $alias->accessories = $avatar['accessories'];
        $alias->specials = $avatar['specials'];
        $alias->save();

        request()['email'] = $user->email;
        request()['password'] = $password;

      } catch (\Illuminate\Database\QueryException $e) {

        abort(422, "The username {$guess} is already taken, please try another name.");
      }
    }

    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
      'remember_me' => 'boolean'
    ]);

    $credentials = request(['email', 'password']);

    if (!Auth::attempt($credentials))
      return response()->json([
        'message' => "{$params['email']} is not a valid user, or credentials is incorrect"
      ], 401);

    $user = $request->user();
    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->token;

    if ($request->remember_me)
      $token->expires_at = Carbon::now()->addWeeks(1);
    $token->save();

    return response()->json([
      'csrf' => $user->id,
      'user_name' => $user->name,
      'access_token' => $tokenResult->accessToken,
      'token_type' => 'Bearer',
      'expires_at' => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString()
    ]);
  }

  /**
   * Login user and create token
   *
   * @param  [string] email
   * @param  [string] password
   * @param  [boolean] remember_me
   * @return [string] access_token
   * @return [string] token_type
   * @return [string] expires_at
   */
  public function loginGuess(Request $request)
  {
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
      'remember_me' => 'boolean'
    ]);
    $credentials = request(['email', 'password']);

    if (!Auth::attempt($credentials))

      return response()->json([
        'message' => 'Unauthorized'
      ], 401);

    $user = $request->user();
    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->token;

    if ($request->remember_me)
      $token->expires_at = Carbon::now()->addWeeks(1);
    $token->save();

    return response()->json([
      'access_token' => $tokenResult->accessToken,
      'token_type' => 'Bearer',
      'expires_at' => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString()
    ]);
  }

  /**
   * Logout user (Revoke the token)
   *
   * @return [string] message
   */
  public function logout(Request $request)
  {
    $request->user()->token()->revoke();
    return response()->json([
      'message' => 'Successfully logged out'
    ]);
  }

  /**
   * Get the authenticated User
   *
   * @return [json] user object
   */
  public function user(Request $request)
  {
    return response()->json($request->user());
  }

  /**
   * Updates the user model.
   *
   * @return [json] user object
   */
  public function update($id)
  {
    $params = request()->all();
    $user = User::find($id);
    $user->chat_interface = $params['chat_interface'];
    $user->save();
    $user->refresh();

    return response()->json($user);
  }

  public function getCountry(Request $request) {
    $country_list = GeoCountry::orderBy('co_name')->get();
    $country = $country_list[0]->co_code;
    $region_list = GeoRegion::where('rg_country', $country)
                  ->select(['rg_code', 'rg_name'])
                  ->orderBy('rg_name')
                  ->get();
    $region =  $region_list[0]->rg_code;
    $city = GeoCity::where('ci_country', $country)
          ->where('ci_region', $region)
          ->select(['ci_id', 'ci_name'])
          ->orderBy('ci_name')
          ->get();

    return response()->json([
      'country' => $country_list,
      'region' => $region_list,
      'city' => $city
    ], 201);
  }

  public function getRegion(Request $request) {
    $city = [];
    $region = [];
    $message = "";
    $country = $request->country;
    if($country) {
      $region = GeoRegion::where('rg_country', $country)
              ->select(['rg_code', 'rg_name'])
              ->orderBy('rg_name')
              ->get();

      if(count($region) > 0) {
        $region1 = $region[0]->rg_code;
        $city = GeoCity::where('ci_country', $country)
              ->where('ci_region', $region1)
              ->select(['ci_id', 'ci_name'])
              ->orderBy('ci_name')
              ->get();
      }
    } else {
      $message = "Invalid country code";
    }

    if(strlen($message) > 0) {
      return response()->json([
        'message' => $message
      ], 400);
    } else {
      return response()->json([
        'region' => $region,
        'city' => $city
      ], 201);
    }
  }

  public function getCity(Request $request) {
    $city = [];
    $message = "";
    $country = $request->country;
    $region = $request->region;
    if($country && $region) {
      $city = GeoCity::where('ci_country', $country)
            ->where('ci_region', $region)
            ->select(['ci_id', 'ci_name'])
            ->orderBy('ci_name')
            ->get();
    } else {
      $message = "Invalid country or region code";
    }

    if(strlen($message) > 0) {
      return response()->json([
        'message' => $message
      ], 400);
    } else {
      return response()->json([
        'city' => $city
      ], 201);
    }
  }
}
