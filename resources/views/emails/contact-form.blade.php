<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova mensagem do portfólio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }
        .message-content {
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            white-space: pre-wrap;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nova Mensagem do Portfólio</h1>
        <p>Você recebeu uma nova mensagem através do formulário de contato</p>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="field-label">Nome:</div>
            <div class="field-value">{{ $name }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">E-mail:</div>
            <div class="field-value">{{ $email }}</div>
        </div>
        
        @if($phone)
        <div class="field">
            <div class="field-label">Telefone:</div>
            <div class="field-value">{{ $phone }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="field-label">Assunto:</div>
            <div class="field-value">{{ $subject_name }}</div>
        </div>
        
        @if($budget)
        <div class="field">
            <div class="field-label">Orçamento Estimado:</div>
            <div class="field-value">{{ $budget_name }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="field-label">Mensagem:</div>
            <div class="message-content">{{ $message }}</div>
        </div>
        
        <div class="footer">
            <p>Esta mensagem foi enviada através do formulário de contato do portfólio.</p>
            <p>Data: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>