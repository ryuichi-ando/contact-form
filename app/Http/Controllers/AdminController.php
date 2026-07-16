<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class AdminController extends Controller
{
    public function index()
    {
        $contacts = Contact::with(['category', 'tags'])
            ->paginate(7);

        $categories = Category::all();

        $tags = Tag::all();

        return view('admin.index', compact(
            'contacts',
            'categories',
            'tags',
        ));
    }
}
