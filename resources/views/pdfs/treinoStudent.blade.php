<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treino PDF</title>

    <!-- Add custom styles -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            text-align: center;
        }

        h1,
        h2 {
            color: #FFA500; /* Orange */
        }

        div {
            border: 1px solid #FFA500; /* Orange border */
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2; /* Light gray background */
        }

        .footer {
            margin-top: auto;
            text-align: center;
            background-color: #f0f0f0; /* Light gray background */
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Plan de Treino - {{ $name }}</h1>
    </div>

    @foreach ($workouts->groupBy('day') as $day => $workoutsForDay)
        <div>
            <h2>{{ $day }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Exercise</th>
                        <th>Repetitions</th>
                        <th>Weight</th>
                        <th>Break Time</th>
                        <th>Observations</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($workoutsForDay as $workout)
                        <tr>
                            <td>{{ $workout->day }}</td>
                            <td>{{ $workout->exercise_id }}</td>
                            <td>{{ $workout->repetitions }}</td>
                            <td>{{ $workout->weight }} </td>
                            <td>{{ $workout->break_time }}</td>
                            <td>{{ $workout->observations }}</td>
                            <td>{{ $workout->time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="header">
        <h1>RESUMO DE TREINOS</h1>
    </div>


    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Exercise</th>
                <th>Repetitions</th>
                <th>Weight</th>
                <th>Break Time</th>
                <th>Observations</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($workouts as $workout)
                <tr>
                    <td>{{ $workout->day }}</td>
                    <td>{{ $workout->exercise_id }}</td>
                    <td>{{ $workout->repetitions }}</td>
                    <td>{{ $workout->weight }} </td>
                    <td>{{ $workout->break_time }}</td>
                    <td>{{ $workout->observations }}</td>
                    <td>{{ $workout->time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        TRAINSYS COMPANY| Latacunga - Ecuador | Contacto: 0999711678
    </div>
</body>

</html>
