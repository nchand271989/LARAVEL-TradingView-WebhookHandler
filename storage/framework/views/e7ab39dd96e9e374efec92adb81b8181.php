<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(isset($strategy) ? 'Edit Strategy' : 'Create Strategy'); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a href="<?php echo e(route('strategies.index')); ?>">Strategies</a>
                <span class="text-gray-500"><?php echo e(isset($strategy) ? 'Edit Strategy' : 'Create Strategy'); ?></span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Form for Creating or Updating a Strategy -->
                <form method="POST" action="<?php echo e(isset($strategy) ? route('strategies.update', $strategy->stratid) : route('strategies.store')); ?>" id="strategy-form">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($strategy)): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <!-- Strategy Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Strategy Name</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name', $strategy->name ?? '')); ?>"
                               class="border rounded p-2 w-full" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Pine Script -->
                    <div class="mt-4">
                        <label for="pineScript" class="block text-sm font-medium text-gray-700">Pine Script</label>
                        <textarea name="pineScript" id="pineScript" rows="10" class="border rounded p-2 w-full" required><?php echo e(old('pineScript', $strategy->pineScript ?? '')); ?></textarea>
                        <?php $__errorArgs = ['pineScript'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Attributes Section -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Attributes</label>
                        <div id="attributes-container">
                            <?php if(isset($strategy) && $strategy->attributes->count() > 0): ?>
                                <?php $__currentLoopData = $strategy->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex gap-2 mb-2 attribute-row">
                                        <input type="text" name="attributes[<?php echo e($index); ?>][name]" value="<?php echo e($attribute->attribute_name); ?>" placeholder="Attribute Name" class="border rounded p-2 w-1/2" required>
                                        <input type="text" name="attributes[<?php echo e($index); ?>][value]" value="<?php echo e($attribute->attribute_value); ?>" placeholder="Attribute Value" class="border rounded p-2 w-1/2" required>
                                        <button type="button" class="rounded remove-attribute"><?php if (isset($component)) { $__componentOriginal75601b88582875c32440c22f9997a20b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75601b88582875c32440c22f9997a20b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.close-icon','data' => ['class' => 'block h-[40px] w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('close-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block h-[40px] w-auto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75601b88582875c32440c22f9997a20b)): ?>
<?php $attributes = $__attributesOriginal75601b88582875c32440c22f9997a20b; ?>
<?php unset($__attributesOriginal75601b88582875c32440c22f9997a20b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75601b88582875c32440c22f9997a20b)): ?>
<?php $component = $__componentOriginal75601b88582875c32440c22f9997a20b; ?>
<?php unset($__componentOriginal75601b88582875c32440c22f9997a20b); ?>
<?php endif; ?></button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="flex gap-2 mb-2 attribute-row">
                                    <input type="text" name="attributes[0][name]" placeholder="Attribute Name" class="border rounded p-2 w-1/2">
                                    <input type="text" name="attributes[0][value]" placeholder="Attribute Value" class="border rounded p-2 w-1/2">
                                    <button type="button" class="rounded remove-attribute"><?php if (isset($component)) { $__componentOriginal75601b88582875c32440c22f9997a20b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75601b88582875c32440c22f9997a20b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.close-icon','data' => ['class' => 'block h-[40px] w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('close-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block h-[40px] w-auto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75601b88582875c32440c22f9997a20b)): ?>
<?php $attributes = $__attributesOriginal75601b88582875c32440c22f9997a20b; ?>
<?php unset($__attributesOriginal75601b88582875c32440c22f9997a20b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75601b88582875c32440c22f9997a20b)): ?>
<?php $component = $__componentOriginal75601b88582875c32440c22f9997a20b; ?>
<?php unset($__componentOriginal75601b88582875c32440c22f9997a20b); ?>
<?php endif; ?></button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-start">
                            <button type="button" id="add-attribute" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">+ Add Attribute</button>
                        </div>
                    </div>

                     <!-- Status -->
                     <div class="mt-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm p-2">
                            <option value="Active" <?php echo e((isset($strategy) && $strategy->status == 'Active') ? 'selected' : ''); ?>>Active</option>
                            <option value="Inactive" <?php echo e((isset($strategy) && $strategy->status == 'Inactive') ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" id="submit-btn" class="bg-green-500 text-white px-4 py-2 rounded">
                            <?php echo e(isset($strategy) ? 'Update Strategy' : 'Create Strategy'); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Double Click Prevention & Dynamic Attributes -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let container = document.getElementById("attributes-container");
            let addBtn = document.getElementById("add-attribute");
            let form = document.getElementById("strategy-form");
            let submitBtn = document.getElementById("submit-btn");

            function addAttribute() {
                let index = container.querySelectorAll(".attribute-row").length;
                let row = document.createElement("div");
                row.classList.add("flex", "gap-2", "mb-2", "attribute-row");
                row.innerHTML = `
                    <input type="text" name="attributes[${index}][name]" placeholder="Attribute Name" class="border rounded p-2 w-1/2" required>
                    <input type="text" name="attributes[${index}][value]" placeholder="Attribute Value" class="border rounded p-2 w-1/2" required>
                    <button type="button" class="rounded remove-attribute"><?php if (isset($component)) { $__componentOriginal75601b88582875c32440c22f9997a20b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal75601b88582875c32440c22f9997a20b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.close-icon','data' => ['class' => 'block h-[40px] w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('close-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block h-[40px] w-auto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal75601b88582875c32440c22f9997a20b)): ?>
<?php $attributes = $__attributesOriginal75601b88582875c32440c22f9997a20b; ?>
<?php unset($__attributesOriginal75601b88582875c32440c22f9997a20b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal75601b88582875c32440c22f9997a20b)): ?>
<?php $component = $__componentOriginal75601b88582875c32440c22f9997a20b; ?>
<?php unset($__componentOriginal75601b88582875c32440c22f9997a20b); ?>
<?php endif; ?></button>
                `;
                container.appendChild(row);
                attachRemoveEvent(row.querySelector(".remove-attribute"));
            }

            function attachRemoveEvent(button) {
                button.addEventListener("click", function () {
                    let row = button.parentElement;
                    if (container.querySelectorAll(".attribute-row").length > 1) {
                        row.remove();
                    }
                });
            }

            // Attach event listeners to existing buttons
            document.querySelectorAll(".remove-attribute").forEach(attachRemoveEvent);
            addBtn.addEventListener("click", addAttribute);

            // Prevent double form submission
            form.addEventListener("submit", function(event) {
                if (submitBtn.disabled) {
                    event.preventDefault(); // Stop form submission if already disabled
                    return;
                }
                submitBtn.disabled = true; // Disable button to prevent double submission
                submitBtn.innerText = "Processing..."; // Optional: Change button text
            });
        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/strategies/create.blade.php ENDPATH**/ ?>