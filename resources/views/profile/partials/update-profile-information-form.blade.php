<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informações do Perfil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Atualize as informações do seu perfil e endereço de e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- NOME E EMAIL --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Nome') }}</label>
                <input id="name" name="name" type="text" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                 @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    {{-- Lógica de verificação de email --}}
                @endif
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Identidade Visual
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Arraste e solte ou clique para enviar sua foto, logomarca e assinatura.
                </p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- FOTO DE PERFIL -->
                <div x-data="{ photoPreview: '{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : '' }}', dragging: false, handleFileSelect(event) { const file = event.target.files[0]; if (file) { this.photoPreview = URL.createObjectURL(file); } }, handleDrop(event) { const file = event.dataTransfer.files[0]; if (file) { document.getElementById('profile_photo').files = event.dataTransfer.files; this.photoPreview = URL.createObjectURL(file); } } }">
                    <label for="profile_photo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Foto de Perfil') }}</label>
                    <input id="profile_photo" name="profile_photo" type="file" class="hidden" @change="handleFileSelect($event)" accept="image/png, image/jpeg, image/jpg">
                    <div @click="document.getElementById('profile_photo').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleDrop($event)" class="mt-1 cursor-pointer flex flex-col justify-center items-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-8 transition-colors h-full" :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragging}">
                        <template x-if="photoPreview"><img :src="photoPreview" alt="Prévia da Foto" class="h-20 w-20 rounded-full object-cover"></template>
                        <template x-if="!photoPreview"><svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></template>
                        <p class="mt-2 text-xs text-center text-gray-600 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Clique</span> ou arraste</p>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>

                <!-- LOGOMARCA -->
                <div x-data="{ logoPreview: '{{ $user->logo_path ? asset('storage/' . $user->logo_path) : '' }}', dragging: false, handleFileSelect(event) { if (event.target.files[0]) { this.logoPreview = URL.createObjectURL(event.target.files[0]); } }, handleDrop(event) { if (event.dataTransfer.files[0]) { document.getElementById('logo').files = event.dataTransfer.files; this.logoPreview = URL.createObjectURL(event.dataTransfer.files[0]); } } }">
                    <label for="logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Logomarca') }}</label>
                    <input id="logo" name="logo" type="file" class="hidden" @change="handleFileSelect($event)" accept="image/png, image/svg+xml">
                    <div @click="document.getElementById('logo').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleDrop($event)" class="mt-1 cursor-pointer flex flex-col justify-center items-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-8 transition-colors h-full" :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragging}">
                        <template x-if="logoPreview"><img :src="logoPreview" alt="Prévia da Logomarca" class="mx-auto h-16 object-contain"></template>
                        <template x-if="!logoPreview"><svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></template>
                        <p class="mt-2 text-xs text-center text-gray-600 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Clique</span> ou arraste</p>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                </div>

                <!-- ASSINATURA -->
                <div x-data="{ signaturePreview: '{{ $user->signature_path ? asset('storage/' . $user->signature_path) : '' }}', dragging: false, handleFileSelect(event) { if (event.target.files[0]) { this.signaturePreview = URL.createObjectURL(event.target.files[0]); } }, handleDrop(event) { if (event.dataTransfer.files[0]) { document.getElementById('signature').files = event.dataTransfer.files; this.signaturePreview = URL.createObjectURL(event.dataTransfer.files[0]); } } }">
                    <label for="signature" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Assinatura') }}</label>
                    <input id="signature" name="signature" type="file" class="hidden" @change="handleFileSelect($event)" accept="image/png">
                    <div @click="document.getElementById('signature').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleDrop($event)" class="mt-1 cursor-pointer flex flex-col justify-center items-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-8 transition-colors h-full" :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragging}">
                        <template x-if="signaturePreview"><img :src="signaturePreview" alt="Prévia da Assinatura" class="mx-auto h-16 dark:invert object-contain"></template>
                        <template x-if="!signaturePreview"><svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></template>
                         <p class="mt-2 text-xs text-center text-gray-600 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Clique</span> ou arraste</p>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('signature')" />
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Informações Fiscais e de Contato
                </h2>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="cpf_cnpj" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('CPF ou CNPJ') }}</label>
                    <input id="cpf_cnpj" name="cpf_cnpj" type="text" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('cpf_cnpj', $user->cpf_cnpj) }}" />
                    <x-input-error class="mt-2" :messages="$errors->get('cpf_cnpj')" />
                </div>
                <div>
                    <label for="whatsapp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('WhatsApp (somente números com DDD)') }}</label>
                    <input id="whatsapp" name="whatsapp" type="text" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="5514991436268" />
                    <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                </div>
                <div>
                    <label for="contact_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('E-mail de Contato') }}</label>
                    <input id="contact_email" name="contact_email" type="email" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('contact_email', $user->contact_email) }}" />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                </div>
                 <div>
                    <label for="website_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Website') }}</label>
                    <input id="website_url" name="website_url" type="url" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('website_url', $user->website_url) }}" placeholder="https://..." />
                    <x-input-error class="mt-2" :messages="$errors->get('website_url')" />
                </div>
                 <div class="md:col-span-2">
                    <label for="behance_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('URL de Rede Social (Behance, etc)') }}</label>
                    <input id="behance_url" name="behance_url" type="url" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" value="{{ old('behance_url', $user->behance_url) }}" placeholder="https://..." />
                    <x-input-error class="mt-2" :messages="$errors->get('behance_url')" />
                </div>
            </div>
        </div>

        {{-- BOTÃO SALVAR --}}
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Salvo.') }}</p>
            @endif
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                {{ __('Salvar Alterações') }}
            </button>
        </div>
    </form>
</section>
