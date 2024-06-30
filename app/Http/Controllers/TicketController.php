<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Message;
use App\Models\User;
use App\Ticket;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTickets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $user = User::where('mobile', $request->mobile)->first();
        // $ticket = Ticket::where('user_id', $user->id)->first();
        $ticket = $user->ticket;
        if(count($ticket) == 0)
            return response()->json([
                'status' => false,
                'message' => 'تیکتی برای این کاربر وجود ندارد.',
            ], 400);

        return response()->json([
            'status' => true,
            'ticket' => $ticket,
        ], 201);
    }

    public function getMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $ticket = Ticket::where('id', $request->id)->first();
        $message = $ticket->message;

        return response()->json([
            'status' => true,
            'message' => $message,

        ], 201);
    }

    public function createTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => 'required',
            'title' => 'required',
            'message' =>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if($user != null){
            $ticket = Ticket::create([
                'title' => $request->title,
                'user_id' => $user->id,
                'status' => Ticket::NOT_ANSWERED,
            ]);
            $message = Message::create([
                'message' => $request->message,
                'ticket_id' => $ticket->id,
                'user_id' => $user->id

            ]);
            return response()->json([
                'status' => true,
                'message' => 'تیکت ثبت شد',
                'ticket_id' => $message->ticket_id,
                'message_id' => $message->id,
            ], 201);
        }
        else
            return response()->json([
                'status' => false,
                'message' => ' کاربر وجود ندارد.',
            ], 400);
    }

    public function createAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answer' =>'required',
            'ticket_id' => 'required',
            'message_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $ticket = Ticket::findOrFail($request->ticket_id);
        $answer = Answer::create([
            'answer' => $request->answer,
            'ticket_id' => $ticket->id,
            'message_id' => $request->message_id,
            'user_id' => $ticket->user_id
        ]);

        $ticket->update([
            'status' => Ticket::ANSWERED,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'پاسخ ثبت شد',
            'ticket_id' => $answer->ticket_id,
            'answer_id' => $answer->id,
        ], 201);
    }

    public function messagAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' =>'required',
            'ticket_id' => 'required',
            'answer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }
        // $ticket = Ticket::where('id' , $request->ticket_id)->first();
        $ticket = Ticket::findOrFail($request->ticket_id);

        $message = Message::create([
            'message' => $request->message,
            'ticket_id' => $ticket->id,
            'answer_id' =>$request->answer_id,
            'user_id' => $ticket->user_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'پاسخ ثبت شد',

        ], 201);
    }

}
