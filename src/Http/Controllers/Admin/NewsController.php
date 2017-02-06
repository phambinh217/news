<?php

namespace App\Modules\News\Src\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AdminController;
use Validator;
use App\Modules\News\Src\Models\News;

class NewsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        \Metatag::set('title', 'Tất cả tin tức');

        $news = new News();
        $filter = $news->getRequestFilter();
        $this->data['filter'] = $filter;
        $this->data['newses'] = $news->ofQuery($filter)->paginate($this->paginate);
        $this->data['news'] = $news;

        return view('News::admin.list', $this->data);
    }

    /**
     *
     * Hiển thị form thêm tin tức
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function create()
    {
        \Metatag::set('title', 'Thêm tin tức mới');

        $news = new News();
        $this->data['news'] = $news;
        
        return view('News::admin.save', $this->data);
    }

    /**
     *
     * Xử lí tin tức trước khi lưu vào database
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'news.title'            =>    'required|max:255',
            'news.content'            =>    'min:0',
            'news.category_id'        =>    'required|exists:terms,id',
            'news.status'            =>    'required|in:0,1',
        ]);

        $news = new News();

        $news->fill($request->news);
        $news->slug = str_slug($news->title);
        $news->author_id = \Auth::user()->id;
        $news->save();
        $news->categories()->sync((array) $request->news['category_id']);

        if ($request->ajax()) {
            return response()->json([
                'title'        =>    'Thành công',
                'message'    =>    'Thành công',
                'redirect'    =>    isset($request->save_only) ?
                    route('admin.news.edit', ['id' => $news->id]) :
                    route('admin.news.create'),
            ], 200);
        }

        if (isset($request->save_only)) {
            return redirect(route('admin.news.edit', ['id' => $news->id]));
        }

        return redirect(route('admin.news.create'));
    }
    
    /**
     *
     * Hiển thị form chỉnh sửa tin tức
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function edit($id)
    {
        \Metatag::set('title', 'Chỉnh sửa tin tức');

        $news = News::find($id);

        $this->data['news_id']    = $id;
        $this->data['news']        = $news;

        return view('News::admin.save', $this->data);
    }

    /**
     *
     * Xử lí tin tức trước khi cập nhật vào database
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'news.title'            =>    'required|max:255',
            'news.content'        =>    'min:0',
            'news.category_id'    =>    'required|exists:terms,id',
            'news.status'            =>    'required|in:0,1',
        ]);

        $news = News::find($id);
        $news->fill($request->news);
        $news->slug = str_slug($news->title);
        $news->save();
        $news->categories()->sync((array) $request->news['category_id']);

        if ($request->ajax()) {
            $response = [
                'title'        =>    'Thành công',
                'message'    =>    'Thành công',
            ];
            if (isset($request->save_and_out)) {
                $response['redirect'] = admin_url('news');
            }

            return response()->json($response, 200);
        }
        
        if (isset($request->save_and_out)) {
            return redirect(admin_url('news'));
        }
                
        return redirect()->back();
    }

    /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function disable(Request $request, $id)
    {
        $news = News::find($id);
        $news->status = '0';
        $news->save();
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Thành công',
            ], 200);
        }

        return redirect()->back();
    }

    /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function enable(Request $request, $id)
    {
        $news = News::find($id);
        $news->status = '1';
        $news->save();
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Thành công',
            ], 200);
        }

        return redirect()->back();
    }

    /**
     *
     *
     *
     * @param
     * @return
     * @author BinhPham
     */
    public function destroy(Request $request, $id)
    {
        $news = News::find($id);
        $news->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Thành công',
            ], 200);
        }

        return redirect()->back();
    }
}
