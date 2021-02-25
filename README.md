# Laravel Test

Laravel Test with Vue JS.

## Demo

[https://test-different-roads.herokuapp.com/](https://test-different-roads.herokuapp.com)


## Requirements

```
PHP >= 7.2.5
Composer
Git SCM
SOAP PHP Extension
BCMath PHP Extension
Ctype PHP Extension
Fileinfo PHP extension
JSON PHP Extension
Mbstring PHP Extension
OpenSSL PHP Extension
PDO PHP Extension
Tokenizer PHP Extension
XML PHP Extension
```

## Installation

Clone the repository.

```
git clone https://github.com/jabg97/test-laravel-vue.git
```

Go into the project folder and type the following command.

```
composer install
cp .env.local .env
```
if you are using Windows CMD, you must use "copy" command insteand "cp" command
```
copy .env.local .env
```
Configure .env file with your database credentials and then type the following command to execute migrations and seeders.

*NOTE: be careful, this will drop all the tables in the database*
```
php artisan migrate:fresh --seed
```
## Run server

```
php artisan serve
```
## Most Important files



## User.php
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        //'email',
        //'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'password',
        //'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    public function hasLiked()
    {
        return $this->belongsToMany(User::class, 'likes', 'user_id', 'user_liked_id');
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes', 'user_liked_id', 'user_id');
    }
}

```

## Menu.php
```
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'url',
        'icon',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

}

```

## ApiController.php
```
<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function __construct()
    {
       
    }

    public function get_menu()
    {
        $data = Menu::select(['id', 'label AS name', 'url', 'icon' ,'parent_id'])
        ->with(['children' => function($query){
            $query->select(['id', 'label AS name', 'url', 'icon' ,'parent_id']);
        }])->whereNull('parent_id')->get();
        return response()->json($data);
    }

    public function user_joins()
    {
        $data = DB::table('likes', 'source')->select('source.id','user1.name AS user1_name', 'user2.name AS user2_name')
        ->join('likes AS target', function($join)
        {
            $join->on('source.user_id', '=', 'target.user_liked_id')
            ->on('target.user_id', '=', 'source.user_liked_id')
            ->on('source.user_id', '<', 'target.user_id');
        })
        ->join('users AS user1', 'source.user_id', '=', 'user1.id')
        ->join('users AS user2', 'source.user_liked_id', '=', 'user2.id')
        
        ->get();
        return response()->json($data);
    }

}
```
## DatabaseSeeder.php
```
<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        $user_1 = new User;
        $user_1->name = 'Jhon Doe';
        $user_1->save();

        $user_2 = new User;
        $user_2->name = 'Meresa Oslo';
        $user_2->save();

        $user_3 = new User;
        $user_3->name = 'Mike Lines';
        $user_3->save();

        $user_1->hasLiked()->attach($user_2);
        $user_1->hasLiked()->attach($user_3);
        $user_2->hasLiked()->attach($user_1);

        $menu = new Menu;
        $menu->label = 'Home';
        $menu->url = '/home';
        $menu->icon = 'home-icon';
        $menu->save();

        $parent = new Menu;
        $parent->label = 'Trips';
        $parent->url = '/trips';
        $parent->icon = 'trip-icon';
        $parent->save();
        
        $sub_menu = new Menu;
        $sub_menu->label = 'Own Products';
        $sub_menu->url = '/trips/own';
        $sub_menu->icon = 'trip-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();

        $sub_menu = new Menu;
        $sub_menu->label = 'Others Products';
        $sub_menu->url = '/trips/other';
        $sub_menu->icon = 'trip-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();

        $parent = new Menu;
        $parent->label = 'Flights';
        $parent->url = '/flights';
        $parent->icon = 'flight-icon';
        $parent->save();

        $sub_menu = new Menu;
        $sub_menu->label = ' National Flights';
        $sub_menu->url = '/flights/nationals';
        $sub_menu->icon = 'flight-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();

        $sub_menu = new Menu;
        $sub_menu->label = 'International Flights';
        $sub_menu->url = '/flights/internationals';
        $sub_menu->icon = 'flight-icon';
        $sub_menu->parent()->associate($parent);
        $sub_menu->save();
    }
}

```

## Sources
[Getting mutual matches in SQL Server](https://stackoverflow.com/questions/30849359/getting-mutual-matches-in-sql-server/30849629/)<br />
[Eloquent: Relationships](https://laravel.com/docs/8.x/eloquent-relationships/)
