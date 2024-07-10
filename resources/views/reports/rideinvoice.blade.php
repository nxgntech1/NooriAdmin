<!doctype html>
<html lang="en">

<head>
  <title>Ride Invoice</title>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

</head>

<body>
  <table style="max-width: 640px; margin: 0 auto; width: 100%; border: 1px solid #eeeeee; padding: 15px 25px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); font-weight: 500; font-family: Open Sans;">
    <tr>
      <td>
    <table style="border-collapse: collapse; margin-bottom: 20px;" align="right">
      <tr>
        <th colspan="3" style="background: #C3E54B; padding: 2px 15px; font-size: 12px; color: #000000; font-weight: 600; border-radius: 4px;">
          {{$statut}}
        </th>
      </tr>
</table>

   
    <table style="width: 100%; margin-bottom: 20px;">
      <tr>
        <td style="width: 70px;">
          <div style="max-width: 70px;">
            <img src="{{$vehicle_imageid}}" style="width: 60px; height: 60px; border-radius: 50%;" alt="">
          </div>
        </td>
        <td>
          <h5 style="color: #666666; font-size: 16px; padding: 0; margin: 0;">{{$model}} {{$brand}}</h5>
          <p style="font-size: 12px; padding-top: 0px; color: #666666;">{{$BookigDate}} | {{$BookingTime}}</p>
        </td>
        <td  align="right">
          <h5>{!! $montant !!}</h5>
        </td>
      </tr>
    </table>
    <table style="width: 100%; margin-bottom: 20px;">
      <tr>
        <td style="width: 30px; text-align: center;"><img src="https://nadmin.nxgnapp.com/images/location_pdf.png" height="20"></td>
        <td style="padding-left: 10px; color: #666666; font-size: 12px;">{{$depart_name}}</td>
      </tr>
      <tr>
        <td style="width: 30px; text-align: center;"><img src="https://nadmin.nxgnapp.com/images/dropoff.png" height="20"></td>
        <td style="padding-left: 10px; color: #666666; font-size: 12px;">{{$destination_name}}</td>
      </tr>
    </table>
    <!-- <table style="width: 100%; margin-bottom: 0px;">
      <tr>
        <td style="width: 33.33%; text-align: center;">
          <div style=" border: 1px solid #cccccc; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: 600; margin: 3% 5%;">
            <p style="margin: 0; padding: 0;"><img src="https://nadmin.nxgnapp.com/images/location_pdf.png" height="20"></p>
            {{$BookingTime}}
          </div>
        </td>
        <td style="width: 33.33%; text-align: center;">
          <div style="border: 1px solid #cccccc; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: 600; margin:  3% 5%;">
            <p style="margin: 0; padding: 0;"><img src="https://nadmin.nxgnapp.com/images/ic_distance.png" height="20"></p>
            {{$distance}}
          </div>
        </td>
        <td style="width: 33.33%; text-align: center;">
          <div style="border: 1px solid #cccccc; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: 600; margin: 3% 5%;">
            <p style="margin: 0; padding: 0;"><img src="https://nadmin.nxgnapp.com/images/time.png" height="20"></p>
            {{$duree}}
          </div>
        </td>
      </tr>
    </table> -->
    <table style="width: 100%; margin-bottom: 20px;">
      <tr>
        <td style="width: 50%; text-align: center;">
          <div style="border: 1px solid #cccccc; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: 600; margin:  3% 5%;">
            <p style="color: #937308;margin: 0; padding: 0;">Trip Duration</p>
            {{$duree}}
          </div>
        </td>
        <td style="width: 50%; text-align: center;">
          <div style="border: 1px solid #cccccc; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: 600; margin:  3% 5%;">
            <p style="color: #937308;margin: 0; padding: 0;">Trip Distance</p>
            {{$distance}}
          </div>
        </td>
      </tr>
    </table>
    <table style="width: 100%; margin-bottom: 10px; border-bottom: 1px solid #cccccc;">
      <tr>
        <td style="color: #666666; font-size: 13px;">Total Fare</td>
        <td style="color: #666666; font-size: 15px;" align="right">{{$car_Price}}</td>
      </tr>
      <tr>
        <td style="color: #666666; font-size: 13px;">Promo code applied</td>
        <td style="color: #9aaf26;" align="right">-{{$discount}}</td>
      </tr>
    </table>
    <table style="width: 100%; margin-bottom: 0px;">
    <tr>
        <td style="color: #130e4d; font-size: 15px; font-weight: 500;">Sub Total</td>
        <td style="color: #130e4d; font-size: 15px; font-weight: 500;" align="right">{{$sub_total}}</td>
      </tr>
      {!! $ride_taxes !!}
    </table>
    <table style="width: 100%; margin-bottom: 20px;">
      <tr>
        <td style="color: #a9700d; font-weight: 500; font-size: 15px;">Total Paid</td>
        <td style="color: #a9700d; font-weight: 500; font-size: 15px;" align="right">{{$montant}}</td>
      </tr>
    </table>
    {!! $ride_addons !!}
    <table style="width: 100%; margin-bottom: 20px; border: 0;">
      <tr>
        <th colspan="2" style="padding: 10px; border: 0px solid #cccccc; text-align: left;">Driver Details</th>
      </tr>
      <tr>
        <td style="width: 70px; padding: 10px; border: 0px solid #cccccc;">
          <div style="max-width: 70px;">
            <img src="{{$driverphoto}}" alt="" style="width: 60px; height: 60px; border-radius: 50%;">
          </div>
        </td>
        <td style="padding: 10px; border: 0px solid #cccccc;">
          <h5 style="color: #666666; font-size: 16px; margin: 0;">{{$numberplate}}</h5>
          <p style="font-size: 12px; color: #666666; margin: 5px 0;">{{$drivername}}</p>
        </td>
      </tr>
    </table>


</td>
</tr>
  </table>

</body>

</html>