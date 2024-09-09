@extends('admin.layouts.master')

@section('styles')
  <style>
    .card-title { font-weight: bold;}
    .card-header { border: none;}
  </style>
@endsection


@section('content')


<div class="row">

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header pb-0">
        <h2 class="card-title">آپلود فایل اکسل</h2>
        <div class="card-options">
          <a 
            href="{{ asset('assets/excel/test.xlsx') }}"
            class="btn btn-outline-info btn-sm" 
            download>
            نمونه فایل  
            <i class="fa fa-download"></i>
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <form action="{{ route('upload-file') }}" class="col-12" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="excel_file">
                    <label class="custom-file-label">انتخاب فایل اکسل</label>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <button class="btn btn-primary btn-block">آپلود</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">جستجوی پیشرفته</h2>
      </div>
      <div class="card-body">
        <div class="row">
          <form action="{{ route('file-data', $file) }}" class="col-12" method="GET">
            @csrf
            <div class="row">
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control" placeholder="شماره موبایل">
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <input type="text" name="tracking_code" value="{{ request('tracking_code') }}" class="form-control" placeholder="کد رهگیری">
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                    <select name="is_send" id="IsSendSelection" class="form-control">
                        <option value="">انتخاب</option>
                        <option value="all">همه</option>
                        <option value="1">ارسال شده</option>
                        <option value="0">ارسال نشده</option>
                    </select>
                </div>
              </div>
              <div class="col-12 col-lg-8">
                <div class="form-group">
                  <button class="btn btn-primary btn-block">جستجو <i class="fa fa-search mr-1"></i></button>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <a href="{{ route('file-data', $file) }}" class="btn btn-danger btn-block">حذف فیلترها <i class="fa fa-close mr-1"></i></a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <p class="card-title">لیست تمامی داده های فایل {{ $file->name }} ({{ $customers->total() }})</p>
				<a href="{{ route('dashboard') }}" class="btn btn-sm btn-warning">بازگشت</a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-striped text-nowrap text-center">
                <thead>
									<tr>
										<th class="fs-16 font-weight-bold">ردیف</th>
										<th class="fs-16">موبایل</th>
										<th class="fs-16">کد رهگیری</th>
										<th class="fs-16">وضعیت ارسال</th>
										<th class="fs-16">عملیات</th>
									</tr>
                </thead>
                <tbody>
                  @forelse ($customers as $customer)
                  <tr>  
                    <td class="font-weight-bold">{{ $loop->iteration }}</td>  
                    <td>{{ $customer->mobile }}</td>  
                    <td>{{ $customer->tracking_code }}</td>  
                    <td>  
											@if ($customer->is_send)
												<span class="badge badge-success-light">ارسال شده</span>
											@else
												<span class="badge badge-danger-light">ارسال نشده</span>
											@endif
                    </td>  
                    <td>  
                      <form 
                        id="SendCustomerSmsForm-{{ $customer->id }}" 
                        action="{{ route('send-customer-sms') }}" 
                        method="POST" 
                        class="d-none">
                        @csrf
                        <input type="hidden" name="customer_id" value="${customer.id}}">
                      </form>
                      <button 
												@class(['btn', 'btn-sm', 'btn-icon', 'btn-success' => !$customer->is_send, 'btn-dark' => $customer->is_send])
                        onclick="$('#SendCustomerSmsForm-{{ $customer->id }}').submit();" 
                        @disabled($customer->is_send)> 
                        <i class="fa fa-send"></i>
                      </button>  
                    </td>  
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5">
                      <span class="text-danger font-weight-bold fs-16">هیچ داده ای یافت نشد !</span>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
							{{ $customers->onEachSide(0)->links("vendor.pagination.bootstrap-4") }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>  
  $('#IsSendSelection').select2({placeholder: 'انتخاب وضعیت ارسال'});
</script>
@endsection
