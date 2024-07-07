<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactForm;
class ContactFormController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        // Create a new contact form entry
        $contactForm = ContactForm::create($validatedData);

        // Return a response
        return response()->json([
            'status'=>true,
            'message' => 'success',
            'data' => $contactForm
        ], 201);
    }

}
