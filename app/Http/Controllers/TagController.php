<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(StoreTagRequest $request)
    {
        Tag::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'タグを追加しました。');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.index')
            ->with('success', 'タグを編集しました');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.index');
    }
}
