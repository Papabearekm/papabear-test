<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">{{$app_name}}</a>
    </div>
    @if($messageType == "Customer")
    <p style="font-size:1.1em">Dear Customer,</p>
    <p>Thanks for your journey with us. This email is regarding to your previous booking with us. On completing the previous appointment, we have scheduled a follow up session on {{$follow_up_date}}</p>
    @else
    <p style="font-size:1.1em">Dear Partner,</p>
    <p>This email is regarding to your previous booking with a customer. On completing the previous appointment, you have scheduled a follow up session on {{$follow_up_date}} with {{$customer_name}}</p>
    @endif
    <p><strong>Date:</strong> {{$follow_up_date}}</p>
    @if($messageType == "Customer")
    <p><strong>Business Name:</strong> {{$business_name}}</p>
    @else
    <p><strong>Customer Name:</strong> {{$customer_name}}</p>
    @endif

    <p style="font-size:0.9em;">Regards,<br /> {{$app_name}} </p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p> {{$app_name}} </p>
    </div>
  </div>
</div>
