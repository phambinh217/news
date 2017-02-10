@extends('Admin::layouts.default',[
	'active_admin_menu' 	=> ['news', isset($news_id) ? 'news.all' : 'news.create'],
	'breadcrumbs' 			=> [
		'title'	=> ['Tin tức', isset($news_id) ? 'Chỉnh sửa' : 'Thêm mới'],
		'url'	=> [
			admin_url('news')
		],
	],
])

@section('page_title', isset($news_id) ? 'Chỉnh sửa tin tức' : 'Thêm tin tức mới')

@if(isset($news_id))
    @section('page_sub_title', $news->title)
    @section('tool_bar')
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> <span class="hidden-xs">Thêm tin tức mới</span>
        </a>
    @endsection
@endif

@section('content')
	<form ajax-form-container method="post" action="{{ isset($news_id) ? admin_url('news/' . $news->id) : admin_url('news') }}" class="form-horizontal form-bordered form-row-stripped">
        @if(isset($news_id))
            <input type="hidden" name="_method" value="PUT" />
        @endif
        {{ csrf_field() }}
        <div class="form-body">
            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Tên tin tức</strong><span class="required">*</span>
                </label>
                <div class="col-md-7">
                    <input value="{{ $news->title }}" name="news[title]" type="text" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Danh mục</strong><span class="required">*</span>
                </label>
                <div class="col-md-10">
                    @include('News::admin.components.form-select-category', [
                        'categories' =>  Phambinh\News\Models\Category::get(),
                        'name' => 'news[category_id][]',
                        'selected' => isset($news_id) ? $news->categories->pluck('id')->toArray() : [],
                        'class' => 'width-auto',
                    ])
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Nội dung</strong><span class="required">*</span>
                </label>
                <div class="col-md-10">
                    <textarea name="news[content]" class="form-control texteditor">{{ $news->content }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Meta title</strong>
                </label>
                <div class="col-md-7">
                    <input type="text" name="news[meta_title]" class="form-control" value="{{ $news->meta_title }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Meta description</strong>
                </label>
                <div class="col-md-7">
                    <textarea class="form-control" name="news[meta_description]">{{ $news->meta_description }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Meta keyword</strong>
                </label>
                <div class="col-md-7">
                    <input type="text" name="news[meta_keyword]" class="form-control" value="{{ $news->meta_keyword }}" />
                </div>
            </div>
            <div class="form-group media-box-group">
                <label class="control-label col-md-2">
                    <strong>Thumbnail</strong>
                </label>
                <div class="col-sm-10">
                    @include('Admin::admin.components.form-chose-media', [
                        'name'              => 'news[thumbnail]',
                        'value'             => old('news.thumbnail', $news->thumbnailOrDefault()),
                        'url_image_preview' => old('news.thumbnail', thumbnail_url($news->thumbnailOrDefault(), ['width' => '100', 'height' => '100']))
                    ])
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">
                    <strong>Trạng thái</strong> <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    @include('News::admin.components.form-select-status', [
                        'statuses' => $news->statusAble(),
                        'class' => 'width-auto',
                        'name' => 'news[status]',
                        'selected' => isset($news_id) ? ($news->status == 1 ? 'enable' : 'disable') : null,
                    ])
                </div>
            </div>
        </div>
        <div class="form-action util-btn-margin-bottom-5">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    @if(!isset($news_id))
                        @include('Admin::admin.components.btn-save-new')
                    @else
                        @include('Admin::admin.components.btn-save-out')
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection

@push('css')
    <link href="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/tinymce/tinymce.min.js')}} "></script>
@endpush
