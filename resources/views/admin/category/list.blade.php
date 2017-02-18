@extends('Admin::layouts.default', [
	'active_admin_menu'	=> ['news', 'news.category'],
	'breadcrumbs'		=>	[
		'title' => ['Tin tức', 'Danh mục'],
		'url'	=> [
			admin_url('news'),
		],
	],	
])

@section('page_title', 'Danh mục tin tức')

@section('tool_bar')
	@can('admin.news.category.create')
		<a href="{{ route('admin.news.category.create') }}" class="btn btn-primary">
			<i class="fa fa-plus"></i> <span class="hidden-xs">Thêm danh mục mới</span>
		</a>
	@endcan
@endsection

@section('content')
<div class="table-function-container">
	<div class="note note-success">
		<p><i class="fa fa-info"></i> Tổng số {{ $categories->count() }} kết quả</p>
	</div>
	<div class="row table-above">
		<div class="col-sm-6">
			<div class="form-inline mb-10 apply-action">
				@include('Admin::admin.components.form-apply-action', [
					'actions' => [
						['action' => '', 'name' => 'Xóa vĩnh viễn', 'method' => 'DELETE'],
					],
				])
				</div>
		</div>
		<div class="col-sm-6 text-right">
			{!!$categories->setPath('category')->appends($filter)->render()!!}
		</div>
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
					<th class="text-center">
						{!! $category->linkSort('ID', 'id') !!}
					</th>
					<th>Thumbnail</th>
					<th>
						{!! $category->linkSort('Tên danh mục', 'title') !!}
					</th>
					<th>
						{!! $category->linkSort('Ngày tạo', 'created_at') !!}
					</th>
					<th> Thao tác </th>
				</tr>
			</thead>
			<tbody category-list>
				@foreach($categories as $category_item)
				<tr class="odd gradeX">
					<td width="50" class="table-checkbox text-center">
						<div class="checker">
							<input type="checkbox" class="icheck" value="{{ $category_item->id }}">
						</div>
					</td>
					<td class="text-center">
						<strong>
							{{ $category_item->id }}
						</strong>
					</td>
					<td><img src="{{ thumbnail_url($category_item->thumbnail, ['height' => '70', 'width' => '70']) }}" /></td>
					<td>
						@can('admin.news.category.edit', $category_item)
							<a href="{{ route('admin.news.category.edit', ['id' => $category_item->id]) }}">
								<strong>
									{{ $category_item->name }}
								</strong>
							</a>
						@endcan

						@cannot('admin.news.category.edit', $category_item)
							<strong>
								{{ $category_item->name }}
							</strong>
						@endcannot
						({{ $category_item->newses()->count() }} tin tức)
					</td>
					<td>
						{{ text_time_difference($category_item->created_at) }}
					</td>
					<td category-action>
						<div class="btn-group" table-function>
							<a href="" class="btn btn-circle btn-xs grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<span class="hidden-xs">
									Chức năng
									<span class="fa fa-angle-down"> </span>
								</span>
								<span class="visible-xs">
									<span class="fa fa-cog"> </span>
								</span>
                            </a>
                            <ul class="dropdown-menu pull-right">
                            	@can('admin.news.category.edit', $category_item)
                                	<li><a href="{{ route('admin.news.category.edit', ['id' => $category_item->id]) }}"><i class="fa fa-pencil"></i> Sửa</a></li>
                                @endcan
                                @can('admin.news.category.destroy', $category_item)
                            		<li><a data-function="destroy" data-method="delete" href="{{ route('admin.news.category.destroy', ['id' => $category_item->id]) }}"><i class="fa fa-times"></i> Xóa</a></li>
                            	@endcan
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