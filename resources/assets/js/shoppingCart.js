console.log('module market shoppingCart.js');

export function shoppingCart() {

    return {

        /**
         * Parent main object (like class Website)
         */
        parent: null,

        /**
         *
         */
        isLoaded: false,

        /**
         *
         */
        editObjectId: 0,

        /**
         *
         */
        object: {
            'qty': 0,
            'items': [],
        },

        /**
         *
         */
        formHtml: '',


        /**
         *
         */
        start(parameterParent) {
            this.parent = parameterParent;
            // shoppingCartInstance.modelName = modelName;
            this.fetchObject();
        },


        /**
         *
         */
        getFetchObjectUrl() {
            let shoppingCartInstance = this;
            return `/cart/get/${shoppingCartInstance.editObjectId}`
        },


        /**
         *
         */
        fetchObject() {
            let shoppingCartInstance = this;
            shoppingCartInstance.isLoaded = false;
            // let modalLoading = new bootstrap.Modal('#modal-loading');
            // modalLoading.show();

            this.parent.requestGet(shoppingCartInstance.getFetchObjectUrl())
                .then(data => {
                    shoppingCartInstance.isLoaded = true;
                    console.log('Cart loaded ...');
                    shoppingCartInstance.formHtml = data.form_html;
                    shoppingCartInstance.object = data.data;
                });
        },


        /**
         *
         */
        getAddProductUrl() {
            let shoppingCartInstance = this;
            return `/cart/add-product`;
        },


        /**
         *
         */
        addProduct(productId) {
            let shoppingCartInstance = this;

            this.parent.requestPost(shoppingCartInstance.getAddProductUrl(), {
                'product_id': productId,
            })
                .then(data => {

                    shoppingCartInstance.object = data.data;

                });

        },


        /**
         *
         */
        getRemoveProductUrl() {
            let shoppingCartInstance = this;
            return `/cart/remove-product`;
        },


        /**
         *
         */
        removeProduct(productId) {
            let shoppingCartInstance = this;

            this.parent.requestPost(shoppingCartInstance.getRemoveProductUrl(), {
                'product_id': productId,
            }).then(data => {
                shoppingCartInstance.object = data.data;
            });

        },


        /**
         *
         */
        getRemoveItemUrl() {
            let shoppingCartInstance = this;
            return `/cart/remove-item`;
        },


        /**
         *
         */
        removeItem(itemId) {

            let shoppingCartInstance = this;

            this.parent.requestPost(shoppingCartInstance.getRemoveItemUrl(), {
                'item_id': itemId,
            }).then(data => {
                shoppingCartInstance.object = data.data;
                // $dispatch('refreshDatatable');
            });

        },

        // @todo: works with @click="" but where to put code AFTER the emit?
        removeItem2(itemId) {
            Livewire.dispatchTo('shopping-cart-item-table', 'deleteItemById', {'itemId':itemId});
        }
    }

} // no ; at the end of class declaration