<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
    
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
        <?php echo e($title); ?>

    </h2>

    
    <!--[if BLOCK]><![endif]--><?php if(isset($actions)): ?>
        <div>
            <?php echo e($actions); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\laragon\www\sistemaDM\resources\views/components/page-header.blade.php ENDPATH**/ ?>