<!DOCTYPE html>
<html lang="en">
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>Hyperpay</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

		<!--end::Web font -->

        <!--begin::Global Theme Styles --> 
		<link href="{{ url('/') }}/front-teachmearabic/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="../../../{{ url('/') }}/front-teachmearabic/assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="{{ url('/') }}/front-teachmearabic/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="../../../{{ url('/') }}/front-teachmearabic/assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
        <link href="{{ url('/') }}/front-teachmearabic/assets/main/Sign.CSS" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles -->
		<link rel="shortcut icon" href="{{ url('/') }}/front-teachmearabic/assets/main/img/logo.png" />
	</head>

	<!-- end::Head -->


	<!-- begin::Body -->
	<body style=" background: #f1f1f1;height: 100%;
    margin: 0px;
    padding: 0px;
    font-size: 13px;
    font-weight: 300;
    font-family: Poppins;
    -webkit-font-smoothing: antialiased;">
@php
$order=$data['order'];
$user=$data['user'];
$json=$data['json'];
$subscribe=$data['subscribe'];
@endphp
        <!-- begin:: Page -->
        <div style="max-width: 1140px;width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
         <div>
            <div  style="margin-left: 0 !important;margin-right: 0 !important; display: flex;flex-wrap: wrap;">
                <div  style="-webkit-box-flex: 0;flex: 0 0 100%;max-width: 100%;    position: relative;width: 100%;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                    <div style="box-shadow: 0px 1px 15px 1px rgba(69,65,78,0.08);background-color: #fff;margin-bottom: 2.2rem;">
                        <div style="padding: 0;color: #575962;">
                            <div>
                                <div>
                                    <div style="background-image: url({{ url('/') }}/front-teachmearabic/assets/app/media/img//logos/bg-6.jpg);">
                                        <div style="width: 70%;margin: 0 auto;padding: 0;">
                                            <div style="padding-top: 7rem;display: table;width: 100%;padding-bottom: 3rem !important;">
                                                <a href="#" style="outline: none !important;vertical-align: top;display: table-cell;text-decoration: none;">
                                                    <h1 style="color: #3f4047;font-weight: 600;font-size: 2.7rem;margin-bottom: .5rem;font-family: inherit;line-height: 1.2;margin-top: 0;">INVOICE</h1>
                                                </a>
                                                <a href="#" style="outline: none !important;display: table-cell;text-decoration: none;text-align: right;">
                                                    <img src="{{ url('/') }}/front-teachmearabic/assets/main/img/logo.png" width="150"  style="max-width: 100%;height: auto;vertical-align: middle;border-style: none;">
                                                </a>
                                            </div>
								
                                            <div  style=" justify-content: space-between;border-top: 1px solid #ebedf2;width: 100%;
                                            padding: 6rem 0 3rem 0;table-layout: fixed;display: flex;">
                                                <div style="display: table-cell;">
                                                    <span style="display: block;font-weight: 600;padding-bottom: 0.5rem;">INVOICE NO.</span>
                                                    <span  style="display: block;">{{$order->id}}</span>
                                                </div>
                                                <div style="display: table-cell;">
                                                    <span style="display: block;font-weight: 600;padding-bottom: 0.5rem;">INVOICE TO.</span>
                                                    <span style="display: block;">{{$user->full_name}}</span>
                                                </div>
                                                <div style="display: table-cell;">
                                                    <span  style="display: block;font-weight: 600;padding-bottom: 0.5rem;">DATE</span>
                                                    <span style="display: block;">{{$order->purchased_on}}</span>
                                                </div>
                                                

                                            </div>
                                            
                                            <div  style=" justify-content: space-between;border-top: 1px solid #ebedf2;width: 100%;
                                            padding: 2rem 0 1rem 0;table-layout: fixed;display: flex;">
                                                <div style="display: table-cell;">
                                                    <span style="display: block;font-weight: 600;padding-bottom: 0.5rem;">PaymentBrand.</span>
                                                    <span  style="display: block;">Paypal</span>
                                                </div>
                                                
                                                <div style="display: table-cell;">
                                                    <span style="display: block;font-weight: 600;padding-bottom: 0.5rem;">First 4 Chars.</span>
                                                    <span style="display: block;">{{substr($json->purchase_units[0]->payee->email_address, 0, 4)}}</span>
                                                </div>
                                                
                                                <div style="display: table-cell;">
                                                    <span  style="display: block;font-weight: 600;padding-bottom: 0.5rem;">STATUS</span>
                                                    <span style="display: block;">{{$json->status}}</span>
                                                </div>
                                               

                                            </div>
                                        
                                        </div>
                                    </div>
                                    <div style="width: 70%;margin: 0 auto;padding: 2rem 0 0 0;">
                                        <div style="display: block;width: 100%;overflow-x: auto;">
                                            <table  style="border-collapse: collapse;width: 100%;margin-bottom: 1rem;background-color: rgba(0,0,0,0);">
                                                <thead>
                                                    <tr>
                                                        <th style="padding: 1rem 0 0.5rem 0;border-top: none;color: #898b96;vertical-align: bottom;border-bottom: 2px solid #f4f5f8;font-weight: 500;text-align: left;">PACKAGE</th>
                                                        <th  style="padding: 1rem 0 0.5rem 0;border-top: none;color: #898b96;text-align: center;vertical-align: bottom;border-bottom: 2px solid #f4f5f8;font-weight: 500;">BEGINNING OF THE PERIOD</th>
                                                        <th  style="padding: 1rem 0 0.5rem 0;border-top: none;color: #898b96;text-align: center;vertical-align: bottom;border-bottom: 2px solid #f4f5f8;font-weight: 500;">END OF THE PERIOD</th>
                                                        <th style="padding: 1rem 0 0.5rem 0;border-top: none;color: #898b96;vertical-align: bottom;border-bottom: 2px solid #f4f5f8;font-weight: 500;">AMOUNT</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    switch($subscribe->package_id){
                                                        case '1': $package_name='First Package';break;
                                                        case '2': $package_name='Second Package';break;
                                                        case '3': $package_name='Third Package';break;
                                                        case '4': $package_name='Forth Package';break;
                                                        default: $package_name='First Package';break;
                                                    }
                                                @endphp
                                                <tbody>
                                                    <tr>
                                                        <td style="padding-top: 0.8rem;color: #6f727d;padding: 1rem 0 1rem 0;vertical-align: middle;border-top: none;font-weight: 600;">{{$package_name}}</td>
                                                        <td  style="padding-top: 0.8rem;color: #6f727d;padding: 1rem 0 1rem 0;vertical-align: middle;border-top: none;font-weight: 600;text-align: center;">{{$subscribe->start_date}}</td>
                                                        <td style="padding-top: 0.8rem;color: #6f727d;padding: 1rem 0 1rem 0;vertical-align: middle;border-top: none;font-weight: 600;text-align: center;">{{$subscribe->end_date}}</td>
                                                        <td style="padding-top: 0.8rem;color: #f4516c !important;padding: 1rem 0 1rem 0;vertical-align: middle;border-top: none;font-weight: 600;text-align: center;">{{$subscribe->package_price}}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div  style="margin-top: 4rem;padding: 4rem 0 4rem 0;background-color: #f7f8fa;">
                                          <div style="display: flex; margin-right: 143px;">
                                        <div  style="width: 70%; margin: 0 auto;padding: 0;display: block;overflow-x: auto;">
                                                 <b style="font-weight: bold; margin-left: 112px;">Signature</b>
                                                
                                        </div>
                                        
                                             <div class="m-login__options">
                                                 <div id="home_div" style="display: inline">
								<a href="https://teachmearabic.org/#home" class="btn btn-primary m-btn m-btn--pill  m-btn  m-btn m-btn--icon">
									<span>
                	                <i class="fas fa-home"></i>									
                	                <span>Home</span>
									</span>
								</a>
								</div>
							 <div id="print_div" style="display: inline">
								<a href="#" class="btn btn-danger m-btn m-btn--pill  m-btn  m-btn m-btn--icon pl-4 pr-4"   onclick="printRequests()">
							
									<span>
                                <i class="fas fa-print"></i>
                                <span>Print</span>
            									</span>
								</a>
								</div>
							</div>
							</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>           
        </div>


		<!-- end:: Page -->

		<!--begin::Global Theme Bundle -->
		
		

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts -->
	

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>


<script>
    
    
         function printRequests() {
             
                document.getElementById("print_div").style.display = 'none';
                document.getElementById("home_div").style.display = 'none';
           
                   window.print();
      

          
        }
</script>