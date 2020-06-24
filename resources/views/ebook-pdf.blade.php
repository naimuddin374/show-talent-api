<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>E-Book</title>
</head>
<body>
    <div class="container">
        @if($data)
        @foreach($data as $c)
        <div class="chapter">
            <h3>{{$c->name}}</h3>
            <span>@php echo $c->description; @endphp</span>
        </div>
        @endforeach
        @endif
    </div>
</body>
</body>
</html>
<style>
    .container{
        width: 100%;
        margin:20px auto;
    }
    .chapter{
        padding: 30px 0;
    }
</style>
