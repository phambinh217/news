@extends('Cms::layouts.default',[
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
    @can('admin.news.create')
        @section('tool_bar')
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> <span class="hidden-xs">Thêm tin tức mới</span>
            </a>
        @endsection
    @endcan
@endif

@section('content')
    <div class="portlet light bordered form-fit">
        <div class="portlet-title with-tab">
            <div class="tab-default">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#news-content" data-toggle="tab" aria-expanded="true"> Nội dung </a>
                    </li>
                    <li class="">
                        <a href="#news-data" data-toggle="tab" aria-expanded="false"> Dữ liệu </a>
                    </li>
                    <li class="">
                        <a href="#news-seo" data-toggle="tab" aria-expanded="false"> SEO </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="portlet-body form">
            <form ajax-form-container method="post" action="{{ isset($news_id) ? admin_url('news/' . $news->id) : admin_url('news') }}" class="form-horizontal form-bordered form-row-stripped">
                @if(isset($news_id))
                    <input type="hidden" name="_method" value="PUT" />
                @endif
                {{ csrf_field() }}
                <div class="form-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="news-content">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Tên tin</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <input value="{{ $news->title }}" name="news[title]" type="text" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="news[slug]" value="{{ $news->slug }}" placeholder="Slug" class="form-control str-slug" value="{{ $news->slug or '' }}" />
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="true" checked="" id="create-slug">
                                                Từ tên tin
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Nội dung<span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <textarea name="news[content]" class="form-control texteditor">{{ $news->content }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="news-data">
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Danh mục<span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    @include('News::admin.components.form-checkbox-category', [
                                        'categories' =>  Packages\News\Category::get(),
                                        'name' => 'news[category_id][]',
                                        'checked' => $news->categories->pluck('id')->all(),
                                        'class' => 'width-auto',
                                    ])
                                </div>
                            </div>
                            <div class="form-group media-box-group">
                                <label class="control-label col-md-2">
                                    Thumbnail
                                </label>
                                <div class="col-sm-10">
                                    @include('Cms::components.form-chose-media', [
                                        'name'              => 'news[thumbnail]',
                                        'value'             => old('news.thumbnail', $news->thumbnailOrDefault()),
                                        'url_image_preview' => old('news.thumbnail', thumbnail_url($news->thumbnailOrDefault(), ['width' => '100', 'height' => '100']))
                                    ])
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">
                                    Trạng thái <span class="required">*</span>
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
                        <div class="tab-pane" id="news-seo">
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Meta title
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="news[meta_title]" class="form-control" value="{{ $news->meta_title }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Meta description
                                </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="news[meta_description]">{{ $news->meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Meta keyword
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="news[meta_keyword]" class="form-control" value="{{ $news->meta_keyword }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions util-btn-margin-bottom-5">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            @if(!isset($news_id))
                                @include('Cms::components.btn-save-new')
                            @else
                                @include('Cms::components.btn-save-out')
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/tinymce/tinymce.min.js')}} "></script>
    <script type="text/javascript">
        $(function(){
            $('#create-slug').click(function() {
                if(this.checked) {
                    var title = $('input[name="news[title]"]').val();
                    var slug = strSlug(title);
                    $('input[name="news[slug]"]').val(slug);
                }
            });

            $('input[name="news[title]"]').keyup(function() {
                if ($('#create-slug').is(':checked')) {
                    var title = $(this).val();
                    var slug = strSlug(title);
                    $('input[name="news[slug]"]').val(slug); 
                }
            });
        });
    </script>
@endpush