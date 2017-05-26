# Proje Tanımı

  Bu projede, sizlere Laravel'in nimetlerinden faydalanarak nasıl "StackOverFlow" benzeri bir soru-cevap sitesi yaratabileceğinizi göstermeye çalıştık. Bu uygulama gayet basit bir seviyede düşünülmüş olup; kayıt olmak, giriş yapmak, soru sormak, cevaplamak ve puan vermek gibi temel işlevlerin yerine getirilmesi üzerine yapılmıştır. Uygulamanın tüm kaynak koduna GitHub üzerinden [bu link](https://github.com/kayalardanmehmet/LaravelTutorial) ile erişebilirsiniz.

  Bu tutorial kapsamında; PHP, HTML, CSS ve JavaScript hakkında temel bilgilerinizin olduğunu kabul ediyoruz. Ayrıca bu örnek projeyi Laravel 5.4'ü kullanarak oluşturduk. Örnek projeyi kendi ortamınızda çalıştırmak için aşağıdaki gereksinimleri sağlamanız gerekmekte.

  * PHP >= 5.6.4
  * OpenSSL PHP Extension
  * PDO PHP Extension
  * Mbstring PHP Extension
  * Tokenizer PHP Extension
  * XML PHP Extension




# İlk Adım : Kurulum

  Laravel, dependency'lerin kurulumu için [Composer](https://getcomposer.org/) 'ı kullanmakta. Dolayısıyla Laravel'i kullanmaya başlamadan önce sisteminize Composer'ı kurmanız gerekmekte.

  Laravel'in kurulumu için iki alternatifimiz var:

  * Laravel installer

    Kurulum için tavsiye ettiğimiz yöntem budur.
    Composer aracılığıyla Laravel installer'ı kurun:

    `composer global require "laravel/installer"`

    Installer yüklenikten sonra, `laravel new` komutu belirttiğiniz klasörde bir kurulum yapmanızı sağlayacak. Örneğin `laravel new tutorial` komutu, bulunduğunuz konumda bir  "tutorial" klasörü oluşturacak ve bunun içerisine bütün dependency yüklenmiş halde bir Laravel kurulumu yapacak.

  * Composer Create-Project

    Bu yöntemde yine Composer'ı kullanarak ancak araya 3. parti uygulamaları dahil etmeden yeni bir Laravel projesi oluşturabilirsiniz.

    `composer create-project --prefer-dist laravel/laravel blog`

  Artık elimizde örnek bir Laravel projesi olduğuna göre, projenin ana dizinindeyken aşağıdaki komutu çalıştırarak, basit bir PHP server'ı ayağa kaldırabiliriz. Çalıştırdıktan sonra bu örnek projeye `http://localhost:8000` adresinden ulaşabiliriz.

  `php artisan serve`

  Son olarak, proje içerisindeki `storage` ve `bootstrap/cache` klasörlerinin izinlerini web server'ın erişebileceği şekilde düzenlemeliyiz. Aksi bir durumda projemiz çalışmayabilir. Ayrıca eğer bu projeyi Laravel installer ile değil de Composer Create-Project yöntemi ile kurduysanız uygulama için bir key tanımlamanız gerekiyor. Bu key'i oluşturabilmek için: `php artisan key:generate` komutunu çalıştırıp çıktıdaki anahtar kodunu `.env` dosyasının içerisinde ilgili yere yapıştırmanız gerekmekte. Eğer projenizde `.env` dosyasını bualmıyor iseniz, bu dosya `.env.example` adında görülebilir.

# Projemize Başlayalım

## Veritabanı

  Öncelikle, bu tarz uygulamalarda verinin kalıcılığını profesyonel bir şekilde sağlamak istiyorsak bir veritabanı kullanmalıyız. Biz bu projede **MySQL** veritabanını kullanmayı seçtik.

  İlgili veritabanını oluşturduktan sonra, Laravel ile bu veritabanına bağlanmak için `.env` dosyasının içerisindeki aşağı kısımda görünen veritabanı bağlantı ayarlarını kendi ayarlarımızla güncellememiz gerekmekte.

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=admin_stack
DB_USERNAME=admin_stack
DB_PASSWORD=ilgilisifre
```

  Bu tarz bir web uygulaması için biz üç adet tabloyu yeterli gördük. Kullanıcı bilgilerinin saklanacağı `users` tablosu, soru bilgilerinin saklanacağı `posts` tablosu ve sorulara verilen cevapların saklanacağı `questions` tablosu bizim veritabanımızı oluşturmakta.

  Frameworkü anlatırken bahsetmiş olduğumuz [*migration*ları](index.md#) burada kullanacağız.

  `php artisan make:migration users_table` komutunu, komut satırından çalıştırarak bir *migration* dosyası oluşturabiliriz. Bu dosya `database/migrations/` otomatik olarak klasörü içerisinde oluşturulur. Aşağıda bizim `posts` tablomuz için oluşturduğumuz migration dosyasının içeriğini görebilirsiniz.

```php
<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class PostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('posts')){
          Schema::create('posts', function (Blueprint $table) {
              $table->increments('id');
              $table->text('title');
              $table->text('content');
              $table->integer('vote')->default(0);
              $table->integer('view')->default(0);
              //Foreign key tanımlamak için önce sütunu tanımlıyoruz
              $table->integer('user_id')->unsigned();
              //Daha sonra bu sütun üzerinden foreign key tanımını gerçekleştiriyoruz
              $table->foreign('user_id')->references('id')->on('users');
              //created_at ve updated_at sütunları için
              $table->timestamps();
          });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('posts');
    }
}
```

  Yukarıda gördüğünüz gibi otomatik olarak oluşturulan sınıfımız `up()` ve `down()` olmak üzere iki adet methoda sahip. `up()` methodu migration gerçekleşirmek yapılacak işlemleri içerirken, `down()` methodu ise gerçekleştirilen bir migration işlemini geri almak istediğimizde yapılacak işlemleri içermekte.

  `up()` methodu içerisinde ilk olarak `posts` adında bir tablo olup olmadığını kontrol ettik. Eğer bu tablo yok ise `posts` adında bir tablo oluşturup, bu tablo içerisinde *id*, *content*, *view*, *vote* ve *user_id* sütunlarının olması gerektiğini belirttik. Bu sütunları belirtirken *id* için `increments()` methodunu kullandık. Bu method bu sütunun veritabanı tarafından *auto-increment* ve *primary key* şeklinde ayarlanmasını sağladı. Yukarıda da görebileceğiniz gibi diğer sütünları da, barındırdığı verinin türünü belirterek tanımladık. Aynı zamanda, her bir *post*'un bir kullanıcıya ait olması gerektiğinden, *user_id* sütununu *foreign key* olarak tanımladık. Daha sonra göreceğimiz Laravel içerisindeki *Modeller*  varsayılan olarak ilgili tabloda *created_at* ve *updated_at* sütunlarının olduğunu kabul etmekte ve ilgili işlemlerde bu sütunları otomatik olarak güncellemekte. Bu sütunları veritabanında otomatik olarak oluşturabilmek için  `$table->timestamps();` tanımlamasını da yaptık.

  İlgili diğer tablo yapılarını paylaşmış olduğumuz [örnek projenin](https://github.com/kayalardanmehmet/LaravelTutorial/tree/master/database/migrations) kaynak kodunda bulabilirsiniz.


  Bu dosyaları oluşturduktan sonra, `php artisan migrate` komutunu kullanarak yazmış olduğumuz migrationların çalışmasını sağlıyoruz. Yapmış olduğumuz son migration işlemini geri almak istersek, `php artisan migrate:rollback` komutunu, tüm migrationları sıfırlamak istersek `php artisan migrate:reset` komutunu çalıştırabiliriz.

## Modeller

  Laravel Framework'ün en büyük nimetlerinden biri olan ORM'i kullanabilmek için, veritabanımızdaki kullanacağımız her bir tabloya karşılık gelecek bir Model sınıfı oluşturmamız gerekiyor.

  Model sınıfı oluşturmak için komut satırından `php artisan make:model SınıfAdı` komutunu çalıştırıyoruz. Bu modeller, `app/` klasörümüzün içerisinde otomatik olarak oluşuyor. Örnek olarak aşağıda bu projedeki `User` ve `Post` modellerini görebilirsiniz.

```php

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //Aksi aşağıdaki gibi belirtilmedi sürece ORM için databasede tablo adı çoğul class adı olarak kabul edilir
    //protected $table = 'my_flights';

    //Constructor olarak parametre gönderebilmek için tanımlamamız gerekiyor
    //Default olarak laravel güvenlik sisteminden dolayı buna izin vermiyor
    protected $fillable = ['email', 'name', 'surname', 'password'];

    public function posts(){

        return $this->hasMany('App\Post');

    }
}

```

  Yukarıdaki yorum satırlarından da anlayacağınız üzere, Framework varsayılan olarak, sınıf adının çoğul halini veritabanındaki tablo adı olarak varsayarak eşleme yapar.  Aksini belirtmek istiyorsak `protected $table = 'my_flights';` şeklinde bir tanımlama yapmamız gerekiyor.

  Bu örnekte gördüğünüz üzere `posts` tablosu ile `users` tablosu arasında one-to-many bir ilişki bulunmakta. Bu ilişkiyi tanımlamak için model içerisinde bir method oluşturarak, *Model* ata sınıfının `hasMany()` methodunu çağırıyoruz. Biz daha sonra bir User nesnesini veritabanından çektiğimizde, bu nesne içerisinde otomatik olarak üretilmiş `posts` adında bir array'e erişebiliyoruz. Bu array, ilgili *User* a ait bütün *post* nesnelerini içerisinde barındırıyor.


```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  //Aksi aşağıdaki gibi belirtilmedi sürece ORM için databasede tablo adı çoğul class adı olarak kabul edilir
  //protected $table = 'my_flights';

  //Constructor olarak parametre gönderebilmek için tanımlamamız gerekiyor
  //Default olarak laravel güvenlik sisteminden dolayı buna izin vermiyor
  protected $fillable = ['title', 'content', 'user_id'];

  public function user(){
      return $this->belongsTo('\App\User');
  }

  //bir posta ait birden çok cevap olabilir. OneToMany ilişkileri bu şekilde tanımlıyoruz
  public function answers(){
      return $this->hasMany('\App\Answer')->orderBy('vote', 'desc');
  }
}

```
  Yukarıda *Post* modelimizin içeriğini görebilirsiniz. Her bir soru ile bu soruya ait cevaplar için one-to-many ilişkisi olacağından, *User* modelinde olduğu gibi `hasMany()` methodunu kullandık. Ayrıca, *Post* içerisinde *User* bilgisine ihtiyacımız da olacağından, bu ilişkinin diğer tarafını burada yine *Model* ata sınıfının `belongsTo()` methodunu kullanan `user()` fonksiyonunu tanımladık. Bu sayede yine veritananından bir *Post* nesnesine erişildiğinde, bu nesneye ait *User* bilgisine de erişebiliyoruz.



  Yukarıdaki örneklerde de gördüğünüz gibi, modellerin içerisinde veritabanı tablolarında bulunan sütunlara ait değişkenleri tanımlamadık. Ancak ileride Controller bölümünde göreceğimiz gibi;

```php
  $user = \App\User:find(1);
  $name = $user->name;
```

  Nesnenin bir değişkeniymiş gibi tablo sütunlarına erişebiliyoruz. Bu da yine Laravel'in bize sağladığı kolaylıklardan bir tanesidir.

## Route'lar

  Bugüne kadar standart yöntemlerle PHP uygulaması geliştirmiş olan kişiler bilirler ki, adres çubuğuna yazılan URL'in belirli bir PHP dosyasına erişmesi beklenmektedir. Kullanılan web servera göre `.htaccess` veya benzeri araçları kullanarak URL'leri yapılandırabiliyoruz. Ancak yine de her URL'in belirli bir PHP dosyasına erişmesi gerekiyor. Fakat bu frameworkte işler daha farklı. Tanımlanmış olan URL'lerin her birini bir method karşılamak zorunda. Laravel Framework içerisinde bulunan *Route*, istekler ve methodlar arasındaki eşleştirmeyi sağlıyor. Laravel'de Route'ları tanımlamanın çeşitli yöntemleri var. Aynı zamanda aşağıdaki kod parçacığında da görebildiğiniz gibi eşleştirmeyi çeşitli istek türlerine göre de sağlayabiliyoruz.

```php
  Route::get($uri, $callback);
  Route::post($uri, $callback);
  Route::put($uri, $callback);
  Route::patch($uri, $callback);
  Route::delete($uri, $callback);
  Route::options($uri, $callback);
```
  Yukarıda da gördüğünüz gibi Route sınıfı *static* olarak çağırılıyor ve iki adet parametre alıyor. Bunlardan ilki isteğe ait URI, diğeri de bu URI'a bir istek geldiğinde çalıştırılacak methodu belirtiyor.

```php
  Route::get('bbm490', function () {
      return 'Merhaba web dersi!';
  });

```

  Bu örnekte de gördüğünüz gibi, istekleri belirtirken fonksiyonları da direkt tanımlayabiliyoruz. Yukarıdaki istek tanımlandıktan sonra, eğer *localhost'ta* çalışıyorsak, `http://localhost/bbm490` adresini ziyaret ettiğimizde ekranda "Merhaba web dersi" yazısını görürüz.


```php
<?php

Route::group(['middleware' => 'checklogin'], function () {

    Route::get('/', function () {
        $posts = \App\Post::orderBy('id', 'desc')->get();
        return view('home', ['posts' => $posts]);
    });

    //posts istekleri
    Route::get('/posts', 'PostController@index');
    Route::get('/posts/new', 'PostController@getNew');
    Route::post('/posts/new', 'PostController@postNew');
    Route::get('/posts/{id}', 'PostController@view');

    //answera ait istekler
    Route::post('/answers/new/{id}', 'PostController@postAnswer');

    //vote işlemleri için gereken istek
    Route::get('/vote/{id}/{type}/{vote}', 'PostController@vote');

});

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/logout', 'UserController@logout');

Route::post('/register', 'UserController@doRegister');
Route::post('/login', 'UserController@doLogin');

```

Yukarıda, bizim sistemimiz için tasarladığımız istekleri ve karşılığı olan methodları görebilirsiniz. Bunlardan bazıları direkt olarak controller methodlarını çağırmakta, bazılarıda yukarıdada örneğini verdiğimiz gibi direkt fonksiyon çalıştırmakta. Sadece statik sayfaları göstereceğimiz istekler için controller methodu oluşturmamız gereksiz olur. Henüz *Controllerları* anlatmadık ancak, şuan da bizim isteklerimiz ileride tanımlayacağımız Controller sınıflarının methodlarına eşleniyor. Bu koda parçacığında gördüğünüz üzere bazı istekleri grupladık. Bunun sebebi, bu istekleri sadece kayıtlı ve giriş yapmış kullanıcıların erişebileceği şekilde kısıtlamaktadır. Grubu tanımladıktan sonra bu gruba bir *Middleware* tanımlıyoruz.

### Middleware

  Gelen isteklere göre tanımlanan methodları çalıştırmadan önce bazı kontroller yapmak isteyebiliriz. Örneğin bizim uygulamamız için, *login* ve *register* sayfaları hariç diğer tüm sayfalara erişimi giriş yapmış kullanıcılarla kısıtlamamız gerekiyor. Bu tarz işlemleri gerçekleştirebilmemiz için *Route* sınıflarını gruplandırarak, bu gruba isteklerden önce çalışacak bir filtre (Middleware) tanımlayabiliriz.

  Bir Middleware sınıfı oluşturabilmek için komut satırında `php artisan make:middleware CheckLogin` komutunu çalıştırmamız lazım. Bu komut çalıştıktan sonra `app/Http/Middleware` klasörü içerisinde `CheckLogin.php` adında bir dosya oluştuğunu göreceksiniz.

```php
<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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

        if(!$request->session()->has('email'))
            return redirect('/login');

        return $next($request);
    }
}
```
  Yukarıda gördüğünüz sınıf, bizim bu filtreleme ihtiyacımızı karşılamak için yazdığımız bir Middleware sınıfı. Bu koda göre, daha sonra login işlemi sırasında *session'a* kaydedeceğimiz *email* bilgisinin varlığını kontrol ediyoruz. Kullanıcı çıkış yaptığında bu veriyi session'dan sileceğimiz için, bu verinin varlığı bize kullanıcının login işlemini yapmış olduğunu kanıtlıyor. Bizim örneğimizde eğer kullanıcı giriş yapmamış ise, login sayfasına yönlendiriliyor.

  Middleware'i oluşturduk, ancak bu sınıfı *Route* tarafında kullanabilmek için bir isim atamamız gerekiyor. Bu işlemi gerçekleştirirken `app/Http/Kernel.php` dosyasının içerisinde bulunan `$routeMiddleware` arrayi içerisine oluşturduğumuz middleware sınıfını aşağıdaki örnekte olduğu gibi eklememiz gerekiyor.

```php
...
protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'checklogin' => \App\Http\Middleware\CheckLogin::class
];
...
```

  Biz bu proje için oluşturduğumuz middleware sınıfını *checklogin* adına sahip olacak şekilde ekledik. Artık Route için middleware tanımlarken *checklogin* ismini kullanabiliriz.

## Controller'lar

  MVC programlama mimarisine aşina olanlar Controllerları anlamakta zorlanmayacaklardır. Bu tarz mimarilerde, sistemin işleyişi bir web uygulaması için şu şekilde işler:

  * İlk olarak, gelen isteğe göre ilgili controller tetiklenir
  * Bu controller içerisinde isteğin çeşidine göre çeşitli işlemler gerçekleştirir. Bu işlemler, bir servisten verileri çekip kullanıcıya göstermek üzere hazırlamak kadar basit bir işlem de olabilir, oldukça kompleks işlemlerde olabilir.
  * Daha sonra controller işlediği verileri kullanıcıya döndürmek için ilgili *view* nesnesine yönlendirir.

  Bu başlık altında yukarıda basettiğimiz controllerları örnekler üzerinden anlatıyor olacağız. Aşağıda, yukarıdaki router örneğinden de görebileceğiniz gibi login sayfasında bulunan formu yönlendireceğimiz */login* isteğinin methodunu görebilirsiniz.

```php
    ...
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
    ...
```

Bu method post isteğini karşıladığından, istekle beraber gelen parametreleri alabilmek için *Request* objesini kullanmalıyız. Bu sınıfı çağırabilmek için methoda argüman olarak ekliyoruz ve Laravel Framework bizim yerimize injection'ı gerçekleştirip bizim method içerisinde kullanmamıza olanak veriyor. Method içerisinde `$request->all()` fonksiyonunu çağırarak, istekle gelen bütün parametreleri bir array olarak elde edebiliriz. Daha sonra gereken parametreler üzerinde gerekli güvenlik kontrollerini yaptık. Bu kod parçacığında ayrıca modeller üzerinde parametreye bağlı sorguların nasıl yapılacağını da göstermiş olduk. `$users = \App\User::where('email', $inputs['email'])->get();` satırında, veritabınındaki kullanıcılar içinde email sütunu parametre olarak aldığımız email adresine eşit olan kullanıcıları elde ettik. Sistemimizi email adresleri biricik olacak şekilde tasarladığımız için bu istek sonrası maksimum 1 kullanıcı döneceğini kabul edebiliriz. `$users` arrayi üzerinde gerekli kontrolü `count` methoduyla sağladıktan sonra şifre uyumunu kontrol ediyoruz. Eğer kullanıcı bu aşamayı da geçerse, oturuma ait session içerisinde ileride kullanacağımız giriş yapan kullanıcıya ait verileri saklıyoruz.

Henüz **view** lardan bahsetmedik ancak, bu kod parçacığında ayrıca değinmek istediğimiz diğer bir konu, controllerlar içerisinde templateleri `view` fonksiyonu ile çağırırken, ikinci bir parametre olarak array objesi eklediğimizde, bu arrayin içerisindeki verileri daha sonra göreceğimiz gibi, template içerisinde direkt bir değişken gibi kullanabiliyor olacağımızdır.

Yukarıdaki örnek, post isteğine karşılık gelen bir methodun örneğiydi. Bir de parametre barındıran get isteğine karşılık gelen method için örnek verelim.

```php
    ...

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

    ...

```

  Bu örnekte, `Route::get('/posts/{id}', 'PostController@view');` şeklinde tanımladığımız isteğin karşılığı olan methodu gösterdik. URI tanımlarken köşeli parantez içerisinde belirttimiz **id** ile kullanıcıdan parametre olarak gelecek bir değer olduğunu söylemiş olduk. `http://localhost/posts/3` şeklinde bir istek yapıldığında, Laravel Framework **PostController** içerisinde bulunan **view** methodunun `$id` şeklinde bir parametresi olduğunu kabul eder ve bu methodu çalıştırırken kullanıcıdan gelen parametre ile çağırır. Yukarıdaki kod parçacığında kullanıcıdan gelen parametre ile veritabanında ki **posts** tablosu içerisinde idsi gelen parametredeki değere eşit olan postu çekmiş olduk. Laravel ORM yapısında `find` methodu verilen parametreyi otomatik olarak ilgili tablonun primary key sütununda arar. Son olarak eğer postu bulduysak ilgili viewı bu bilgi ile beraber derleyip kullanıcıya yönlendiriyoruz, bulamadıysak kullanıcıyı anasayfaya yölendiriyoruz.

## View'lar

  Giriş kısmında da bahsettiğimiz gibi Laravel Framework bünyesinde Blade Template Engine'i barındırır. Yukarıdaki Controller ve Route nesneleri içerisindeki methodlarda genellikle `view()` fonksiyonu aracılığıyla bir view nesnesine erişip kullanıcıya döndürdük.

  Örneğin `/register` adresine bir **get** isteği yapıldığında, `return view('register');` satırında `view()` fonksiyonuna sadece "register" parametresini gönderdik. Laravel Framework, aldığı bu parametreye göre, ilgili dosyanın `resources/views/` klasörü altında, `register.blade.php` adında bulunduğunu kabul ediyor. Bu klasör içerisinde ayrıca biz de karışıklığı engellemek için, klasörler oluşturup viewlarımızı bunlar içerisinde tutabiliriz. Bizim projemizde, `resources/views/posts/` klasörü içerisinde `view.blade.php` dosyası bulunuyor. Bu tarz yapıları çağırmak için, `return view('posts.view', ['post' => $post]);` şeklinde noktalarla yolu da belirtebiliyoruz.

  Bu uygulamayı geliştirirken Bootstrap kullanılmış hazır bir HTML template kullandık. Blade Template Engine, ayrıca *Nesneye Yönelik Programlama* dillerinden aşina olduğumuz **kalıtım** özelliğini de kullanmamızı sağlıyor. Peki bu özelliği  nerede kullanıyoruz ? Projemiz birden fazla sayfadan oluşmakta ancak bu sayfalarda menü kısmı, html yapısı vb. sürekli olarak tekrar eden kısımlar bulunmakta. Her sayfada bu kısımları tekrar etmek yerine, bunları içeren sayfalarda ata olarak belirtmek üzere `main.blade.php` adında bir şablon oluşturduk. Bu şablonun yapısını aşağıda görebilirsiniz.

```php
  <!DOCTYPE html>
  <html lang="en" style="height: 100%;">
      <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title>@yield('title')</title>

          <link href="{{asset('assets/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

          <link href="{{asset('custom.css')}}" rel="stylesheet" type="text/css">

          <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
          <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
          <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->

          <!-- fonts -->
          <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">



          <script   src="https://code.jquery.com/jquery-3.2.1.min.js"   integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="   crossorigin="anonymous"></script>
          <script src="{{asset('assets/bootstrap/dist/js/bootstrap.min.js')}}"></script>
      </head>
      <body>

          <div class="container-fluid">

              <div class="headernav">
                  <div class="container">
                      <div class="row">
                          <div class="col-lg-1 col-xs-3 col-sm-2 col-md-2" style="font-size: 29px; font-family: 'Open Sans Light', sans-serif; margin-top: 16px; height: 38px"><a href="{{url('/')}}">Q&A</a></div>
                          @if(session()->has('email'))
                          <div class="col-lg-4 search hidden-xs hidden-sm col-md-3">
                              <div class="wrap">
                                  <form action="#" method="post" class="form">
                                      <div class="pull-left txt"><input type="text" class="form-control" placeholder="Sorularda Ara"></div>
                                      <div class="pull-right"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></div>
                                      <div class="clearfix"></div>
                                  </form>
                              </div>
                          </div>
                          <div class="col-lg-4 col-xs-12 col-sm-5 col-md-4 avt">
                              <div class="stnt pull-left">
                                  <form action="03_new_topic.html" method="post" class="form">
                                      <a class="btn btn-primary" href="{{url('/posts/new')}}">Yeni Soru Sor</a>
                                  </form>
                              </div>

                              <div class="avatar env pull-left dropdown">
                                  <a data-toggle="dropdown" href="#"><i class="fa fa-user"></i> <b class="caret"></b>
                                  <div class="status green">&nbsp;</div>
                                  <ul class="dropdown-menu" role="menu">
                                      <li role="presentation"><a role="menuitem" style="margin-left: 15px;" tabindex="-3" href="{{url('/logout')}}">Çıkış Yap</a></li>
                                  </ul>
                              </div>

                              <div class="clearfix"></div>
                          </div>
                          @endif
                      </div>
                  </div>
              </div>


              <section class="content">

                  <div class="container" style="margin-top: 20px;">
                      @yield('content')
                  </div>

              </section>

              <footer>
                  <div class="container">
                      <div class="row">
                      </div>
                  </div>
              </footer>
          </div>

          <!-- END REVOLUTION SLIDER -->
      </body>
  </html>

```

  Oluşturduğumuz bu şablonda, bazı kısımlarda `@yield()` annotation'ı kullandığımızı görebilirsiniz. Bu kısımlar, bu şablonu ata olarak kullanacak sayfalar tarafından doldurulacak. Aşağıda, bu işlemi gerçekleştirdiğimiz ve **post** ları gösterdiğimiz `view.blade.php` sayfasının içeriğini görebilirsiniz.


```php

@extends('main')

@section('title')
Soru ve Cevapları
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="post">
            <div class="wrap-ut pull-left">
                <div class="userinfo pull-left">
                    <div class="vote_area">
                      <div class="vote_row"><i class="fa fa-chevron-up vote_up vote" data-id = "{{$post->id}}" data-type="post" data-vote="up"></i></div>
                      <div class="vote_row"><i class="fa fa-chevron-down vote_down vote" data-id = "{{$post->id}}" data-type="post" data-vote="down"></i></div>
                    </div>
                    <div class="vote_count">
                        {{$post->vote}}
                    </div>
                </div>
                <div class="posttext pull-left">
                    <h2><a href="02_topic.html">{{$post->title}}</a></h2>
                    <p>{{$post->content}}</p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="postinfo pull-left">
                <div class="comments">
                    <div class="commentbg">
                        {{count($post->answers)}}
                        <div class="mark"></div>
                    </div>

                </div>
                <div class="views"><i class="fa fa-eye"></i> {{$post->view}}</div>
                <div class="time"><i class="fa fa-clock-o"></i> {{$post->created_at}}</div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- Cevaplar -->

        @foreach($post->answers as $answer)
        <div class="post">
            <div class="wrap-ut pull-left">
                <div class="userinfo pull-left">
                    <div class="vote_area">
                      <div class="vote_row"><i class="fa fa-chevron-up vote_up vote" data-id = "{{$answer->id}}" data-type="answer" data-vote="up"></i></div>
                      <div class="vote_row"><i class="fa fa-chevron-down vote_down vote" data-id = "{{$answer->id}}" data-type="answer" data-vote="down"></i></div>
                    </div>
                    <div class="vote_count">
                        {{$answer->vote}}
                    </div>
                </div>
                <div class="posttext pull-left">
                    <p>{{$answer->content}}</p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="postinfo pull-left">
                <div class="time"><i class="fa fa-clock-o"></i> {{$answer->created_at}}</div>
            </div>
            <div class="clearfix"></div>
        </div><!-- POST -->
        @endforeach

        <div class="post">
            <form action="{{url('/answers/new/' . $post->id)}}" class="form newtopic" method="post">
                <div class="postinfotop">
                    <h2>Cevap Yaz</h2>
                </div>

                <!-- acc section -->
                <div class="accsection">
                    <div class="acccap">
                        <div class="userinfo pull-left">&nbsp;</div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="topwrap">
                        <div class="userinfo pull-left">
                        </div>
                        <div class="posttext pull-left">
                            @if(isset($error))
                            <div class="row"><div class="col-md-12"><div class="alert alert-danger"><strong>Hata!</strong> {{$error}}</div></div></div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <textarea style="width: 100%;" name="content" placeholder="İçerik">@if(isset($inputs)){{$inputs['content']}}@endif</textarea>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div><!-- acc section END -->


                <div class="postinfobot">

                    <div class="pull-right postreply">
                        {{ csrf_field() }}
                        <div class="pull-left"><button type="submit" class="btn btn-primary">Cevap Ver</button></div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="clearfix"></div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
  //Vote işlemlerini sayfa değişmeden dinamik bir şekilde yapabilmek için hazırlamış olduğumuz urle get isteği gönderiyoruz
  $(function(){

      $(".vote").click(function(){

          var $this = $(this);
          var $id = $this.data('id');
          var $type = $this.data('type');
          var $vote = $this.data('vote');
          var $count = $this.parent().parent().parent().find('.vote_count');

          $.get('{{url('/vote')}}/' + $id + '/' + $type + '/' + $vote, function(data){
              if(data.status == false){
                alert(data.error);
              }else{
                if($vote == 'up')
                  $count.html($count.html()*1+1);
                else
                  $count.html($count.html()*1-1)
              }
          }, 'json');

      });

  });

</script>

@endsection


```

  Bu dosyanın en üstünde, `@extends('main')` annotation'ı ile, ata şablon olarak `main.blade.php` dosyasını kullanacağımızı belirttik. Daha sonra, ata sınıfında `@yield()` ile tanımlamış olduğumuz iki adet bölümü, burada `@section('sectionAdı') ... @endsection` yapısı içerisinde belirttiğimiz içerik ile  doldurduk.

  Daha önce, Blade Template Engine'in en önemli avantajlarından birinin, tasarımcıyı PHP kodu yazmak zorunda bırakmaması olduğunu belirtmiştik. Örnek olarak bu özelliği;
```php
<div class="vote_count">
    {{$post->vote}}
</div>

```

  burada gördüğünüz gibi kullanarak, değişkeni PHP taglerini açmadan ve `echo` koduna gerek kalmadan ekrana bastırmış olduk.

  Tabi bu kolaylıklar sadece ekrana veri bastırmakla sınırlı değil. PHP içerisinde kullandığımız döngüler, koşul cümleleri gibi işlemleri de yukarıda da görebileceğiniz gibi kolaylıkla halledebiliyoruz. Örneğin; kullanıcı `http://localhost/posts/3` URL'ine bir **get** isteği yaptığında, framework öncelikle ilgili *Route* u buluyor ve burada tanımlı olan methodu kullanıcının göndermiş olduğu parametre ile çağırıyor. Bu örnek için bu method [Controller](tutorial.md#controllerlar)'lar bölümünde içeriğini örnek olarak verdiğimiz PostController sınıfındaki `view()` methodu olacaktır. Hatırlarsanız, bu method **id** adında bir parametre alıyordu ve bu parametreye göre ilgili **post** u veritabanından seçip kullanıcıya gösteriyor. Bir postu kullanıcıya gösterebilmemiz için, o post ile ilişkili cevapları da görüntülememiz gerekiyor. Yine hatırlarsanız bu cevapları Post modeli içerisindeki `answers()` methodu ile bağlamıştık. Blade Engine'in önemli bir özelliğini bu işlem için kullandık.

```php
@foreach($post->answers as $answer)
<div class="post">
    <div class="wrap-ut pull-left">
        <div class="userinfo pull-left">
            <div class="vote_area">
              <div class="vote_row"><i class="fa fa-chevron-up vote_up vote" data-id = "{{$answer->id}}" data-type="answer" data-vote="up"></i></div>
              <div class="vote_row"><i class="fa fa-chevron-down vote_down vote" data-id = "{{$answer->id}}" data-type="answer" data-vote="down"></i></div>
            </div>
            <div class="vote_count">
                {{$answer->vote}}
            </div>
        </div>
        <div class="posttext pull-left">
            <p>{{$answer->content}}</p>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="postinfo pull-left">
        <div class="time"><i class="fa fa-clock-o"></i> {{$answer->created_at}}</div>
    </div>
    <div class="clearfix"></div>
</div><!-- POST -->
@endforeach

```

  Bu örnekte gördüğünüz gibi, `@foreach` ve `@endforeach` annotationları sayesinde, tıpkı herhangi bir programlama dilinde olduğu gibi döngüsel bir biçimde PHP kullanmadan post içerisindeki cevapları kullanıcıya gösterebildik.

# Sonuç

  Bu anlattıklarımızın ardından, artık, Laravel Framework aracılığıyla basit bir web uygulaması yapabileceğinizi düşünüyoruz. Daha detaylı öğrenebilmeniz için bu örnek projeyi [GitHub'da paylaştık](https://github.com/kayalardanmehmet/LaravelTutorial). Kendi ortamınıza indirip çalıştırarak ve üzerinde istediğiniz değişiklikleri yaparak kendinizi geliştirebilirsiniz. Örneğin bir post görüntülerken post'un ait olduğu kullanıcının adını ve soyadını görüntülemedik. Bunları eklemek sizin için iyi bir egzersiz olabilir. Bu proje üzerinde kendinizi geliştirdikten sonra, kendinize ait bir uygulama geliştirebilirsiniz. Burada cevabını bulamadığınız sorularınız için, [Laravel Documentation](https://laravel.com/docs/5.4) sitesini ziyaret edebilir veya [bize ulaşabilirsiniz](about.md). 
