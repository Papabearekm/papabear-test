<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">{{$app_name}}</a>
    </div>
    <p style="font-size:1.1em">Dear Partner,</p>
    <p>A customer {{ $orderType == "Product" ? 'purchased a product' : 'booked a Service'  }} from you. Below is your {{ $orderType == "Product" ? 'product order' : 'appointment' }} details:</p>

    <p><strong>Date:</strong> {{$date}}</p>
    <p><strong>{{ $orderType == "Product" ? 'Order ID' : 'Appointment ID' }}:</strong> {{$order_id}}</p>
    @if($messageType == "Customer")
    <p><strong>Business Name:</strong> {{$business_name}}</p>
    @else
    <p><strong>Customer Name:</strong> {{$business_name}}</p>
    @endif
    <p><strong>Status:</strong> {{$status}}</p>

    <p style="font-size:0.9em;">Regards,<br /> {{$app_name}} </p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p> {{$app_name}} </p>
    </div>
  </div>
</div>
