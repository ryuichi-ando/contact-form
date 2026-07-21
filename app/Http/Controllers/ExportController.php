<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $contacts = Contact::with('category')

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

            ->latest()
            ->get();

        return response()->streamDownload(function () use ($contacts) {

            $csv = fopen('php://output', 'w');

            fwrite($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($csv, [
                '姓',
                '名',
                '性別',
                'メール',
                'カテゴリ',
                '登録日',
            ]);

            foreach ($contacts as $contact) {

                fputcsv($csv, [
                    $contact->last_name,
                    $contact->first_name,
                    match ($contact->gender) {
                        1 => '男性',
                        2 => '女性',
                        default => 'その他',
                    },
                    $contact->email,
                    optional($contact->category)->content,
                    $contact->created_at,
                ]);
            }

            fclose($csv);

        }, 'contacts.csv');
    }
}
