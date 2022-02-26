<div class="min-w-full border rounded lg:grid lg:grid-cols-3" wire:poll.2000>
    <div class="border-r border-gray-300 lg:col-span-1">
        <div class="mx-3 my-3">
            <div class="relative text-gray-600">
              <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     viewBox="0 0 24 24" class="w-6 h-6 text-gray-300">
                  <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </span>
                <input wire:model.debounce.400="search" type="search"
                       class="block w-full py-2 pl-10 bg-gray-100 rounded outline-none"
                       name="search"
                       placeholder="Search For Chats" autocomplete="off" required/>
            </div>
        </div>

        <ul class="overflow-auto h-[32rem]">
            @if($users->count())
                <h2 class="my-2 mb-2 ml-2 text-lg text-gray-600">Chats</h2>
                <li>
                    @foreach($users as $user)
                        <a wire:click="showChat({{$user->id}})"
                           @if(isset($chat)&& $user->id==$chat->id)
                           class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out bg-gray-100 border-b border-gray-300 cursor-pointer focus:outline-none"
                           @else
                           class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out bg-white-100 border-b border-gray-300 cursor-pointer focus:outline-none"
                            @endif>
                            <div class="w-full pb-2">
                                <div class="flex justify-between">
                                    <span class="block ml-2 font-semibold text-gray-600">{{$user->name}}</span>

                                    @if(sizeof(\App\Models\Typing::where('sender',$user->id)->where('receiver',Auth::id())->get())>0)
                                        <span class="block ml-2 text-sm text-green-600">User is Typing...</span>
                                    @endif
                                    @php
                                        $counter=\App\Models\Message::where('sender',$user->id)
                                            ->where('receiver',Auth::id())->where('viewed',0)->count();
                                    @endphp
                                    @if($counter)
                                        <span class="block ml-2 text-xs font-bold uppercase">{{$counter}}</span>
                                    @endif
                                </div>
                            </div>

                        </a>
                    @endforeach
                </li>
            @else
                <p class="text-center mt-8">No Chats Found ...</p>
            @endif
        </ul>
    </div>

    <div class="hidden lg:col-span-2 lg:block">
        <div class="w-full">
            @if(!empty($chat))
                <div class="relative flex items-center p-3 border-b border-gray-300">
                    <span class="block ml-2 font-bold text-gray-600">{{$chat->name}}</span>
                    @if(sizeof(\App\Models\Typing::where('sender',$chat->id)->where('receiver',Auth::id())->get())>0)
                        <span class="block ml-2 text-sm text-green-600">User is Typing...</span>
                    @endif
                </div>
                <div class="relative w-full p-6 overflow-y-auto h-[40rem]">
                    <ul class="space-y-2">
                        @if($messages->count())
                            @foreach($messages->reverse() as $message)
                                @if($message->sender!=Auth::id())
                                    <li class="flex justify-start">
                                        <div class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow">
                                            <span class="block">{{$message->body}}</span>
                                        </div>
                                    </li>
                                @else
                                    <li class="flex justify-end">
                                        <div
                                            class="relative max-w-xl px-4 py-2 text-gray-700 bg-gray-100 rounded shadow">
                                            <span class="block">{{$message->body}}</span>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <p class="text-center mt-8">This is the beginning of this conversation</p>
                        @endif
                    </ul>
                </div>
                {{$messages->links()}}
                <form wire:submit.prevent="sendMessage" method="POST"
                      class="flex items-center justify-between w-full p-3 border-t border-gray-300">
                    @csrf
                    <input wire:model.debounce.500="body" wire:key="isTyping({{Auth::id()}})" type="text"
                           placeholder="Message"
                           class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
                           name="body" required autocomplete="off"/>

                    <button type="submit">
                        <svg class="w-5 h-5 text-gray-500 hover:text-blue-500 origin-center transform rotate-90"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                    </button>
                </form>
        </div>
        @else
            <p class="text-center mt-8">Please Select a Chat..</p>
        @endif
    </div>
</div>
