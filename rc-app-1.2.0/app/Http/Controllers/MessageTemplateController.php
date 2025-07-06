<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageTemplate;

class MessageTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = MessageTemplate::all();
        return view('message-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('message-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|unique:message_templates,name',
            'title'   => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        MessageTemplate::create($request->only('name', 'title', 'content'));

        return redirect()->route('message-templates.index')->with('success', 'Template berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageTemplate $messageTemplate)
    {
        return view('message-templates.edit', compact('messageTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        $request->validate([
            'name'    => 'required|string|unique:message_templates,name,' . $messageTemplate->id,
            'title'   => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $messageTemplate->update($request->only('name', 'title', 'content'));

        return redirect()->route('message-templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageTemplate $messageTemplate)
    {
        $messageTemplate->delete();
        return redirect()->route('message-templates.index')->with('success', 'Template berhasil dihapus.');
    }
}
