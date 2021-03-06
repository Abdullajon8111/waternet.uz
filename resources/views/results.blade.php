@extends('layouts.master')
@section('content')


    <div class="container-fluid">
       
        <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2>Xisobotlar</h2>
                </div>            
                <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                    <ul class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href=""><i class="icon-home"></i></a></li>                            
                        <li class="breadcrumb-item active">Xisobotlar</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="body taskboard">
            <div class="dd" data-plugin="nestable">
                <ol class="dd-list">                 
                        <div class="dd-handle">
                             <ul class="list-unstyled team-info">
                                <li><button class="btn btn-primary mr-3"><i class="fa fa-calendar"></i> Kunlik</button></li>
                                <li><button class="btn btn-warning mr-3"><i class="fa fa-money"></i> Tushum</button></li>
                                <li><button class="btn btn-dark mr-3"><i class="fa fa-users"></i> Xodimlar</button></li>
                                <li><button class="btn btn-danger mr-3"><i class="fa fa-info-circle"></i> Qarzdorlar</button></li>
                            </ul>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
          
        

        <div class="card" style="margin-top: 20px">
            <div class="header" style="padding-bottom: 1">
               
            </div>
            
            <div class="body">
                <div class="table-responsive">
                    <table class="table mb-0 table-bordered">
                        <thead>
                            <tr>
                                <th>FIO</th>
                                <th>Roli</th>
                                <th>Zakas oldi</th>
                                <th>Tovar oldi</th>
                                <th>Tovar sotdi</th>
                                <th>Sotilgan summasi</th>
                                <th>Idish oldi</th>
                                <th>Idish qaytardi</th>
                                <th>Naqd pul</th>
                                <th>Plastik</th>
                                <th>Pul ko'chirish</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                                <tr>
                                    <td>
                                        {{$user->name}}
                                    </td>
                                    <td>
                                        Xodim
                                    </td>
                                    <td>
                                        <a href="">{{$order[$user->id]}}</a>
                                    </td>
                                    <td>
                                        <a href="">{{$takeproduct[$user->id]}}</a>
                                    </td>
                                    <td>
                                        <a href="">{{$soldproducts[$user->id]}}</a>
                                    </td>
                                    <td>
                                        <a href="">{{$soldsumm[$user->id]}} / {{$amount[$user->id]}} / {{$dolgsumm[$user->id]}}</a>
                                    </td>
                                    <td>
                                        <a href="">{{$entrycon[$user->id]}}</a>
                                    </td>
                                    <td>
                                        0
                                    </td>
                                    <td>
                                        <a href="">{{$payment1[$user->id]}}</a>
                                    </td>
                                    <td>
                                        <a href="">{{$payment2[$user->id]}}</a>
                                    </td>
                                    <td>
                                        <a href="">{{$payment3[$user->id]}}</a>
                                    </td>
                                </tr>  
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content mt-3">             
                    <ul class="pagination mb-0">
                    </ul> 
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
       function setlocation(event){ 
      event.preventDefault(); 
      var myWindow=window.open("{{route('location')}}", 'Select Client Location', 'width=auto,height=auto')
    }

      function setlocation1(event, l1, l2){ 
            event.preventDefault(); 
            var myWindow=window.open('set_location_edit.asp?lat='+l1+'&lng='+l2+'', 'Joylashuvni tanlash', 'width=800,height=500')
        }

    function dotReplace(event){
                event.taget.value=event.target.value.replaceAll(",", ".")
            }
</script>

<script>
    // ---------horizontal-navbar-menu-----------
		var tabsNewAnim = $('#navbar-animmenu');
		var selectorNewAnim = $('#navbar-animmenu').find('li').length;
		//var selectorNewAnim = $(".tabs").find(".selector");
		var activeItemNewAnim = tabsNewAnim.find('.active');
		var activeWidthNewAnimWidth = activeItemNewAnim.innerWidth();
		var itemPosNewAnimLeft = activeItemNewAnim.position();
		$(".hori-selector").css({
			"left":itemPosNewAnimLeft.left + "px",
			"width": activeWidthNewAnimWidth + "px"
		});
		$("#navbar-animmenu").on("click","li",function(e){
			$('#navbar-animmenu ul li').removeClass("active");
			$(this).addClass('active');
			var activeWidthNewAnimWidth = $(this).innerWidth();
			var itemPosNewAnimLeft = $(this).position();
			$(".hori-selector").css({
				"left":itemPosNewAnimLeft.left + "px",
				"width": activeWidthNewAnimWidth + "px"
			});
		});
</script>
@endsection


