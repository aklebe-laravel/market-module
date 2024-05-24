console.log('module market crossSelling.js');

export function crossSelling() {

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
        userId: 0,

        /**
         *
         */
        maxItemsToShow: 6,

        /**
         * product list for cross-selling
         * depends on site and can be filled by different locations like website.user.fetchObject()
         */
        items: [],

        /**
         *
         * @param parameterParent
         */
        start(parameterParent) {
            this.parent = parameterParent;
        },


        /**
         *
         */
        carouselItemsToShow() {
            let result = (this.items.length < this.maxItemsToShow) ? this.items.length : this.maxItemsToShow;
            if (this.parent.getViewportNumber() < 4) {
                result = 3;
            }
            if (this.parent.getViewportNumber() < 3) {
                result = 1;
            }
            return result;
        },


        /**
         *
         */
        carouselRotateLeft() {
            if (this.items.length > 1) {
                this.items.unshift(this.items.splice(this.items.length - 1, 1)[0]);
            }
        },


        /**
         *
         */
        carouselRotateRight() {
            if (this.items.length > 1) {
                this.items.push(this.items.splice(0, 1)[0]);
            }
        },


        /**
         *
         */
        carouselItemClassColX() {
            let n = this.carouselItemsToShow();
            let colNo = (n < 3) ? 12 : ((n < 4) ? 4 : 2);
            return 'col-' + colNo;
        },

    }

} // no ; at the end of class declaration