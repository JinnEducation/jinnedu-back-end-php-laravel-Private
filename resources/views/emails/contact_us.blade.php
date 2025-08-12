<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
    </head>
    <body style="background-color: #E5E5E5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <div style="background-color: #fff; max-width: 636px; margin: 60px auto;  border-radius: 10px; overflow: hidden;">
            <table style="width: 100%; border-spacing: 0; border: none;" border="0">
                <!-- logo  -->
                <tr>
                    <td style="text-align: center;">
                        <div class="logo" style="margin: auto; margin-top: 40px;">
                            <img     src="{{asset('logo/logo-light.png')}}" style="width: 120px;" alt="">
                        </div>
                        <h1 style="font-size: 16px; font-weight: bold; color: #383B37; margin: 0; margin-top: 20px;">
                        Contact Us
                        </h1>
                    </td>
                </tr>


                <tr>
                    <td>
                        <div style="background-color: #F5F6FA; padding: 30px;  border-radius: 10px; max-width: 516px; width: 100%; margin: auto; margin-top: 20px;">
                            <!-- meeting description -->

                            <!-- meeting info -->
                            <table style="font-size: 14px; line-height: 2;">
                                <tr>
                                    <th>Name:</th>
                                    <td style="text-align: left;">{{$contact->f_name}} {{$contact->l_name}}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td style="text-align: left;">{{$contact->email}}</td>
                                </tr>
                                <tr>
                                    <th>Mobile:</th>
                                    <td style="text-align: left;">{{$contact->mobile}}</td>
                                </tr>
                                 <tr>
                                    <th>Message:</th>
                                    <td style="text-align: left;">{{$contact->message}}</td>
                                </tr>
                                
                            </table>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td style=" background: #ddd; color: #000; font-size: 16px; text-align: center; border-radius: 0 0 10px 10px; overflow: hidden;">
                        <div style="margin-top: 20px;">
                            <img     src="{{asset('logo/logo-light.png')}}"  style="width: 120px;" alt="">
                        </div>
                        <h2 style="font-weight: bold; font-size: 20px; color: #000; margin: 10px auto;">Contact Us</h2>
                        <a href="mailto:{{$site_email}}" style="color: #000; font-size: 14px; display: block; margin: 0; margin-bottom: 20px;">Email: <strong>{{$site_email}}</strong></a>
                        <p style="color: #000; font-size: 14px; margin: 0; margin-bottom: 15px !important;">Thank You</p>
                    </td>
                </tr>
                <!-- meeting footer  -->
            <tr>
                <td style="font-size: 14px; text-align: center; padding: 1rem 0; background-color: #E5E5E5;">
                        <p style="font-size: 14px; color:#383B37;"> Copyrights {{$site_name}}  {{date('Y')}}</p>
                </td>
            </tr>
            </table>
        </div>
    </body>
</html>
