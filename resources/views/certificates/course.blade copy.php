<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
            padding: 80px;
            background-image: url("{{ public_path('./Gemini_Generated_Image_meth6smeth6smeth.jpg') }}")
        }

        h1 {
            font-size: 42px;
            margin-bottom: 40px;
        }

        p {
            font-size: 20px;
        }
    </style>
</head>

<body>

    <h1>Certificate of Completion</h1>

    <p>This certifies that</p>

    <h2>{{ $user->name }}</h2>

    <p>has successfully completed the course</p>

    <h3>{{ $course->title }}</h3>

    <p>Issued at: {{ $certificate->issued_at->format('Y-m-d') }}</p>
    <p>Code: {{ $certificate->certificate_code }}</p>

</body>

</html>