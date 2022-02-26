<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\Typing;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Chat extends Component
{
    use WithPagination;

    public $search = "";
    public $userID = "";
    public $body;
    protected $rules = [
        //rules used for validation
        'body' => 'required|string|max:255|min:1',
    ];
    protected $queryString = ['search', 'userID'];

    public function showChat($id)
    {
        //show chat for the given $id
        $this->userID = $id;
    }


    public function updatingBody()
    {
        //check if there's already is typing object made by the logged-in user
        //to the other one in the opened window
        $bool = sizeof(Typing::where('sender', auth()->id())
                ->where('receiver', $this->userID)->get()) > 0;
        if (!$bool) {
            Typing::create([
                'sender' => auth()->id(),
                'receiver' => $this->userID,
            ]);
        }
    }

    public function updatedBody()
    {
        if ($this->body == "") {

            //delete the is typing object
            $type = Typing::where('sender', auth()->id())->where('receiver', $this->userID)
                ->get()->first->delete();
        }
    }


    public function sendMessage()
    {
        try {
            //validate and assign values to array $atr
            $atr = array_merge($this->validate(), [
                'receiver' => $this->userID,
                'viewed' => 0,
                'sender' => \Auth::id(),
            ]);
            //create the message and reset the message body
            Message::create($atr);
            $this->resetForm();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        //delete the is typing object
        $type = Typing::where('sender', auth()->id())->where('receiver', $this->userID)
            ->get()->first->delete();
        //set the $body variable to empty string
        $this->body = "";
    }

    public function render()
    {
        if ($this->search != "") {
            //if there's a search get users for this search
            $users = User::Filter($this->search)->paginate(10, ['*'], 'usersResults')->withQueryString();
        } else {
            // if not then get chats by the last messages made using scope MessagesUsers()
            $users = User::MessagesUsers()->paginate(10, ['*'], 'usersResults')->withQueryString();
        }

        return view('livewire.chat', [
            'users' => $users,
            //get messages for the selected user id
            'messages' => Message::Filter($this->userID)->paginate(10, ['*'], 'messagesResults')->withQueryString(),
            //assign a User model instance to the given $userID in a variable
            'chat' => User::where('id', $this->userID)->first(),
        ]);
    }
}
