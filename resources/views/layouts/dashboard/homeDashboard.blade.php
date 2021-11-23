@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block bg-light sidebar">
        <div class="position-sticky pt-5">
            <a class="nav-link active" aria-current="page" href="{{route('home')}}">
              <h4><i class="fa fa-tachometer-alt"></i>&nbsp;لوحة التحكم</h4>
            </a>         
          <br> 
          <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" id ="flush-buttonOne" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  <i class="fa fa-university"></i> &nbsp;المسارات
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href={{ route('paths.allwithTrashed') }}>
                      <span data-feather="home"></span>
                      <i class="fa fa-table"></i>&nbsp;عرض المسارات
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('path.create') }}">
                      <span data-feather="home"></span>
                      <i class="fa fa-plus"></i>&nbsp; إضافة مسار
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('paths.openedPaths') }}">
                      <span data-feather="home"></span>
                      <i class="fa fa-tasks"></i>&nbsp; متابعة المسارات المفتوحة
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed" id ="flush-buttonTwo" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  <i class="fa fa-certificate"></i>&nbsp; الامتحانات
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('showExams') }}">
                      <span data-feather="home"></span>
                      <i class="fa fa-table"></i>&nbsp;عرض الامتحانات
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fa fa-exchange-alt"></i>&nbsp;تعديل موعد امتحان
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fa fa-tasks"></i>&nbsp;عرض أسئلة مرحلة محددة
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed" id ="flush-buttonThree"type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                  <i class="fa fa-star"></i>&nbsp; الأحداث
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" onclick="test123()" href="#">
                      <span data-feather="home"></span>
                      <i class="fa fa-table"></i>&nbsp;عرض الأحداث
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fa fa-plus"></i>&nbsp;إضافة حدث
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fas fa-trash-alt"></i>&nbsp; حذف حدث
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFour">
                <button class="accordion-button collapsed" id ="flush-buttonFour"type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                  <i class="fas fa-envelope"></i>&nbsp; الرسائل
                </button>
              </h2>
              <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fa fa-envelope-open-text"></i>&nbsp;عرض الرسائل
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fa fa-paper-plane"></i>&nbsp;إرسال رسالة
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      <i class="fas fa-trash-alt"></i>&nbsp;حذف رسالة
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFive">
                <button class="accordion-button collapsed" id ="flush-buttonFive"type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                  <i class="fa fa-chart-bar"></i>&nbsp;الاحصائيات
                </button>
              </h2>
              {{-- <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                       عرض الرسائل
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                     إرسال رسالة
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      حذف رسالة
                    </a>
                  </li>
                </ul>
              </div> --}}
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingSix">
                <button class="accordion-button collapsed" id ="flush-buttonSix" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                  <i class="fa fa-bookmark"></i>&nbsp; التقارير
                </button>
              </h2>
              {{-- <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                       عرض الرسائل
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                     إرسال رسالة
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                      <span data-feather="home"></span>
                      حذف رسالة
                    </a>
                  </li>
                </ul>
              </div> --}}
            </div>
          </div>            
            {{-- <li class="nav-item">
              <a class="nav-link" href="#">
                <span class="fas fa-edit"></span>
                الطلبات
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="shopping-cart"></span>
                المنتجات
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="users"></span>
                الزبائن
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="bar-chart-2"></span>
                التقارير
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="layers"></span>
                التكاملات
              </a>
            </li>
          </ul> --}}
  
          {{-- <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>التقارير المحفوظة</span>
            <a class="link-secondary" href="#" aria-label="إضافة تقرير جديد">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                الشهر الحالي
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                الربع الأخير
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                التفاعل الإجتماعي
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="file-text"></span>
                مبيعات نهاية العام
              </a>
            </li>
          </ul>
        </div> --}}
      </nav> 
      <main class="col-md-8 ms-sm-auto col-lg-9 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
          <h1>لوحة التحكم</h1>     
        </div> 
        @yield('dashboard-content')  
      </main>
    </div>
  </div>
@stop