<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\IndexContactRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index(IndexContactRequest $request)
    {
        $contacts = Contact::with(['category', 'tags'])
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;
                $query->where(function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })

            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->where('gender', $request->gender);
            })

            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })

            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })

            ->paginate(7)
            ->withQueryString();

        $categories = Category::all();

        $tags = Tag::all();

        return view('admin.index', compact(
            'contacts',
            'categories',
            'tags',
        ));
    }

    public function show(Contact $contact)
    {
        $contact->load([
            'category',
            'tags',
        ]);

        return view('admin.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.index');
    }
}
