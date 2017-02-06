@extends( 'Admin::layouts.default',[
	'active_admin_menu' 	=> ['news', isset( $news_id ) ? 'news.all' : 'news.create'],
	'breadcrumbs' 			=> [
		'title'	=> ['Tin tức', isset( $news_id ) ? 'Chỉnh sửa' : 'Thêm mới'],
		'url'	=> [
			admin_url( 'news' )
		],
	],
])

@section( 'page_title', isset( $news_id ) ? 'Chỉnh sửa tin tức' : 'Thêm tin tức mới' )

@if( isset( $news_id ))
    @section( 'page_sub_title', $news->title )
    @section( 'tool_bar' )
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm tin tức mới
        </a>
    @endsection
@endif

@section( 'content' )
	<form ajax-form-container method="post" action="{{ isset( $news_id ) ? admin_url( 'news/' . $news->id ) : admin_url( 'news' ) }}" class="form-horizontal form-bordered form-row-stripped">
        @if( isset( $news_id ))
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
                    <select name="news[category_id][]" class="form-control width-auto">
                        <?php $category = new App\Modules\News\Src\Models\Category(); ?>
                        @foreach( $category->get() as $category_item )
                            <option {{ in_array( $category_item->id, isset( $news_id ) ? $news->categories()->getRelatedIds()->all() : [] ) ? 'selected' : NULL }} value="{{ $category_item->id }}">{{ $category_item->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">
                    <strong>Nội dung</strong><span class="required">*</span>
                </label>
                <div class="col-md-10">
                    <textarea name="news[content]" id="post-content" class="form-control">{{ $news->content }}</textarea>
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
                    <input type="hidden" name="news[thumbnail]" class="hide file-input" value="{{ $news->thumbnailOrDefault() }}" />
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mt-element-card mt-element-overlay">
                                <div class="mt-card-item">
                                    <div class="mt-card-thumbnail mt-overlay-1 fileinput-new fileinput">
                                        <div class="fileinput-new">
                                            @if( old( 'news.thumbnail' ))
                                                <img src="{{old( 'news.thumbnail' )}}" class="image-preview" />
                                            @else
                                                <img src="{{ $news->thumbnailOrDefault() }}" class="image-preview" />
                                            @endif
                                        </div>
                                        <div class="fileinput-preview fileinput-exists"></div>
                                        <div class="mt-overlay">
                                            <ul class="mt-info">
                                                <li>
                                                    <a class="btn default btn-outline open-file-broswer">
                                                        <i class="fa fa-upload"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="btn default btn-outline">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">
                    <strong>Trạng thái</strong> <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <select name="news[status]" class="form-control width-auto">
                        <option {{ isset( $news_id ) && $news->status == '1' ? 'selected' : '' }} value="1">Đăng</option>
                        <option {{ isset( $news_id ) && $news->status == '0' ? 'selected' : '' }} value="0">Ẩn</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2"></label>
                <div class="col-md-10">
                    <button type="submit" class="btn btn-primary" name="save_only">
                        <i class="fa fa-save"></i> Lưu thay đổi
                    </button>
                    @if( ! isset( $news_id ))
                        <button type="submit" class="btn btn-primary" name="save_and_new">
                            <i class="fa fa-save"></i> Lưu và tiếp tục thêm
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary" name="save_and_out">
                            <i class="fa fa-save"></i> Lưu và thoát
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection

@push( 'css' )
    <link href="{{ url( 'assets/admin/global/plugins/bootstrap-toastr/toastr.min.css' )}}" rel="stylesheet" type="text/css" />
    <link href="{{ url( 'assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' ) }}" rel="stylesheet" type="text/css" />
@endpush

@push( 'js_footer' )
    <script type="text/javascript" src="{{ url( 'assets/admin/global/plugins/jquery-form/jquery.form.min.js' ) }}"></script>
    <script type="text/javascript" src="{{ url( 'assets/admin/global/plugins/bootstrap-toastr/toastr.min.js' ) }}"></script>
    <script type="text/javascript" src="{{ url( 'assets/admin/global/plugins/tinymce/tinymce.min.js' )}} "></script>
    <script type="text/javascript">
        $(function(){
            $(function(){   
            pb.ajaxForm();
            pb.textEditor({
                    selector: '#post-content',
                    switch_toolbar: 'base',
                });
            });
        });
    </script>
@endpush

@include( 'Trainer::admin.component.find-trainer' )
