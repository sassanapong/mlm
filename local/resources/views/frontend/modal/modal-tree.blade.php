
    <div class="modal fade" id="tree" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                   {{$data->user_name}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class='bx bxs-info-circle me-2'></i>
                        <div>
                            การถอนเงิน eWallet ขั้นต่ำ = 300 บาท
                        </div>
                    </div> --}}
                    <div class="row gx-2">
                        <div class="col-sm-6">
                            <div class="alert alert-white p-2 h-82 borderR10">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('frontend/images/man.png') }} " alt="..."
                                            width="50px">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        {{-- <p class="small mb-0">{{$data->user_name}} </p>
                                        <h6> {{ Auth::guard('c_user')->user()->name }}
                                            {{ Auth::guard('c_user')->user()->last_name }}</h6> --}}

                                            @if(@$data->business_name and $data->business_name  != '-')
                                            <h6>{{$data->user_name}}</h6>
                                            <h6>{{$data->business_name}} </h6>
                                            @else
                                            <h6>{{$data->user_name}}</h6>
                                            <h6>{{$data->name.' '.$data->last_name }} </h6>

                                            @endif


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="alert alert-purple p-2 h-82 borderR10">
                                <p class="small">คะแนนสะสม</p>
                                <p class="text-end mb-0"><span class="h5 text-purple1 bg-opacity-100">
                                    {{ number_format($data->pv_all) }} </span>PV
                                </p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                             <tbody>
                               <tr  class="table-success">
                                 <td><strong>อัพไลน์</strong></td>
                                 <?php

                                 $customer_upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($data->upline_id);
                                 ?>

                                 <td>
                                   @if($customer_upline)
                                     @if ($customer_upline->business_name and $customer_upline->business_name  != '-')
                                     {{ $customer_upline->business_name }} ({{$customer_upline->user_name}})
                                   @else
                                     {{$customer_upline->name.' '.$customer_upline->last_name }} ({{$customer_upline->user_name}})
                                   @endif

                                 @else
                                    -
                                 @endif
                               </td>
                                 <td></td>
                               </tr>
                              <tr>
                               <td><strong> ผู้แนะนำ </strong></td>
                               <?php

                                 $sponsor = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($data->introduce_id);
                                 ?>

                                 <td>
                                   @if($sponsor)
                                     @if ($sponsor->business_name and $sponsor->business_name  != '-')
                                     {{ $sponsor->business_name }} ({{$sponsor->user_name}})
                                   @else
                                     {{$sponsor->name.' '.$sponsor->last_name }} ({{$sponsor->user_name}})
                                   @endif

                                 @else
                                    -
                                 @endif
                               <td></td>
                             </tr>

                             <tr class="table-success">
                               <td>ตำแหน่ง</td>
                               <td> </td>
                               <td><b class="text-danger">{{ $data->qualification_id }}</b></td>

                             </tr>
                             <tr>

                               <td><strong> วันคงเหลือ </strong></td>
                               <td>
                                @if(empty($data->expire_date) || $data->expire_date == '0000-00-00')
                                 -
                                 @else
                                 {{ date('d/m/Y',strtotime($data->expire_date)) }}
                               @endif
                            </td>
                               <td> </td>
                             </tr>


                           </tbody>
                         </table>
                         </div>



                    </div>

                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill"
                        data-bs-dismiss="modal">Class</button>
                        <form action="{{route('tree_view')}}" method="POST">
                            <input type="hidden" name="user_name" value="{{$data->user_name}}">
                            @csrf
                            <button  type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                                class='bx bxs-check-circle me-2'></i>ดูสายงาน</button>
                          </form>

                </div>
            </div>

        </div>
    </div>




