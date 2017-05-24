<?php

namespace App\Http\Controllers;

use App\Post;
use App\Answer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PostController extends Controller
{

    public function index(){

        $posts = Post::orderBy('id', 'desc')->get();
        return view('home', ['posts' => $posts]);

    }

    public function getNew(){

        return view('posts.new');

    }

    public function postNew(Request $request){

        $inputs = $request->all();

        if(empty($inputs['title']) || empty($inputs['content'])){
            return view('posts.new', ['error' => 'Boş alan bırakmadığınızdan emin olun!', 'inputs' => $inputs]);
        }else{

            //Postu bir kullanıcıya bağlayabilmek için session üzerinde tuttuğumuz userID verisine erişmemiz gerekiyor
            $userID = $request->session()->get('userID');

            try{
                $post = new Post(['title' => $inputs['title'], 'content' => $inputs['content'], 'user_id' => $userID]);
                $post->save();

                return redirect('/posts');
            }catch(QueryException $ex){
                return view('posts.new', ['error' => 'Soru kaydedilirken bir sorun oluştu :( => ' . $ex->getMessage(), 'inputs' => $inputs]);

            }

        }

    }

    //Posta ait detay sayfasını görüntülerken kullanacağımız metot
    //Diğerlerinden farklı olarak bu metot get parametresi kabul ediyor

    public function view($id){

        $post = Post::find($id);

        if($post != null){

            //postlar için görüntüleme sayısınıda tutuyorduk
            //sayfa görüntülendiği için görüntülenmeyi 1 artırabiliriz
            $post->view++;
            $post->save();

            return view('posts.view', ['post' => $post]);

        }else{
            //Eğer parametre olarak girilen id ye ait post bulunamadıysa kullanıcıyı tekrar anasayfaya yönlendiriyoruz
            return redirect('/');
        }

    }

    //Posta cevap yazılmak istendiğinde bu metot ile gerekli işlemleri yapıyoruz
    public function postAnswer($id, Request $request){

        $inputs = $request->all();

        $post = Post::find($id);

        $userID = $request->session()->get('userID');

        if($post != null && $userID != null){

            if(empty($inputs['content'])){
                return view('posts.view', ['post' => $post, 'error' => 'Cevap yazdın ama boş :(']);
            }else{
                try{
                    $answer = new Answer(['content' => $inputs['content'], 'user_id' => $userID, 'post_id' => $id]);
                    $answer->save();

                    return redirect('/posts/' . $id);
                }catch(QueryException $ex){
                    return view('posts.new', ['post' => $post, 'error' => 'Cevap kaydedilirken bir sorun oluştu :( => ' . $ex->getMessage(), 'inputs' => $inputs]);
                }

            }

        }else{
            //Eğer parametre olarak girilen id ye ait post bulunamadıysa veya sessionda user id yoksa kullanıcıyı tekrar anasayfaya yönlendiriyoruz
            return redirect('/');
        }

    }

    //Posların ve cevapların oylanabilmesi için jQuery ile dinamik bir ajax request yazmıştık
    //Bu istkelerin karşılığı olan metodu yazıyoruz
    public function vote($id, $type, $vote){
        //iki çeşit entitiymiz olduğu için $type değişkenini kullanıyoruz. answer veya post olabilir
        //aynı zamanda iki şekilde oy verilebildiği için vote parametresini kullanıoyuruz. up veya down olabilir

        $response = [];

        $object = null;

        if($type == 'answer'){

            $object = Answer::find($id);

        }else if($type == 'post'){

            $object = Post::find($id);

        }

        if($object != null){

            if($vote == 'up')
                $object->vote++;
            else
                $object->vote--;

            try{
                $object->save();
                $response['status'] = true;
            }catch(QueryException $ex){
                $response['status'] = false;
                $response['error'] = "Kaydederken bir hata oldu :( => " . $ex->getMessage();
            }

        }else{
            $response['status'] = false;
            $response['error'] = "İlgili obje bulunamadı";
        }

        return json_encode($response);


    }

}
