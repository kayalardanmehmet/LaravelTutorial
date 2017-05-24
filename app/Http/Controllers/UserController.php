<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\User;

class UserController extends Controller
{

    public function doRegister(Request $request){
        //methoda eklediğimiz Request objesi sayesinde istekle beraber gelen
        // parameterlerin tümünü alıyoruz
        $inputs = $request->all();

        //kayıt işlemi için gerekli kontrolleri sağlıyoruz
        if(empty($inputs['name']) || empty($inputs['surname']) || empty($inputs['email']) || empty($inputs['password']) || empty($inputs['password-again'])){
            return view('register', ['error' => 'Boş alan bırakmadığınızdan emin olun!', 'inputs' => $inputs]);
        }else if($inputs['password'] != $inputs['password-again']){
            return view('register', ['error' => 'Şifreler birbiri ile uyuşmuyor!', 'inputs' => $inputs]);
        }else if(!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)){
            return view('register', ['error' => 'Geçerli bir email adresi girdiğinden emin olup tekrar dene!', 'inputs' => $inputs]);
        }else{
            try{
                $user = new User(['email' => $inputs['email'], 'name' => $inputs['name'], 'surname' => $inputs['surname'], 'password' => $inputs['password']]);
                $user->save();

                //Kayıt işlemi başarılı olursa girişte olduğu gibi kullanıcıya ait verileri sessionda saklıyoruz
                $request->session()->put('userID', $user['id']);
                $request->session()->put('email', $user['email']);
                $request->session()->put('name', $user['name']);
                $request->session()->put('surname', $user['surname']);

                return redirect('/');
            }catch(QueryException $ex){
                return view('register', ['error' => 'Bu email adresi daha önce kayıt edilmiş veya bir sorun oluştu!', 'inputs' => $inputs]);
            }
        }

    }

    //Login formunun yönlendiği controller metodu
    public function doLogin(Request $request){

        //methoda eklediğimiz Request objesi sayesinde istekle beraber gelen
        // parameterlerin tümünü alıyoruz
        $inputs = $request->all();

        if(empty($inputs['email']) || empty($inputs['password'])){
            return view('login', ['error' => 'Boş alan bırakmadığınızdan emin olun!', 'inputs' => $inputs]);
        }else{

            $users = \App\User::where('email', $inputs['email'])->get();

            if(count($users) > 0){

                if($users[0]->password == $inputs['password']){
                    //Eğer giriş işlemi başarılı olursa kullanıcıya ait verileri session a kaydediyoruz
                    //Daha sonra bu verileri kullanarak kullancının giriş yapıp yapmadığını anlayacağız
                    //Ayrıca ileride kullanıcı ile ilgili işlemleri yaparken ayırt etmek için kullanılacak
                    $request->session()->put('userID', $users[0]['id']);
                    $request->session()->put('email', $inputs['email']);
                    $request->session()->put('name', $users[0]['name']);
                    $request->session()->put('surname', $users[0]['surname']);

                    return redirect('/');
                }else{
                    return view('login', ['error' => 'Şifre yanlış oldu!', 'inputs' => $inputs]);
                }

            }else{
                return view('login', ['error' => 'Bu email adresine sahip kullanıcı bulamadık!', 'inputs' => $inputs]);
            }

        }

    }

    //Logout isteğinin karşılığı olan metot
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/login');
    }

}
