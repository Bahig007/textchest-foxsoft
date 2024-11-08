<?php

namespace App\Http\Controllers;

use App\Events\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class smsContoller extends Controller
{
   

 
      public function getNumber()
         {
            $rawKey = env('TEXT_CHEST_kEY');
          
            // Decode the token (e.g., base64 decoding)
            $encodedKey = base64_encode($rawKey);
         
            $response = Http::withOptions([
                'verify' => false, // You can temporarily disable SSL verification (not recommended for production)
            ])->withHeaders([
                'Authorization' => 'Basic ' . $encodedKey,
                'Accept' => 'application/json',
            ])->get('https://api.textchest.com/numbers');
            

     
             if ($response->successful()) {
                return $response->json();
               // return response()->json(['data' => $response,'token' => $rawKey], );
             }
     
             return response()->json(['error' => 'Failed to fetch data'], $response->status());
         }


         public function getSms($number)
         {
           // $rawKey = env('TEXT_CHEST_kEY');
            $rawKey = env('TEXT_CHEST_kEY');
             // Correct the environment variable name
             $encodedKey = base64_encode($rawKey);
             if (!$rawKey) {
                return response()->json(['error' => 'Environment variable TEXT_CHEST_KEY not set'], 500);
            }
             $response = Http::withOptions([
                 'verify' => false, // Temporarily disable SSL verification (not recommended for production)
             ])->withHeaders([
                 'Authorization' => 'Basic ' . $encodedKey,
                 'Accept' => 'application/json',
             ])->get("https://api.textchest.com/sms?number=" . $number); // Use double quotes or single quotes
         
             if ($response->successful()) {
                 $messages = $response->json();
                 
                 // Define keywords and their associated company names
                 $keywordMap = [
                     'Yahoo' => 'Yahoo',
                     'Your X confirmation' => 'X',
                     'Facebook' => 'Facebook'
                 ];
         
                 // Initialize an array to hold the classified messages by company
                 $classifiedMessages = [];
         
                 // Initialize each company in the classifiedMessages array
                 foreach ($keywordMap as $keyword => $companyName) {
                     $classifiedMessages[] = [
                         'company' => $companyName,
                         'messages' => []
                     ];
                 }
         
                 // Classify messages into the corresponding arrays
                 foreach ($messages as $message) {
                     foreach ($keywordMap as $keyword => $companyName) {
                         if (stripos($message['msg'], $keyword) !== false) { // Case insensitive check
                             // Find the correct company array to add the message to
                             foreach ($classifiedMessages as &$classifiedMessage) {
                                 if ($classifiedMessage['company'] === $companyName) {
                                     $classifiedMessage['messages'][] = $message; // Add message to the corresponding company
                                 }
                             }
                         }
                     }
                 }
         
                 // Return the structured response with classified messages
                 return response()->json($classifiedMessages); // Return the array of objects
             }
         
             return response()->json(['error' => 'Failed to fetch data' , 'token' => $rawKey], $response->status()); // Return error if failed
         }
    


    
         public function handleWebhook(Request $request)
         {
             // Extract token and data from the request
             $token = $request->input('token');
             $data = $request->input('data');
     
             // Validate the token
             if ($token !== 'YOUR_TOKEN_ON_WEBHOOKS') {
                 return response()->json(['error' => 'Invalid token'], 403);
             }
     
             // Extract the message from the data
             $message = $data['message'] ?? null;
     
             // Define the keyword map
             $keywordMap = [
                 'Yahoo' => 'Yahoo',
                 'Your X confirmation' => 'X',
                 'Facebook' => 'Facebook'
             ];
     
             // Initialize a variable to hold the matched company name
             $matchedCompany = null;
     
             // Check if the message contains any of the keywords
             foreach ($keywordMap as $keyword => $company) {
                 if (strpos($message, $keyword) !== false) {
                     $matchedCompany = $company;
                    //  \Log::info("Message contains $matchedCompany: " . $message);
                     break; // Exit the loop after the first match
                 }
             }
     
             // Optional: Handle cases where no company matched
            //  if ($matchedCompany === null) {
            //      \Log::info("No matching company found in message: " . $message);
            //  }
     
             // Respond to the webhook
             event(new Message($matchedCompany, $data['recipient'], $message));
             return response()->json(['message' => 'Webhook received' , 'recipient' => $data['recipient'],'company' =>  $matchedCompany]);
         }
     

        
   
    //  public function index()
    //  {
    //      $smsMessages = SMS::all();
    //      return response()->json($smsMessages, 200);
    //  }
 
    //  // Store a newly created SMS message in storage
    //  public function store(Request $request)
    //  {
    //      $request->validate([
    //          'msg' => 'required|string',
    //          'sender_name' => 'required|string',
    //          'recipient_number' => 'required|string',
    //      ]);
 
    //      $sms = SMS::create($request->all());
    //      return response()->json($sms, 201);
    //  }
 
    //  // Display the specified SMS message
    //  public function show($id)
    //  {
    //      $sms = SMS::findOrFail($id);
    //      return response()->json($sms, 200);
    //  }
 
    //  // Update the specified SMS message in storage
    //  public function update(Request $request, $id)
    //  {
    //      $sms = SMS::findOrFail($id);
 
    //      $request->validate([
    //          'msg' => 'sometimes|required|string',
    //          'sender_name' => 'sometimes|required|string',
    //          'recipient_number' => 'sometimes|required|string',
    //          'seen' => 'sometimes|required|boolean',
    //      ]);
 
    //      $sms->update($request->all());
    //      return response()->json($sms, 200);
    //  }
 
    //  // Remove the specified SMS message from storage (soft delete)
    //  public function destroy($id)
    //  {
    //      $sms = SMS::findOrFail($id);
    //      $sms->delete();
    //      return response()->json(null, 204);
    //  }

}
