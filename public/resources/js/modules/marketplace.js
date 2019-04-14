function Marketplace() {

    // because this is overwritten on jquery events
    var self = this;

    /**
     * Initialize events
     */
    this._init = function() 
    {

        self.set_events();
        self.set_configs();

    },

    /**
    * events delaration
    */
    this.set_events = function()
    {

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    }

}


var Marketplace = new Marketplace();
$(document).ready(function(){
    Marketplace._init();
});