<!DOCTYPE html>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }

    </style>

</head>

<body>


     @php

         // echo app()->getlocale();
            //  app()->setLocale("ar");
     @endphp



    <!-- container -->
    <div class="container">


        <div class="page-header">
            <h1>index </h1>
            <br>




            {{   'welcome' .', ' . auth('userauth')->user()->name }}

            <p>

                {{ session()->get('Message') }}

            </p>

        </div>

        <a href="create.php">+ task</a> || <a href="{{ url('/Logout') }}">LogOut</a>
        <br>





        <table class='table table-hover table-responsive table-bordered'>
            <!-- creating our table heading -->
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>title</th>
                <th>content</th>
                <th>start date</th>
                <th>end date</th>
                <th>image</th>
                <th>created by</th>
                <th>action </th>

            </tr>


            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->title }}</td>
                    <td>{{ $value->content }}</td>
                    <td>{{ date('d-m-Y',$value->start_date) }}</td>
                    <td>{{ date('d-m-Y',$value->end_date) }}</td>


                    @php

                        $image = empty($value->image) ? '03.jpg' : $value->image;

                    @endphp


                    <td> <img src=" {{ url('/taskImages/' . $image) }}" alt="" width="70px" height="70px"> </td>

                    <td>{{$value->userName}}</td>



                    <td>


                        <a href='' data-toggle="modal" data-target="#modal_single_del{{$value->id}}" class='btn btn-danger m-r-1em'>delete </a>


                        <a href="{{ url('/task/'.$value->id.'/edit') }}" class='btn btn-primary m-r-1em'>edit</a>
                    </td>
                </tr>




                <div class="modal" id="modal_single_del{{$value->id}}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">delete confirmation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                           </button>
                            </div>

                            <div class="modal-body">
                              Remove     {{$value->title}}  !!!!
                            </div>
                            <div class="modal-footer">
                                <form action="{{url('task/'.$value->id)}}"  method="post"   >

                                    @method('delete')
                                    @csrf

                                    <div class="not-empty-record">
                                        <button type="submit" class="btn btn-primary">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>






            @endforeach
            <!-- end table -->
        </table>

    </div>
    <!-- end .container -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- confirm delete record will be here -->

</body>

</html>
