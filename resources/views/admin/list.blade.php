@extends('Admin::layouts.default', [
	'active_admin_menu'	=> ['news', 'news.all'],
	'breadcrumbs' 		=> [
		'title'	=>	['Tin tức', 'Danh sách'],
		'url'	=>	[
			admin_url('news'),
			admin_url('news'),
		],
	],
])

@section('page_title', 'Tất cả tin tức')

@section('tool_bar')
	@if(can('news.create'))
		<a href="{{ route('admin.news.create') }}" class="btn btn-primary">
			<i class="fa fa-plus"></i> <span class="hidden-xs">Thêm tin tức mới</span>
		</a>
	@endif
@endsection

@section('content')
<div class="table-function-container">
   	<div class="portlet light bordered">
	    <div class="portlet-title">
	        <div class="caption">
	            <i class="fa fa-filter"></i> Bộ lọc kết quả
	        </div>
	    </div>
	    <div class="portlet-body form">
	        <form class="form-horizontal form-bordered form-row-stripped">
	            <div class="form-body">
	                <div class="row">
	                    <div class="col-sm-6 md-pr-0">
	                    	<div class="form-group">
	                            <label class="control-label col-md-3">Tin tức</label>
	                            <div class="col-md-9">
	                                 @include('News::admin.components.form-find-news', [
                                		'name' => 'id',
                                		'selected' => isset($filter['id']) ? $filter['id'] : '0',
                                	])
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Category</label>
	                            <div class="col-md-9">
	                                @include('News::admin.components.form-select-category', [
                                		'categories' =>  Phambinh\News\Models\Category::get(),
                                		'name' => 'category_id',
                                		'selected' => isset($filter['category_id']) ? $filter['category_id'] : '0',
                                	])
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6 md-pl-0">
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Trạng thái</label>
	                            <div class="col-md-9">
	                            	@include('News::admin.components.form-select-status', [
				                        'statuses' => Phambinh\News\Models\News::statusAble(),
				                        'name' => 'status',
				                        'selected' => isset($filter['status']) ? $filter['status'] : null,
				                    ])
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Tác giả</label>
	                            <div class="col-md-9">
	                                @include('User::admin.components.form-find-user', [
	                            		'name' => 'author_id',
	                            		'selected' => isset($filter['author_id']) ? $filter['author_id'] : '0',
	                            	])
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="form-actions util-btn-margin-bottom-5">
	                <div class="row">
	                    <div class="col-md-12 text-right">
	                        <button type="submit" class="btn btn-primary">
	                            <i class="fa fa-filter"></i> Lọc
							</button>
	                        <a href="{{ admin_url('news') }}" class="btn default accordion-toggle">
	                            <i class="fa fa-times"></i> Hủy
	                        </a>
	                    </div>
	                </div>
	            </div>
	        </form>
	    </div>
	</div>
    <div class="row table-above">
	    <div class="col-sm-6">
	    	<div class="form-inline mb-10">
		    	@include('Admin::admin.components.form-apply-action', [
		    		'actions' => [
		    			['action' => '', 'name' => ''],
		    			['action' => '', 'name' => ''],
		    			['action' => '', 'name' => ''],
		    		],
		    	])
		    </div>
	    </div>
	    <div class="col-sm-6 text-right">
	    	{!! $newses->appends($filter)->render() !!}
	    </div>
    </div>
    <div class="note note-success">
        <p><i class="fa fa-info"></i> Tổng số {{ $newses->total() }} kết quả</p>
    </div>
    <div class="table-responsive main">
		<table class="master-table table table-striped table-hover table-checkable order-column">
			<thead>
				<tr>
					<th width="50" class="table-checkbox text-center">
						<div class="checker">
									<input type="checkbox" class="icheck check-all">
								</div>
					</th>
					<th width="50" class="text-center">
						{!! \Phambinh\News\Models\News::linkSort('ID', 'id') !!}
					</th>
					<th class="text-center">
						{!! \Phambinh\News\Models\News::linkSort('Tên tin tức', 'title') !!}
					</th>
					<th class="text-center">
						
					</th>
					<th class="text-center">
						
					</th>
					<th class="text-center">Thao tác</th>
				</tr>
			</thead>
			<tbody>
				@foreach($newses as $news_item)
				<tr class="odd gradeX hover-display-container {{ $news_item->statusHtmlClass() }}">
					<td width="50" class="table-checkbox text-center">
						<div class="checker">
							<input type="checkbox" class="icheck" value="{{ $news_item->id }}">
						</div>
					</td>
					<td class="text-center">
						<strong>{{ $news_item->id }}</strong>
					</td>
					<td>
						<a href="">
							<strong>{{ $news_item->title }}</strong>
						</a>
					</td>
					<td class="text-center">
						
					</td>
					<td class="text-center">
						
					</td>

					<td>
						<div class="btn-group pull-right" table-function>
	                        <a href="" class="btn btn-circle btn-xs grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
											<span class="hidden-xs">
				                            	Chức năng
				                                <span class="fa fa-angle-down"> </span>
			                                </span>
			                                <span class="visible-xs">
			                                	<span class="fa fa-cog"> </span>
			                                </span>
	                            <span class="fa fa-angle-down"> </span>
	                        </a>
	                        <ul class="dropdown-menu pull-right">
	                            <li><a href="{{ route('admin.news.show', ['id' => $news_item->id]) }}"><i class="fa fa-eye"></i> Xem</a></li>

	                            <li role="presentation" class="divider"> </li>
	                            
	                            @if(can('news.edit'))
	                            	<li><a href="{{ route('admin.news.edit',['id' => $news_item->id]) }}"><i class="fa fa-pencil"></i> Sửa</a></li>
	                            @endif
	                        	
	                        	@if($news_item->isEnable())
	                        		@if(can('news.disable'))
	                        			<li><a data-function="disable" data-method="put" href="{{ route('admin.news.disable', ['id' => $news_item->id]) }}"><i class="fa fa-recycle"></i> Xóa tạm</a></li>
	                        		@endif
	                        	@endif

	                            @if($news_item->isDisable())
	                        		@if(can('news.enable'))
	                            		<li><a data-function="enable" data-method="put" href="{{ route('admin.news.enable', ['id' => $news_item->id]) }}"><i class="fa fa-recycle"></i> Khôi phục</a></li>
	                            	@endif

	                            	@if(can('news.destroy'))
	                            		<li role="presentation" class="divider"></li>
	                            		<li><a data-function="destroy" data-method="delete" href="{{ route('admin.news.destroy', ['id' => $news_item->id]) }}"><i class="fa fa-times"></i> Xóa</a></li>
	                            	@endif
	                        	@endif
	                        </ul>
	                    </div>
					</td>

				</tr>
				@endforeach
			</tbody>
		</table>  
	</div>
</div>
@endsection

@push('css')
	<link href="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ url('assets/admin/global/plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/icheck/icheck.min.js')}} "></script>
@endpush