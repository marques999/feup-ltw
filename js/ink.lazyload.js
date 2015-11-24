Ink.createModule('Ink.UI.LazyLoad', '1', ['Ink.UI.Common_1', 'Ink.Dom.Event_1', 'Ink.Dom.Element_1', 'Ink.Dom.Css_1'], function(Common, InkEvent, InkElement, Css) {
'use strict';

var scrollSupport = 'onscroll' in document &&
    // Opera Mini reports having the scroll event, but it does not.
    typeof operamini === 'undefined';

function LazyLoad() {
    Common.BaseUIComponent.apply(this, arguments);
}

LazyLoad._name = 'LazyLoad_1';

LazyLoad._optionDefinition = {
    item: ['String', '.lazyload-item'],
    placeholder: ['String', null],
    loadedClass: ['String', null],
    source: ['String', 'data-src'],
    destination: ['String', 'src'],
    delay: ['Number', 100],
    delta: ['Number', 0],
    image: ['Boolean', true],
    scrollElement: ['Element', window],
    touchEvents: ['Boolean', true],
    onInsideViewport: ['Function', false],
    onAfterAttributeChange: ['Function', false],
    autoInit: ['Boolean', true]
};

LazyLoad.prototype = {
 
    _init: function() {
        this._aData = [];
        this._hasEvents = false;
   
        if(this._options.autoInit) {
            this._activate();
        }
    },

    _activate: function() 
    {
        this._getData();
        if (!scrollSupport) {
            // If there is no scroll event support (Opera Mini!), load everything now.
            // A trivial fallback, that avoids entire pages without images.
            for (var i = 0; i < this._aData.length; i++) {
                this._elInViewport(this._aData[i]);
            }
        }
        if(!this._hasEvents) {
            this._addEvents(); 
        }
        this._onScrollThrottled();
    },

    _getData: function()
    {
        var aElms = Ink.ss(this._options.item, this._element);
        var attr = null;
        for(var i=0, t=aElms.length; i < t; i++) {
            if (this._options.placeholder != null && !InkElement.hasAttribute(aElms[i], this._options.destination)) {
                // [todo]: this function's name implies that it doesn't touch anything, yet it's changing attributes.
                aElms[i].setAttribute(this._options.destination, this._options.placeholder);
            }
            attr = aElms[i].getAttribute(this._options.source);
            if(attr !== null || !this._options.image) {
                this._aData.push({elm: aElms[i], original: attr});
            }
        }
    },

    _addEvents: function() 
    {
        if (!scrollSupport) { return; }
        this._onScrollThrottled = InkEvent.throttle(Ink.bindEvent(this._onScroll, this), this._options.delay);
        if('ontouchmove' in document.documentElement && this._options.touchEvents) {
            InkEvent.observe(document.documentElement, 'touchmove', this._onScrollThrottled);
        }
        InkEvent.observe(this._options.scrollElement, 'scroll', this._onScrollThrottled);
        this._hasEvents = true;
    },

    _removeEvents: function() {
        if('ontouchmove' in document.documentElement && this._options.touchEvents) {
            InkEvent.stopObserving(document.documentElement, 'touchmove', this._onScrollThrottled);
        }
        InkEvent.stopObserving(this._options.scrollElement, 'scroll', this._onScrollThrottled);
        this._hasEvents = false;
    }, 

    _onScroll: function() {
        var curElm;

        for (var i = 0; i < this._aData.length; i++) {
            curElm = this._aData[i];

            if (InkElement.inViewport(curElm.elm, { partial: true, margin: this._options.delta })) {
                this._elInViewport(curElm);
                this._aData.splice(i, 1);
                i -= 1;
            }
        }

        if (this._aData.length === 0) {
            this._removeEvents();
        }
    },

    _elInViewport: function (curElm) {
        this._userCallback('onInsideViewport', { element: curElm.elm });

        if(this._options.image) {
            curElm.elm.setAttribute(this._options.destination, curElm.original);
            if (this._options.loadedClass) {
                Css.addClassName(curElm.elm, this._options.loadedClass);
            }
            curElm.elm.removeAttribute(this._options.source);
        }

        this._userCallback('onAfterAttributeChange', { element: curElm.elm });
    },

    _userCallback: function (name) {
        if (typeof this._options[name] === 'function') {
            this._options[name].apply(this, [].slice.call(arguments, 1));
        }
    },

    reload: function() {
        this._activate(); 
    },

    destroy: function() {
        if(this._hasEvents) {
            this._removeEvents();
        }
        Common.destroyComponent.call(this);
    }
};

Common.createUIComponent(LazyLoad);

return LazyLoad;
});