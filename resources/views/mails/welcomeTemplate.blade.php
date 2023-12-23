<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Plano</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #FFA500;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #FFA500;
        }

        p {
            margin-bottom: 10px;
        }

        .welcome-message {
            background-color: #FFA500;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .plano-info p {
            margin: 10px 0;
            font-weight: bold;
        }

        .training-image {
            margin-top: 20px;
            max-width: 100%;
            height: auto;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Detalhes do Plano</h1>
    
    <p>Bem-vindo, {{ $userName }}!</p> 
    
    <div class="welcome-message">
        <p>Obrigado por escolher o software TRAINSYS. Estamos felizes em tê-lo conosco!</p>
    </div>

    <div class="plano-info">
        <p><strong>Tipo de Plano:</strong> {{ $planDescription }}</p>
        <p><strong>Limite de Alunos:</strong> {{ $planLimit > 0 ? $planLimit : 'ILIMITADO' }}</p>
    </div>
    
    <img class="training-image" src="data:image/jpeg;base64,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" alt="Imagen de Entrenamiento">
     
</div>

<footer>
    <p>TRAINSYS ACADEMIA | Latacunga-Ecuador | Contato: 0999711632</p>
</footer>

</body>
</html>
