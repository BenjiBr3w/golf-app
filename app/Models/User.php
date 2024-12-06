<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        
    ];

    /**
     * Specifies the player that a given user_id links to.
     */
    public function player()
    {
        return $this->hasOne(Player::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    /**
     * Function specifying the many to many relationship of a user and their friends.
     */
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_users', 'user_id', 'friend_id')
                    ->withTimestamps();
    }

    /**
     * Function that assigns a friend to a given user.
     */
    public function addFriend(User $user)
    {
        $this->friends()->attach($user->id);
    }

    /**
     * Function that removes a friend from a given user.
     */
    public function removeFriend(User $user)
    {
        $this->friends()->detach($user->id);
    }

    /**
     * Function that finds the friends associated with a given user.
     */
    public function isFriendsWith(User $user)
    {
        return $this->friends()->where('friend_id', $user->id)->exists();
    }

    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id')->wherePivot('accepted', false);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function scores()
{
    return $this->hasMany(Scores::class);
}



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
