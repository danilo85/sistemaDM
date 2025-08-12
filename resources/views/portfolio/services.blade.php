@extends('layouts.portfolio')

@section('title', 'Serviços - Portfólio')
@section('description', 'Conheça os serviços de design que ofereço: ilustração digital, diagramação, design de jogos e muito mais.')

@section('content')
<div>
    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-gray-50 to-white py-20 lg:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Serviços de
                    <span class="text-blue-600">Design</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Soluções criativas personalizadas para transformar suas ideias em realidade visual. 
                    Cada projeto é único e desenvolvido com atenção aos detalhes.
                </p>
            </div>
        </div>
    </section>

    {{-- Services Grid --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @php
                    $services = App\Models\Service::active()->ordered()->get();
                @endphp
                
                @forelse($services as $service)
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    @if($service->icon)
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                            <i class="{{ $service->icon }} text-blue-600 text-2xl"></i>
                        </div>
                    @endif
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $service->title }}</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ $service->description }}</p>
                    <a href="{{ route('contact.public.index') }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                        Solicitar Orçamento
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum serviço disponível</h3>
                    <p class="text-gray-600">Os serviços serão adicionados em breve.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Process Section --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Meu Processo</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Como trabalho para garantir os melhores resultados</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Briefing</h3>
                    <p class="text-gray-600">Entendimento completo das necessidades, objetivos e expectativas do projeto.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-blue-600">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Conceituação</h3>
                    <p class="text-gray-600">Desenvolvimento de conceitos e direções criativas baseadas no briefing.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-blue-600">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Criação</h3>
                    <p class="text-gray-600">Execução do projeto com atenção aos detalhes e qualidade técnica.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-blue-600">4</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Entrega</h3>
                    <p class="text-gray-600">Finalização e entrega dos arquivos em todos os formatos necessários.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">O que dizem sobre meu trabalho</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Depoimentos de clientes satisfeitos</p>
            </div>
            
            @php
                $testimonials = App\Models\Testimonial::active()->latest()->take(3)->get();
            @endphp
            
            @if($testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($testimonials as $testimonial)
                <div class="bg-gray-50 p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"{{ $testimonial->content }}"</p>
                    <div class="flex items-center">
                        @if($testimonial->client_photo)
                            <img src="{{ $testimonial->client_photo_url }}" 
                                 alt="{{ $testimonial->client_name }}"
                                 class="w-12 h-12 rounded-full object-cover mr-4">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $testimonial->client_name }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-blue-600">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Pronto para começar seu projeto?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Entre em contato e vamos conversar sobre como posso ajudar a transformar suas ideias em realidade.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact.public.index') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Entrar em Contato
                </a>
                <a href="{{ route('portfolio.index') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    Ver Portfólio
                </a>
            </div>
        </div>
    </section>
</div>
@endsection