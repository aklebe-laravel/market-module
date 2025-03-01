<template x-if="product.is_locked">
    <div class="btn w-100 btn-secondary">
        <span class="bi bi-cart"></span> {{ __('Not available') }}
    </div>
</template>
<template x-if="!product.is_locked && cart.object.items.findIndex(x => x.product_id == product.id) != -1">
    <button class="btn w-100 btn-outline-danger btn-remove-from-cart" @click="cart.removeProduct(product.id)">
        <span class="bi bi-cart"></span> {{ __('Remove') }}
    </button>
</template>
<template x-if="!product.is_locked && cart.object.items.findIndex(x => x.product_id == product.id) == -1">
    <button class="btn w-100 btn-outline-success btn-add-to-cart" @click="cart.addProduct(product.id)">
        <span class="bi bi-cart"></span> {{ __('Add') }}
    </button>
</template>