<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Страница отображения информации о категориях
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::paginate(10)
        ]);
    }

    /**
     * Страница создания новой категории
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Принимает данные из формы создания новой категории, создает категорию и редиректит на страницу со списком
     * категорий
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Category::create($this->validate($request, [
            'title' => 'required|unique:categories|max:30',
            'published' => 'boolean',
        ], [
            'required' => 'Поле :attribute не заполнено',
            'unique' => 'Поле :attribute не уникально',
            'max' => 'Поле :attribute не может быть длиннее 30 символов',
        ]));

        return redirect()->route('admin.category.index')->with('status', 'Категория успешно создана!');
    }

    /**
     * Страница редактирования категории
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Изменяет данные о категории и редиректит обратно на страницу редактирования этой категории
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->update($this->validate($request, [
            'title' => 'required|unique:categories|max:30',
            'published' => 'boolean',
        ], [
            'required' => 'Поле :attribute не заполнено',
            'unique' => 'Поле :attribute не уникально',
            'max' => 'Поле :attribute не может быть длиннее 30 символов',
        ]));

        return redirect()->route('admin.category.edit', $category)->with('status', 'Категория успешно обновлена!');
    }

    /**
     * Удаление категории
     *
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.category.index')->with('status', 'Категория успешно удалена');
    }
}
