<!DOCTYPE html>
<html>
<head>
    <title>Logs</title>
</head>
<body>
    <h1>Logs</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NUIP</th>
                <th>Decision</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Finger Number</th>
                <th>User Code</th>
                <th>IP Address</th>
                <th>MAC Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->nuip }}</td>
                    <td>{{ $log->decision }}</td>
                    <td>{{ $log->start_time }}</td>
                    <td>{{ $log->end_time }}</td>
                    <td>{{ $log->finger_number }}</td>
                    <td>{{ $log->user_code }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->mac_address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
