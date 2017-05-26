# Laravel Nedir ?

  Laravel, temelde bir PHP framework olarak karşımıza çıkıyor. Kolay bir syntax'a sahip, hızlı ve çoğu can sıkıcı işleri bizim için oldukça kolaylaştıran bir araç aslında.
  En büyük artılarından birisi düzenli ve kolay anlaşılabilir bir yapıya sahip olması.
  Laravelin bize sağladığı kolaylıklardan en önemlilerini aşağıda biraz detaylandırdık. Aynı zamanda bu kolaylıklardan faydalanmanın yollarını göstermek için de size örnek bir web uygulaması hazırladık. Uygulamanın anlatımına [buradan](about.md) ulaşabilirsiniz.

## Object Relational Mapping (ORM)

  Laravelin en büyük kolaylıklarından birisi olan ORM, temelde bize veritabanımız ile olan iletişimimizde yardımcı oluyor. Bizi bütün o klasik SQL sorgularından kurtarıyor.

  Veritabanımızdaki her bir tablo, o tablo ile iletişime geçmemizi sağlayan bir "Model" ile ilişkilendiriliyor. Bu modeller aracılığıyla tablolarımıza erişip istediğimiz verileri
  sorgulayabildiğimiz gibi, yeni veriler ekleyebiliyor ya da silebiliyoruz.

  Modelleri yaratırken aynı zamanda modellerin iletişime geçeceği veritabanı yapılarını da Laravel framework'ün sağlamış olduğu "migration"lar sayesinde yaratabiliyoruz veya düzenleyebiliyoruz. Bu yapılar bizim veritabanımızda değişiklikler yapmamızı sağlarken aynı zamanda olası bir ekip çalışmasında veritabanının kişiler arasında paylaşılması yerine aynı framework içerisindeki kod parçacıkları sayesinde veritabandaki değişiklikleri de kolayca paylaşabiliyoruz.

## Template Engine

  Laravel Framework, içerisinde, front-end yazılımını ve tasarımını kolaylaştırmak için [Blade Engine](https://laravel.com/docs/5.4/blade)'i sunuyor. Java ile web kodlayanların aşina olduğu gibi, Blade, oldukça kolay ve güçlü bir yapıya sahip. Blade sayesinde verileri tasarım içerisinde gösterirken, PHP kodlarının kullanılmasına gerek kalmıyor.

  Örneğin ekip halinde bir web uygulaması geliştirdiğimizi düşünelim. Uygulamanın logic kısmını yazan ve front-end tarafıyla ilgilenen yazılımcıların, birbilerinin ilgi alanlarıyla ilgili detaylı bilgilerinin olmadığını varsayarsak, bu framework tasarım ile uğraşan kişilerin işini oldukça kolaylaştırıyor. Bu tasarımcıların sadece kendi alanları ile ilgili bilgilerinin olması yeterli. Kısacası bu framework için Blade Engine, tasarımcıyı PHP kodu yazmaktan kurtarıyor.

## Performans

  Genelde web projeleriyle uğraşanlar, projelerindeki veri büyüdükçe ve uygulamayı kullanan kişi sayısı arttıkça, performansın yüksek oranda azaldığını farketmişlerdir. Böyle durumlarda, öncelikle, kişilerin aklına web uygulamasını barındırdıkları sunucunun işlem gücünü artırmak gelmektedir. Ancak, önbellek sistemleri kullanılarak bu performans düşüklükleri büyük bir oranda azaltılabilir.

  Laravel Framework bu gibi durumlar için bünyesinde [Redis](https://laravel.com/docs/5.4/redis)'i barındırmaktadır.


# Sonuç

  Sonuç olarak, uzun yıllardır PHP ile çeşitli uygulamalar geliştiren kişiler olarak, Laravel Framework'ü yukarıda belirttiğimiz birçok nedenlerden dolayı kullanmanızı tavsiye ediyoruz.

  Yazılım geliştiricilerin çoğu tarafından farklı cevabı olan bir soru vardır. Framework'ü öğrenerek harcayacağımız efor, uygulamayı bildiğimiz yöntemlerle geliştirirken harcayacağımız efora kıyasla ne durumdadır? Bu soruyla ilgili, bildiği yöntemi tercih eden kişilerin gözden kaçırdığı nokta, Laravel gibi frameworkleri bir kez öğrendiklerinde, bundan sonra yapacakları bütün projelerden kazanacakları zamandır.

  Eğer bir mühendisin yapması gerektiği gibi, düzenli ve anlaşılır bir kod yazmak istiyorsanız, bu tarz frameworkler, özellikle Laravel, işinizi oldukça kolaylaştıracaktır.
