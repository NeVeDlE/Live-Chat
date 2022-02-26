<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilter($query, $id)
    {
        $auth = auth()->id();
        // set messages for this chat as viewed
        Message::where('sender', $id)->where('receiver', $auth)
            ->where('viewed', 0)->update(array('viewed' => 1));

        /* using Model Scopes get the messages where the sender
        is the logged-in user and the receiver is a user with
        the given $id or vice versa */

        $query->select('*')->where(function ($query) use ($id, $auth) {
            $query->where('sender', $id)->where('receiver', $auth);
        })->orWhere(function ($query) use ($id, $auth) {
            $query->where('sender', $auth)->where('receiver', $id);
        })->orderBy('created_at', 'desc');
    }


    public function send()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function receive()
    {
        return $this->belongsTo(User::class, 'receiver');
    }
}
