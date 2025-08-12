

<?php $__env->startSection('title', 'Contato - Portfólio'); ?>
<?php $__env->startSection('description', 'Entre em contato para discutir seu próximo projeto. Estou sempre aberto a novos desafios e oportunidades de colaboração.'); ?>

<?php $__env->startSection('content'); ?>
<div>
    
    <section class="bg-gradient-to-br from-gray-50 to-white py-20 lg:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Vamos conversar sobre
                    <span class="text-blue-600">seu projeto</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Estou sempre aberto a novos desafios e oportunidades de colaboração. 
                    Entre em contato e vamos transformar suas ideias em realidade.
                </p>
            </div>
        </div>
    </section>

    
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-8">Envie uma mensagem</h2>
                        
                        <form action="<?php echo e(route('api.contact.store')); ?>" method="POST" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           required
                                           value="<?php echo e(old('name')); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           required
                                           value="<?php echo e(old('email')); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone"
                                       value="<?php echo e(old('phone')); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Assunto *</label>
                                <select id="subject" 
                                        name="subject" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Selecione um assunto</option>
                                    <option value="ilustracao" <?php echo e(old('subject') == 'ilustracao' ? 'selected' : ''); ?>>Ilustração Digital</option>
                                    <option value="identidade" <?php echo e(old('subject') == 'identidade' ? 'selected' : ''); ?>>Identidade Visual</option>
                                    <option value="diagramacao" <?php echo e(old('subject') == 'diagramacao' ? 'selected' : ''); ?>>Diagramação</option>
                                    <option value="jogos" <?php echo e(old('subject') == 'jogos' ? 'selected' : ''); ?>>Design de Jogos</option>
                                    <option value="ui-ux" <?php echo e(old('subject') == 'ui-ux' ? 'selected' : ''); ?>>UI/UX Design</option>
                                    <option value="outros" <?php echo e(old('subject') == 'outros' ? 'selected' : ''); ?>>Outros</option>
                                </select>
                                <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Orçamento Estimado</label>
                                <select id="budget" 
                                        name="budget"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Selecione uma faixa</option>
                                    <option value="500-1000" <?php echo e(old('budget') == '500-1000' ? 'selected' : ''); ?>>R$ 500 - R$ 1.000</option>
                                    <option value="1000-2500" <?php echo e(old('budget') == '1000-2500' ? 'selected' : ''); ?>>R$ 1.000 - R$ 2.500</option>
                                    <option value="2500-5000" <?php echo e(old('budget') == '2500-5000' ? 'selected' : ''); ?>>R$ 2.500 - R$ 5.000</option>
                                    <option value="5000-10000" <?php echo e(old('budget') == '5000-10000' ? 'selected' : ''); ?>>R$ 5.000 - R$ 10.000</option>
                                    <option value="10000+" <?php echo e(old('budget') == '10000+' ? 'selected' : ''); ?>>Acima de R$ 10.000</option>
                                    <option value="conversar" <?php echo e(old('budget') == 'conversar' ? 'selected' : ''); ?>>Prefiro conversar</option>
                                </select>
                                <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Mensagem *</label>
                                <textarea id="message" 
                                          name="message" 
                                          rows="6" 
                                          required
                                          placeholder="Conte-me mais sobre seu projeto, objetivos, prazos e qualquer informação relevante..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('message')); ?></textarea>
                                <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="flex items-start">
                                <input type="checkbox" 
                                       id="privacy" 
                                       name="privacy" 
                                       required
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="privacy" class="ml-3 text-sm text-gray-600">
                                    Concordo com o tratamento dos meus dados pessoais de acordo com a política de privacidade. *
                                </label>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Enviar Mensagem
                            </button>
                        </form>
                    </div>
                    
                    
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-8">Outras formas de contato</h2>
                        
                        <div class="space-y-8">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">E-mail</h3>
                                    <p class="text-gray-600">contato@designer.com</p>
                                    <p class="text-sm text-gray-500 mt-1">Respondo em até 24 horas</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Telefone</h3>
                                    <p class="text-gray-600">(11) 99999-9999</p>
                                    <p class="text-sm text-gray-500 mt-1">WhatsApp disponível</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Localização</h3>
                                    <p class="text-gray-600">São Paulo, SP</p>
                                    <p class="text-sm text-gray-500 mt-1">Atendo presencial e remoto</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Horário de Atendimento</h3>
                                    <p class="text-gray-600">Segunda a Sexta: 9h às 18h</p>
                                    <p class="text-sm text-gray-500 mt-1">Sábado: 9h às 14h</p>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="mt-12">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Redes Sociais</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Perguntas Frequentes</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Respostas para as dúvidas mais comuns</p>
                </div>
                
                <div class="space-y-6">
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Qual é o prazo médio para entrega de um projeto?</h3>
                        <p class="text-gray-600">O prazo varia conforme a complexidade do projeto. Identidades visuais simples podem levar de 7 a 14 dias, enquanto projetos mais complexos podem levar de 3 a 6 semanas. Sempre definimos prazos realistas durante o briefing inicial.</p>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Como funciona o processo de aprovação?</h3>
                        <p class="text-gray-600">Trabalho com etapas de aprovação bem definidas. Apresento conceitos iniciais, refinamos juntos e só parto para a finalização após sua aprovação. Incluo até 3 rodadas de revisões no valor do projeto.</p>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Quais formatos de arquivo são entregues?</h3>
                        <p class="text-gray-600">Entrego todos os arquivos necessários: vetoriais (AI, EPS), alta resolução (PNG, JPG), e arquivos editáveis. Para projetos web, também forneço assets otimizados e guias de uso.</p>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Trabalha com contratos e direitos autorais?</h3>
                        <p class="text-gray-600">Sim, todos os projetos são formalizados com contrato. Os direitos autorais são transferidos ao cliente após o pagamento integral, garantindo total propriedade sobre o trabalho desenvolvido.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php if(session('success')): ?>
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portfolio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistemaDM\resources\views/portfolio/contact.blade.php ENDPATH**/ ?>