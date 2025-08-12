<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Personalizável
        </h2>
    </x-slot>

    {{-- Componente Livewire do Dashboard --}}
    @livewire('customizable-dashboard')
</x-app-layout>

{{-- Scripts necessários --}}
@push('scripts')
{{-- SortableJS para drag & drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

{{-- Toastr para notificações --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

{{-- Configuração do Toastr --}}
<script>
    // Configuração global do Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    
    // Escutar eventos do Livewire para mostrar toasts
    document.addEventListener('livewire:load', function () {
        Livewire.on('showToast', function (data) {
            const { type, message } = data;
            
            switch(type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                default:
                    toastr.info(message);
            }
        });
        
        // Evento para confirmação de ações
        Livewire.on('confirmAction', function (data) {
            const { message, action } = data;
            
            if (confirm(message)) {
                Livewire.emit(action);
            }
        });
    });
    
    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + E = Toggle Edit Mode
        if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
            e.preventDefault();
            Livewire.emit('toggleEditMode');
        }
        
        // Ctrl/Cmd + S = Save Configuration
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            Livewire.emit('saveConfiguration');
        }
        
        // Escape = Cancel Edit
        if (e.key === 'Escape') {
            Livewire.emit('cancelEdit');
        }
        
        // Ctrl/Cmd + L = Toggle Library
        if ((e.ctrlKey || e.metaKey) && e.key === 'l') {
            e.preventDefault();
            Livewire.emit('toggleLibrary');
        }
    });
    
    // Função para detectar preferência de movimento reduzido
    function respectsReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
    
    // Aplicar classes CSS baseadas na preferência de movimento
    if (respectsReducedMotion()) {
        document.documentElement.classList.add('reduce-motion');
    }
    
    // Sistema de animações avançadas
    class DashboardAnimations {
        constructor() {
            this.reducedMotion = respectsReducedMotion();
            this.sortableInstance = null;
            this.initializeAnimations();
        }
        
        initializeAnimations() {
            this.setupSortable();
            this.setupWidgetAnimations();
            this.setupDrawerAnimations();
            this.setupResizeAnimations();
        }
        
        setupSortable() {
            const dashboardGrid = document.querySelector('.dashboard-grid');
            if (dashboardGrid && typeof Sortable !== 'undefined') {
                this.sortableInstance = Sortable.create(dashboardGrid, {
                    animation: this.reducedMotion ? 0 : 300,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'widget-drag',
                    forceFallback: true,
                    fallbackClass: 'widget-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    
                    onStart: (evt) => {
                        this.onDragStart(evt);
                    },
                    
                    onMove: (evt) => {
                        this.onDragMove(evt);
                    },
                    
                    onEnd: (evt) => {
                        this.onDragEnd(evt);
                    }
                });
            }
        }
        
        onDragStart(evt) {
            if (this.reducedMotion) return;
            
            const draggedElement = evt.item;
            draggedElement.classList.add('widget-drag');
            
            // Animar vizinhos
            this.animateNeighbors(draggedElement, true);
        }
        
        onDragMove(evt) {
            if (this.reducedMotion) return;
            
            // Criar placeholder visual
            const placeholder = evt.related;
            if (placeholder && !placeholder.classList.contains('widget-drag-placeholder')) {
                placeholder.classList.add('widget-drag-placeholder');
                setTimeout(() => {
                    placeholder.classList.remove('widget-drag-placeholder');
                }, 200);
            }
        }
        
        onDragEnd(evt) {
            const draggedElement = evt.item;
            draggedElement.classList.remove('widget-drag');
            
            if (!this.reducedMotion) {
                // Animar vizinhos de volta
                this.animateNeighbors(draggedElement, false);
                
                // Animar elemento final
                draggedElement.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    draggedElement.style.transform = '';
                }, 200);
            }
            
            // Emitir evento para Livewire
            const newOrder = Array.from(evt.to.children).map((el, index) => ({
                id: el.dataset.widgetId,
                position: index
            }));
            
            Livewire.emit('updateWidgetOrder', newOrder);
        }
        
        animateNeighbors(draggedElement, isStart) {
            if (this.reducedMotion) return;
            
            const siblings = Array.from(draggedElement.parentNode.children)
                .filter(el => el !== draggedElement);
            
            siblings.forEach((sibling, index) => {
                setTimeout(() => {
                    if (isStart) {
                        sibling.classList.add('widget-neighbor');
                    } else {
                        sibling.classList.remove('widget-neighbor');
                    }
                }, index * 50);
            });
        }
        
        setupWidgetAnimations() {
            // Animar entrada de widgets com stagger
            const widgets = document.querySelectorAll('.widget-container');
            
            if (!this.reducedMotion) {
                widgets.forEach((widget, index) => {
                    widget.style.animationDelay = `${index * 100}ms`;
                });
            }
        }
        
        setupDrawerAnimations() {
            // Drawer animations are handled by Livewire directly
            // No additional JavaScript needed
        }
        
        setupResizeAnimations() {
            // Configurar animações de redimensionamento
            const widgets = document.querySelectorAll('.widget-container');
            
            widgets.forEach(widget => {
                this.addResizeHandle(widget);
            });
        }
        
        addResizeHandle(widget) {
            const handle = document.createElement('div');
            handle.className = 'widget-resize-handle';
            widget.appendChild(handle);
            
            let isResizing = false;
            let startX, startY, startWidth, startHeight;
            
            handle.addEventListener('mousedown', (e) => {
                isResizing = true;
                startX = e.clientX;
                startY = e.clientY;
                startWidth = parseInt(document.defaultView.getComputedStyle(widget).width, 10);
                startHeight = parseInt(document.defaultView.getComputedStyle(widget).height, 10);
                
                widget.classList.add('widget-resize');
                e.preventDefault();
            });
            
            document.addEventListener('mousemove', (e) => {
                if (!isResizing) return;
                
                const width = startWidth + e.clientX - startX;
                const height = startHeight + e.clientY - startY;
                
                widget.style.width = width + 'px';
                widget.style.height = height + 'px';
            });
            
            document.addEventListener('mouseup', () => {
                if (isResizing) {
                    isResizing = false;
                    widget.classList.remove('widget-resize');
                    
                    if (!this.reducedMotion) {
                        widget.classList.add('widget-morphing');
                        setTimeout(() => {
                            widget.classList.remove('widget-morphing');
                        }, 300);
                    }
                    
                    // Emitir evento para Livewire
                    Livewire.emit('updateWidgetSize', {
                        id: widget.dataset.widgetId,
                        width: widget.style.width,
                        height: widget.style.height
                    });
                }
            });
        }
        
        // Método para animar entrada de novos widgets
        animateNewWidget(widget) {
            if (this.reducedMotion) return;
            
            widget.classList.add('widget-enter');
            
            requestAnimationFrame(() => {
                widget.classList.add('widget-enter-active');
                
                setTimeout(() => {
                    widget.classList.remove('widget-enter', 'widget-enter-active');
                }, 300);
            });
        }
        
        // Método para destruir instâncias
        destroy() {
            if (this.sortableInstance) {
                this.sortableInstance.destroy();
            }
        }
    }
    
    // Inicializar sistema de animações
    let dashboardAnimations;
    
    document.addEventListener('DOMContentLoaded', function() {
        dashboardAnimations = new DashboardAnimations();
    });
    
    // Reinicializar quando Livewire atualizar
    document.addEventListener('livewire:load', function() {
        if (dashboardAnimations) {
            dashboardAnimations.destroy();
        }
        dashboardAnimations = new DashboardAnimations();
    });
    
    // Auto-save periódico (a cada 30 segundos se houver mudanças)
    let hasUnsavedChanges = false;
    
    document.addEventListener('livewire:load', function () {
        Livewire.on('markAsChanged', function () {
            hasUnsavedChanges = true;
        });
        
        Livewire.on('markAsSaved', function () {
            hasUnsavedChanges = false;
        });
        
        // Auto-save a cada 30 segundos
        setInterval(function() {
            if (hasUnsavedChanges) {
                Livewire.emit('autoSave');
            }
        }, 30000);
    });
    
    // Aviso antes de sair da página se houver mudanças não salvas
    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?';
            return e.returnValue;
        }
    });
</script>

{{-- Estilos customizados --}}
<style>
    /* Variáveis CSS para animações */
    :root {
        --animation-duration: 0.3s;
        --animation-easing: cubic-bezier(0.4, 0, 0.2, 1);
        --stagger-delay: 100ms;
        --drag-scale: 1.05;
        --drag-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Animações de entrada com fade + slide */
    .widget-enter {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        transition: all var(--animation-duration) var(--animation-easing);
    }
    
    .widget-enter-active {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    
    /* Stagger animation para widgets */
    .widget-container:nth-child(1) { animation-delay: 0ms; }
    .widget-container:nth-child(2) { animation-delay: 100ms; }
    .widget-container:nth-child(3) { animation-delay: 200ms; }
    .widget-container:nth-child(4) { animation-delay: 300ms; }
    .widget-container:nth-child(5) { animation-delay: 400ms; }
    .widget-container:nth-child(6) { animation-delay: 500ms; }
    .widget-container:nth-child(7) { animation-delay: 600ms; }
    .widget-container:nth-child(8) { animation-delay: 700ms; }
    .widget-container:nth-child(9) { animation-delay: 800ms; }
    
    /* Animação de entrada inicial */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .widget-container {
        animation: fadeInUp var(--animation-duration) var(--animation-easing) both;
    }
    
    /* Animações de drag & drop */
    .widget-drag {
        transform: scale(var(--drag-scale)) rotate(2deg);
        box-shadow: var(--drag-shadow);
        z-index: 1000;
        transition: all 0.2s var(--animation-easing);
    }
    
    .widget-drag-placeholder {
        background: rgba(59, 130, 246, 0.1);
        border: 2px dashed #3b82f6;
        border-radius: 0.5rem;
        transition: all 0.2s var(--animation-easing);
    }
    
    /* Animação dos vizinhos durante drag */
    .widget-neighbor {
        transform: translateX(10px);
        transition: transform 0.2s var(--animation-easing);
    }
    
    /* Animações do drawer lateral */
    .drawer-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 40;
        opacity: 0;
        transition: opacity var(--animation-duration) var(--animation-easing);
    }
    
    .drawer-backdrop.active {
        opacity: 1;
    }
    
    .drawer-container {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 320px;
        background: white;
        z-index: 50;
        transform: translateX(100%);
        transition: transform var(--animation-duration) var(--animation-easing);
        box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .drawer-container.active {
        transform: translateX(0);
    }
    
    .dark .drawer-container {
        background: #1f2937;
    }
    
    /* Animações de redimensionamento */
    .widget-resize {
        transition: all 0.3s var(--animation-easing);
    }
    
    .widget-resize-handle {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        cursor: se-resize;
        background: linear-gradient(-45deg, transparent 30%, #cbd5e1 30%, #cbd5e1 40%, transparent 40%);
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .widget-container:hover .widget-resize-handle {
        opacity: 1;
    }
    
    /* Animação de morph para redimensionamento */
    @keyframes morphResize {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .widget-morphing {
        animation: morphResize 0.3s var(--animation-easing);
    }
    
    /* Desabilitar animações se preferir movimento reduzido */
    @media (prefers-reduced-motion: reduce) {
        :root {
            --animation-duration: 0.01ms;
            --stagger-delay: 0ms;
        }
        
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        
        .widget-container {
            animation: none;
        }
    }
    
    .reduce-motion * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    /* Estilos para o grid responsivo */
    .dashboard-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(4, 1fr);
    }
    
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Estilos para widgets */
    .widget-container {
        transition: all 0.2s ease;
    }
    
    .widget-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .reduce-motion .widget-container:hover {
        transform: none;
    }
    
    /* Estilos para o sortable */
    .sortable-ghost {
        opacity: 0.5;
    }
    
    .sortable-chosen {
        transform: scale(1.02);
    }
    
    /* Customização do Toastr */
    .toast-top-right {
        top: 80px;
        right: 12px;
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
    
    .dark .skeleton {
        background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
        background-size: 200% 100%;
    }
</style>
@endpush
</x-app-layout>