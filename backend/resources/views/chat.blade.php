@extends('layouts.chat')
@section('content')


<div class="container  overflow-auto">
    <div class="row justify-content-center ">
        <div class="col-md-12" >
            <div class="card" >
                <div class="card-header">
                    <h2 class="text-center mt-2 text-danger fw-bold"><i class="fa-brands fa-rocketchat"></i> Live Chat</h2>
                </div>
                <div class="card-body bg-white overflow-auto" id="scroll" style="height: 650px">
                    
                    <div class="container bootstrap snippets bootdeys">
                        <div class="col-md-12 col-xs-12 col-md-offset-2">
                        <!-- Panel Chat -->
                        <div class="panel" id="chat">
                            <div class="panel-heading">
                            <h3 class="panel-title">
                            </h3>
                            </div>
                            <div class="panel-body">
                            @if (count($chats)>0)
                                @foreach ($chats as $chat)
                                <div class="chats">

                                    @if (Session::get('role') === '1') {{-- Admin View --}} 
                                        
                                        {{-- Admin Bubble --}}
                                        @if ($chat->role === '1')
                                        <div class="chat">
                                            <div class="chat-avatar">
                                                <a class="avatar avatar-online" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="June Lane">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bb/Eo_circle_red_letter-a.svg/2048px-Eo_circle_red_letter-a.svg.png" alt="...">
                                                <i></i>
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content">
                                                <p>
                                                    {{$chat->text}}
                                                </p>
                                                <time class="chat-time" >{{date( ' h:i:s A', strtotime($chat->created_at))}}</time>
                                                </div>
                                            </div>
                                        </div>

                                        @else
                                        {{-- Customer Bubble--}}
                                        <div class="chat chat-left">
                                            <div class="chat-avatar">
                                                <a class="avatar avatar-online" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="Edward Fletcher">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png" alt="...">
                                                <i></i>
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content">
                                                <p>{{$chat->text}}</p>
                                                <time class="chat-time">{{date( ' h:i:s A', strtotime($chat->created_at))}}</time>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                    @elseif ($chat->sender === auth()->user()->email || $chat->receiver === auth()->user()->email) {{-- Customer View --}} 

                                        @if ($chat->sender === auth()->user()->email)
                                        <div class="chat">
                                            <div class="chat-avatar">
                                                <a class="avatar avatar-online" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="June Lane">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bb/Eo_circle_red_letter-a.svg/2048px-Eo_circle_red_letter-a.svg.png" alt="...">
                                                <i></i>
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content">
                                                <p>
                                                    {{$chat->text}}
                                                </p>
                                                <time class="chat-time" >{{date( ' h:i:s A', strtotime($chat->created_at))}}</time>
                                                </div>
                                            </div>
                                        </div>

                                        @else
                                        <div class="chat chat-left">
                                            <div class="chat-avatar">
                                                <a class="avatar avatar-online" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="Edward Fletcher">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png" alt="...">
                                                <i></i>
                                                </a>
                                            </div>
                                            <div class="chat-body">
                                                <div class="chat-content">
                                                <p>{{$chat->text}}</p>
                                                <time class="chat-time">{{date( ' h:i:s A', strtotime($chat->created_at))}}</time>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endif

                                </div>
                                @endforeach
                            @endif
                            </div>
                        </div>
                        <!-- End Panel Chat -->
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    {!! Form::open(['class' => "row g-3", 'action' => '\App\Http\Controllers\ChatController@store', 'method'=>'POST','enctype' => 'multipart/form-data']) !!}
                        <div class="input-group">
                        <input type="text" name="text" class="form-control" placeholder="Say something" required>
                        @if (Session::get('role') === '1')
                        <input type="hidden" name="receiver" value="{{$sender}}" >
                        @endif
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </span>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var objDiv = document.getElementById("scroll");
    objDiv.scrollTop = objDiv.scrollHeight;
</script>



@endsection