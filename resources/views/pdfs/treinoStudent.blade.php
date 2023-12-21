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
            justify-content: space-between;
        }

        .header {
            text-align: left;
        }

        h1 {
            color: #FFA500; /* Orange */
        }

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
    </style>
</head>
<body>
    <div class="header">
        <h1>Plan de Treinos - {{ $name }}</h1>
        <p class="company-name">TRAINSYS COMPANY</p>
    </div>

    @foreach($workouts as $workout)
        <div>
            <h2>{{ $workout->day }}</h2>
            <ul>
                <li><strong>Exercise:</strong> {{ $workout->exercise_id }}</li>
                <li><strong>Repetitions:</strong> {{ $workout->repetitions }}</li>
                <li><strong>Weight:</strong> {{ $workout->weight }}</li>
                <li><strong>Break Time:</strong> {{ $workout->break_time }}</li>
                <li><strong>Observations:</strong> {{ $workout->observations }}</li>
                <li><strong>Time:</strong> {{ $workout->time }}</li>
            </ul>
        </div>
    @endforeach
</body>
</html>
