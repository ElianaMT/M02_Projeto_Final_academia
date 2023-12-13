<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Plano</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 10px;
        }

        .plano-info {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Detalhes do Plano</h1>
    
    <p>Olá, {{ $userName}}!</p> 
    
    <div class="plano-info">
        <p><strong>Tipo de Plano:</strong> {{ $userName}}</p>
        <p><strong>Limite de Alunos:</strong> {{ $userName}}</p>
    </div>
</div>

</body>
</html>

