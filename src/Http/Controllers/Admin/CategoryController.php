<?php

namespace Phambinh\News\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Phambinh\News\Models\Category;
use AdminController;
use Validator;

class CategoryController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        \Metatag::set('title', 'Danh sách danh mục tin tức');

        $category = new Category();
        $filter = $category->getRequestFilter();
        $this->data['category']    = $category;
        $this->data['categories']    = $category->ofQuery($filter)->paginate($this->paginate);
        $this->data['filter'] = $filter;

        return view('News::admin.category.list', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        \Metatag::set('title', 'Thêm danh mục mới');

        $this->data['category'] = new Category();
        return view('News::admin.category.save', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category.title'        => 'required|max:255',
            'category.slug'            => 'max:255',
            'category.description'    => 'max:300',
        ]);

        $category = new Category();
        
        $category->fill($request->category);
        if (empty($category->slug)) {
            $category->slug = str_slug($category->title);
        }

        $category->save();

        if ($request->ajax()) {
            return response()->json([
                'title'        =>    'Thành công',
                'message'    =>    'Thành công',
                'redirect'    =>    isset($request->save_only) ?
                    route('admin.news.category.edit', ['id' => $category->id]) :
                    route('admin.news.category.create'),
            ]);
        }
        
        if (isset($request->save_only)) {
            return redirect(route('admin.news.category.edit', ['id' => $category->id]));
        }

        return redirect(route('admin.news.category.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        \Metatag::set('title', 'Chỉnh sửa danh mục');

        $category = Category::find($id);
        $this->data['category'] = $category;
        $this->data['category_id'] = $id;

        return view('News::admin.category.save', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category.title'        => 'required|max:255',
            'category.slug'            => 'max:255',
            'category.description'    => 'max:300',
        ]);

        $category = Category::find($id);
        $category->fill($request->category);
        if (empty($category->slug)) {
            $category->slug = str_slug($category->title);
        }
        $category->save();

        if ($request->ajax()) {
            $response = [
                'title'        =>    'Thành công',
                'message'    =>    'Thành công',
            ];
            if (isset($request->save_and_out)) {
                $response['redirect'] = admin_url('news/category');
            }

            return response()->json($response, 200);
        }
        
        if (isset($request->save_and_out)) {
            return redirect(admin_url('news/category'));
        }
                
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category->newses()->count()) {
            if ($request->ajax()) {
                return response()->json([
                    'title'        =>    'Lỗi',
                    'message'    =>    'Danh mục đã có tin tức',
                ], 422);
            }
            
            return redirect()->back();
        }

        $category->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Thành công',
            ], 200);
        }

        return redirect()->back();
    }
}
