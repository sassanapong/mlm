@extends('layouts.frontend.app')
@section('conten')
    <style>
        .all-sale-entry {
            position: relative;
            display: block;
            overflow: hidden;
            min-height: 96px;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(145deg, #f7f9fd, #e7ecf4);
            box-shadow: 10px 10px 22px rgba(125, 138, 158, .24), -10px -10px 22px rgba(255, 255, 255, .95);
            color: #13213a;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .all-sale-entry:hover {
            color: #13213a;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 14px 14px 28px rgba(125, 138, 158, .26), -12px -12px 26px rgba(255, 255, 255, 1);
        }

        .all-sale-entry.is-disabled {
            background: linear-gradient(145deg, #f8fafc, #edf1f7);
        }

        .all-sale-entry.is-disabled .all-sale-entry__gift {
            color: #ef4444;
        }

        .all-sale-entry.is-disabled .all-sale-entry__cta {
            color: #991b1b;
        }

        .all-sale-entry::before {
            content: "";
            position: absolute;
            inset: 10px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .42);
            box-shadow: inset 5px 5px 10px rgba(148, 163, 184, .18), inset -5px -5px 12px rgba(255, 255, 255, .9);
        }

        .all-sale-entry__inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
            height: 100%;
            min-height: 96px;
            padding: 16px 18px;
        }

        .all-sale-entry__gift {
            display: inline-flex;
            width: 62px;
            height: 62px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(145deg, #ffffff, #dfe7f2);
            box-shadow: 7px 7px 16px rgba(116, 128, 148, .24), -7px -7px 16px rgba(255, 255, 255, .96);
            color: #1787ff;
            flex: 0 0 62px;
            font-size: 34px;
        }

        .all-sale-entry__copy {
            min-width: 0;
            flex: 1;
        }

        .all-sale-entry__copy h5 {
            margin: 0;
            color: #111827;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.35;
        }

        .all-sale-entry__copy p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.45;
        }

        .all-sale-entry__cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 132px;
            border-radius: 999px;
            background: linear-gradient(145deg, #ffffff, #e4eaf3);
            box-shadow: 6px 6px 14px rgba(116, 128, 148, .22), -6px -6px 14px rgba(255, 255, 255, .94);
            color: #0f3f88;
            font-size: 13px;
            font-weight: 800;
            padding: 11px 13px;
            white-space: nowrap;
        }

        .all-sale-entry__cta small {
            display: block;
            color: #64748b;
            font-size: 10px;
            font-weight: 600;
            line-height: 1.1;
        }

        @media (max-width: 575.98px) {
            .all-sale-entry {
                min-height: 118px;
            }

            .all-sale-entry__inner {
                align-items: flex-start;
                min-height: 118px;
                gap: 12px;
                padding: 14px;
            }

            .all-sale-entry__gift {
                width: 48px;
                height: 48px;
                flex-basis: 48px;
                font-size: 26px;
            }

            .all-sale-entry__copy h5 {
                font-size: 14px;
            }

            .all-sale-entry__cta {
                position: absolute;
                right: 14px;
                bottom: 14px;
                min-width: 118px;
                padding: 9px 11px;
                font-size: 12px;
            }
        }
    </style>
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-box borderR10 mb-2 mb-lg-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 col-xxl-3 text-center">
                                    <div class="ratio ratio-1x1">
                                        <div class="rounded-circle">
                                            @if(Auth::guard('c_user')->user()->profile_img)

                                            <img src="{{asset('profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" class="mw-100"
                                                alt="" />
                                            @else
                                            <img src="{{ asset('frontend/images/man.png') }}" class="mw-100"
                                            alt="" />
                                            @endif


                                        </div>

                                    </div>
                                    <a href="{{route('editprofileimg')}}" type="button" class="btn btn-outline-primary btn-sm mt-2 rounded-pill" > แก้ไขรูปโปรไฟล์ </a>
                                </div>
                                <div class="col-8 col-xxl-9">
                                    <div class="row">
                                        <div class="col-6">
                                            @php
                                                if (empty(Auth::guard('c_user')->user()->expire_date) || strtotime(Auth::guard('c_user')->user()->expire_date) < strtotime(date('Ymd'))) {
                                                    if (empty(Auth::guard('c_user')->user()->expire_date)) {
                                                        $date_mt_active = 'Not Active';
                                                    } else {
                                                        //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                                                        $date_mt_active = 'Not Active';
                                                    }
                                                    $status = 'danger';
                                                } else {
                                                    $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                                                    $status = 'success';
                                                }
                                            @endphp

                                            
                                            @php
                                           
                                            if (empty(Auth::guard('c_user')->user()->expire_date_bonus_balance) || strtotime(Auth::guard('c_user')->user()->expire_date_bonus_balance) < strtotime(date('Ymd'))) {
                                            
                                                if (empty(Auth::guard('c_user')->user()->expire_date_bonus_balance)) {
                                                    $date_mt_active_bonus_balance = 'Not Active Balance Bonus';
                                                } else {
                                                 
                                                    $date_mt_active_bonus_balance = 'Not Active Balance Bonus';
                                                }
                                                $status_bonus_balance = 'danger';
                                            } else {
                                                   
                                                $date_mt_active_bonus_balance = 'Active Balance Bonus ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date_bonus_balance));
                                                $status_bonus_balance = 'success';
                                            }
                                        @endphp

                                      

                                            @php
                                            if (empty(Auth::guard('c_user')->user()->expire_date_bonus) || strtotime(Auth::guard('c_user')->user()->expire_date_bonus) < strtotime(date('Ymd'))) {
                                                if (empty(Auth::guard('c_user')->user()->expire_date_bonus)) {
                                                    $date_mt_active_bonus = 'Not Active SuperBonus';
                                                } else {
                                                    //$date_mt_active_bonus= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                                                    $date_mt_active_bonus = 'Not Active SuperBonus';
                                                }
                                                $status_bonus = 'danger';
                                            } else {
                                                $date_mt_active_bonus = 'Active SuperBonus ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date_bonus));
                                                $status_bonus = 'success';
                                            }
                                        @endphp
                                      
                                        <span
                                        class="badge rounded-pill bg-{{ $status }} bg-opacity-20 text-{{ $status }} fw-light ps-1">
                                        <i class="fas fa-circle text-{{ $status }}"></i> {{ $date_mt_active }}
                                        </span>

                                           <span
                                            class="badge rounded-pill mt-2 bg-{{ $status_bonus_balance }} bg-opacity-20 text-{{ $status_bonus_balance }} fw-light ps-1">
                                            <i class="fas fa-circle text-{{ $status_bonus_balance }}"></i> {{ $date_mt_active_bonus_balance }}
                                        </span>

                                       
                                        <span
                                            class="badge rounded-pill mt-2 bg-{{ $status_bonus }} bg-opacity-20 text-{{ $status_bonus }} fw-light ps-1">
                                            <i class="fas fa-circle text-{{ $status_bonus }}"></i> {{ $date_mt_active_bonus }}
                                        </span>

                                        <hr>

                                        </div>
                                        
                                        <div class="col-6 text-end">
                                            <a type="button" class="btn btn-warning px-2"
                                                href="{{ route('editprofile') }}"><i class="bx bxs-edit"></i></a>
                                        </div>
                                    </div>


                                    <h5>{{ __('text.MemberID') }} :
                                        {{ Auth::guard('c_user')->user()->user_name }}
                                        ({{ Auth::guard('c_user')->user()->qualification->business_qualifications }})</h5>
                                    <h5> {{ Auth::guard('c_user')->user()->name }}
                                        {{ Auth::guard('c_user')->user()->last_name }}</h5>
                                    {{-- <p class="fs-12">
                                        รักษาสภาพสมาชิกมาแล้ว
                                        <span class="badge rounded-pill bg-light text-dark fw-light">
                                            56 วัน
                                        </span>
                                    </p> --}}

                                    @if (Auth::guard('c_user')->user()->regis_doc1_status == 3)
                                        <p class="text-warning">
                                            - รอตรวจสอบเอกสาร บัตรประชาชน
                                        </p>
                                    @endif
                                    @if (Auth::guard('c_user')->user()->regis_doc1_status == 4)
                                        <p class="text-danger">
                                            - บัตรประชาชนไม่ถูกต้อง กรุณาส่งเอกสารใหม่อีกครั้ง
                                        </p>
                                    @endif
                                    @if (Auth::guard('c_user')->user()->regis_doc4_status == 3)
                                        <p class="text-warning">
                                            - รอตรวจสอบเอกสาร บัญชีธนาคาร
                                        </p>
                                    @endif
                                    @if (Auth::guard('c_user')->user()->regis_doc4_status == 4)
                                        <p class="text-danger">
                                            - บัญชีธนาคารไม่ถูกต้อง กรุณาส่งเอกสารใหม่อีกครั้ง
                                        </p>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <span class="label-xs">{{ __('text.Business Opportunnity') }}</span>
                            <?php

                            $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline(Auth::guard('c_user')->user()->introduce_id);

                            ?>
                            <span class="badge bg-light text-dark fw-light">รหัส {{ @$upline->user_name }} |
                                {{ @$upline->name }} {{ @$upline->last_name }}</span>



                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row gx-2 gx-md-3">
                        <div class="col-4 col-lg-6">
                            {{-- <a href="#!"> --}}
                            <a href="{{ route('tree') }}">
                                <div class="card cardL card-body borderR10 bg-pink bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-pink bg-opacity-100 borderR8 iconFlex">
                                                <i class='fa fa-sitemap'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('ผังไบนารี') }}</h5>
                                            <p class="fs-12 text-pink">{{ __('ผังไบนารี') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- <div class="col-4 col-lg-6">
                           
                            <a href="{{ route('tree_uni') }}">
                                <div class="card cardL card-body borderR10 bg-pink bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-pink bg-opacity-100 borderR8 iconFlex">
                                                <i class='fa fa-sitemap'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('ผัง Unilevel') }}</h5>
                                            <p class="fs-12 text-pink">{{ __('ผัง Unilevel ') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> --}}


                        <div class="col-4 col-lg-6">
                            <a href="{{ route('Workline') }}">


                                <div class="card cardL card-body borderR10 bg-primary bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary borderR8 iconFlex">
                                                <i class='bx bx-group'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('ผังแนะนํา (สายเลือด)') }}</h5>
                                            <p class="fs-12 text-primary">{{ __('ผังแนะนํา (สายเลือด)') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6">
                          
                              <a href="{{ route('upgradePosition') }}">  
                                <div class="card cardL card-body borderR10 bg-warning bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-warning borderR8 iconFlex">
                                                <i class='bx bx-slider-alt'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('เงื่อนไขการขึ้นตำแหน่ง') }}</h5>
                                            <p class="fs-12 text-warning">{{ __('เงื่อนไขการขึ้นตำแหน่ง') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6">
                            <a href="{{ route('register') }}">
                                {{-- <a href="#!"> --}}

                                <div class="card cardL card-body borderR10 bg-success bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-success borderR8 iconFlex">
                                                <i class='bx bx-plus'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('text.Register') }}</h5>
                                            <p class="fs-12 text-success">{{ __('text.Managing Add Members') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="{{ route('Order') }}">
                                <div class="card cardL card-body borderR10 bg-info bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-info borderR8 iconFlex">
                                                <i class='bx bx-cart'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('text.BuyProduct') }}</h5>
                                            <p class="fs-12 text-info">{{ __('text.Online ordering') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="{{ route('order_history') }}">
                                <div class="card cardL card-body borderR10 bg-info bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-info borderR8 iconFlex">
                                                <i class='fa fa-history'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('text.OrderHistory') }}</h5>
                                            <p class="fs-12 text-info">{{ __('text.OrderHistory') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @php
                            $allSaleBonusQualifications = ['VVIP', 'STAR', 'MDK_STAR', 'XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'];
                            $homeUser = Auth::guard('c_user')->user();
                            $homeQualificationName = optional($homeUser->qualification)->business_qualifications ?? $homeUser->qualification_id;
                            $homeHasAllSalePosition = in_array($homeUser->qualification_id, $allSaleBonusQualifications);
                            $homeHasActiveSuperBonus = !empty($homeUser->expire_date_bonus) && strtotime($homeUser->expire_date_bonus) >= strtotime(date('Y-m-d'));
                            $homeCanReceiveAllSale = $homeHasAllSalePosition && $homeHasActiveSuperBonus;
                        @endphp
                        <div class="col-12">
                            <a href="{{ route('bonus_all_sale') }}" class="all-sale-entry {{ $homeCanReceiveAllSale ? '' : 'is-disabled' }} mb-2 mb-md-3">
                                <div class="all-sale-entry__inner">
                                    <div class="all-sale-entry__gift">
                                        <i class='bx {{ $homeCanReceiveAllSale ? 'bxs-gift' : 'bxs-lock-alt' }}'></i>
                                    </div>
                                    <div class="all-sale-entry__copy">
                                        @if ($homeCanReceiveAllSale)
                                            <h5>ขอแสดงความยินดี คุณได้รับโบนัสออลเซลล์</h5>
                                            <p>ตำแหน่ง {{ $homeQualificationName }} พร้อมสถานะ SuperBonus Active สำหรับรอบคาดการณ์นี้</p>
                                        @else
                                            <h5>คุณยังไม่ได้รับสิทธิ์โบนัสออลเซลล์</h5>
                                            <p>ตำแหน่งปัจจุบัน {{ $homeQualificationName }} หรือสถานะ SuperBonus ยังไม่ผ่านเงื่อนไข กดเพื่อดูเหตุผล</p>
                                        @endif
                                    </div>
                                    <div class="all-sale-entry__cta">
                                        <span>
                                            {{ $homeCanReceiveAllSale ? 'รับโบนัสของคุณ' : 'ดูเหตุผล' }}
                                            <small>{{ $homeCanReceiveAllSale ? 'คลิกเพื่อรับสิทธิ์' : 'ตรวจเงื่อนไข' }}</small>
                                        </span>
                                        <i class='bx bx-chevron-right'></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {{-- <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="{{ route('Learning') }}">
                                <div class="card cardL card-body borderR10 bg-pink bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-pink bg-opacity-100 borderR8 iconFlex">
                                                <i class='bx bx-book-open'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('text.MdkLerning') }}</h5>
                                            <p class="fs-12 text-pink">{{ __('text.Learning/CT') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> --}}
                        {{-- <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="{{ route('Contact') }}">
                                <div class="card cardL card-body borderR10 bg-danger bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-danger borderR8 iconFlex">
                                                <i class='bx bx-buildings'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>{{ __('text.Contact') }}</h5>
                                            <p class="fs-12 text-danger">{{ __('text.Report') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                        id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple1 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bx-box text-purple1 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-end ">
                                    <h4 class="mb-0 text-purple1 bg-opacity-100 fw-bold" style="padding-right: 20px;">
                                        {{ number_format(Auth::guard('c_user')->user()->pv_upgrad) }}</h4>
                                    <p class="fs-12 text-secondary mb-0" style="padding-right: 20px;">{{ __('text.Pv. Accumulated Position') }}</p>
                                </div>

                                
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                            <li><a class="dropdown-item"
                                    href="{{ route('reportsws') }}">{{ __('คะแนนเคลื่อนไหวรายวัน') }}</a></li>
                            {{-- <li><a class="dropdown-item" href="{{ route('jp_transfer') }}">รับ-โอน PV.</a></li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100 pe-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple2 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bx-plus text-purple2 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-start">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">{{ __('text.Pv. Use') }}</h5>
                                        <h5 class="text-p1 text-end mb-0 fw-bold">
                                            {{ number_format(Auth::guard('c_user')->user()->pv) }}</h5>
                                    </div>
                                    <p class="fs-12 text-secondary mb-0">{{ __('text.Pv. Use') }}</p>
                                </div>
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item"
                                    href="{{ route('jp_clarify') }}">{{ __('text.Clarify PV.') }}</a></li>
                            {{-- <li><a class="dropdown-item" href="{{ route('jp_transfer') }}">รับ-โอน PV.</a></li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100 pe-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple2 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bxs-wallet text-purple2 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-start">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">eWallet</h5>
                                        <h5 class="text-p1 text-end mb-0 fw-bold">
                                            {{ number_format(Auth::guard('c_user')->user()->ewallet, 2) }}</h5>
                                    </div>
                                    <p class="fs-12 text-secondary mb-0">{{ __('text.Ewallet Mangament') }}</p>
                                </div>
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                            @if(Auth::guard('c_user')->user()->qualification_id != 'MC')
                       
                            
                            <li><a class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#transferModal">{{ __('text.Transferewallet') }}</a></li>

                                    
                            <li><a class="dropdown-item" type="button"
                                    id="withdraw">{{ __('text.Withdrawewallet') }}</a></li>
                           
                            @endif

                                 <li><a onclick="resetForm()" class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#depositModal">{{ __('text.Depositewallet') }}</a></li>
 
                            <li><a class="dropdown-item"
                                    href="{{ route('eWallet_history') }}">{{ __('text.Historyewallet') }}</a></li>
                                    
                            <li><a class="dropdown-item"
                                href="{{ route('eWallet-TranferHistory') }}"> ประวัติการฝากเงิน eWallet </a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100 pe-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple2 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bxs-coin-stack text-purple2 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-start">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">{{ __('text.Income') }}</h5>
                                        <h5 class="text-p1 text-end mb-0 fw-bold"> - </h5>
                                    </div>
                                    <p class="fs-12 text-secondary mb-0">{{ __('text.Bonus Management') }}</p>
                                </div>
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            {{-- <li><a class="dropdown-item" href="{{ route('bonus_es') }}">โบนัสข้อ Easy Cashback </a>
                            </li>

                            <li><a class="dropdown-item" href="{{ route('bonus7') }}">  โบนัสทีม Uni 24 ชั้น</a>
                            </li> --}}

                            <li><a class="dropdown-item" href="{{ route('bonus8') }}"> โบนัสบาลานซ์ทีม W/S </a>
                            </li>

                            <li><a class="dropdown-item" href="{{ route('bonus9') }}"> โบนัส Matching </a>
                            </li>

                            <li><a class="dropdown-item" href="{{ route('bonus_all_sale') }}">โบนัส All Sale</a></li> 

                            {{-- <li><a class="dropdown-item" href="{{ route('bonus-ws') }}">  นัสบาลานซ์ W/S</a></li> --}}
                            {{-- <li><a class="dropdown-item" href="{{ route('bonus_fastStart') }}">โบนัส Fast Start</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_team') }}">โบนัสบริหารทีม</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_discount') }}">โบนัสส่วนลด</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_matching') }}">โบนัส Matching</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_history') }}">ประวัติการโอนโบนัส</a></li> --}}
                        </ul> 
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <h4 class="card-title">{{ __('text.News Announcements') }}</h4>
                            <hr>
                            <div class="row">
                                @if (isset($News))
                                    @foreach ($News as $item => $value)
                                        @php
                                            $date = new DateTime();
                                            $date->setTimezone(new DateTimeZone('Asia/Bangkok'));
                                        @endphp
                                        @if ($value->end_date_news >= $date->format('Y-m-d'))
                                            <div class="col-md-6 col-xl-4">
                                                <div class="card cardNewsH mb-3">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <div class="box-imageNews">
                                                                <img src="{{ isset($value->image_news) ? asset('upload/news/image/' . $value->image_news) : '' }}"
                                                                    class="img-fluid rounded-start" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <span
                                                                    class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                                    {{ $value->start_date_news }} to
                                                                    {{ $value->end_date_news }}
                                                                </span>
                                                                <h5 class="card-title">{{ $value->title_news }}</h5>
                                                                <p class="card-text">
                                                                    {{ isset($value->detail_news) ? $value->detail_news : '' }}
                                                                </p>
                                                                <a href="{{ url('news_detail') }}/{{ $value->id }}"
                                                                    class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                                        class='bx bxs-right-arrow-circle'></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <nav aria-label="...">
                                        <ul class="pagination justify-content-end">
                                            {{-- <li class="page-item disabled">
                                                <a class="page-link">Previous</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item" aria-current="page">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li> --}}
                                            {{ $News->links() }}
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- HTML - Modal ข้อตกลง -->
  @if(Auth::guard('c_user')->user()->terms_accepted == 'no')
     @include('frontend.modal.modal-terms')
  @endif
    
    @include('frontend.modal.modal-deposit')
    @include('frontend.modal.modal-changePassword')
    @include('frontend.modal.modal-transfer')
    @include('frontend.modal.modal-withdraw')
@endsection


@section('script')
@if(Auth::guard('c_user')->user()->terms_accepted == 'no')
            <script>
                $(document).ready(function() {
                    // แสดง SweetAlert2
                    Swal.fire({
                        title: 'แจ้งเตือน!',
                        text: 'เงื่อนไขและข้อตกลงการเป็นสมาชิกบริษัทมารวยด้วยกัน',
                        icon: 'info',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        // ถ้าผู้ใช้กดปุ่ม 'ตกลง', ให้เปิด Modal
                        if (result.isConfirmed) {
                            $('#termsModal').modal('show');
                        }
                    });
                });
            
    
        </script>
 @endif
         
 <script>
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }
 
        $('#withdraw').click(function() {
            $('#withdrawModal').modal('hide')
            id = {{ Auth::guard('c_user')->user()->id }} ;
            $.ajax({
                type: "post",
                url: '{{ route('check_customerbank') }}',
                asyns: true,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                success: function(data) {
                    if (data == "fail") {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'กรุณากรอกข้อมูล ธนาคาร!',
                        }).then((result) => {
                            location.href = '{{ route('editprofile') }}';
                        });
                    } else {
                        $('#withdrawModal').modal('show')
                    }
                }
            });
        })
 
      
        $('#amt').change(function() {
            amt = $(this).val();
            amount = {{Auth::guard('c_user')->user()->ewallet}};
           
        })
        $('#withdraw').change(function() {
            amt = $(this).val();
            amount = {{Auth::guard('c_user')->user()->ewallet}};
           
        })
 
    </script>
@endsection
