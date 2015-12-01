Ink.createModule('Ink.UI.Modal', '1', ['Ink.UI.Common_1','Ink.Dom.Event_1','Ink.Dom.Css_1','Ink.Dom.Element_1','Ink.Dom.Selector_1','Ink.Util.Array_1'], function(Common, Event, Css, InkElement, Selector, InkArray ) {
    'use strict';

    var opacitySupported = (function (div) {
        div.style.opacity = 'invalid';
        return div.style.opacity !== 'invalid';
    }(InkElement.create('div', {style: 'opacity: 1'})));

    var vhVwSupported = (function (elem) {
        var height = parseInt(window.innerHeight / 2, 10);
        var compHeight = parseInt((window.getComputedStyle ?
                                  getComputedStyle(elem, null) :
                                  elem.currentStyle)['height'], 10);
        var width = parseInt(window.innerWidth / 2, 10);
        var compWidth = parseInt((window.getComputedStyle ?
                                  getComputedStyle(elem, null) :
                                  elem.currentStyle).width, 10);

        return compHeight === height && compWidth === width
    }(InkElement.create('div', { style: 'height:50vh;width:50vw' })));

    var flexSupported = (function (div) {
        return div.style.display !== '';
    }(InkElement.create('div', { style: 'display: flex' })));

    var cleanDimension = function (dim) {
        dim = dim.replace(/^\s+|\s+$/g, '');
        var hasPercent = dim.indexOf('%') !== -1;
        var hasPx = dim.indexOf('px') !== -1;
        return !hasPercent && !hasPx ? dim + '%' :
            !hasPercent && hasPx ? dim :
            !hasPx && hasPercent ? dim :
            dim + 'px';
    };

    var dimensionOfLayout = function (dimensionList, needleLayout) {
        var dims = dimensionList.split(/\s+/g);
        var theDefault;
        for (var i = 0; i < dims.length; i++) {
            var _dim = dims[i].split('-');
            var layout = _dim[0].replace(/^\s+|\s+$/g, '');

            if (layout === needleLayout) {
                return cleanDimension(_dim[1]);
            }

            if (layout === 'all') {
                theDefault = cleanDimension(_dim[1]);
            }
        }
        return theDefault;
    };

    var openModals = [];

    function Modal() {
        Common.BaseUIComponent.apply(this, arguments);
    }

    Modal._name = 'Modal_1';

    Modal._optionDefinition = {
        width:        ['String', '90%'],
        height:       ['String', '90%'],
        shadeClass:   ['String', undefined],
        modalClass:   ['String', undefined],
        trigger:      ['String', undefined],
        triggerEvent: ['String', 'click'],
        autoDisplay:  ['Boolean', true],
        markup:       ['String', undefined],
        onShow:       ['Function', undefined],
        onDismiss:    ['Function', undefined],
        closeOnClick: ['Boolean', false],
        closeOnEscape: ['Boolean', true],
        responsive:    ['Boolean', true]
    };

    Modal.prototype = {
        _init: function () {
            this._handlers = {
                click:   Ink.bindEvent(this._onShadeClick, this),
                keyDown: Ink.bindEvent(this._onKeyDown, this),
                resize: null
            };

            this._dimensionIsVariant = {
                width: ('' + this._options.width).indexOf(' ') !== -1,
                height: ('' + this._options.height).indexOf(' ') !== -1
            };

            this._isOpen = false;

            /**
             * Modal Markup
             */
            if( this._element ){
                this._markupMode = Css.hasClassName(this._element,'ink-modal'); // Check if the full modal comes from the markup
            } else {
                this._markupMode = false;
            }

            if( !this._markupMode ){
                this._modalShadow = InkElement.create('div', { className: 'ink-shade' });
                this._modalDiv    = InkElement.create('div', { className: 'ink-modal ink-space' });

                if( !!this._element ){
                    this._options.markup = this._element.innerHTML;
                }

                /**
                 * Applying the main css styles
                 */
                // this._modalDiv.style.position = 'absolute';
                this._modalShadow.appendChild( this._modalDiv);
                document.body.appendChild( this._modalShadow );
            } else {
                this._modalDiv         = this._element;
                this._modalShadow      = this._modalDiv.parentNode;

                this._contentContainer = Selector.select(".modal-body", this._modalDiv)[0];
                if( !this._contentContainer){
                    throw new Error('Ink.UI.Modal: Missing div with class "modal-body"');
                }

                this._options.markup = this._contentContainer.innerHTML;
            }

            if( !this._markupMode ){
                this.setContentMarkup(this._options.markup);
            }

            if( typeof this._options.shadeClass === 'string' ){
                Css.addClassName(this._modalShadow, this._options.shadeClass);
            }

            if( typeof this._options.modalClass === 'string' ){
                Css.addClassName(this._modalDiv, this._options.modalClass);
            }

            if( this._options.trigger ) {
                var triggerElements = Common.elsOrSelector(this._options.trigger, '');
                Event.observeMulti(triggerElements, this._options.triggerEvent, Ink.bindEvent(this.open, this));
            } else if ( this._options.autoDisplay ) {
                this.open();
            }
        },

        _dimensionIsPercentage: function () {
            var dims = this._getDimensions();
            return {
                width: ('' + dims.width).indexOf('%') !== -1,
                height: ('' + dims.height).indexOf('%') !== -1
            };
        },

        _getDimensions: function (opt) {
            opt = opt || {};
            var dims = {
                width: this._options.width,
                height: this._options.height
            };
            var currentLayout;
            if (this._dimensionIsVariant.width || this._dimensionIsVariant.height) {
                currentLayout = Common.currentLayout();
            }
            if (this._dimensionIsVariant.width) {
                dims.width = dimensionOfLayout(dims.width, currentLayout);
            }
            if (this._dimensionIsVariant.height) {
                dims.height = dimensionOfLayout(dims.height, currentLayout);
            }
            if (opt.dynamic) {
                var isPercentage = this._dimensionIsPercentage();
                if (!isPercentage.width) {
                    // TODO maxWidth and maxHeight should be options, not bound to 90%
                    var maxWidth = InkElement.viewportWidth() * 0.9;
                    if (parseFloat(dims.width) >= maxWidth) {
                        dims.width = maxWidth + 'px';
                    }
                }
                if (!isPercentage.height) {
                    var maxHeight = InkElement.viewportHeight() * 0.9;
                    if (parseFloat(dims.height) >= maxHeight) {
                        dims.height = maxHeight + 'px';
                    }
                }
            }
            return dims;
        },

        /**
         * Responsible for repositioning the modal
         * 
         * @method _reposition
         * @private
         */
        _reposition: function(){
            // reposition vertically
            var largerThan90Percent;

            var dimensionIsPercentage = this._dimensionIsPercentage();
            var dims = this._getDimensions();

            if (vhVwSupported && dimensionIsPercentage.height) {
                this._modalDiv.style.marginTop = (-parseFloat(dims.height)/2) + 'vh';
            } else if (vhVwSupported) {
                largerThan90Percent = parseFloat(dims.height) > InkElement.viewportHeight() * 0.9;

                if (largerThan90Percent !== this._heightWasLargerThan90Percent || !largerThan90Percent || this._dimensionIsVariant.height) {
                    this._heightWasLargerThan90Percent = largerThan90Percent;

                    if (largerThan90Percent) {
                        this._modalDiv.style.marginTop = '0';
                        this._modalDiv.style.top = '5vh';
                    } else {
                        this._modalDiv.style.marginTop = (-parseFloat(dims.height)/2) + 'px';
                        this._modalDiv.style.top = '';
                    }
                }
            } else {
                this._modalDiv.style.marginTop = (-InkElement.elementHeight(this._modalDiv)/2) + 'px';
            }

            // reposition horizontally
            if (vhVwSupported && dimensionIsPercentage.width) {
                this._modalDiv.style.marginLeft = (-parseFloat(dims.width)/2) + 'vw';
            } else if (vhVwSupported) {
                largerThan90Percent = parseFloat(dims.width) > InkElement.viewportWidth() * 0.9;

                if (largerThan90Percent !== this._widthWasLargerThan90Percent || !largerThan90Percent || this._dimensionIsVariant.width) {
                    this._widthWasLargerThan90Percent = largerThan90Percent;

                    if (largerThan90Percent) {
                        this._modalDiv.style.marginLeft = '0';
                        this._modalDiv.style.left = '5vw';
                    } else {
                        this._modalDiv.style.marginLeft = (-parseFloat(dims.width)/2) + 'px';
                        this._modalDiv.style.left = '';
                    }
                }
            } else {
                this._modalDiv.style.marginLeft = (-InkElement.elementWidth(this._modalDiv)/2) + 'px';
            }
        },

        _onResize: function( ){
            var dimensionsAreVariant = this._dimensionIsVariant.height || this._dimensionIsVariant.width;
            var dimensionIsPercentage = this._dimensionIsPercentage();
            var dimensionsArePercentage = !dimensionIsPercentage.height || !dimensionIsPercentage.width;

            if (dimensionsAreVariant) {
                this._resize();
            }

            if (!vhVwSupported || dimensionsAreVariant) {
                this._avoidModalLargerThanScreen();
            }

            if (!vhVwSupported || dimensionsArePercentage || dimensionsAreVariant) {
                this._reposition();
            }

            if (!flexSupported) {
                this._resizeContainer();
            }
        },

        _onShadeClick: function(ev) {
            if (!this._isOpen) { return; }

            var tgtEl = Event.element(ev);

            if (tgtEl === this._modalShadow && this._options.closeOnClick) {
                this.dismiss();
            }
            else if (Css.hasClassName(tgtEl, 'ink-close') || Css.hasClassName(tgtEl, 'ink-dismiss') || 
                InkElement.findUpwardsBySelector(tgtEl, '.ink-close,.ink-dismiss') ||
                (
                    this._options.closeOnClick &&
                    (!InkElement.descendantOf(this._shadeElement, tgtEl) || (tgtEl === this._shadeElement))
                )
            ) {
                var alertsInTheModal = Selector.select('.ink-alert', this._shadeElement),
                    alertsLength = alertsInTheModal.length;
                for( var i = 0; i < alertsLength; i++ ){
                    if( InkElement.descendantOf(alertsInTheModal[i], tgtEl) ){
                        return;
                    }
                }

                this.dismiss();

                // Only stop the event if this dismisses this modal
                if (!this._isOpen) {
                    Event.stop(ev);
                }
            }
        },

        _onKeyDown: function(ev) {
            if (ev.keyCode !== 27 || !this._isOpen) { return; }
            if (this._options.closeOnEscape &&
                    openModals[openModals.length - 1] === this) {
                this.dismiss();
                if (!this._isOpen) {
                    Event.stopDefault(ev);
                }
            }
        },

        _resize: function () {
            var dims = this._getDimensions();
            this._modalDiv.style.width = dims.width;
            this._modalDiv.style.height = dims.height;
        },

        _resizeContainer: function() {
            var containerHeight = InkElement.elementHeight(this._modalDiv);
            this._modalHeader = Selector.select('.modal-header',this._modalDiv)[0];
           
            if( this._modalHeader ){
                containerHeight -= InkElement.elementHeight(this._modalHeader);
            }

            this._modalFooter = Selector.select('.modal-footer',this._modalDiv)[0];
            
            if( this._modalFooter ){
                containerHeight -= InkElement.elementHeight(this._modalFooter);
            }

            this._contentContainer.style.height = containerHeight + 'px';

            if( this._markupMode ){ return; }
        },

        _resizeContainerFlex: function() {
            this._contentContainer.style.flex = '1';
            this._modalDiv.style.display = 'flex';
            this._modalDiv.style.flexDirection = 'column';
        },

        _avoidModalLargerThanScreen: function () {
            var dimensionIsPercentage = this._dimensionIsPercentage();

            if (!vhVwSupported) {
                var currentViewport = {
                    height: InkElement.viewportHeight(),
                    width: InkElement.viewportWidth()
                };

                var dims = this._getDimensions();

                InkArray.forEach(['height', 'width'], Ink.bind(function (dimension) {
                    // Not used for percentage measurements
                    if (dimensionIsPercentage[dimension]) { return; }

                    var dim = Math.round(currentViewport[dimension] * 0.9);

                    if (parseFloat(dims[dimension]) > dim) {
                        this._modalDiv.style[dimension] = dim + 'px';
                    } else {
                        if (isNaN(parseFloat(dims[dimension]))) { return; }
                        this._modalDiv.style[dimension] = parseFloat(dims[dimension]) + 'px';
                    }
                }, this));
            } else {
                if (!dimensionIsPercentage.width) {
                    this._modalDiv.style.maxWidth = '90vw';
                }
                if (!dimensionIsPercentage.height) {
                    this._modalDiv.style.maxHeight = '90vh';
                }
            }
        },

        open: function(event) {
            /* jshint -W030 */

            if (this.isOpen()) { return false; }

            if( event ){ Event.stopDefault(event); }

            Css.addClassName( this._modalShadow,'ink-shade' );
           
            this._modalShadow.style.display = this._modalDiv.style.display = 'block';
            this._modalShadow.offsetHeight;  // Cause a reflow

            Css.addClassName( this._modalShadow, 'visible' );
            Css.addClassName( this._modalDiv, 'visible' );

            /**
             * Fallback to the old one
             */
            this._contentElement = this._modalDiv;
            this._shadeElement   = this._modalShadow;

            if( !this._markupMode ){
                this.setContentMarkup( this._options.markup );
            }

            this._resize();
            this._avoidModalLargerThanScreen();
            this._reposition();

            if (!flexSupported) {
                this._resizeContainer();
            }
            else {
                this._resizeContainerFlex();
            }

            if( this._options.responsive ) {
                var isPercentage = this._dimensionIsPercentage();
                var needResizeHandler = !(
                    vhVwSupported &&
                    flexSupported &&
                    isPercentage.height &&
                    isPercentage.width &&
                    !this._dimensionIsVariant.height &&
                    !this._dimensionIsVariant.width );

                if (needResizeHandler) {
                    this._handlers.resize = Event.throttle(Ink.bind(this._onResize, this), 500);
                    Event.observe(window, 'resize', this._handlers.resize);
                }
            }

            if (this._options.onShow) {
                if (event) {
                    var trigger = InkElement.findUpwardsBySelector(
                            Event.element(event),
                            this._options.trigger);
                }

                this._options.onShow.call(this, this, {
                    trigger: trigger
                });
            }

            Event.observe(this._shadeElement, 'click', this._handlers.click);
            if (this._options.closeOnEscape ) {
                Event.observe(document, 'keydown', this._handlers.keyDown);
            }

            this._isOpen = true;

            openModals.push(this);
            Css.addClassName(document.documentElement, 'ink-modal-open');
        },

        isOpen: function () {
            return this._isOpen;
        },

        dismiss: function() {
            if (!this._isOpen) {
                return; 
            }

            if (this._options.onDismiss) {
                var ret = this._options.onDismiss(this);
                if (ret === false) { return; }
            }

            this._isOpen = false;

            if( this._handlers.resize ){
                Event.stopObserving(window, 'resize', this._handlers.resize);
            }

            if( !this._markupMode ){
                this._modalShadow.parentNode.removeChild(this._modalShadow);
                this.destroy();
            } else {
                Css.removeClassName( this._modalDiv, 'visible' );
                Css.removeClassName( this._modalShadow, 'visible' );

                this._waitForFade(this._modalShadow, Ink.bind(function () {
                    this._modalShadow.style.display = 'none';
                }, this));
            }

            openModals = InkArray.remove(openModals, InkArray.keyValue(this, openModals), 1);

            if (openModals.length === 0) {  // Document level stuff now there are no modals in play.
                var htmlEl = document.documentElement;

                // Remove the class from the HTML element.
                Css.removeClassName(htmlEl, 'ink-modal-open');
            }
        },

        _waitForFade: function (elem, callback) {
            if (!opacitySupported) { return callback(); }

            var fadeChecks = 5;
            var fadeChecker = function () {
                if( +Css.getStyle(elem, 'opacity') > 0 && fadeChecks > 0) {
                    fadeChecks--;
                    setTimeout(fadeChecker, 250);
                } else {
                    callback();
                }
            };

            setTimeout(fadeChecker, 500);
        },

        destroy: function() {
            Common.unregisterInstance(this._instanceId);
        },

        getContentElement: function() {
            return this._contentContainer || null;
        },

        getModalElement: function () {
            return this._modalDiv || null;
        },

        getShadeElement: function () {
            return this._modalShadow || null;
        },

        setContentMarkup: function(contentMarkup) {
            if( !this._markupMode ){
                this._modalDiv.innerHTML = [contentMarkup].join('');
                this._contentContainer = Selector.select(".modal-body", this._modalDiv);
                if( !this._contentContainer.length ){
                    // throw 'Missing div with class "modal-body"';
                    var tempHeader = Selector.select(".modal-header", this._modalDiv);
                    var tempFooter = Selector.select(".modal-footer", this._modalDiv);

                    InkArray.each(tempHeader, InkElement.remove);
                    InkArray.each(tempFooter, InkElement.remove);

                    var body = document.createElement('div');
                    Css.addClassName(body,'modal-body');
                    body.innerHTML = this._modalDiv.innerHTML;
                    this._modalDiv.innerHTML = '';

                    var toAdd = tempHeader.concat([body]).concat(tempFooter);
                    InkArray.each(toAdd, Ink.bindMethod(this._modalDiv, 'appendChild'));

                    this._contentContainer = Selector.select(".modal-body",this._modalDiv);
                }
                this._contentContainer = this._contentContainer[0];
            } else {
                this._contentContainer.innerHTML = contentMarkup;
            }
            this._contentElement = this._modalDiv;
            this._resizeContainer();
        }
    };

    Common.createUIComponent(Modal, { elementIsOptional: true });
    Modal._vhVwSupported = vhVwSupported;
    Modal._flexSupported = flexSupported;

    return Modal;
});