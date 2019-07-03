<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workplace</title>
    <link href="https://fonts.googleapis.com/css?family=Muli:600,700,800" rel="stylesheet">
    <base href="">
</head>
<body style="width:600px !important; margin:0 auto !important; padding:0 !important; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:#ececec;">
<center>
    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important; margin:0; padding:0; width:100% !important; background-color:#ffffff;">
        <tbody>
        <tr>
            <td style="text-align: center">
                <table align='center' cellpadding="0" cellspacing="0" border="0" style="height:auto !important; margin:0 auto;text-align: center; padding:0; width:600px !important; background-color:#ffffff;">
                    <tbody>


                    <tr style="background-color: #ffffff;">
                        <td align='center'>
                            <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #ffffff;">
                                <tbody>


                                <tr style="background-color: #ffffff;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #ffffff;">
                                        <tbody>
                                        <tr>
                                            <td align='center' style="font-family: 'Muli', sans-serif;font-size:30px;font-style:normal;text-align:center;font-weight:200; margin-bottom: 0px;color:#7e7e7e;padding: 41px;padding-bottom:10px;">
                                                Workspace
                                            </td>
                                        </tr>
                                        <tr style="text-align: center;">
                                            <td style="width: 200px;height: 1px;background-color: #ff2342;display: inline-block"></td>
                                        </tr>


                                        </tbody>
                                    </table>
                                </tr>

                                <tr style="background-color: #ffffff;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #ffffff;">
                                        <tbody>
                                        <tr>
                                            <td align='center' style="font-family: 'Muli', sans-serif;font-size:33px;font-style:normal;text-align:center;font-weight:800; margin-bottom: 0px;color:#7e7e7e;padding: 20px;">
                                                Task Reminder Email
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </tr>


                                <tr style="background-color: #ffffff;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #ffffff;">
                                        <tbody>
                                        <tr>
                                            <td align='center' style="font-family: 'Muli', sans-serif;font-size:16px;font-style:normal;text-align:center;font-weight:100; margin-bottom: 0px;color:#7e7e7e;padding: 20px;">
                                               Hello {{$name}},<br>
                                            This is a reminder mail for task "{{$task}}" and the due date is : {{$dueDate}}
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </tr>


                                <tr style="background-color: #ffffff;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #ffffff;">
                                        <tbody>
                                        <tr style="text-align: center">
                                            <td align='center' style="font-family: 'Muli', sans-serif;font-size:16px;font-style:normal;text-align:center;font-weight:100;color:white;background-color: #ff2342;display: inline-block;padding: 13px 27px;margin-bottom: 20px;margin-top:10px;cursor: pointer;">
                                                <a style="color:white !important;text-decoration:none;" href="{{$taskUrl}}">Click Here</a>
                                                {{--<a style="color:white !important;text-decoration:none;" href="{{$resetLink}}{{$user->remember_token}}">Click Here</a>--}}
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </tr>


                                <tr style="background-color: #ffffff;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #ffffff;">
                                        <tbody>
                                        <tr>
                                            <td align='center' style="font-family: 'Muli', sans-serif;font-size:16px;font-style:normal;text-align:center;font-weight:100; margin-bottom: 0px;color:#7e7e7e;padding: 20px;">
                                                Or, in case that doesn't work,paste this link in your browser :<br>
                                               {{-- <span style="color: #f6687c;">{{$resetLink}}{{$user->remember_token}}</span>--}}
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </tr>



                                <tr style="background-color: #e1e1e1;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="height:auto !important;width:100% !important;margin:0 auto;background-color: #e1e1e1;">
                                        <tbody>
                                        <tr>
                                            <td align='center' style="font-family: 'Muli', sans-serif;font-size:16px;font-style:normal;text-align:center;font-weight:100; margin-bottom: 0px;color:black;padding: 20px;">
                                                <p>Powered By</p>

                                                <img style="width: 81px;display: inline-block;vertical-align: middle;" src="http://52.220.41.10/workspace-frontend/dist/assets/images/log_dark.png">
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </tr>


                                </tbody>
                            </table>

                        </td>
                    </tr>


                    </tbody>

                </table>
            </td>



        </tr>
        </tbody>
    </table>
</center>

</body>
</html>