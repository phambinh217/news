<?php

namespace Phambinh\News\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AdminController;
use Validator;
use Phambinh\News\News;

class NewsController extends AdminController
{
    public function index()
    {
        $filter = News::getRequestFilter();
        $this->data['filter'] = $filter;
        $this->data['newses'] = News::ofQuery($filter)->with('author')->paginate($this->paginate);

        \Metatag::set('title', 'Tất cả tin tức');
        return view('News::admin.list', $this->data);
    }

    public function create()
    {
        \Metatag::set('title', 'Thêm tin tức mới');

        $news = new News();
        $this->data['news'] = $news;
        
        return view('News::admin.save', $this->data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'news.title'            =>    'required|max:255',
            'news.content'            =>    'min:0',
            'news.category_id'        =>    'required|exists:news_categories,id',
            'news.status'            =>    'required|in:enable,disable',
        ]);

        $news = new News();

        $news->fill($request->news);
        
        switch ($news->status) {
            case 'disable':
                $news->status = '0';
                break;

            case 'enable':
                $news->status = '1';
                break;
        }

        if (empty($news->slug)) {
            $news->slug = str_slug($news->title);
        }

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
            return redirect()->route('admin.news.edit', ['id' => $news->id]);
        }

        return redirect()->route('admin.news.create');
    }
    
    public function edit(News $news)
    {
        $this->data['news_id'] = $news->id;
        $this->data['news']    = $news;

        \Metatag::set('title', 'Chỉnh sửa tin tức');
        return view('News::admin.save', $this->data);
    }

    public function update(Request $request, News $news)
    {
        $this->validate($request, [
            'news.title'            =>    'required|max:255',
            'news.content'        =>    'min:0',
            'news.category_id'    =>    'required|exists:news_categories,id',
            'news.status'            =>    'required|in:enable,disable',
        ]);

        $news->fill($request->news);

        switch ($news->status) {
            case 'disable':
                $news->status = '0';
                break;

            case 'enable':
                $news->status = '1';
                break;
        }

        if (empty($news->slug)) {
            $news->slug = str_slug($news->title);
        }
        
        $news->save();
        $news->categories()->sync((array) $request->news['category_id']);

        if ($request->ajax()) {
            $response = [
                'title'        =>    'Thành công',
                'message'    =>    'Cập nhật tin thành công',
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

    public function disable(Request $request, News $news)
    {
        $news->status = '0';
        $news->save();
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Đã ẩn tin',
            ], 200);
        }

        return redirect()->back();
    }

    public function enable(Request $request, News $news)
    {
        $news->status = '1';
        $news->save();
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Đã công khai tin',
            ], 200);
        }

        return redirect()->back();
    }

    public function destroy(Request $request, News $news)
    {
        $news->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Đã xóa tin',
            ], 200);
        }

        return redirect()->back();
    }
}
