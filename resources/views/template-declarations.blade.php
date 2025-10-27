<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        body {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div>
        <h2 style="text-transform:uppercase;text-align:center;">{{ $title }}</h2>
    </div>
    <div style="margin:20px 0;">
        {!! $pre_declarations !!}
    </div>
    <table style="border-collapse: collapse;">
        @foreach ($declarations as $declaration)
        <tr>
            <td style="border:1pt solid black; padding: 2px 10px;">
                {!! $declaration->declaration !!}
            </td>
            <td style="border:1pt solid black; padding: 2px 10px;">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAvgAAAL4BsOPnlwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAFxSURBVEiJ3da7axVREMfxz+jFBwEhighaWQghBIs0aSxTaCorOwX/gFgFbC0NISBYC5ImZUiIklI7qysY0MJGm5TaBCwUJ0VO4Hjdu4n30Tgwxc7j9z07e/bsRmYap50ah2hETEbEI+hExJcR6b7MzCcRcQ07mImIG5Aj8Kdl1FP4WmI/8aBTreA5Pg6w8r3M3IqIObzCJfzAvczcru/gdmYaxHEH+0XnO25VueEAuF/GkdjDzZ784AAs4Xfp/4zrDTV/A3AGF1uEAytV73tcwXkstAJwFtv4gMsN4h2sVX1vcKH4Wzw7DjCFbyX2BwQTeF31bOBcya2XWDugBGd7IWX7vavqX+B01bNzYkADZBefqtrlhtE1AvqeRZnZxbzDfT1TRpdYyszH/fp6rfWw64H8wsPMXD2pOIc7otUysxsR87h69OqPFHAEQfdfxRnT9+D/AtTPYDEi7g6hNX0cYGEI8b7WweaINXfrixj3b8sB5tOks04krOwAAAAASUVORK5CYII=" />
            </td>
        </tr>
        @endforeach
    </table>
    <div style="margin:20px 0;">
        {!! $post_declarations !!}
    </div>
    <div style="margin:20px 0;text-align:right;width: 100%;">
        Sottoscritto online da <span>{{ $declarations_signature }}</span>
        In data <span> {{ $date }} </span>
    </div>
</body>

</html>