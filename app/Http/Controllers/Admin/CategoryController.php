<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request) {
        // 検索ボックスに入力されたキーワードを取得する
        $keyword = $request->input('keyword');

        // キーワードが存在すれば検索を行い、そうでなければ全件取得する
        if ($keyword) {
            $categories = Category::where('name', 'like', "%{$keyword}%")->paginate(15);
        } else {
            $categories = Category::paginate(15);
        }

        $total = $categories->total();

        return view('admin.categories.index', compact('categories', 'keyword', 'total'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを登録しました。');
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'name' => 'required',
        ]);

        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを編集しました。');
    }

    public function destroy(Category $category) {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを削除しました。');
    }
}
