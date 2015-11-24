Ink.createModule('Ink.UI.FormValidator', '2', [ 'Ink.UI.Common_1','Ink.Dom.Element_1','Ink.Dom.Event_1','Ink.Dom.Selector_1','Ink.Dom.Css_1','Ink.Util.Array_1','Ink.Util.I18n_1','Ink.Util.Validator_1'], function( Common, Element, Event, Selector, Css, InkArray, I18n, InkValidator ) {
    'use strict';

    function getValue(element) {

        switch(element.nodeName.toLowerCase()){
            case 'select':
                var checkedOpt = Ink.s('option:checked', element);
                if (checkedOpt) { return checkedOpt.value; }
                return '';
            case 'textarea':
                return element.value;
            case 'input':
                if( "type" in element ){
                    if( (element.type === 'radio') || (element.type === 'checkbox') ){
                        if( element.checked ){
                            return element.value;
                        }
                    } else if( element.type !== 'file' ){
                        return element.value;
                    }
                } else {
                    return element.value;
                }
                return;
            default:
                return element.innerHTML;
        }
    }

    var validationFunctions = {

        'required': function( value ){
            return ((typeof value !== 'undefined') && ( !(/^\s*$/).test(value) ) );
        },

        'min_length': function( value, minSize ){
            return ((typeof value === 'string') && (value.length >= parseInt(minSize,10) ) );
        },

        'max_length': function( value, maxSize ) {
            return ((typeof value === 'string') && (value.length <= parseInt(maxSize,10) ) );
        },

        'exact_length': function( value, exactSize ) {
            return ((typeof value === 'string') && (value.length === parseInt(exactSize, 10)));
        },

        'email': function( value ){
            return ((typeof value === 'string') && InkValidator.mail(value));
        },

        'url': function( value, fullCheck ){
            fullCheck = fullCheck || false;
            return ((typeof value === 'string') && InkValidator.url( value, fullCheck ) );
        },

        'ip': function( value, ipType ){

            if (typeof value !== 'string'){
                return false;
            }

            return InkValidator.isIP(value, ipType);
        },

        'phone': function( value, phoneType ){
  
            if (typeof value !== 'string'){
                return false;
            }

            var countryCode = phoneType ? phoneType.toUpperCase() : '';

            return InkValidator['is' + countryCode + 'Phone'](value);
        },

        'credit_card': function( value, cardType ){
            if( typeof value !== 'string' ){
                return false;
            }

            return InkValidator.isCreditCard( value, cardType || 'default' );
        },

        'date': function( value, format ){
            return ((typeof value === 'string') && InkValidator.isDate(format, value));
        },

        'alpha': function( value, supportSpaces ){
            return InkValidator.ascii(value, {singleLineWhitespace: supportSpaces});
        },

        'text': function (value, whitespace, punctuation) {
            return InkValidator.unicode(value, {singleLineWhitespace: whitespace, numbers: true, unicodePunctuation: punctuation});
        },

        'latin': function (value, punctuation, whitespace) {
            if ( typeof value !== 'string') { return false; }
            return InkValidator.latin1(value, {
                latin1Punctuation: punctuation,
                singleLineWhitespace: whitespace,
                numbers: true });
        },

        'alpha_numeric': function( value ){
            return InkValidator.ascii(value, {numbers: true});
        },

        'alpha_dash': function( value ){
            return InkValidator.ascii(value, {dash: true, underscore: true});
        },

        'digit': function( value ){
            return ((typeof value === 'string') && /^[0-9]{1}$/.test(value));
        },

        'integer': function(value, positive){
            return InkValidator.number(value, {negative: !positive, decimalPlaces: 0});
        },

        'decimal': function(value, decimalSeparator, decimalPlaces, leftDigits ){
            return InkValidator.number(value, {decimalSep: decimalSeparator || '.', decimalPlaces: +decimalPlaces || null, maxDigits: +leftDigits});
        },

        'numeric': function( value, decimalSeparator, decimalPlaces, leftDigits ){
            decimalSeparator = decimalSeparator || '.';
            if( value.indexOf(decimalSeparator) !== -1  ){
                return validationFunctions.decimal( value, decimalSeparator, decimalPlaces, leftDigits );
            } else {
                return validationFunctions.integer( value );
            }
        },

        'range': function(value, minValue, maxValue, multipleOf) {

            value = +value;
            minValue = +minValue;
            maxValue = +maxValue;

            if (isNaN(value) || isNaN(minValue) || isNaN(maxValue)) {
                return false;
            }

            if (value < minValue || value > maxValue) {
                return false;
            }

            if (multipleOf) {
                return (value - minValue) % multipleOf === 0;
            }
            
            return true;
        },

        'color': function(value) {
            return InkValidator.isColor(value);
        },

        'matches': function( value, fieldToCompare ) {

            var otherField = this.getFormElements()[fieldToCompare];

            if (!otherField) {
                // It's in the actual <form>, not in the FormValidator's fields
                var possibleFields = Ink.ss('input, select, textarea, .control-group', this._options.form._element);
                for (var i = 0; i < possibleFields.length; i++) {
                    if ((possibleFields[i].name || possibleFields[i].id) === fieldToCompare) {
                        return getValue(possibleFields[i]) === value;
                    }
                }
                return false;
            } else {
                otherField = otherField[0];
            }

            var otherFieldValue = otherField.getValue();
            if (otherField._rules.required) {
                if (otherFieldValue === '') {
                    return false;
                }
            }
            return value === otherFieldValue;
        },

        'ean': function (value) {
            return InkValidator.isEAN(value.replace(/[^\d]/g, ''), 'ean-13');
        }
    };

    var validationMessages = new I18n({
        en_US: {
            'formvalidator.generic_error' : '{field} is invalid',
            'formvalidator.required' : 'Filling {field} is mandatory',
            'formvalidator.min_length': 'The {field} must have a minimum size of {param1} characters',
            'formvalidator.max_length': 'The {field} must have a maximum size of {param1} characters',
            'formvalidator.exact_length': 'The {field} must have an exact size of {param1} characters',
            'formvalidator.email': 'The {field} must have a valid e-mail address',
            'formvalidator.url': 'The {field} must have a valid URL',
            'formvalidator.ip': 'The {field} does not contain a valid {param1} IP address',
            'formvalidator.phone': 'The {field} does not contain a valid {param1} phone number',
            'formvalidator.credit_card': 'The {field} does not contain a valid {param1} credit card',
            'formvalidator.date': 'The {field} should contain a date in the {param1} format',
            'formvalidator.alpha': 'The {field} should only contain letters',
            'formvalidator.text': 'The {field} should only contain alphabetic characters',
            'formvalidator.latin': 'The {field} should only contain alphabetic characters',
            'formvalidator.alpha_numeric': 'The {field} should only contain letters or numbers',
            'formvalidator.alpha_dash': 'The {field} should only contain letters or dashes',
            'formvalidator.digit': 'The {field} should only contain a digit',
            'formvalidator.integer': 'The {field} should only contain an integer',
            'formvalidator.decimal': 'The {field} should contain a valid decimal number',
            'formvalidator.numeric': 'The {field} should contain a number',
            'formvalidator.range': 'The {field} should contain a number between {param1} and {param2}',
            'formvalidator.color': 'The {field} should contain a valid color',
            'formvalidator.matches': 'The {field} should match the field {param1}'
        },
        pt_PT: {
            'formvalidator.generic_error' : '{field} inválido',
            'formvalidator.required' : 'Preencher {field} é obrigatório',
            'formvalidator.min_length': '{field} deve ter no mínimo {param1} caracteres',
            'formvalidator.max_length': '{field} tem um tamanho máximo de {param1} caracteres',
            'formvalidator.exact_length': '{field} devia ter exactamente {param1} caracteres',
            'formvalidator.email': '{field} deve ser um e-mail válido',
            'formvalidator.url': 'O {field} deve ser um URL válido',
            'formvalidator.ip': '{field} não tem um endereço IP {param1} válido',
            'formvalidator.phone': '{field} deve ser preenchido com um número de telefone {param1} válido.',
            'formvalidator.credit_card': '{field} não tem um cartão de crédito {param1} válido',
            'formvalidator.date': '{field} deve conter uma data no formato {param1}',
            'formvalidator.alpha': 'O campo {field} deve conter apenas caracteres alfabéticos',
            'formvalidator.text': 'O campo {field} deve conter apenas caracteres alfabéticos',
            'formvalidator.latin': 'O campo {field} deve conter apenas caracteres alfabéticos',
            'formvalidator.alpha_numeric': '{field} deve conter apenas letras e números',
            'formvalidator.alpha_dash': '{field} deve conter apenas letras e traços',
            'formvalidator.digit': '{field} destina-se a ser preenchido com apenas um dígito',
            'formvalidator.integer': '{field} deve conter um número inteiro',
            'formvalidator.decimal': '{field} deve conter um número válido',
            'formvalidator.numeric': '{field} deve conter um número válido',
            'formvalidator.range': '{field} deve conter um número entre {param1} e {param2}',
            'formvalidator.color': '{field} deve conter uma cor válida',
            'formvalidator.matches': '{field} deve corresponder ao campo {param1}'
        }
    }, 'en_US');

    function FormElement(){
        Common.BaseUIComponent.apply(this, arguments);
    }

    FormElement._name = 'FormElement_1';
    FormElement._optionDefinition = {
        label: ['String', null],
        rules: ['String', null],  // The rules to apply
        error: ['String', null],  // Error message
        autoReparse: ['Boolean', false],
        form: ['Object']
    };

    /**
     * FormElement's prototype
     */
    FormElement.prototype = {
        _init: function () {
            this._errors = {};
            this._rules = {};
            this._value = null;
            this._forceInvalid = null;
            this._forceValid = null;
            this._errorParagraph = null;

            if (this._options.label === null) {
                this._options.label = this._getLabel();
            }

            // Mostly true, whether the element has an attribute named "data-rules".
            // Used only if options.autoReparse is true.
            this._elementHadDataRules = this._element.hasAttribute('data-rules');
        },

        _getLabel: function(){
            var label = Element.findUpwardsBySelector(this._element,'.control-group label');

            if( label ){
                return Element.textContent(label);
            } else {
                return this._element.name || this._element.id || '';
            }
        },

        _parseRules: function( rules ){
            this._rules = {};
            rules = rules.split("|");
            var i, rulesLength = rules.length, rule, params, paramStartPos ;
            if( rulesLength > 0 ){
                for( i = 0; i < rulesLength; i++ ){
                    rule = rules[i];
                    if( !rule ){
                        continue;
                    }

                    if( ( paramStartPos = rule.indexOf('[') ) !== -1 ){
                        params = rule.substr( paramStartPos+1 );
                        params = params.split(']');
                        params = params[0];
                        params = params.split(',');
                        for (var p = 0, len = params.length; p < len; p++) {
                            params[p] =
                                params[p] === 'true' ? true :
                                params[p] === 'false' ? false :
                                params[p];
                        }
                        params.splice(0,0,this.getValue());

                        rule = rule.substr(0,paramStartPos);

                        this._rules[rule] = params;
                    } else {
                        this._rules[rule] = [this.getValue()];
                    }
                }
            }
        },

        _addError: function(opt){
            if (typeof opt === 'string') { opt = { rule: opt }; }
            var rule = opt.rule;
            var message = opt.message;

            if (!message && !rule) { throw new Error('FormElement#_addError: Please pass an error message, or a rule that was broken'); }

            if (!message) {
                var params = this._rules[rule] || [];

                var paramObj = {
                    field: this._options.label,
                    value: this.getValue()
                };

                for( var i = 1; i < params.length; i++ ){
                    paramObj['param' + i] = params[i];
                }

                var i18nKey = 'formvalidator.' + rule;

                if (this._options.error) {
                    message = this._options.error;
                } else {
                    message = this._options.form.getI18n().text(i18nKey, paramObj);

                    if (message === i18nKey) {
                        message = '[Validation message not found for rule ]' + rule;
                    }
                }
            }

            this._errors[rule] = message;
        },

        getValue: function(){
            return getValue(this._element);
        },

        getLabel: function () {
            return this._options.label;
        },

        getErrors: function(){
            return this._errors;
        },

        getElement: function(){
            return this._element;
        },

        getFormElements: function () {
            return this._options.form._formElements;
        },

        setRules: function (rulesStr) {
            this._options.rules = rulesStr;
        },

        forceInvalid: function (message) {
            this._forceInvalid = message ?
                message :
                this._options.form.getI18n().text('formvalidator.generic_error', { field: this.getLabel() });
        },

        unforceInvalid: function () {
            this._forceInvalid = null;
        },

        forceValid: function() {
            this._forceValid = true;
        },

        unforceValid: function() {
            this._forceValid = false;
        },

        getControlGroup: function () {
            if( Css.hasClassName(this._element, 'control-group') ){
                return this._element;
            } else {
                return Element.findUpwardsByClass(this._element, 'control-group');
            }
        },

        getControl: function () {
            
            if (Css.hasClassName(this._element, 'control-group')) {
                return Ink.s('.control', this._element) || undefined;
            }
            
            return Element.findUpwardsByClass(this._element, 'control');
        },

        removeErrors: function() {
            
            var controlGroup = this.getControlGroup();
           
            if (controlGroup) {
                Css.removeClassName(controlGroup, ['validation', 'error']);
            }
            
            if (this._errorParagraph) {
                Element.remove(this._errorParagraph);
            }
        },

        displayErrors: function() {
            this.validate();
            this.removeErrors();

            var errors = this.getErrors();
            var errorArr = [];
            for (var k in errors) {
                if (errors.hasOwnProperty(k)) {
                    errorArr.push(errors[k]);
                }
            }

            if (!errorArr.length) { return; }

            var controlGroupElement = this.getControlGroup();
            var controlElement = this.getControl();

            if(controlGroupElement) {
                Css.addClassName( controlGroupElement, ['validation', 'error'] );
            }

            var paragraph = document.createElement('p');
            Css.addClassName(paragraph, 'tip');
            if (controlElement || controlGroupElement) {
                (controlElement || controlGroupElement).appendChild(paragraph);
            } else {
                Element.insertAfter(paragraph, this._element);
            }

            paragraph.innerHTML = errorArr.join('<br/>');
            this._errorParagraph = paragraph;
        },

        /**
         * Validates the element based on the rules defined.
         * It parses the rules defined in the _options.rules property.
         *
         * @method validate
         * @return {Boolean} True if every rule was valid. False if one fails.
         * @public
         */
        validate: function(){
            if (this._forceValid) {
                /* The user says it's valid */
                this._errors = {};
                return true;
            }

            if (this._element.disabled) {
                return true;
            }

            if (this._forceInvalid) {
                /* The user says it's invalid */
                this._addError({ message: this._forceInvalid });
                return false;
            }

            this._errors = {};

            if (this._options.autoReparse) {
                var rules = this._element.getAttribute('data-rules');
                if (rules) {
                    this._options.rules = rules;
                } else if (this._elementHadDataRules && !this._element.hasAttribute('data-rules')) {
                    // Element had [data-rules], but it was removed.
                    // Which means it is actually valid.
                    return true;
                }
            }

            this._parseRules( this._options.rules );

            // We want to validate this field only if it's not empty
            // "" is not an invalid number.
            var doValidate = this.getValue() !== '' ||
                // If it's required it will be validated anyway.
                ("required" in this._rules) ||
                // If it has a "matches" rule it will also be validated because "" is not a valid password confirmation.
                ("matches" in this._rules);

            if (doValidate) {
                for(var rule in this._rules) {
                    if (this._rules.hasOwnProperty(rule)) {
                        if( (typeof validationFunctions[rule] === 'function') ){
                            if( validationFunctions[rule].apply(this, this._rules[rule] ) === false ){
                                this._addError({ rule: rule });
                                return false;
                            }

                        } else {
                            Ink.warn('Rule "' + rule + '" not found. Used in element:', this._element);
                            this._addError({
                                message: this._options.form.getI18n().text('formvalidator.generic_error', { field: this.getLabel() })
                            });
                            return false;
                        }
                    }
                }
            }

            return true;

        }
    };

    Common.createUIComponent(FormElement);

    function FormValidator(){
        Common.BaseUIComponent.apply(this, arguments);
    }

    FormValidator._name = 'FormValidator_1';

    FormValidator._optionDefinition = {
        lang: ['String', null],
        eventTrigger: ['String', 'submit'],
        neverSubmit: ['Boolean', false],
        autoReparse: ['Boolean', false],
        searchFor: ['String', 'input, select, textarea, .control-group'],
        beforeValidation: ['Function', undefined],
        onError: ['Function', undefined],
        onSuccess: ['Function', undefined],
        extraValidation: ['Function', undefined]
    };

    FormValidator.setRule = function( name, errorMessage, cb ){
        validationFunctions[ name ] = cb;

        if (validationMessages.getKey('formvalidator.' + name) !== errorMessage) {
            var langObj = {}; langObj['formvalidator.' + name] = errorMessage;
            var dictObj = {}; dictObj[validationMessages.lang()] = langObj;
            validationMessages.append(dictObj);
        }
    };

    FormValidator.getI18n = function () {
        return validationMessages;
    };

    FormValidator.setI18n = function (i18n) {
        validationMessages = i18n;
    };

    FormValidator.appendI18n = function () {
        validationMessages.append.apply(validationMessages, [].slice.call(arguments));
    };

    FormValidator.setLanguage = function (language) {
        validationMessages.lang(language);
    };

    FormValidator.getRules = function(){
        return validationFunctions;
    };

    FormValidator.prototype = {
        _init: function(){
            /**
             * Element of the form being validated
             *
             * @property _rootElement
             * @type {Element}
             */
            this._rootElement = this._element;

            /**
             * Object that will gather the form elements by name
             *
             * @property _formElements
             * @type {Object}
             */
            this._formElements = {};

            /**
             * Error message Elements
             * 
             * @property _errorMessages
             */
            this._errorMessages = [];

            /**
             * Array of FormElements marked with validation errors
             *
             * @property _markedErrorElements
             */
            this._markedErrorElements = [];

            // Sets an event listener for a specific event in the form, if defined.
            // By default is the 'submit' event.
            if( typeof this._options.eventTrigger === 'string' ){
                Event.observe(
                    this._rootElement,
                    this._options.eventTrigger,
                    Ink.bindEvent(this.validate,this) );
            }

            if (this._options.lang) {
                this.setLanguage(this._options.lang);
            }
        },

        /**
         * Searches for the elements in the form.
         * This method is based in the this._options.searchFor configuration.
         *
         * Returns an object mapping names of object to arrays of FormElement instances.
         *
         * @method getElements
         * @return {Object} An object with the elements in the form, indexed by name/id
         * @public
         */
        getElements: function(){
            if (!this._formElements) {
                this._formElements = {};
            }
            var i;
            for (var k in this._formElements) if (this._formElements.hasOwnProperty(k)) {
                i = this._formElements[k].length;
                while (i--) {
                    if (!Element.isAncestorOf(document.documentElement,
                            this._formElements[k][i]._element)) {
                        // Element was detached from DOM, remove its formElement from our roster.
                        this._formElements[k][i].removeErrors();
                        this._formElements[k].splice(i, 1);
                    }
                }
                // Check if formElement was removed.
                if (this._formElements[k].length === 0) {
                    delete this._formElements[k];
                }
            }
            var formElements = Selector.select( this._options.searchFor, this._rootElement );

            for(i=0; i<formElements.length; i+=1 ){
                var element = formElements[i];

                var dataAttrs = Element.data( element );

                if( !("rules" in dataAttrs) ){
                    continue;
                }

                var options = {
                    form: this
                };

                var key;
                if( ("name" in element) && element.name ){
                    key = element.name;
                } else if( ("id" in element) && element.id ){
                    key = element.id;
                } else {
                    key = 'element_' + Math.floor(Math.random()*100);
                    element.id = key;
                }

                if( !(key in this._formElements) ){
                    this._formElements[key] = [];
                }

                var formElement = this._getOrCreateFormElementInstance(key, element, options);

                if (formElement) {
                    this._formElements[key].push(formElement);
                }
            }

            return this._formElements;
        },

        _getOrCreateFormElementInstance: function (key, element, options) {
            for (var j = 0; j < this._formElements[key].length; j++) {
                if (this._formElements[key][j].getElement() === element) {
                    return null;
                }
            }
            if (!element.getAttribute('data-auto-reparse')) {
                options.autoReparse = this._options.autoReparse;
            }
            return new FormElement(element, options);
        },

        /**
         * Set my I18n instance with the validation messages.
         * @method setI18n
         * @param {Ink.Util.I18n_1} i18n I18n instance
         **/
        setI18n: function (i18n) {
            if (i18n.clone) {
                // New function, added safety
                i18n = i18n.clone();
            }
            this.i18n = i18n;
        },

        /**
         * Get my I18n instance with the validation messages.
         * @method getI18n
         * @return {Ink.Util.I18n_1} I18n instance
         **/
        getI18n: function () {
            return this.i18n || validationMessages;
        },

        /**
         * Set the language of this form validator to the given language code
         * If we don't have an i18n instance, create one which is a copy of the global one.
         * @method setLanguage
         * @param {String} language Language code (ex: en_US, pt_PT)
         * @return {void}
         * @public
         **/
        setLanguage: function (language) {
            if (!this.i18n) {
                this.setI18n(validationMessages);
            }
            this.i18n.lang(language);
        },

        /**
         * Gets the language code string (pt_PT or en_US for example) currently in use by this formvalidator.
         * May be global
         *
         * @method getLanguage
         * @public
         * @return {String} Language code.
         **/
        getLanguage: function () {
            return this.i18n ? this.i18n.lang() : validationMessages.lang();
        },

        /**
         * Validates every registered FormElement 
         * This method looks inside the this._formElements object for validation targets.
         * Also, based on the this._options.beforeValidation, this._options.onError, and this._options.onSuccess, this callbacks are executed when defined.
         *
         * @method validate
         * @param  {Event} event    Window.event object
         * @return {Boolean} Whether the form is considered valid
         * @public
         */
        validate: function( event ) {

            if(this._options.neverSubmit && event) {
                Event.stopDefault(event);
            }

            this.getElements();

            if( typeof this._options.beforeValidation === 'function' ){
                this._options.beforeValidation.call(this, {
                    event: event,
                    validator: this,
                    elements: this._formElements
                });
            }

            Css.removeClassName(this._element, 'form-error');

            var errorElements = [];

            for( var key in this._formElements ){
                if( this._formElements.hasOwnProperty(key) ){
                    for( var counter = 0; counter < this._formElements[key].length; counter+=1 ){
                        this._formElements[key][counter].removeErrors();
                        if( !this._formElements[key][counter].validate() ) {
                            errorElements.push(this._formElements[key][counter]);
                        }
                    }
                }
            }

            var isValid = errorElements.length === 0;

            if (typeof this._options.extraValidation === 'function') {
                var param = {
                    event: event,
                    validator: this,
                    elements: this._formElements,
                    errorCount: errorElements.length
                };
                var result = this._options.extraValidation.call(this, param);
                if (result === false) { isValid = false; }
            }
            
            if( isValid ){
                if( typeof this._options.onSuccess === 'function' ){
                    this._options.onSuccess();
                }
            } else {
                if(event) {
                    Event.stopDefault(event);
                }

                if( typeof this._options.onError === 'function' ){
                    this._options.onError( errorElements );
                }

                this._invalid(errorElements);
            }

            return isValid;
        },

        _invalid: function (errorElements) {
            errorElements = errorElements || [];
            this._errorMessages = [];

            Css.addClassName(this._element, 'form-error');

            for (var i = 0; i < errorElements.length; i++) {
                errorElements[i].displayErrors();
            }
        }
    };

    Common.createUIComponent(FormValidator);

    FormValidator.FormElement = FormElement;  // Export FormElement too, for testing.
    FormValidator.validationFunctions = validationFunctions;  // Export the raw validation functions too, for fiddling.

    return FormValidator;

});
