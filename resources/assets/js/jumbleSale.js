import {Website} from "../../../../WebsiteBase/resources/assets/js/website";
import {shoppingCart} from "./shoppingCart";
import {crossSelling} from "./crossSelling";

console.log('module market jumbleSale.js');

/**
 * Uses basic functionality from Website
 */
export class JumbleSale extends Website {

    /**
     *
     */
    cart = shoppingCart();

    /**
     *
     */
    crossSelling = crossSelling();

    startJumbleSale = function (classInstance) {

        classInstance.startWebsite(classInstance);
        classInstance.crossSelling.start(classInstance);
        classInstance.cart.start(classInstance);

    };

    /**
     *
     */
    start = function () {

        let classInstance = this;
        classInstance.startJumbleSale(classInstance);

    };


} // no ; at the end of class declaration