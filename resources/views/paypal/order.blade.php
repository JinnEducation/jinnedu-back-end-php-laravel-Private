<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>Paypal</title>
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
        <script
    src="https://www.paypal.com/sdk/js?components=buttons,hosted-fields&client-id={{$json->id}}"
    data-client-token="{{$json->id}}"
  ></script>

		<!--end::Web font -->

	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-grid--hor-tablet-and-mobile m-login m-login--6" id="m_login">
				
				<div class="m-grid__item m-grid__item--fluid  m-grid__item--order-tablet-and-mobile-1  m-login__wrapper">

					<!--begin::Head-->


					<!--end::Head-->

					<!--begin::Body-->
					<div class="m-login__body">

						<!--begin::Signin-->
						<div class="m-login__signin">
							<div class="m-login__title">
								<h3>Paypal</h3>
								<button type="button" onclick="window.location='{{'https://jinntest.jinnedu.com/checkout-response/'.$order->id.'/success'}}';">Pay Success</button>
								<button type="button" onclick="window.location='{{'https://jinntest.jinnedu.com/checkout-response/'.$order->id.'/failed'}}';">Pay Failed</button>
							</div>

						    <iframe src="https://www.sandbox.paypal.com/checkoutnow?token={{$json->id}}"height="600" width="100%"></iframe>
						    

                            <!--end::Options-->
                            
						</div>

						<!--end::Signin-->
					</div>

					<!--end::Body-->
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