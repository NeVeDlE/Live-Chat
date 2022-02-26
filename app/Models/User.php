<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter($query, $search)
    {
        $query->where('name', 'like', '%' . $search . '%')->where('id', '!=', auth()->id());
    }

    public function scopeMessagesUsers($query)
    {
        //getting distinct user ids for every chat made
        $messages = \DB::table('messages')->distinct()->select('sender', 'receiver')
            ->where('receiver', auth()->id())->orWhere('sender', auth()->id())->latest()->get();

        //making a queue to pass ids to in the order messages were sent

        $ids = new \SplQueue();
        foreach ($messages as $message) {
            if ($message->sender != auth()->id()) {
                $vis[$message->sender] = 0;
                $ids->enqueue($message->sender);
            }
            if ($message->receiver != auth()->id()) {
                $vis[$message->receiver] = 0;
                $ids->enqueue($message->receiver);
            }
        }

        //using array as Visited array
        //to save if I already have this id or nah
        $ids->rewind();
        while ($ids->valid()) {
            if (!$vis[$ids->current()]) {
                $values[] = $ids->current();
                $vis[$ids->current()] = 1;
            }
            $ids->next();
        }
        //sorted the ids in the order the messages were sent and now
        //getting the users from the DB in the same order

        if (!empty($values)) $query->whereIn('id', $values)
            ->orderByRaw('FIELD(id,' . implode(", ", $values) . ')');

        else  $query->where('id', 0);
        //it may not be the best approach but that's what I could come up with xD
    }
}
