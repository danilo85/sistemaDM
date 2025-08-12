<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação - Sua mensagem foi recebida</title>
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
        .message-summary {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .field {
            margin-bottom: 10px;
        }
        .field-label {
            font-weight: bold;
            color: #374151;
            display: inline-block;
            width: 120px;
        }
        .field-value {
            color: #6b7280;
        }
        .next-steps {
            background-color: #dbeafe;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .contact-info {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Obrigado pelo contato!</h1>
        <p>Sua mensagem foi recebida com sucesso</p>
    </div>
    
    <div class="content">
        <p>Olá <strong>{{ $name }}</strong>,</p>
        
        <p>Muito obrigado por entrar em contato! Recebi sua mensagem e ficarei feliz em ajudar com seu projeto de <strong>{{ $subject_name }}</strong>.</p>
        
        <div class="message-summary">
            <h3>Resumo da sua mensagem:</h3>
            <div class="field">
                <span class="field-label">Assunto:</span>
                <span class="field-value">{{ $subject_name }}</span>
            </div>
            @if($budget)
            <div class="field">
                <span class="field-label">Orçamento:</span>
                <span class="field-value">{{ $budget_name }}</span>
            </div>
            @endif
            <div class="field">
                <span class="field-label">Data:</span>
                <span class="field-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <div class="next-steps">
            <h3>Próximos passos:</h3>
            <ul>
                <li><strong>Análise:</strong> Vou analisar sua solicitação e necessidades do projeto</li>
                <li><strong>Resposta:</strong> Entrarei em contato em até 24 horas</li>
                <li><strong>Proposta:</strong> Se for adequado, enviarei uma proposta detalhada</li>
                <li><strong>Reunião:</strong> Poderemos agendar uma conversa para alinhar detalhes</li>
            </ul>
        </div>
        
        <p>Enquanto isso, sinta-se à vontade para:</p>
        <ul>
            <li>Explorar meu <a href="{{ route('portfolio.public.index') }}" style="color: #2563eb;">portfólio completo</a></li>
            <li>Conhecer mais sobre meus <a href="{{ route('services.public.index') }}" style="color: #2563eb;">serviços</a></li>
            <li>Ler mais <a href="{{ route('about.public.index') }}" style="color: #2563eb;">sobre mim</a></li>
        </ul>
        
        <div class="contact-info">
            <h3>Informações de contato:</h3>
            <p><strong>E-mail:</strong> contato@designer.com</p>
            <p><strong>Telefone:</strong> (11) 99999-9999</p>
            <p><strong>WhatsApp:</strong> Disponível no mesmo número</p>
        </div>
        
        <p>Estou ansioso para trabalhar com você e transformar suas ideias em realidade!</p>
        
        <p>Atenciosamente,<br>
        <strong>Designer</strong></p>
        
        <div class="footer">
            <p>Esta é uma confirmação automática. Por favor, não responda a este e-mail.</p>
            <p>Se precisar de ajuda imediata, entre em contato diretamente pelos canais acima.</p>
        </div>
    </div>
</body>
</html>