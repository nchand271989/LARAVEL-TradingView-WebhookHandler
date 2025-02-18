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
            <?php echo e(isset($webhook) ? __('Edit Webhook') : __('Create Webhook')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                <a href="<?php echo e(route('webhooks.index')); ?>">Webhooks</a>
                <span class="text-gray-500"><?php echo e(isset($webhook) ? __('Edit Webhook') : __('Create Webhook')); ?></span>
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
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form id="webhook-form" method="POST" action="<?php echo e(isset($webhook) ? route('webhooks.update', $webhook->webhid) : route('webhooks.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($webhook)): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="block text-gray-700">Webhook Name:</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $webhook->name ?? '')); ?>" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Select Strategy:</label>
                        <select name="stratid" id="strategy-select" class="w-full border rounded p-2" required>
                            <option value="">-- Select Strategy --</option>
                            <?php $__currentLoopData = $strategies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($strategy->stratid); ?>" 
                                    data-attributes='<?php echo json_encode($strategy->attributes, 15, 512) ?>'
                                    <?php echo e(isset($webhook) && $webhook->strategy_id == $strategy->stratid ? 'selected' : ''); ?>>
                                    <?php echo e($strategy->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>


                    <div id="strategy-attributes" class="mb-4 <?php echo e(isset($webhook) && $webhook->attributes->count() > 0 ? '' : 'hidden'); ?>">
                        <h3 class="text-gray-700">Strategy Attributes:</h3>
                        <?php if(!isset($webhook)): ?>
                            <div class="flex justify-between items-center w-full border-b gap-2 pb-2 mb-2">
                                <input class="text-gray-700 w-1/4 border-0" 
                                    value="Timeframe" 
                                    readonly />
                                <input type="text" 
                                    name="timeframe" 
                                    class="w-3/4 border rounded p-2" 
                                    value="5m" 
                                    required />
                            </div>
                        <?php endif; ?>
                        <div id="attributes-container">
                            <?php if(isset($webhook)): ?>
                                <?php $__currentLoopData = $webhook->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex justify-between items-center w-full border-b gap-2 pb-2 mb-2">
                                        <input class="text-gray-700 w-1/4 border-0" 
                                               name="attributes[<?php echo e($index); ?>][name]" 
                                               value="<?php echo e($attribute->attribute_name); ?>" 
                                               readonly />
                                        <input type="text" 
                                               name="attributes[<?php echo e($index); ?>][value]" 
                                               class="w-3/4 border rounded p-2" 
                                               value="<?php echo e($attribute->attribute_value); ?>" 
                                               required />
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Select Exchange:</label>
                        <select name="exid" id="exchange-select" class="w-full border rounded p-2" required>
                            <option value="">-- Select Exchange --</option>
                            <?php $__currentLoopData = $exchanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($exchange->exid); ?>" 
                                    exchange-attributes='<?php echo json_encode($exchange->currencies, 15, 512) ?>'
                                    <?php echo e(isset($webhook) && $webhook->exchange_id == $exchange->exid ? 'selected' : ''); ?>>
                                    <?php echo e($exchange->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div id="currency-container" class="mb-4 <?php echo e(isset($webhook) && $webhook->exchange_id!= null ? '' : 'hidden'); ?>">
                        <label class="block text-gray-700">Select Currencies:</label>
                        <select name="curid" id="currency-select" class="w-full border rounded p-2" required>
                            <option value="">-- Select Currencies --</option>
                            <?php if(isset($webhook) && $exchanges->contains('exid', $webhook->exchange_id)): ?>
                                <?php
                                    // Find the exchange that matches the webhook's exchange_id
                                    $selectedExchange = $exchanges->firstWhere('exid', $webhook->exchange_id);
                                ?>
                                
                                <?php $__currentLoopData = $selectedExchange->currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option 
                                        value="<?php echo e($currency->curid); ?>"
                                        <?php echo e(isset($webhook) && $webhook->currency_id == $currency->curid ? 'selected' : ''); ?>>
                                            <?php echo e($currency->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>


                    <!-- Select Rules -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Select Test Rules:</label>

                        <div class="flex justify-start gap-2 mb-2">
                            <button type="button" id="select-all" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Select All</button>
                            <button type="button" id="deselect-all" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Deselect All</button>
                        </div>

                        <div class="border border-gray-300 rounded-lg p-2 overflow-y-auto" style="max-height: 300px;">
                            <?php $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center mb-2">
                                    <label class="flex items-center cursor-pointer w-full">
                                        <input type="checkbox" name="rules[]" value="<?php echo e($rule->rid); ?>" 
                                            class="rules-checkbox mr-2"
                                            <?php echo e(isset($webhook) && $webhook->rules->contains('rid', $rule->rid) ? 'checked' : ''); ?>>
                                        <span class="flex items-center space-x-2">
                                            <span><?php echo e($rule->name); ?></span>
                                        </span>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['rules'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm p-2">
                            <option value="Active" <?php echo e((isset($webhook) && $webhook->status == 'Active') ? 'selected' : ''); ?>>Active</option>
                            <option value="Inactive" <?php echo e((isset($webhook) && $webhook->status == 'Inactive') ? 'selected' : ''); ?>>Inactive</option>
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

                    <button type="submit" id="submit-btn" class="bg-blue-500 text-white px-4 py-2 rounded">
                        <?php echo e(isset($webhook) ? 'Update Webhook' : 'Save Webhook'); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const strategySelect = document.getElementById("strategy-select");
            const exchangeSelect = document.getElementById("exchange-select");
            const currencySelect = document.getElementById("currency-select");
            
            const currencyContainer = document.getElementById("currency-container");
            const attributesContainer = document.getElementById("attributes-container");
            const strategyAttributesDiv = document.getElementById("strategy-attributes");
            const form = document.getElementById("webhook-form");
            const submitBtn = document.getElementById("submit-btn");

            function loadAttributes(attributesJson) {
                attributesContainer.innerHTML = "";

                if (!attributesJson || attributesJson === "null") {
                    strategyAttributesDiv.classList.add("hidden");
                    return;
                }

                try {
                    const attributes = JSON.parse(attributesJson);
                    if (attributes.length > 0) {
                        strategyAttributesDiv.classList.remove("hidden");
                        attributes.forEach((attribute, index) => {
                            const attributeDiv = document.createElement("div");
                            attributeDiv.classList.add("flex", "justify-between", "items-center", "w-full", "border-b", "gap-2", "pb-2", "mb-2");

                            attributeDiv.innerHTML = `
                                <input class="text-gray-700 w-1/4 border-0" 
                                       name="attributes[${index}][name]" 
                                       value="${attribute.attribute_name}" 
                                       readonly />
                                <input type="text" 
                                       name="attributes[${index}][value]" 
                                       class="w-3/4 border rounded p-2" 
                                       value="${attribute.attribute_value || ''}" 
                                       required />
                            `;

                            attributesContainer.appendChild(attributeDiv);
                        });
                    } else {
                        strategyAttributesDiv.classList.add("hidden");
                    }
                } catch (error) {
                    console.error("Error parsing attributes JSON:", error);
                }
            }

            strategySelect.addEventListener("change", function () {
                const selectedOption = strategySelect.options[strategySelect.selectedIndex];
                loadAttributes(selectedOption.getAttribute("data-attributes"));
            });

            exchangeSelect.addEventListener("change", function () {
                const selectedOption = exchangeSelect.options[exchangeSelect.selectedIndex];
                loadExchangeAttributes(selectedOption.getAttribute("exchange-attributes"));
            });

            function loadExchangeAttributes(attributesJson) {
                if (!attributesJson || attributesJson === "null") {
                    currencyContainer.classList.add("hidden");
                    return;
                }

                const processedJson = attributesJson.replace(/"curid":(\d{15,})/g, '"curid":"$1"');

                const attributes = JSON.parse(processedJson);

                if (attributes.length > 0) {
                    console.log(attributes);
                    currencyContainer.classList.remove("hidden");
                    currencySelect.innerHTML = '<option value="">-- Select Currencies --</option>';
                    attributes.forEach((attribute, index) => { 
                        const option = document.createElement('option');
                        option.value = attribute.curid;  // assuming 'id' is the value for each attribute
                        option.textContent = attribute.name;  // assuming 'name' is the display text
                        currencySelect.appendChild(option);
                    });
                }
            }


            form.addEventListener("submit", function () {
                submitBtn.disabled = true;
                submitBtn.innerText = "Processing...";
            });

            // Load attributes if editing a webhook
            <?php if(isset($webhook)): ?>
                loadAttributes(`<?php echo json_encode($webhook->attributes, JSON_HEX_APOS | JSON_HEX_QUOT); ?>`);
            <?php else: ?>
                loadAttributes(<?php echo json_encode($webhook->strategy->attributes ?? [], 15, 512) ?>);
            <?php endif; ?>

            // Select all checkboxes
            document.getElementById('select-all').addEventListener('click', function() {
                document.querySelectorAll('.rules-checkbox').forEach(checkbox => checkbox.checked = true);
            });

            // Deselect all checkboxes
            document.getElementById('deselect-all').addEventListener('click', function() {
                document.querySelectorAll('.rules-checkbox').forEach(checkbox => checkbox.checked = false);
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
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/webhooks/create.blade.php ENDPATH**/ ?>