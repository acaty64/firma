<?php

namespace App;

use App\Access;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        // $this->connection = 'mysql_user';
        $this->connection = config('app.env') === 'testing' ? 'mysql_tests' : 'mysql_user';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $append = ['access'];

    public function getAccessAttribute()
    {
        $access = Access::where('user_id', $this->id)->first();
        if($access){
            return $access;
        }
        return false;
    }

    public function scopeUsers()
    {
        $data = [];
        $users = User::orderBy('id', 'DESC')->get();
        foreach ($users as $key => $item) {
            if($item->access){
                $data[] = $item;
            }
        }
        return $data;
    }

    public function scopeNoUsers()
    {
        $data = [];
        $users = User::orderBy('id', 'DESC')->get();
        foreach ($users as $key => $item) {
            if(!$item->access){
                $data[] = $item;
            }
        }
        return $data;
    }

}
