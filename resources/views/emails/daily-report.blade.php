<!DOCTYPE html>
<html>
<head>
    <title>Daily Activity Report</title>
</head>
<body>
    <h1>Daily Activity Report - {{ $date }}</h1>
    <p>Hello Admin,</p>
    <p>Here is the summary of today's activities:</p>
    <ul>
        <li><strong>New Posts:</strong> {{ $newPostsCount }}</li>
        <li><strong>New Users:</strong> {{ $newUsersCount }}</li>
    </ul>
    <p>Thank you for using our application!</p>
</body>
</html>