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
                                <div class="list-group">
                                    @if(count($chats)>0) 
                                        @foreach ($chats as $chat)
                                            @if ($chat->sender === auth()->user()->email)
                                                @continue
                                            @endif
                                        <a href="/chat/{{$chat->sender}}" class="list-group-item list-group-item-action mb-1" aria-current="true">
                                            <div class="d-flex justify-content-start">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png" style="height:3.0rem;  border-radius: 50%;">
                                                <div class="col ms-3">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">{{$chat->sender}}</h6>

                                                            <?php 
                                                            $now = time(); // or your date as well
                                                            $your_date = strtotime($chat->created_at);

                                                            $datediff = $now - $your_date;

                                                            if (round($datediff / (60 * 60 * 24)) == 0)
                                                                $days = "Today";

                                                            else {
                                                                $days = round($datediff / (60 * 60 * 24)).' days ago'; 
                                                            }

    
                                                            // echo ;
                                                            ?>
                                                            <small>{{$days}} , {{date( 'g:i a', strtotime($chat->created_at))}}</small>
                                                        </div>

                                                    
                                                        <small><i class="fa-solid fa-reply text-muted"></i> {{$chat->text}}</small>
                                                </div>
                                            </div>
                                        </a>
                                        @endforeach

                                        @else
                                        <ul class="list-group">
                                            <li class="list-group-item text-muted">No live chat requests</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- End Panel Chat -->
                        </div>
                    </div>
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