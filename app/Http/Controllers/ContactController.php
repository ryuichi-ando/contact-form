<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('contact.index', compact('categories', 'tags'));
    }

    public function confirm(StoreContactRequest $request)
    {
        $validated = $request->validated();

        $category = Category::find($validated['category_id']);

        $tags = collect();

        if ($request->filled('tag_ids')) {
            $tags = Tag::whereIn('id', $request->tag_ids)->get();
        }

        return view('contact.confirm', [
            'validated' => $validated,
            'category' => $category,
            'tags' => $tags,
        ]);
    }

    public function back(StoreContactRequest $request)
    {
        return redirect('/')
            ->withInput($request->all());
    }

    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
        $contact = Contact::create($validated);

        if ($request->filled('tag_ids')) {
            $contact->tags()->attach($request->tag_ids);
        }

        return redirect()->route('contact.thanks');
    }
}
