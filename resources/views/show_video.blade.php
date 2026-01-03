<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Video Player</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        video {
            max-width: 95%;
            max-height: 95%;
            border-radius: 8px;
            background: #000;
        }
    </style>
</head>
<body>

<video controls autoplay>
    <source src="{{ $videoUrl }}" type="video/mp4">
    Your browser does not support the video tag.
</video>

</body>
</html>
