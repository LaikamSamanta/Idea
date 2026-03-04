## Routes un autentifikācijas, User loģika

1. Izveidojam Route routes/web.php failā

```php
Route::get('/register', [RegisteredUserController::class, 'create']);
```

2. Izveidojam kontrolieri reģistrētiem lietotājiem ar nosaukumu RegisteredUserController un tukšu

```bash
php artisan make:controller
```

3. Pēc kontroliera izveides web.php failā importējam jauno kontrolieri

```php
use App\Http\Controllers\RegisteredUserController;
```

4. RegisteredUserController failā pievienojam funkciju, kas rādīs reģistrēšanās formu

```php
public function create() {
        return view('auth.register');
    }
```

5. Izveidojam views mapē vēl vienu mapi (auth) un failu (register.blade.php)

```php
<x-layout>
 <x-form title="Register an account" description="Start tracking your ideas today!">
      <form action="/register" method="POST" class="mt-10 space-y-4">
        @csrf

        <x-form.field name="name" label="Name" />

        <x-form.field name="email" label="Email" type="email" />

        <x-form.field name="password" label="Password" type="password" />

        <button type="submit" class="btn mt-2 h-10 w-full">Create Account</button>
      </form>
 </x-form>
</x-layout>
```

6. Izveido jaunu mapi (form) iekš views/components un tajā failu ar nosaukumu (form.blade.php)

```php
 @props(['title', 'description'])
 
 <div class="flex min-h-[calc(100dvh-4rem)] items-center justify-center px-4">
    <div class="w-full max-w-md">
      <div class="text-center">
        <h1 class="text-3xl font-bold tracking-tight">{{ $title }}</h1>
        <p class="text-muted-foreground mt-1">{{ $description }}</p>
      </div>

    {{ $slot }}
    </div>
  </div>
  ```
7. Mapē views/components/form, izveido failu ar nosaukumu (field.blade.php)

```php
@props(['label', 'name', 'type' => 'text'])

<div class="space-y-2">
  <label for="{{ $name }}" class="label">{{ $label }}</label>
 <input type="{{ $type }}" class="input" id="{{ $name }}" name="{{ $name }}" value="{{ old($name) }}" {{ $attributes }}>
</div>
```

8. Mapē views/auth, izveidot login.blade.php

9. Izveido jaunu Route Ielogošanai

```php
Route::get('/login', [SessionsUserController::class, 'create']);
```

10. Izveido ielogošanās kontrolieri tukšu - SessionsUserController

11. SessionsUserController failā pievieno funkciju

```php
 public function create() {
        return view('auth.login');
    }
```
12. Atjaunojam RegsiteredUserController kontrolieri

```php
<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // password is already hashed in Laravel 12
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registration complete!');
    }
}

```

13. Izveido store action Route priekš reģistrācijas un ielogošanās web.php failā

```php
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [SessionsUserController::class, 'store']);
```

14. Pievienojam error ziņas formai

```php

 @error($name)
    <p class="error">{{ message }}</p>
  @enderror 

```

14. Pievienojam routiem middleware, lai norādītu piekļuvi un logout route

```php
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [SessionsUserController::class, 'create'])->middleware('guest');
Route::post('/login', [SessionsUserController::class, 'store'])->middleware('guest');

Route::post('/logout', [SessionsUserController::class, 'destroy'])->middleware('auth');
```

15. Navigāvijas failā nav.blade.php pievienojam piekļuves jeb lomas skatījuma dažādību, ko viesi redz un ko reģistrējušies lietotāji redz

```php

<nav class="border-b border-border px-6">
  <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
    <div>
      <a href="/">
        <img src="/images/logo.png" alt="Idea logo" width="100" />
      </a>
    </div>

    <div class="flex gap-x-5 items-center">
      @auth
        <form method="POST" action="/logout">
          @csrf

          <button>Log out</button>
        </form>

      @endauth

      @guest

        <a href="/login">Sign In</a>
        <a href="/register" class="btn">Register</a>
      @endguest
    </div>
  </div>
</nav>

```

16. SessionsUserController kontrolierī pievieno klāt funkcijas, kas ļaus ielogoties ar korektiem datiem un izlogošanās loģika

```php

 public function store(Request $request) {
        $attributes = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        if (!Auth::attempt($attributes)) {
            return back()
                ->withErrors(['password' => 'We were unable to authenticate using the provided credentials.'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'You are now logged in.');
    }

    public function destroy() {
        Auth::logout();

        return redirect('/');
    }

```