<?php

use App\Models\Notification;
use Illuminate\Support\Facades\Config;

function get_default_lang(){
    return   Config::get('app.locale');
}


function uploadImage($folder, $image)
{
    //$image->store( $folder);
    $filename = $image->hashName();
    $path2 = public_path("images/".$folder);
    $image->move($path2,$filename);
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}

function sendmessage( $title ,$body, $token ,$id)
{

    $token = $token;
    $from = "AAAAfXEwyJw:APA91bHl1BfrVdn2fq-GXuA3IE8C6B51PEYOlJfggsGd__oS0fNv7BqwfnEt8Gs5vXkYm7f2AtQqQFncsC7Hlg_672RqFOUvEtiYLsr3p2ZADL11TtXntoMdti1q-IIUrso_NZulvSDG";
    $msg = array
    (
        'body'     => $body,
        'title'    => $title,
        'receiver' => 'erw',
        'icon'     => "https://salon-eljoker.com/images/joker.jpg",/*Default Icon*/
        'vibrate'	=> 1,
        'sound'		=> "http://commondatastorage.googleapis.com/codeskulptor-demos/DDR_assets/Kangaroo_MusiQue_-_The_Neverwritten_Role_Playing_Game.mp3",
    );

    $fields = array
    (
        'to'        => $token,
        'notification'  => $msg
    );

    $headers = array
    (
        'Authorization: key=' . $from,
        'Content-Type: application/json'
    );
    //#Send Reponse To FireBase Server
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    Notification::create([
        'user_id'               =>$id,
        'title'                 =>$title,
        'body'                  =>$body,
    ]);
    return $result;
}

