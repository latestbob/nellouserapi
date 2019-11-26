<?php

namespace App\Http\Controllers;

use App\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);
        $data = $request->all();
        $data['user_uuid'] = $request->user()->uuid;
        $message = ContactMessage::create($data);
        return $message;
    }
}
