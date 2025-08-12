@extends('layouts.portfolio')

@section('title', 'Sobre - Portfólio')
@section('description', 'Conheça minha trajetória como designer, experiências, habilidades e paixão por criar soluções visuais únicas.')

@section('content')
<div>
    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-gray-50 to-white py-20 lg:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                            Olá, eu sou
                            <span class="text-blue-600">Designer</span>
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            Sou um designer apaixonado por criar experiências visuais únicas e memoráveis. 
                            Com anos de experiência em design gráfico, ilustração digital e design de jogos, 
                            transformo ideias em soluções criativas que conectam marcas aos seus públicos.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('contact.public.index') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center">
                                Vamos Conversar
                            </a>
                            <a href="{{ route('portfolio.index') }}" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold hover:border-gray-400 transition-colors text-center">
                                Ver Portfólio
                            </a>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="w-full max-w-md mx-auto">
                            <div class="aspect-square bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center">
                                <svg class="w-32 h-32 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Skills Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Habilidades &amp; Especialidades</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Áreas de expertise e ferramentas que domino</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Ilustração Digital</h3>
                        <p class="text-gray-600">Criação de ilustrações originais para diversos propósitos, desde conceitos artísticos até materiais comerciais.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Identidade Visual</h3>
                        <p class="text-gray-600">Desenvolvimento de logotipos, paletas de cores e sistemas visuais completos para marcas.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Diagramação</h3>
                        <p class="text-gray-600">Layout e organização visual para materiais impressos e digitais, como livros, revistas e catálogos.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Design de Jogos</h3>
                        <p class="text-gray-600">Criação de assets visuais, interfaces e elementos gráficos para jogos digitais e analógicos.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">UI/UX Design</h3>
                        <p class="text-gray-600">Design de interfaces digitais focadas na experiência do usuário e usabilidade.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Motion Graphics</h3>
                        <p class="text-gray-600">Animações e elementos visuais em movimento para vídeos, apresentações e mídias digitais.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tools Section --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Ferramentas &amp; Software</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Tecnologias que utilizo no dia a dia</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-orange-600">Ai</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Illustrator</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">Ps</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Photoshop</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">Id</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">InDesign</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-green-600">Fg</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Figma</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-red-600">Sk</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Sketch</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-indigo-600">Ae</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">After Effects</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Experience Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Experiência</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Minha trajetória profissional</p>
                </div>
                
                <div class="space-y-12">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/3">
                            <div class="text-blue-600 font-semibold mb-2">2020 - Presente</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Designer Freelancer</h3>
                            <p class="text-gray-600">Autônomo</p>
                        </div>
                        <div class="md:w-2/3">
                            <p class="text-gray-600 leading-relaxed">
                                Desenvolvimento de projetos de identidade visual, ilustração digital e design de jogos 
                                para clientes diversos. Especialização em soluções criativas personalizadas e 
                                atendimento direto ao cliente.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/3">
                            <div class="text-blue-600 font-semibold mb-2">2018 - 2020</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Designer Gráfico</h3>
                            <p class="text-gray-600">Agência Criativa</p>
                        </div>
                        <div class="md:w-2/3">
                            <p class="text-gray-600 leading-relaxed">
                                Criação de materiais gráficos para campanhas publicitárias, desenvolvimento de 
                                identidades visuais e coordenação de projetos de design em equipe multidisciplinar.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-1/3">
                            <div class="text-blue-600 font-semibold mb-2">2016 - 2018</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Designer Júnior</h3>
                            <p class="text-gray-600">Estúdio de Design</p>
                        </div>
                        <div class="md:w-2/3">
                            <p class="text-gray-600 leading-relaxed">
                                Início da carreira profissional com foco em diagramação, criação de layouts 
                                e desenvolvimento de habilidades em design digital e impresso.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Section --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Meus Valores</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Princípios que guiam meu trabalho</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Qualidade</h3>
                        <p class="text-gray-600">Compromisso com a excelência em cada detalhe, desde o conceito até a entrega final.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Criatividade</h3>
                        <p class="text-gray-600">Busca constante por soluções inovadoras e originais que se destaquem no mercado.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Colaboração</h3>
                        <p class="text-gray-600">Trabalho próximo com clientes para entender suas necessidades e superar expectativas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-blue-600">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Vamos trabalhar juntos?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Estou sempre aberto a novos desafios e projetos interessantes. 
                Entre em contato e vamos conversar sobre suas ideias.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact.public.index') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Entrar em Contato
                </a>
                <a href="{{ route('services.public.index') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    Ver Serviços
                </a>
            </div>
        </div>
    </section>
</div>
@endsection