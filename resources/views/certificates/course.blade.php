<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Certificate - {{ $stName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ public_path('cer.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .certificate-container {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        /* المحتوى */
        .content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            padding: 60px 80px;
            display: table;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }

        .logo {
            display: table-cell;
            width: 150px;
            vertical-align: top;
        }

        .logo svg {
            width: 150px;
            height: auto;
        }

        .certificate-meta {
            display: table-cell;
            text-align: right;
            vertical-align: top;
            font-size: 10px;
            color: #666;
            line-height: 1.8;
        }

        /* العنوان */
        .main-title {
            text-align: center;
            margin-top: 100px;
        }

        .main-title h1 {
            font-size: 24px;
            color: #5a5a5a;
            font-weight: 300;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .main-title h2 {
            font-size: 42px;
            color: #2d2d2d;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
        }

        /* المدرب */
        .instructor {
            text-align: center;
            margin-bottom: 50px;
        }

        .instructor-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .instructor-name {
            font-size: 16px;
            font-weight: 600;
            color: #2d2d2d;
        }

        /* اسم الطالب */
        .student-section {
            text-align: center;
            margin-top: 208px;
            margin-bottom: 50px;
            padding: 30px 0;
        }

        .student-name {
            font-size: 48px;
            font-weight: 700;
            color: #2d2d2d;
            padding-bottom: 15px;
            display: inline-block;
            min-width: 450px;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: bottom;
            padding: 0;
        }

        .footer-left {
            width: 50%;
            text-align: left;
        }

        .footer-right {
            width: 50%;
            text-align: right;
        }

        .footer-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .footer-value {
            font-size: 15px;
            font-weight: 600;
            color: #2d2d2d;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="content">
            {{-- معلومات المدرب
            <div class="instructor">
                <div class="instructor-label">Instructors</div>
                <div class="instructor-name">{{ $course->instructor->full_name ?? 'Jinn Education' }}</div>
            </div> --}}

            {{-- اسم الطالب --}}
            <div class="student-section">
                <div class="student-name">{{ $stName }}</div>
            </div>

            {{-- العنوان الرئيسي --}}
            <div class="main-title">
                <h1>{{ $courseTitle }}</h1>
            </div>


            {{-- Footer --}}
            {{-- Footer - في جدول واحد --}}
            {{-- Footer --}}
            {{-- <table class="footer-table">
                <tr>
                    <td class="footer-right">
                        <div class="footer-label">Length</div>
                        <div class="footer-value">{{ $course->duration ?? '25.5' }} total hours</div>
                    </td>
                    <td class="footer-left">
                        <div class="footer-label">Date</div>
                        <div class="footer-value">{{ $certificate->issued_at->format('F d, Y') }}</div>
                    </td>
                </tr>
            </table> --}}

        </div>
    </div>
</body>

</html>