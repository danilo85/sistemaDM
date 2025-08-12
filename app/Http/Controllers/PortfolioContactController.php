<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PortfolioContactController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:ilustracao,identidade,diagramacao,jogos,ui-ux,outros',
            'budget' => 'nullable|string|in:500-1000,1000-2500,2500-5000,5000-10000,10000+,conversar',
            'message' => 'required|string|min:10|max:2000',
            'privacy' => 'required|accepted',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'subject.required' => 'Por favor, selecione um assunto.',
            'subject.in' => 'Assunto inválido.',
            'budget.in' => 'Faixa de orçamento inválida.',
            'message.required' => 'A mensagem é obrigatória.',
            'message.min' => 'A mensagem deve ter pelo menos 10 caracteres.',
            'message.max' => 'A mensagem não pode exceder 2000 caracteres.',
            'privacy.required' => 'Você deve concordar com a política de privacidade.',
            'privacy.accepted' => 'Você deve concordar com a política de privacidade.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        
        // Mapear assuntos para nomes amigáveis
        $subjects = [
            'ilustracao' => 'Ilustração Digital',
            'identidade' => 'Identidade Visual',
            'diagramacao' => 'Diagramação',
            'jogos' => 'Design de Jogos',
            'ui-ux' => 'UI/UX Design',
            'outros' => 'Outros',
        ];
        
        // Mapear orçamentos para nomes amigáveis
        $budgets = [
            '500-1000' => 'R$ 500 - R$ 1.000',
            '1000-2500' => 'R$ 1.000 - R$ 2.500',
            '2500-5000' => 'R$ 2.500 - R$ 5.000',
            '5000-10000' => 'R$ 5.000 - R$ 10.000',
            '10000+' => 'Acima de R$ 10.000',
            'conversar' => 'Prefiro conversar',
        ];

        $data['subject_name'] = $subjects[$data['subject']] ?? $data['subject'];
        $data['budget_name'] = isset($data['budget']) ? ($budgets[$data['budget']] ?? $data['budget']) : 'Não informado';
        
        try {
            // Enviar e-mail para o administrador
            Mail::send('emails.contact-form', $data, function ($message) use ($data) {
                $message->to(config('mail.admin_email', 'admin@example.com'))
                    ->subject('Nova mensagem do portfólio - ' . $data['subject_name'])
                    ->replyTo($data['email'], $data['name']);
            });

            // Enviar e-mail de confirmação para o cliente
            Mail::send('emails.contact-confirmation', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])
                    ->subject('Confirmação - Sua mensagem foi recebida');
            });

            return redirect()->back()->with('success', 'Mensagem enviada com sucesso! Entrarei em contato em breve.');
            
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail de contato: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao enviar sua mensagem. Tente novamente ou entre em contato diretamente.')
                ->withInput();
        }
    }
}